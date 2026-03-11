<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_sellers'  => User::where('role', 'seller')->count(),
            'active_sellers' => User::where('role', 'seller')->where('is_active', true)->count(),
            'total_drivers'  => User::where('role', 'driver')->count(),
            'active_drivers' => User::where('role', 'driver')->where('is_active', true)->count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::where('status', 'delivered')->sum('delivery_fee'),
        ];

        $ordersByStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

        // Orders last 30 days (for chart)
        $orderTrend = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')->orderBy('date')->get();

        // Revenue last 12 months
        $revenueTrend = Order::selectRaw("strftime('%Y-%m', created_at) as month, SUM(delivery_fee) as revenue")
            ->where('status', 'delivered')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')->orderBy('month')->get();

        // Top drivers
        $topDrivers = Driver::with('user')
            ->withCount(['orders as delivered_count' => fn($q) => $q->where('status', 'delivered')])
            ->orderByDesc('delivered_count')->take(5)->get();

        $recentOrders = Order::with(['seller.user', 'driver.user'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'ordersByStatus', 'orderTrend', 'revenueTrend', 'topDrivers', 'recentOrders'));
    }

    // ─── User Management ─────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $role = $request->get('role', 'seller');
        $users = User::with(['seller', 'driver'])
            ->where('role', $role)
            ->latest()->paginate(15);
        return view('admin.users.index', compact('users', 'role'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'unique:users'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'role'             => ['required', Rule::in(['seller', 'driver'])],
            'password'         => ['required', 'confirmed', Password::min(8)],
            'business_name'    => ['nullable', 'string', 'max:255'],
            'business_address' => ['nullable', 'string'],
            'vehicle_type'     => ['nullable', Rule::in(['Motorcycle', 'Car', 'Van'])],
            'vehicle_number'   => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        if ($data['role'] === 'seller') {
            Seller::create(['user_id' => $user->id, 'business_name' => $data['business_name'] ?? null, 'business_address' => $data['business_address'] ?? null]);
        } else {
            Driver::create(['user_id' => $user->id, 'vehicle_type' => $data['vehicle_type'] ?? null, 'vehicle_number' => $data['vehicle_number'] ?? null]);
        }

        return redirect()->route('admin.users', ['role' => $data['role']])->with('success', ucfirst($data['role']) . ' created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'is_active'        => ['boolean'],
            'business_name'    => ['nullable', 'string', 'max:255'],
            'business_address' => ['nullable', 'string'],
            'vehicle_type'     => ['nullable', Rule::in(['Motorcycle', 'Car', 'Van'])],
            'vehicle_number'   => ['nullable', 'string', 'max:50'],
        ]);

        $user->update(['name' => $data['name'], 'phone' => $data['phone'], 'is_active' => $request->boolean('is_active')]);

        if ($user->isSeller() && $user->seller) {
            $user->seller->update(['business_name' => $data['business_name'] ?? null, 'business_address' => $data['business_address'] ?? null]);
        } elseif ($user->isDriver() && $user->driver) {
            $user->driver->update(['vehicle_type' => $data['vehicle_type'] ?? null, 'vehicle_number' => $data['vehicle_number'] ?? null]);
        }

        return redirect()->route('admin.users', ['role' => $user->role])->with('success', 'User updated.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    // ─── Orders ──────────────────────────────────────────────────────────────
    public function orders(Request $request)
    {
        $query = Order::with(['seller.user', 'driver.user']);

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('seller_id')) $query->where('seller_id', $request->seller_id);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('order_number', 'like', "%$s%")
                ->orWhere('customer_name', 'like', "%$s%")
                ->orWhere('customer_phone', 'like', "%$s%"));
        }

        $orders  = $query->latest()->paginate(15)->withQueryString();
        $sellers = Seller::with('user')->get();
        return view('admin.orders.index', compact('orders', 'sellers'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['seller.user', 'driver.user']);
        $drivers = Driver::with('user')->get();
        return view('admin.orders.show', compact('order', 'drivers'));
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate(['driver_id' => ['required', 'exists:drivers,id']]);

        if ($order->isFinal()) {
            return back()->withErrors(['driver_id' => 'Cannot assign driver to a finalized order.']);
        }

        $order->update([
            'driver_id'   => $request->driver_id,
            'status'      => 'assigned',
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Driver assigned successfully.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => ['required', 'string']]);

        if (!$order->canTransitionTo($request->status)) {
            return back()->withErrors(['status' => 'Invalid status transition.']);
        }

        $update = ['status' => $request->status];
        if ($request->status === 'failed') $update['failure_reason'] = $request->failure_reason;
        if ($request->status === 'delivered') $update['delivered_at'] = now();

        $order->update($update);
        return back()->with('success', 'Order status updated.');
    }

    // ─── Reports ─────────────────────────────────────────────────────────────
    public function reports(Request $request)
    {
        $type = $request->get('type', 'daily');
        $data = [];

        if ($type === 'daily') {
            $date = $request->get('date', today()->toDateString());
            $data = [
                'date'          => $date,
                'total_created' => Order::whereDate('created_at', $date)->count(),
                'delivered'     => Order::where('status', 'delivered')->whereDate('delivered_at', $date)->count(),
                'failed'        => Order::where('status', 'failed')->whereDate('updated_at', $date)->count(),
                'revenue'       => Order::where('status', 'delivered')->whereDate('delivered_at', $date)->sum('delivery_fee'),
                'by_status'     => Order::whereDate('created_at', $date)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
                'active_drivers'=> Driver::whereHas('orders', fn($q) => $q->whereDate('updated_at', $date))->count(),
            ];
        } elseif ($type === 'monthly') {
            $data = Order::selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as orders, SUM(CASE WHEN status='delivered' THEN delivery_fee ELSE 0 END) as revenue, SUM(CASE WHEN status='delivered' THEN 1 ELSE 0 END) as completed")
                ->groupBy('month')->orderByDesc('month')->take(12)->get();
        } elseif ($type === 'seller') {
            $data = Seller::with('user')
                ->withCount(['orders', 'orders as delivered_count' => fn($q) => $q->where('status', 'delivered'), 'orders as failed_count' => fn($q) => $q->where('status', 'failed')])
                ->withSum('orders', 'delivery_fee')
                ->orderByDesc('orders_count')->get();
        } elseif ($type === 'driver') {
            $data = Driver::with('user')
                ->withCount(['orders', 'orders as delivered_count' => fn($q) => $q->where('status', 'delivered'), 'orders as failed_count' => fn($q) => $q->where('status', 'failed')])
                ->orderByDesc('delivered_count')->get();
        }

        return view('admin.reports', compact('type', 'data'));
    }
}
