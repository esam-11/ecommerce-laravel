<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the user's wishlists.
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('wishlistItems.product')->get();
        return view('wishlists.index', compact('wishlists'));
    }

    /**
     * Show the form for creating a new wishlist.
     */
    public function create()
    {
        return view('wishlists.create');
    }

    /**
     * Store a newly created wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->wishlists()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist created successfully.');
    }

    /**
     * Display the specified wishlist.
     */
    public function show(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $wishlist->load('wishlistItems.product');

        return view('wishlists.show', compact('wishlist'));
    }

    /**
     * Add a product to wishlist.
     */
    public function addProduct(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existingItem = $wishlist->wishlistItems()
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Product is already in this wishlist.', 'wishlist_count' => $wishlist->wishlistItems->count()], 409);
            }
            return back()->with('error', 'Product is already in this wishlist.');
        }

        $wishlistItem = $wishlist->wishlistItems()->create([
            'product_id' => $request->product_id,
        ]);

        if ($request->ajax()) {
            $wishlist->load('wishlistItems.product');
            return response()->json([
                'message' => 'تمت إضافة المنتج إلى قائمة الأمنيات.',
                'wishlist_count' => $wishlist->wishlistItems->count(),
                'wishlist_item' => $wishlistItem->load('product')
            ]);
        }
        return back()->with('success', 'Product added to wishlist.');
    }

    /**
     * Remove a product from wishlist.
     */
    public function removeProduct(Wishlist $wishlist, WishlistItem $wishlistItem)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $wishlistItem->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }

    /**
     * Show the form for editing the specified wishlist.
     */
    public function edit(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('wishlists.edit', compact('wishlist'));
    }

    /**
     * Update the specified wishlist.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $wishlist->update($request->only('name'));

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist updated successfully.');
    }

    /**
     * Remove the specified wishlist.
     */
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $wishlist->delete();

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist deleted successfully.');
    }
}
