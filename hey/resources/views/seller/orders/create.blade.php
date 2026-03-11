@extends('layouts.app')
@section('title', 'New Order')
@section('page-title', 'Create Order')
@section('page-sub', 'Fill in the delivery details')

@section('topbar-actions')
    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-md-9 col-lg-8">
<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-plus-circle me-2 text-primary"></i>New Delivery Order</h5>
    </div>
    <div class="p-4">
        <form action="{{ route('seller.orders.store') }}" method="POST">
            @csrf

            <div class="p-3 mb-4 rounded" style="background:#f0f9ff;border:1px solid #bae6fd">
                <p class="mb-0 small" style="color:#0369a1">
                    <i class="bi bi-info-circle me-1"></i>
                    Fill in the customer and delivery details. Once submitted, the order will be pending until an admin assigns a driver.
                </p>
            </div>

            <h6 class="mb-3" style="font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:1px;color:#64748b">
                Customer Information
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                           value="{{ old('customer_name') }}" placeholder="Full name of recipient" required>
                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer Phone *</label>
                    <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                           value="{{ old('customer_phone') }}" placeholder="07xx-xxx-xxxx" required>
                    @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Delivery Address *</label>
                    <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                              rows="2" placeholder="Full delivery address including landmarks..." required>{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="mb-3" style="font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:1px;color:#64748b">
                Package & Pricing
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Product Description *</label>
                    <textarea name="product_description" class="form-control @error('product_description') is-invalid @enderror"
                              rows="2" placeholder="What is being delivered? Include size, fragility, etc.">{{ old('product_description') }}</textarea>
                    @error('product_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Fee (USD) *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="delivery_fee" class="form-control @error('delivery_fee') is-invalid @enderror"
                               value="{{ old('delivery_fee') }}" step="0.01" min="0.01" placeholder="0.00" required>
                    </div>
                    @error('delivery_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Zone</label>
                    <select name="delivery_zone" class="form-select">
                        <option value="">— Select Zone —</option>
                        <option {{ old('delivery_zone') === 'Zone A - City Center' ? 'selected':'' }}>Zone A - City Center</option>
                        <option {{ old('delivery_zone') === 'Zone B - Suburbs' ? 'selected':'' }}>Zone B - Suburbs</option>
                        <option {{ old('delivery_zone') === 'Zone C - Industrial' ? 'selected':'' }}>Zone C - Industrial</option>
                        <option {{ old('delivery_zone') === 'Zone D - Remote' ? 'selected':'' }}>Zone D - Remote</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Special Instructions</label>
                    <input type="text" name="special_instructions" class="form-control"
                           value="{{ old('special_instructions') }}" placeholder="e.g. Call before delivery">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send me-2"></i>Submit Order
                </button>
                <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
