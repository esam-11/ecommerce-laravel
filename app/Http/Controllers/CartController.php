<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        $cart = Auth::user()->cart()->with('cartItems.product')->first();

        if (!$cart) {
            $cart = Auth::user()->cart()->create();
        }

        $cartItems = $cart->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart = Auth::user()->cart()->first();
        if (!$cart) {
            $cart = Auth::user()->cart()->create();
        }

        $existingItem = $cart->cartItems()->where('product_id', $request->product_id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;

            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Insufficient stock available for the requested quantity.');
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        if ($request->ajax()) {
            $cartCount = Auth::user()->cart()->first()->cartItems()->sum('quantity');
            return response()->json([
                
                'message' => 'product added to cart.',
                'cart_count' => $cartCount
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart.');
    }

    /**
     * Clear all items from the cart.
     */
    public function clear()
    {
        $cart = Auth::user()->cart()->first();

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return back()->with('success', 'Cart cleared.');
    }

    /**
     * Get cart count for AJAX requests.
     */
    public function count()
    {
        $cart = Auth::user()->cart()->first();
        $count = $cart ? $cart->cartItems()->sum('quantity') : 0;

        return response()->json(['count' => $count]);
    }
}
