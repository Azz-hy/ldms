<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    private function seller()
    {
        return auth()->user()->seller;
    }

    public function dashboard()
    {
        $seller = $this->seller();
        $stats  = [
            'total'     => $seller->totalOrders(),
            'delivered' => $seller->deliveredOrders(),
            'pending'   => $seller->pendingOrders(),
            'failed'    => $seller->failedOrders(),
            'total_spent'       => $seller->totalSpent(),
            'avg_fee'           => $seller->orders()->avg('delivery_fee') ?? 0,
            'this_month_spent'  => $seller->thisMonthSpent(),
        ];
        $recentOrders = $seller->orders()->with('driver.user')->latest()->take(5)->get();
        return view('seller.dashboard', compact('stats', 'recentOrders'));
    }

    public function orders(Request $request)
    {
        $query = $this->seller()->orders()->with('driver.user');
        if ($request->filled('status')) $query->where('status', $request->status);
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('seller.orders.index', compact('orders'));
    }

    public function createOrder()
    {
        return view('seller.orders.create');
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name'       => ['required', 'string', 'min:3', 'max:100'],
            'customer_phone'      => ['required', 'string', 'max:20'],
            'delivery_address'    => ['required', 'string'],
            'product_description' => ['required', 'string', 'min:10'],
            'delivery_fee'        => ['required', 'numeric', 'min:0.01'],
            'special_instructions'=> ['nullable', 'string'],
            'delivery_zone'       => ['nullable', 'string', 'max:100'],
        ]);

        $order = $this->seller()->orders()->create($data);
        return redirect()->route('seller.orders.show', $order)->with('success', 'Order created! Order #' . $order->order_number);
    }

    public function showOrder(Order $order)
    {
        $this->authorizeOrder($order);
        return view('seller.orders.show', compact('order'));
    }

    public function editOrder(Order $order)
    {
        $this->authorizeOrder($order);
        if (!$order->isEditable()) {
            return back()->withErrors(['order' => 'Only pending orders can be edited.']);
        }
        return view('seller.orders.edit', compact('order'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $this->authorizeOrder($order);
        if (!$order->isEditable()) {
            return back()->withErrors(['order' => 'Only pending orders can be edited.']);
        }

        $data = $request->validate([
            'customer_name'       => ['required', 'string', 'min:3', 'max:100'],
            'customer_phone'      => ['required', 'string', 'max:20'],
            'delivery_address'    => ['required', 'string'],
            'product_description' => ['required', 'string', 'min:10'],
            'delivery_fee'        => ['required', 'numeric', 'min:0.01'],
            'special_instructions'=> ['nullable', 'string'],
            'delivery_zone'       => ['nullable', 'string', 'max:100'],
        ]);

        $order->update($data);
        return redirect()->route('seller.orders.show', $order)->with('success', 'Order updated.');
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->seller_id !== $this->seller()->id) {
            abort(403);
        }
    }
}
