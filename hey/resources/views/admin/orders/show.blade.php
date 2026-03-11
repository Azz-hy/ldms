@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Detail')
@section('page-sub', $order->order_number)

@section('topbar-actions')
    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Orders
    </a>
@endsection

@section('content')
<div class="row g-3">
    {{-- Order Info --}}
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header-custom">
                <h5><i class="bi bi-box-seam me-2"></i>Order Information</h5>
                <span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="color:#64748b">Customer Name</label>
                        <p class="fw-600 mb-0">{{ $order->customer_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="color:#64748b">Customer Phone</label>
                        <p class="fw-600 mb-0">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="color:#64748b">Delivery Address</label>
                        <p class="mb-0">{{ $order->delivery_address }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="color:#64748b">Product Description</label>
                        <p class="mb-0">{{ $order->product_description }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="color:#64748b">Delivery Fee</label>
                        <p class="fw-700 mb-0" style="font-size:1.1rem;color:#059669">${{ number_format($order->delivery_fee, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="color:#64748b">Zone</label>
                        <p class="mb-0">{{ $order->delivery_zone ?? '—' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="color:#64748b">Created</label>
                        <p class="mb-0">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($order->special_instructions)
                    <div class="col-12">
                        <label class="form-label" style="color:#64748b">Special Instructions</label>
                        <div class="p-2 rounded" style="background:#fef9c3;font-size:.875rem">{{ $order->special_instructions }}</div>
                    </div>
                    @endif
                    @if($order->failure_reason)
                    <div class="col-12">
                        <label class="form-label" style="color:#64748b">Failure Reason</label>
                        <div class="p-2 rounded" style="background:#fee2e2;font-size:.875rem">{{ $order->failure_reason }}</div>
                    </div>
                    @endif
                    @if($order->driver_notes)
                    <div class="col-12">
                        <label class="form-label" style="color:#64748b">Driver Notes</label>
                        <div class="p-2 rounded" style="background:#f0fdf4;font-size:.875rem">{{ $order->driver_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="card">
            <div class="card-header-custom">
                <h5><i class="bi bi-clock-history me-2"></i>Status Timeline</h5>
            </div>
            <div class="p-4">
                @php
                    $timeline = [
                        ['pending',    'Pending',    $order->created_at,   'clock',        'warning'],
                        ['assigned',   'Assigned',   $order->assigned_at,  'person-check', 'info'],
                        ['picked_up',  'Picked Up',  $order->picked_up_at, 'bag-check',    'primary'],
                        ['on_the_way', 'On The Way', null,                 'truck',        'secondary'],
                        ['delivered',  'Delivered',  $order->delivered_at, 'check-circle', 'success'],
                        ['failed',     'Failed',     null,                 'x-circle',     'danger'],
                    ];
                    $statuses = ['pending','assigned','picked_up','on_the_way','delivered','failed'];
                    $currentIdx = array_search($order->status, $statuses);
                @endphp

                <div class="d-flex flex-wrap gap-2">
                    @foreach($timeline as $i => [$key, $label, $time, $icon, $color])
                    @php $idx = array_search($key, $statuses); $done = $idx <= $currentIdx; @endphp
                    <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:{{ $done ? '#f0f9ff' : '#f8fafc' }};border:1px solid {{ $done ? '#bae6fd' : '#e2e8f0' }};min-width:130px">
                        <div style="width:30px;height:30px;border-radius:50%;background:{{ $done ? "var(--bs-$color)" : '#e2e8f0' }};display:flex;align-items:center;justify-content:center;color:{{ $done ? '#fff' : '#94a3b8' }};font-size:.8rem;flex-shrink:0">
                            <i class="bi bi-{{ $icon }}"></i>
                        </div>
                        <div>
                            <div style="font-size:.78rem;font-weight:600;color:{{ $done ? '#1e293b' : '#94a3b8' }}">{{ $label }}</div>
                            @if($time)
                                <div style="font-size:.7rem;color:#64748b">{{ $time->format('M d, H:i') }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Side Actions --}}
    <div class="col-md-4">
        {{-- Seller Info --}}
        <div class="card mb-3">
            <div class="card-header-custom"><h5><i class="bi bi-shop me-2"></i>Seller</h5></div>
            <div class="p-3">
                <p class="fw-600 mb-1">{{ $order->seller->user->name }}</p>
                <p class="small text-muted mb-1">{{ $order->seller->business_name ?? '—' }}</p>
                <p class="small text-muted mb-0">{{ $order->seller->user->phone ?? '—' }}</p>
            </div>
        </div>

        {{-- Driver Assignment --}}
        @if(!$order->isFinal())
        <div class="card mb-3">
            <div class="card-header-custom"><h5><i class="bi bi-person-badge me-2"></i>Assign Driver</h5></div>
            <div class="p-3">
                @if($order->driver)
                    <div class="alert alert-info py-2 mb-3">
                        <small><strong>Current driver:</strong> {{ $order->driver->user->name }}</small>
                    </div>
                @endif
                <form action="{{ route('admin.orders.assign', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Driver</label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">— Choose Driver —</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->user->name }} ({{ $driver->activeOrdersCount() }} active)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-person-check me-1"></i>{{ $order->driver ? 'Reassign' : 'Assign' }} Driver
                    </button>
                </form>
            </div>
        </div>

        {{-- Status Update --}}
        <div class="card">
            <div class="card-header-custom"><h5><i class="bi bi-arrow-repeat me-2"></i>Update Status</h5></div>
            <div class="p-3">
                @php $validTransitions = \App\Models\Order::validTransitions($order->status); @endphp
                @if(count($validTransitions) > 0)
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select" id="adminStatusSelect">
                            @foreach($validTransitions as $s)
                                <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="failureReasonBlock" class="mb-3 d-none">
                        <label class="form-label">Failure Reason</label>
                        <textarea name="failure_reason" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm w-100">
                        <i class="bi bi-arrow-repeat me-1"></i>Update Status
                    </button>
                </form>
                @else
                    <p class="text-muted small mb-0">This order is finalized and cannot be updated.</p>
                @endif
            </div>
        </div>
        @else
        <div class="card">
            <div class="p-3 text-center">
                <i class="bi bi-lock" style="font-size:2rem;color:#94a3b8"></i>
                <p class="text-muted small mt-2 mb-0">Order is finalized</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('adminStatusSelect')?.addEventListener('change', function () {
    const block = document.getElementById('failureReasonBlock');
    block.classList.toggle('d-none', this.value !== 'failed');
});
</script>
@endpush
