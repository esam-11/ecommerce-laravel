<x-app-layout>
    <x-slot name="title">المنتجات</x-slot>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-box ml-2"></i>
            المنتجات
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Product Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img src="https://via.placeholder.com/300x200?text={{ urlencode($product->name) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $product->name }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-3">
                            {{ Str::limit($product->description, 100) }}
                        </p>

                        <div class="flex items-center justify-between mb-3">
                            <div class="text-lg font-bold text-blue-600">
                                {{ number_format($product->price, 2) }} ج.م
                            </div>
                            <div class="text-sm text-gray-500">
                                متوفر: {{ $product->stock_quantity }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('products.show', $product) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                <i class="fas fa-eye ml-1"></i>
                                عرض التفاصيل
                            </a>

                            @auth
                                <form method="POST" action="{{ route('cart.add') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm"
                                            {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus ml-1"></i>
                                        أضف للسلة
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                                    <i class="fas fa-sign-in-alt ml-1"></i>
                                    تسجيل الدخول
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد منتجات</h3>
                    <p class="text-gray-500">لم يتم العثور على منتجات</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
