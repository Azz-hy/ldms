@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')
@section('page-sub', $order->order_number)

@section('topbar-actions')
    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    @if($order->isEditable())
    <a href="{{ route('seller.orders.edit', $order) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    @endif
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-custom">
                <h5><i class="bi bi-box-seam me-2"></i>Order Info</h5>
                <span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Customer</div>
                        <div class="fw-600">{{ $order->customer_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Phone</div>
                        <div class="fw-600">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Delivery Address</div>
                        <div>{{ $order->delivery_address }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Product Description</div>
                        <div>{{ $order->product_description }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Fee</div>
                        <div class="fw-700" style="color:#059669;font-size:1.1rem">${{ number_format($order->delivery_fee, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Zone</div>
                        <div>{{ $order->delivery_zone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Created</div>
                        <div>{{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    @if($order->special_instructions)
                    <div class="col-12">
                        <div class="text-muted small">Special Instructions</div>
                        <div class="p-2 rounded" style="background:#fef9c3">{{ $order->special_instructions }}</div>
                    </div>
                    @endif
                    @if($order->failure_reason)
                    <div class="col-12">
                        <div class="text-muted small">Failure Reason</div>
                        <div class="p-2 rounded" style="background:#fee2e2">{{ $order->failure_reason }}</div>
                    </div>
                    @endif
                    @if($order->driver_notes)
                    <div class="col-12">
                        <div class="text-muted small">Driver Notes</div>
                        <div class="p-2 rounded" style="background:#f0fdf4">{{ $order->driver_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($order->driver)
        <div class="card mb-3">
            <div class="card-header-custom"><h5><i class="bi bi-person-badge me-2"></i>Assigned Driver</h5></div>
            <div class="p-3">
                <p class="fw-600 mb-1">{{ $order->driver->user->name }}</p>
                <p class="small text-muted mb-1">{{ $order->driver->vehicle_type ?? '—' }} · {{ $order->driver->vehicle_number ?? '' }}</p>
                <p class="small text-muted mb-0">{{ $order->driver->user->phone ?? '—' }}</p>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header-custom"><h5><i class="bi bi-clock-history me-2"></i>Timeline</h5></div>
            <div class="p-3">
                @php
                    $statuses = ['pending','assigned','picked_up','on_the_way','delivered'];
                    $currentIdx = array_search($order->status, $statuses);
                @endphp
                @foreach($statuses as $i => $s)
                @php $done = $i <= $currentIdx; @endphp
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:22px;height:22px;border-radius:50%;background:{{ $done ? '#2563eb' : '#e2e8f0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        @if($done)
                            <i class="bi bi-check" style="color:#fff;font-size:.7rem"></i>
                        @endif
                    </div>
                    <span style="font-size:.82rem;{{ $done ? 'font-weight:600' : 'color:#94a3b8' }}">
                        {{ ucwords(str_replace('_',' ',$s)) }}
                    </span>
                </div>
                @endforeach
                @if($order->status === 'failed')
                <div class="d-flex align-items-center gap-2">
                    <div style="width:22px;height:22px;border-radius:50%;background:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-x" style="color:#fff;font-size:.7rem"></i>
                    </div>
                    <span style="font-size:.82rem;font-weight:600;color:#dc2626">Failed</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
