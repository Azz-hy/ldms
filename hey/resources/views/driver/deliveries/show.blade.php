@extends('layouts.app')
@section('title', 'Delivery ' . $order->order_number)
@section('page-title', 'Delivery Detail')
@section('page-sub', $order->order_number)

@section('topbar-actions')
    <a href="{{ route('driver.deliveries') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header-custom">
                <h5><i class="bi bi-box-seam me-2"></i>Delivery Info</h5>
                <span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Customer Name</div>
                        <div class="fw-600">{{ $order->customer_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Customer Phone</div>
                        <div class="fw-600">
                            <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Delivery Address</div>
                        <div class="fw-500">{{ $order->delivery_address }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Package</div>
                        <div>{{ $order->product_description }}</div>
                    </div>
                    @if($order->special_instructions)
                    <div class="col-12">
                        <div class="text-muted small">Special Instructions</div>
                        <div class="p-2 rounded" style="background:#fef9c3">{{ $order->special_instructions }}</div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="text-muted small">Seller</div>
                        <div>{{ $order->seller->user->name }}</div>
                        <div style="font-size:.8rem;color:#64748b">{{ $order->seller->business_name ?? '' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Delivery Fee</div>
                        <div class="fw-700" style="color:#059669;font-size:1.1rem">${{ number_format($order->delivery_fee, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @php $transitions = \App\Models\Order::validTransitions($order->status); @endphp

        @if(!$order->isFinal() && count($transitions) > 0)
        <div class="card mb-3">
            <div class="card-header-custom"><h5><i class="bi bi-arrow-repeat me-2"></i>Update Status</h5></div>
            <div class="p-4">

                {{-- Quick action buttons for common transitions --}}
                @foreach($transitions as $newStatus)
                @if($newStatus !== 'failed')
                <form action="{{ route('driver.deliveries.status', $order) }}" method="POST" class="mb-2">
                    @csrf
                    <input type="hidden" name="status" value="{{ $newStatus }}">
                    @php
                        $btnConfig = [
                            'picked_up'  => ['primary',  'bag-check',    'Mark as Picked Up'],
                            'on_the_way' => ['secondary','truck',        'Start Delivery'],
                            'delivered'  => ['success',  'check-circle', 'Mark as Delivered'],
                        ];
                        [$btnClass, $icon, $btnLabel] = $btnConfig[$newStatus] ?? ['warning','arrow-repeat', ucwords(str_replace('_',' ',$newStatus))];
                    @endphp
                    <button type="submit" class="btn btn-{{ $btnClass }} w-100">
                        <i class="bi bi-{{ $icon }} me-2"></i>{{ $btnLabel }}
                    </button>
                </form>
                @endif
                @endforeach

                {{-- Fail delivery form --}}
                @if(in_array('failed', $transitions))
                <div class="mt-3 pt-3 border-top">
                    <button class="btn btn-outline-danger btn-sm w-100" type="button"
                            data-bs-toggle="collapse" data-bs-target="#failForm">
                        <i class="bi bi-x-circle me-1"></i>Report Failed Delivery
                    </button>
                    <div class="collapse mt-2" id="failForm">
                        <form action="{{ route('driver.deliveries.status', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="failed">
                            <div class="mb-2">
                                <label class="form-label">Reason *</label>
                                <textarea name="failure_reason" class="form-control" rows="2"
                                          placeholder="Customer not available, wrong address, etc." required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Notes</label>
                                <input type="text" name="driver_notes" class="form-control" placeholder="Additional notes...">
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100">Confirm Failed</button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Driver notes --}}
                <div class="mt-3 pt-3 border-top">
                    <form action="{{ route('driver.deliveries.status', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $order->status }}">
                        <label class="form-label">Add Note</label>
                        <div class="d-flex gap-2">
                            <input type="text" name="driver_notes" class="form-control" placeholder="Add delivery note...">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="card mb-3">
            <div class="p-4 text-center">
                @if($order->status === 'delivered')
                    <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0 fw-600">Delivery Complete!</p>
                    @if($order->delivered_at)
                        <p class="text-muted small">{{ $order->delivered_at->format('M d, Y H:i') }}</p>
                    @endif
                @else
                    <i class="bi bi-x-circle-fill text-danger" style="font-size:2.5rem"></i>
                    <p class="mt-2 mb-0 fw-600">Delivery Failed</p>
                    @if($order->failure_reason)
                        <p class="text-muted small">{{ $order->failure_reason }}</p>
                    @endif
                @endif
            </div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="card">
            <div class="card-header-custom"><h5><i class="bi bi-clock-history me-2"></i>Progress</h5></div>
            <div class="p-3">
                @php
                    $steps = [
                        ['assigned',   'Order Assigned',  $order->assigned_at],
                        ['picked_up',  'Picked Up',       $order->picked_up_at],
                        ['on_the_way', 'On The Way',      null],
                        ['delivered',  'Delivered',       $order->delivered_at],
                    ];
                    $statusOrder = ['assigned','picked_up','on_the_way','delivered'];
                    $curIdx = array_search($order->status, $statusOrder);
                @endphp
                @foreach($steps as $i => [$key, $label, $time])
                @php $done = $i <= $curIdx || ($order->status === 'delivered'); @endphp
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div style="position:relative">
                        <div style="width:24px;height:24px;border-radius:50%;background:{{ $done ? '#059669' : '#e2e8f0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            @if($done)<i class="bi bi-check" style="color:#fff;font-size:.7rem"></i>@endif
                        </div>
                        @if($i < count($steps)-1)
                        <div style="width:2px;height:20px;background:{{ $done ? '#059669' : '#e2e8f0' }};margin:2px auto"></div>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:.85rem;font-weight:{{ $done ? '600':'400' }};color:{{ $done ? '#1e293b':'#94a3b8' }}">{{ $label }}</div>
                        @if($time)
                            <div style="font-size:.75rem;color:#64748b">{{ $time->format('M d, H:i') }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
