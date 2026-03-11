@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', 'Add User')
@section('page-sub', 'Create a new seller or driver account')

@section('topbar-actions')
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header-custom"><h5><i class="bi bi-person-plus me-2"></i>New User Account</h5></div>
    <div class="p-4">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" id="roleSelect" required>
                        <option value="seller" {{ old('role','seller') === 'seller' ? 'selected':'' }}>Seller</option>
                        <option value="driver" {{ old('role') === 'driver' ? 'selected':'' }}>Driver</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                {{-- Seller fields --}}
                <div id="sellerFields" class="col-12">
                    <hr>
                    <h6 style="font-weight:700;color:#64748b;font-size:.8rem;text-transform:uppercase;letter-spacing:1px">Seller Info</h6>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Business Name</label>
                            <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Business Address</label>
                            <input type="text" name="business_address" class="form-control" value="{{ old('business_address') }}">
                        </div>
                    </div>
                </div>

                {{-- Driver fields --}}
                <div id="driverFields" class="col-12 d-none">
                    <hr>
                    <h6 style="font-weight:700;color:#64748b;font-size:.8rem;text-transform:uppercase;letter-spacing:1px">Driver Info</h6>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type</label>
                            <select name="vehicle_type" class="form-select">
                                <option value="">— Select —</option>
                                <option value="Motorcycle" {{ old('vehicle_type') === 'Motorcycle' ? 'selected':'' }}>Motorcycle</option>
                                <option value="Car" {{ old('vehicle_type') === 'Car' ? 'selected':'' }}>Car</option>
                                <option value="Van" {{ old('vehicle_type') === 'Van' ? 'selected':'' }}>Van</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Number (Plate)</label>
                            <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number') }}">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Create User
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
const roleSelect = document.getElementById('roleSelect');
function toggleRoleFields() {
    const role = roleSelect.value;
    document.getElementById('sellerFields').classList.toggle('d-none', role !== 'seller');
    document.getElementById('driverFields').classList.toggle('d-none', role !== 'driver');
}
roleSelect.addEventListener('change', toggleRoleFields);
toggleRoleFields();
</script>
@endpush
