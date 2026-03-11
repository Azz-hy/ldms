@extends('layouts.app')
@section('title', 'My Deliveries')
@section('page-title', 'My Deliveries')
@section('page-sub', 'All assigned delivery orders')

@section('content')
<div class="mb-3">
    <form action="{{ route('driver.deliveries') }}" method="GET" class="d-flex gap-2">
        <select name="status" class="form-select" style="max-width:180px">
            <option value="">All</option>
            @foreach(['assigned','picked_up','on_the_way','delivered','failed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
        <a href="{{ route('driver.deliveries') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Address</th><th>Seller</th><th>Fee</th><th>Status</th><th>Actions</th></tr>
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
                    <td style="font-size:.85rem">{{ $order->seller->user->name }}</td>
                    <td class="fw-600">${{ number_format($order->delivery_fee, 2) }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td>
                        <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        No deliveries found
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
