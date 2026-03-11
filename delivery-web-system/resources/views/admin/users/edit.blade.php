@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-sub', $user->name)

@section('topbar-actions')
    <a href="{{ route('admin.users', ['role' => $user->role]) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header-custom"><h5><i class="bi bi-pencil me-2"></i>Edit: {{ $user->name }}</h5></div>
    <div class="p-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email (read-only)</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Account Active</label>
                    </div>
                </div>

                @if($user->isSeller() && $user->seller)
                <div class="col-12"><hr>
                    <h6 style="font-weight:700;color:#64748b;font-size:.8rem;text-transform:uppercase;letter-spacing:1px">Seller Info</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business Name</label>
                    <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $user->seller->business_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business Address</label>
                    <input type="text" name="business_address" class="form-control" value="{{ old('business_address', $user->seller->business_address) }}">
                </div>
                @elseif($user->isDriver() && $user->driver)
                <div class="col-12"><hr>
                    <h6 style="font-weight:700;color:#64748b;font-size:.8rem;text-transform:uppercase;letter-spacing:1px">Driver Info</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select">
                        <option value="">— Select —</option>
                        @foreach(['Motorcycle','Car','Van'] as $v)
                            <option {{ old('vehicle_type',$user->driver->vehicle_type) === $v ? 'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle Number</label>
                    <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number', $user->driver->vehicle_number) }}">
                </div>
                @endif

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Update User
                    </button>
                    <a href="{{ route('admin.users', ['role' => $user->role]) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
