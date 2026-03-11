@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'System overview & analytics')

@section('topbar-actions')
    <a href="{{ route('admin.orders') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-box-seam me-1"></i>All Orders
    </a>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-shop"></i></div>
            <div class="stat-value">{{ $stats['total_sellers'] }}</div>
            <div class="stat-label">Total Sellers ({{ $stats['active_sellers'] }} active)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
            <div class="stat-value">{{ $stats['total_drivers'] }}</div>
            <div class="stat-label">Total Drivers ({{ $stats['active_drivers'] }} active)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-teal">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
</div>

{{-- Order Status Overview --}}
<div class="row g-3 mb-4">
    @php
        $statusConfig = [
            'pending'    => ['warning',  'clock',        'Pending'],
            'assigned'   => ['info',     'person-check', 'Assigned'],
            'picked_up'  => ['primary',  'bag-check',    'Picked Up'],
            'on_the_way' => ['secondary','truck',        'On The Way'],
            'delivered'  => ['success',  'check-circle', 'Delivered'],
            'failed'     => ['danger',   'x-circle',     'Failed'],
        ];
    @endphp
    @foreach($statusConfig as $key => [$color, $icon, $label])
    <div class="col-4 col-md-2">
        <div class="card text-center p-3">
            <div class="text-{{ $color }} mb-1" style="font-size:1.5rem">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <div style="font-size:1.4rem;font-weight:800">{{ $ordersByStatus[$key] ?? 0 }}</div>
            <div style="font-size:.72rem;color:#64748b">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    {{-- Orders Trend Chart --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header-custom">
                <h5><i class="bi bi-graph-up me-2 text-primary"></i>Orders Last 30 Days</h5>
            </div>
            <div class="p-3">
                <canvas id="ordersChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Drivers --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-custom">
                <h5><i class="bi bi-trophy me-2 text-warning"></i>Top Drivers</h5>
            </div>
            <div class="p-3">
                @forelse($topDrivers as $i => $driver)
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:26px;height:26px;border-radius:50%;background:{{ ['#2563eb','#7c3aed','#059669','#d97706','#dc2626'][$i] ?? '#64748b' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size:.85rem;font-weight:600">{{ $driver->user->name }}</div>
                        <div style="font-size:.75rem;color:#64748b">{{ $driver->user->driver->vehicle_type ?? '—' }}</div>
                    </div>
                    <div style="font-size:.85rem;font-weight:700;color:#059669">{{ $driver->delivered_count }}</div>
                </div>
                @empty
                    <p class="text-muted text-center small">No data yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card">
    <div class="card-header-custom">
        <h5><i class="bi bi-clock-history me-2 text-info"></i>Recent Orders</h5>
        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Seller</th>
                    <th>Customer</th>
                    <th>Driver</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><code style="font-size:.78rem">{{ $order->order_number }}</code></td>
                    <td>{{ $order->seller->user->name }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->driver?->user->name ?? '<span class="text-muted">—</span>' }}</td>
                    <td>${{ number_format($order->delivery_fee, 2) }}</td>
                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                    <td style="font-size:.78rem;color:#64748b">{{ $order->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const trendData = @json($orderTrend);
const labels = [];
const values = [];

// Fill last 30 days
for (let i = 29; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    const key = d.toISOString().slice(0, 10);
    labels.push(key.slice(5)); // MM-DD
    const found = trendData.find(r => r.date === key);
    values.push(found ? found.count : 0);
}

new Chart(document.getElementById('ordersChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Orders',
            data: values,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,.08)',
            fill: true,
            tension: .4,
            pointRadius: 3,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
        }
    }
});
</script>
@endpush
