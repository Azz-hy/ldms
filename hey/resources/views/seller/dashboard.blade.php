@extends('layouts.app')
@section('title', 'Seller Dashboard')
@section('page-title', 'My Dashboard')
@section('page-sub', auth()->user()->seller->business_name ?? 'Seller Portal')

@section('topbar-actions')
    <a href="{{ route('seller.orders.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Order
    </a>
@endsection

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $stats['delivered'] }}</div>
            <div class="stat-label">Delivered</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-amber">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-red">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-value">{{ $stats['failed'] }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div class="text-muted small mb-1">Total Spent</div>
            <div style="font-size:1.5rem;font-weight:800;color:#2563eb">${{ number_format($stats['total_spent'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div class="text-muted small mb-1">Average Fee</div>
            <div style="font-size:1.5rem;font-weight:800;color:#059669">${{ number_format($stats['avg_fee'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div class="text-muted small mb-1">This Month</div>
            <div style="font-size:1.5rem;font-weight:800;color:#d97706">${{ number_format($stats['this_month_spent'], 2) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-clock-history me-2 text-info"></i>Recent Orders</h5>
        <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Fee</th><th>Driver</th><th>Status</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td><code style="font-size:.78rem">{{ $order->order_number }}</code></td>
                    <td>
                        <div>{{ $order->customer_name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $order->customer_phone }}</div>
                    </td>
                    <td class="fw-600">${{ number_format($order->delivery_fee, 2) }}</td>
                    <td style="font-size:.85rem">{{ $order->driver?->user->name ?? '<span class="text-muted">—</span>' }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td style="font-size:.78rem;color:#64748b">{{ $order->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No orders yet. <a href="{{ route('seller.orders.create') }}">Create your first order!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
