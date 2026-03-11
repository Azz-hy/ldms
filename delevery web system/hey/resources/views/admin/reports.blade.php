@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-sub', 'Data insights and performance metrics')

@section('content')

<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach(['daily'=>'Daily','monthly'=>'Monthly','seller'=>'Seller Performance','driver'=>'Driver Performance'] as $key => $label)
    <a href="{{ route('admin.reports', ['type' => $key]) }}"
       class="btn btn-sm {{ request('type', 'daily') === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

@php $type = request('type', 'daily'); @endphp

{{-- Daily Report --}}
@if($type === 'daily')
<div class="row mb-3 g-2 align-items-end">
    <div class="col-auto">
        <form action="{{ route('admin.reports') }}" method="GET" class="d-flex gap-2 align-items-end">
            <input type="hidden" name="type" value="daily">
            <div>
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $data['date'] }}">
            </div>
            <button type="submit" class="btn btn-primary">View</button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-plus-circle"></i></div>
            <div class="stat-value">{{ $data['total_created'] }}</div>
            <div class="stat-label">Orders Created</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $data['delivered'] }}</div>
            <div class="stat-label">Delivered</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-red">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-value">{{ $data['failed'] }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-amber">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-value">${{ number_format($data['revenue'], 2) }}</div>
            <div class="stat-label">Revenue</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom"><h5>Orders by Status</h5></div>
            <div class="p-3">
                @foreach(['pending','assigned','picked_up','on_the_way','delivered','failed'] as $s)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="badge-status badge-{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</span>
                    <strong>{{ $data['by_status'][$s] ?? 0 }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom"><h5>Summary</h5></div>
            <div class="p-3">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Active Drivers Today</span>
                    <strong>{{ $data['active_drivers'] }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Total Revenue</span>
                    <strong>${{ number_format($data['revenue'], 2) }}</strong>
                </div>
                @if($data['delivered'] > 0)
                <div class="d-flex justify-content-between py-2">
                    <span>Success Rate</span>
                    <strong>{{ round($data['delivered'] / max($data['total_created'],1) * 100, 1) }}%</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- Monthly Report --}}
@if($type === 'monthly')
<div class="card">
    <div class="card-header-custom"><h5><i class="bi bi-calendar3 me-2"></i>Monthly Revenue</h5></div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Month</th><th>Total Orders</th><th>Completed</th><th>Revenue</th><th>Avg Fee</th></tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('F Y') }}</td>
                    <td>{{ $row->orders }}</td>
                    <td>{{ $row->completed }}</td>
                    <td class="fw-600">${{ number_format($row->revenue, 2) }}</td>
                    <td>${{ $row->orders > 0 ? number_format($row->revenue / $row->orders, 2) : '0.00' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Seller Report --}}
@if($type === 'seller')
<div class="card">
    <div class="card-header-custom"><h5><i class="bi bi-shop me-2"></i>Seller Performance</h5></div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Seller</th><th>Business</th><th>Total Orders</th><th>Delivered</th><th>Failed</th><th>Completion %</th><th>Total Spent</th></tr>
            </thead>
            <tbody>
                @foreach($data as $seller)
                @php $rate = $seller->orders_count > 0 ? round($seller->delivered_count / $seller->orders_count * 100, 1) : 0; @endphp
                <tr>
                    <td>{{ $seller->user->name }}</td>
                    <td>{{ $seller->business_name ?? '—' }}</td>
                    <td>{{ $seller->orders_count }}</td>
                    <td class="text-success fw-600">{{ $seller->delivered_count }}</td>
                    <td class="text-danger">{{ $seller->failed_count }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px">
                                <div class="progress-bar bg-success" style="width:{{ $rate }}%"></div>
                            </div>
                            <span style="font-size:.8rem;min-width:35px">{{ $rate }}%</span>
                        </div>
                    </td>
                    <td class="fw-600">${{ number_format($seller->orders_sum_delivery_fee ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Driver Report --}}
@if($type === 'driver')
<div class="card">
    <div class="card-header-custom"><h5><i class="bi bi-person-badge me-2"></i>Driver Performance</h5></div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>Driver</th><th>Vehicle</th><th>Total Assigned</th><th>Delivered</th><th>Failed</th><th>Success Rate</th></tr>
            </thead>
            <tbody>
                @foreach($data as $driver)
                @php $rate = $driver->orders_count > 0 ? round($driver->delivered_count / $driver->orders_count * 100, 1) : 0; @endphp
                <tr>
                    <td>{{ $driver->user->name }}</td>
                    <td>{{ $driver->vehicle_type ?? '—' }} {{ $driver->vehicle_number ? "({$driver->vehicle_number})" : '' }}</td>
                    <td>{{ $driver->orders_count }}</td>
                    <td class="text-success fw-600">{{ $driver->delivered_count }}</td>
                    <td class="text-danger">{{ $driver->failed_count }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px">
                                <div class="progress-bar bg-success" style="width:{{ $rate }}%"></div>
                            </div>
                            <span style="font-size:.8rem;min-width:35px">{{ $rate }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
