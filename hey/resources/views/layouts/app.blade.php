<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LDMS') — Local Delivery Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark:    #1e3a5f;
            --brand-accent:  #f59e0b;
            --sidebar-bg:    #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-text:  #94a3b8;
            --sidebar-active:#2563eb;
            --body-bg:       #f1f5f9;
            --card-bg:       #ffffff;
            --border:        #e2e8f0;
            --text-main:     #1e293b;
            --text-muted:    #64748b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-brand .logo-mark {
            width: 38px; height: 38px;
            background: var(--brand-primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff;
            margin-bottom: .5rem;
        }

        .sidebar-brand h1 {
            font-size: 1.1rem; font-weight: 800;
            color: #fff; margin: 0; letter-spacing: -.3px;
        }

        .sidebar-brand p {
            font-size: .7rem; color: var(--sidebar-text);
            margin: 0; text-transform: uppercase; letter-spacing: 1px;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }

        .nav-section {
            padding: .25rem 1.25rem .5rem;
            font-size: .65rem; font-weight: 700;
            color: #475569; text-transform: uppercase; letter-spacing: 1.5px;
            margin-top: .5rem;
        }

        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1.5rem;
            color: var(--sidebar-text);
            font-size: .875rem; font-weight: 500;
            border-radius: 0;
            transition: all .15s ease;
            text-decoration: none;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            color: #e2e8f0;
            background: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link.active {
            color: #fff;
            background: var(--sidebar-active);
        }

        .sidebar-nav .nav-link .bi { font-size: 1.05rem; flex-shrink: 0; }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #1e293b;
        }

        .user-card {
            display: flex; align-items: center; gap: .75rem;
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-primary), #7c3aed);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .875rem;
            flex-shrink: 0;
        }

        .user-card .user-name { font-size: .85rem; font-weight: 600; color: #e2e8f0; }
        .user-card .user-role { font-size: .72rem; color: var(--sidebar-text); }

        /* ── Main ── */
        .main-wrapper {
            margin-left: 260px;
            display: flex; flex-direction: column; min-height: 100vh;
        }

        .topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: .875rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }

        .topbar-title { font-size: 1.15rem; font-weight: 700; color: var(--text-main); }
        .topbar-sub   { font-size: .8rem; color: var(--text-muted); }

        .main-content { padding: 1.75rem; flex: 1; }

        /* ── Cards ── */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }

        .card-header-custom {
            padding: 1.25rem 1.5rem .75rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .card-header-custom h5 {
            font-size: .95rem; font-weight: 700; margin: 0; color: var(--text-main);
        }

        /* ── Stat Cards ── */
        .stat-card {
            border-radius: 14px;
            padding: 1.35rem 1.5rem;
            position: relative; overflow: hidden;
        }

        .stat-card .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: .75rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem; font-weight: 800; line-height: 1;
        }

        .stat-card .stat-label {
            font-size: .78rem; margin-top: .3rem; opacity: .75; font-weight: 500;
        }

        .stat-blue    { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; }
        .stat-green   { background: linear-gradient(135deg, #059669, #10b981); color: #fff; }
        .stat-amber   { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; }
        .stat-purple  { background: linear-gradient(135deg, #7c3aed, #a78bfa); color: #fff; }
        .stat-red     { background: linear-gradient(135deg, #dc2626, #f87171); color: #fff; }
        .stat-teal    { background: linear-gradient(135deg, #0d9488, #2dd4bf); color: #fff; }

        .stat-card .stat-icon { background: rgba(255,255,255,.2); }

        /* ── Status Badges ── */
        .badge-status {
            font-size: .72rem; font-weight: 600;
            padding: .3rem .7rem; border-radius: 20px;
        }

        .badge-pending    { background: #fef3c7; color: #92400e; }
        .badge-assigned   { background: #dbeafe; color: #1e40af; }
        .badge-picked_up  { background: #ede9fe; color: #5b21b6; }
        .badge-on_the_way { background: #e0f2fe; color: #075985; }
        .badge-delivered  { background: #dcfce7; color: #166534; }
        .badge-failed     { background: #fee2e2; color: #991b1b; }

        /* ── Tables ── */
        .table-custom { font-size: .875rem; }
        .table-custom thead th {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px;
            color: var(--text-muted); background: #f8fafc;
            border-bottom: 2px solid var(--border); padding: .875rem 1rem;
        }
        .table-custom tbody td { padding: .875rem 1rem; vertical-align: middle; }
        .table-custom tbody tr:hover { background: #f8fafc; }

        /* ── Buttons ── */
        .btn-primary   { background: var(--brand-primary); border-color: var(--brand-primary); }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }

        /* ── Forms ── */
        .form-label { font-size: .85rem; font-weight: 600; color: var(--text-main); margin-bottom: .35rem; }
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 8px; font-size: .875rem;
            padding: .55rem .875rem;
            transition: border-color .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        /* ── Alerts ── */
        .alert { border-radius: 10px; font-size: .875rem; border: none; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #dbeafe; color: #1e40af; }

        /* ── Sidebar toggle (mobile) ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-260px); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

@auth
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-mark"><i class="bi bi-truck"></i></div>
        <h1>LDMS</h1>
        <p>Delivery Management</p>
    </div>

    <nav class="sidebar-nav">
        @if(auth()->user()->isAdmin())
            <div class="nav-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <div class="nav-section">Management</div>
            <a href="{{ route('admin.users', ['role' => 'seller']) }}" class="nav-link {{ request()->routeIs('admin.users') && request('role') !== 'driver' ? 'active' : '' }}">
                <i class="bi bi-shop"></i> Sellers
            </a>
            <a href="{{ route('admin.users', ['role' => 'driver']) }}" class="nav-link {{ request()->routeIs('admin.users') && request('role') === 'driver' ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Drivers
            </a>
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> All Orders
            </a>
            <div class="nav-section">Reports</div>
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Reports & Analytics
            </a>
        @elseif(auth()->user()->isSeller())
            <div class="nav-section">Overview</div>
            <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <div class="nav-section">Orders</div>
            <a href="{{ route('seller.orders') }}" class="nav-link {{ request()->routeIs('seller.orders') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> My Orders
            </a>
            <a href="{{ route('seller.orders.create') }}" class="nav-link {{ request()->routeIs('seller.orders.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> New Order
            </a>
        @elseif(auth()->user()->isDriver())
            <div class="nav-section">Overview</div>
            <a href="{{ route('driver.dashboard') }}" class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <div class="nav-section">Deliveries</div>
            <a href="{{ route('driver.deliveries') }}" class="nav-link {{ request()->routeIs('driver.deliveries') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> My Deliveries
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-secondary p-0" title="Logout">
                    <i class="bi bi-box-arrow-right" style="font-size:1.1rem"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">
        <div>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('page-sub', 'Local Delivery Management System')</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            @yield('topbar-actions')
        </div>
    </div>

    <div class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
@else
    @yield('content')
@endauth

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
