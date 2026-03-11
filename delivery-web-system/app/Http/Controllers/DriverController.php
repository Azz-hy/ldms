<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    private function driver()
    {
        return auth()->user()->driver;
    }

    public function dashboard()
    {
        $driver = $this->driver();
        $stats  = [
            'assigned'      => $driver->activeOrdersCount(),
            'today'         => $driver->completedToday(),
            'this_week'     => $driver->completedThisWeek(),
            'total'         => $driver->totalCompleted(),
            'success_rate'  => $driver->successRate(),
        ];
        $activeOrders = $driver->orders()->with('seller.user')
            ->whereNotIn('status', ['delivered', 'failed'])->latest()->get();
        return view('driver.dashboard', compact('stats', 'activeOrders'));
    }

    public function deliveries(Request $request)
    {
        $query = $this->driver()->orders()->with('seller.user');
        if ($request->filled('status')) $query->where('status', $request->status);
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('driver.deliveries.index', compact('orders'));
    }

    public function showDelivery(Order $order)
    {
        $this->authorizeOrder($order);
        return view('driver.deliveries.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $request->validate([
            'status'         => ['required', 'string'],
            'driver_notes'   => ['nullable', 'string'],
            'failure_reason' => ['nullable', 'string'],
        ]);

        if (!$order->canTransitionTo($request->status)) {
            return back()->withErrors(['status' => 'Invalid status transition.']);
        }

        $update = ['status' => $request->status];
        if ($request->filled('driver_notes'))  $update['driver_notes']   = $request->driver_notes;
        if ($request->status === 'failed')      $update['failure_reason'] = $request->failure_reason;
        if ($request->status === 'picked_up')   $update['picked_up_at']   = now();
        if ($request->status === 'delivered')   $update['delivered_at']   = now();

        $order->update($update);
        return back()->with('success', 'Delivery status updated to: ' . $order->fresh()->statusLabel());
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->driver_id !== $this->driver()->id) {
            abort(403);
        }
    }
}
