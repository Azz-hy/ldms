@extends('layouts.app')
@section('title', 'Edit Order')
@section('page-title', 'Edit Order')
@section('page-sub', $order->order_number)

@section('topbar-actions')
    <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-md-9 col-lg-8">
<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-pencil me-2"></i>Edit Order: {{ $order->order_number }}</h5>
    </div>
    <div class="p-4">
        <form action="{{ route('seller.orders.update', $order) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                           value="{{ old('customer_name', $order->customer_name) }}" required>
                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer Phone *</label>
                    <input type="text" name="customer_phone" class="form-control"
                           value="{{ old('customer_phone', $order->customer_phone) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Delivery Address *</label>
                    <textarea name="delivery_address" class="form-control" rows="2" required>{{ old('delivery_address', $order->delivery_address) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Product Description *</label>
                    <textarea name="product_description" class="form-control" rows="2">{{ old('product_description', $order->product_description) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Fee *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="delivery_fee" class="form-control" step="0.01" min="0.01"
                               value="{{ old('delivery_fee', $order->delivery_fee) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Zone</label>
                    <select name="delivery_zone" class="form-select">
                        <option value="">— Select Zone —</option>
                        @foreach(['Zone A - City Center','Zone B - Suburbs','Zone C - Industrial','Zone D - Remote'] as $z)
                            <option {{ old('delivery_zone', $order->delivery_zone) === $z ? 'selected':'' }}>{{ $z }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Special Instructions</label>
                    <input type="text" name="special_instructions" class="form-control"
                           value="{{ old('special_instructions', $order->special_instructions) }}">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Save Changes</button>
                <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
