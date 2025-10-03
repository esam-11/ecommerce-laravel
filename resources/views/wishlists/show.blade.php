<x-app-layout>
    <x-slot name="title">{{ $wishlist->name }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-heart ml-2"></i>
                {{ $wishlist->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('wishlists.edit', $wishlist) }}"
                   class="bg-blue-600 text-red px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-edit ml-1"></i>
                    تعديل
                </a>
                <form method="POST" action="{{ route('wishlists.destroy', $wishlist) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه القائمة؟')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <i class="fas fa-trash ml-1"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($wishlist->wishlistItems->count() > 0)
            <div class="my-4 text-lg">
                <strong id="wishlist-item-count">{{ $wishlist->wishlistItems->count() }}</strong> منتج في هذه القائمة
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlist->wishlistItems as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                 class="w-full h-48 object-cover" 
                                 onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('products.show', $item->product) }}" class="hover:text-blue-600">
                                    {{ $item->product->name }}
                                </a>
                            </h3>

                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $item->product->description }}
                            </p>

                            <div class="flex items-center justify-between mb-3">
                                <div class="text-lg font-bold text-blue-600">
                                    {{ number_format($item->product->price, 2) }} ج.م
                                </div>
                                <div class="text-sm text-gray-500">
                                    متوفر: {{ $item->product->stock_quantity }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.show', $item->product) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض التفاصيل
                                </a>

                                <form method="POST" action="{{ route('cart.add') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                            {{ $item->product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus ml-1"></i>
                                        أضف للسلة
                                    </button>
                                </form>
                            </div>

                            <!-- Remove from Wishlist -->
                            <div class="mt-3 text-center">
                                <form method="POST" action="{{ route('wishlists.remove-product', ['wishlist' => $wishlist, 'wishlistItem' => $item]) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج من القائمة؟')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                                        <i class="fas fa-trash ml-1"></i>
                                        حذف من القائمة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-heart text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">القائمة فارغة</h3>
                <p class="text-gray-600 mb-8">لم تقم بإضافة أي منتجات إلى هذه القائمة بعد</p>
                <a href="{{ route('products.index') }}"
                   class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                    <i class="fas fa-arrow-right ml-2"></i>
                    ابدأ التسوق
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to update the wishlist item count
                function updateWishlistItemCount(count) {
                    const wishlistItemCountElement = document.getElementById('wishlist-item-count');
                    if (wishlistItemCountElement) {
                        wishlistItemCountElement.textContent = count;
                    }
                }

                // Listen for a custom event to update the wishlist count (e.g., from product page AJAX)
                document.addEventListener('wishlist:updated', function (event) {
                    const newCount = event.detail.count;
                    updateWishlistItemCount(newCount);
                    // Optionally, re-render the product list or show/hide empty state
                    // This part will be more complex and might require re-fetching the wishlist content
                    // or receiving updated HTML from the server.
                });
            });
        </script>
    @endpush
</x-app-layout>