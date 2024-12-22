<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Place order
    public function placeOrder()
    {
        try {
            $cart = Redis::get('cart:' . Auth::id());

            if (!$cart) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $cart = json_decode($cart, true);
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($orderItems as $orderItem) {
                $order->orderItems()->create($orderItem);
            }

            Redis::del('cart:' . Auth::id());

            return response()->json($order->load('orderItems'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to place the order'], 500);
        }
    }


    // View orders
    public function viewOrders()
    {
        $orders = Order::with('orderItems.product')->where('user_id', Auth::id())->get();
        return $orders;
    }

}