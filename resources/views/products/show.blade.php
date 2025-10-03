<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home ml-1"></i>
                        الرئيسية
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400 mx-2"></i>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">
                            المنتجات
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400 mx-2"></i>
                        <span class="text-gray-500">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-100 object-cover"
                             onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <span class="flex items-center">
                            <i class="fas fa-tag ml-1"></i>
                            {{ $product->category->name }}
                        </span>
                        @if($product->subcategory)
                            <span class="flex items-center">
                                <i class="fas fa-layer-group ml-1"></i>
                                {{ $product->subcategory->name }}
                            </span>
                        @endif
                        <span class="flex items-center">
                            <i class="fas fa-barcode ml-1"></i>
                            {{ $product->sku }}
                        </span>
                    </div>
                </div>

                <div class="text-3xl font-bold text-blue-600">
                    {{ number_format($product->price, 2) }} ج.م
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">الوصف</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">معلومات المنتج</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">الكمية المتاحة:</span>
                            <span class="font-semibold {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الحالة:</span>
                            <span class="font-semibold {{ $product->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->is_active ? 'متوفر' : 'غير متوفر' }}
                            </span>
                        </div>
                        @if($product->weight)
                            <div class="flex justify-between">
                                <span class="text-gray-600">الوزن:</span>
                                <span class="font-semibold">{{ $product->weight }} كجم</span>
                            </div>
                        @endif
                        @if($product->dimensions)
                            <div class="flex justify-between">
                                <span class="text-gray-600">الأبعاد:</span>
                                <span class="font-semibold">{{ $product->dimensions }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add to Cart Form -->
            @auth
                @if(auth()->user()->is_admin)
                    <div class="flex space-x-2 mb-4">
                        <a href="{{ route('products.edit', $product) }}"
                           class="flex-1 btn btn-warning bg-yellow-500 text-white px-6 py-3 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 font-semibold text-center">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل المنتج
                        </a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full btn btn-danger bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 font-semibold">
                                <i class="fas fa-trash ml-2"></i>
                                حذف المنتج
                            </button>
                        </form>
                    </div>
                @endif
                <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <label for="quantity" class="text-sm font-medium text-gray-700">الكمية:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                               class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold"
                                {{ $product->stock_quantity <= 0 || !$product->is_active ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus ml-2"></i>
                            أضف للسلة
                        </button>
                    </div>
                </form>

                @if(Auth::user()->wishlists->count() > 0)
                    <div class="relative mt-4" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-heart ml-2"></i>
                            أضف للمفضلة
                        </button>
                        
                        <!-- Dropdown for wishlist selection -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                            <div class="py-1">
                                @foreach(Auth::user()->wishlists as $wishlist)
                                    <form method="POST" action="{{ route('wishlists.add-product', $wishlist) }}" class="block add-to-wishlist-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                            {{ $wishlist->name }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('wishlists.create') }}" 
                       class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-center mt-4">
                        <i class="fas fa-heart ml-2"></i>
                        إنشاء قائمة مفضلة
                    </a>
                @endif
            @else
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <p class="text-gray-600 mb-4">يجب تسجيل الدخول لإضافة المنتج للسلة</p>
                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        تسجيل الدخول
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">منتجات ذات صلة</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-40 object-cover" onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $relatedProduct->name }}
                            </h3>
                            <div class="text-lg font-bold text-blue-600 mb-3">
                                {{ number_format($relatedProduct->price, 2) }} ر.س
                            </div>
                            <a href="{{ route('products.show', $relatedProduct) }}" 
                               class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-md hover:bg-blue-700">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.add-to-wishlist-form').forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const productId = formData.get('product_id');
                        const formAction = form.action;
                        const wishlistIdMatch = formAction.match(/wishlists\/(\d+)\/add-product/);
                        const wishlistId = wishlistIdMatch ? wishlistIdMatch[1] : 'N/A';

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            // Optionally, update the UI to reflect the product being added to the wishlist
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء إضافة المنتج إلى قائمة الأمنيات.');
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
