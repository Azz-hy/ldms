@extends('layouts.app')
@section('title', 'Driver Dashboard')
@section('page-title', 'Driver Dashboard')
@section('page-sub', 'Your delivery overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-truck"></i></div>
            <div class="stat-value">{{ $stats['assigned'] }}</div>
            <div class="stat-label">Active Deliveries</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $stats['today'] }}</div>
            <div class="stat-label">Delivered Today</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-amber">
            <div class="stat-icon"><i class="bi bi-calendar-week"></i></div>
            <div class="stat-value">{{ $stats['this_week'] }}</div>
            <div class="stat-label">This Week</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-teal">
            <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
            <div class="stat-value">{{ $stats['success_rate'] }}%</div>
            <div class="stat-label">Success Rate</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-truck me-2 text-primary"></i>Active Deliveries</h5>
        <a href="{{ route('driver.deliveries') }}" class="btn btn-sm btn-outline-primary">All Deliveries</a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Address</th><th>Seller</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($activeOrders as $order)
                <tr>
                    <td><code style="font-size:.78rem">{{ $order->order_number }}</code></td>
                    <td>
                        <div>{{ $order->customer_name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $order->customer_phone }}</div>
                    </td>
                    <td style="font-size:.82rem;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $order->delivery_address }}
                    </td>
                    <td style="font-size:.85rem">{{ $order->seller->user->name }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td>
                        <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-right me-1"></i>Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-check2-all" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#059669"></i>
                        No active deliveries right now
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
