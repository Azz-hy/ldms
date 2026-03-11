@extends('layouts.app')
@section('title', 'My Orders')
@section('page-title', 'My Orders')
@section('page-sub', 'Track all your delivery orders')

@section('topbar-actions')
    <a href="{{ route('seller.orders.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Order
    </a>
@endsection

@section('content')
<div class="mb-3">
    <form action="{{ route('seller.orders') }}" method="GET" class="d-flex gap-2">
        <select name="status" class="form-select" style="max-width:180px">
            <option value="">All Statuses</option>
            @foreach(['pending','assigned','picked_up','on_the_way','delivered','failed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
        <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Address</th><th>Fee</th><th>Driver</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><code style="font-size:.78rem">{{ $order->order_number }}</code></td>
                    <td>
                        <div>{{ $order->customer_name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $order->customer_phone }}</div>
                    </td>
                    <td style="font-size:.82rem;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $order->delivery_address }}
                    </td>
                    <td class="fw-600">${{ number_format($order->delivery_fee, 2) }}</td>
                    <td style="font-size:.85rem">{{ $order->driver?->user->name ?? '—' }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td style="font-size:.78rem;color:#64748b">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($order->isEditable())
                            <a href="{{ route('seller.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        No orders found. <a href="{{ route('seller.orders.create') }}">Create one now!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-3">{{ $orders->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
