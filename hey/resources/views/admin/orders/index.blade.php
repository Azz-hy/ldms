@extends('layouts.app')
@section('title', 'All Orders')
@section('page-title', 'Orders')
@section('page-sub', 'Manage all delivery orders')

@section('topbar-actions')
    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
    </a>
@endsection

@section('content')

{{-- Filters --}}
<div class="card mb-4">
    <div class="p-3">
        <form action="{{ route('admin.orders') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Order #, Customer, Phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','assigned','picked_up','on_the_way','delivered','failed'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Seller</label>
                <select name="seller_id" class="form-select">
                    <option value="">All Sellers</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>{{ $seller->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Orders Table --}}
<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-box-seam me-2"></i>Orders ({{ $orders->total() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Seller</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Driver</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><code style="font-size:.78rem">{{ $order->order_number }}</code></td>
                    <td>
                        <div style="font-size:.85rem;font-weight:500">{{ $order->seller->user->name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $order->seller->business_name ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-size:.85rem">{{ $order->customer_name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $order->customer_phone }}</div>
                    </td>
                    <td style="font-size:.82rem;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $order->delivery_address }}
                    </td>
                    <td>
                        @if($order->driver)
                            <div style="font-size:.85rem">{{ $order->driver->user->name }}</div>
                        @else
                            <span class="text-muted" style="font-size:.82rem">Unassigned</span>
                        @endif
                    </td>
                    <td style="font-weight:600">${{ number_format($order->delivery_fee,2) }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td style="font-size:.78rem;color:#64748b">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        No orders found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-3">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
