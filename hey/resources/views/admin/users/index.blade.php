@extends('layouts.app')
@section('title', ucfirst($role) . 's')
@section('page-title', ucfirst($role) . 's')
@section('page-sub', 'Manage ' . $role . ' accounts')

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Add {{ ucfirst($role) }}
    </a>
@endsection

@section('content')
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.users', ['role' => 'seller']) }}"
       class="btn btn-sm {{ $role === 'seller' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-shop me-1"></i>Sellers
    </a>
    <a href="{{ route('admin.users', ['role' => 'driver']) }}"
       class="btn btn-sm {{ $role === 'driver' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-person-badge me-1"></i>Drivers
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    @if($role === 'seller')
                        <th>Business</th>
                    @else
                        <th>Vehicle</th>
                    @endif
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:#94a3b8;font-size:.78rem">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;font-size:.875rem">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.875rem">{{ $user->email }}</td>
                    <td style="font-size:.875rem">{{ $user->phone ?? '—' }}</td>
                    @if($role === 'seller')
                        <td style="font-size:.85rem">{{ $user->seller?->business_name ?? '—' }}</td>
                    @else
                        <td style="font-size:.85rem">
                            {{ $user->driver?->vehicle_type ?? '—' }}
                            @if($user->driver?->vehicle_number)
                                <span class="text-muted">({{ $user->driver->vehicle_number }})</span>
                            @endif
                        </td>
                    @endif
                    <td>
                        @if($user->is_active)
                            <span class="badge-status" style="background:#dcfce7;color:#166534">Active</span>
                        @else
                            <span class="badge-status" style="background:#fee2e2;color:#991b1b">Inactive</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:#64748b">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        No {{ $role }}s found. <a href="{{ route('admin.users.create') }}">Add one now.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-3">{{ $users->links() }}</div>
    @endif
</div>
@endsection
