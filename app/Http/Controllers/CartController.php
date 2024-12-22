<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Product;

class CartController extends Controller
{
    // Add item to cart
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $cart = Redis::get('cart:' . auth()->id());

        $cart = $cart ? json_decode($cart, true) : [];

        $cart[$request->product_id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => isset($cart[$request->product_id]) ? $cart[$request->product_id]['quantity'] + 1 : 1
        ];

        Redis::set('cart:' . auth()->id(), json_encode($cart));

        return response()->json(['message' => 'Item added to cart']);
    }

    // Remove item from cart
    public function removeFromCart($productId)
    {
        $cart = Redis::get('cart:' . auth()->id());

        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $cart = json_decode($cart, true);

        if (!isset($cart[$productId])) {
            return response()->json(['error' => 'Product not in cart'], 404);
        }

        unset($cart[$productId]);

        Redis::set('cart:' . auth()->id(), json_encode($cart));

        return response()->json(['message' => 'Item removed from cart']);
    }

    // View cart
    public function viewCart()
    {
        $cart = Redis::get('cart:' . auth()->id());

        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        return response()->json(json_decode($cart));
    }
    // Update product quantity in cart
    public function updateCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is at least 1
        ]);

        $cart = Redis::get('cart:' . auth()->id());

        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $cart = json_decode($cart, true);

        if (!isset($cart[$productId])) {
            return response()->json(['error' => 'Product not in cart'], 404);
        }

        // Update the product's quantity
        $cart[$productId]['quantity'] = $request->quantity;

        // If quantity is 0, remove the item from the cart
        if ($request->quantity == 0) {
            unset($cart[$productId]);
        }

        Redis::set('cart:' . auth()->id(), json_encode($cart));

        return response()->json(['message' => 'Cart updated successfully']);
    }
}