<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/test', function () {
    $products = \App\Models\Product::with(['category', 'subcategory'])->take(6)->get();
    return view('test', compact('products'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products-simple', function () {
    $products = \App\Models\Product::with(['category', 'subcategory'])->paginate(12);
    return view('products.simple', compact('products'));
})->name('products.simple');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes (public for guest users, but will need session-based cart later)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
});

// Wishlist routes
Route::middleware('auth')->group(function () {
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/{wishlist}/add-product', [WishlistController::class, 'addProduct'])->name('wishlists.add-product');
    Route::delete('/wishlists/{wishlist}/items/{wishlistItem}', [WishlistController::class, 'removeProduct'])->name('wishlists.remove-product');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin product routes
    Route::prefix('admin/products')->group(function () {
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
