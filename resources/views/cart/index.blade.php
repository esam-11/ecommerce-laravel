<x-app-layout>
    <x-slot name="title">سلة التسوق</x-slot>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-shopping-cart ml-2"></i>
            سلة التسوق
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                المنتجات في السلة ({{ $cartItems->count() }})
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover" 
                                                     onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                                <a href="{{ route('products.show', $item->product) }}" class="hover:text-blue-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ $item->product->category->name }}
                                                @if($item->product->subcategory)
                                                    - {{ $item->product->subcategory->name }}
                                                @endif
                                            </p>
                                            <div class="text-lg font-bold text-blue-600">
                                                {{ number_format($item->product->price, 2) }} ج.م
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2">
                                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <label for="quantity_{{ $item->id }}" class="text-sm text-gray-600">الكمية:</label>
                                                <input type="number" 
                                                       name="quantity" 
                                                       id="quantity_{{ $item->id }}" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="{{ $item->product->stock_quantity }}"
                                                       class="w-16 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                <button type="submit" 
                                                        class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                    تحديث
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">
                                                {{ number_format($item->quantity * $item->product->price, 2) }} ج.م
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="flex-shrink-0">
                                            <form method="POST" action="{{ route('cart.remove', $item) }}" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md p-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Clear Cart -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('cart.clear') }}" 
                                  onsubmit="return confirm('هل أنت متأكد من مسح جميع المنتجات من السلة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-trash-alt ml-1"></i>
                                    مسح السلة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ملخص الطلب</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">عدد المنتجات:</span>
                                <span class="font-semibold">{{ $cartItems->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span class="font-semibold">{{ number_format($total, 2) }} ج.م</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الشحن:</span>
                                <span class="font-semibold text-green-600">مجاني</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>المجموع الكلي:</span>
                                    <span class="text-blue-600">{{ number_format($total, 2) }} ج.م</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                <i class="fas fa-credit-card ml-2"></i>
                                متابعة للدفع
                            </button>
                            
                            <a href="{{ route('products.index') }}" 
                               class="block w-full bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 font-semibold text-center">
                                <i class="fas fa-arrow-right ml-2"></i>
                                متابعة التسوق
                            </a>
                        </div>

                        <!-- Security Info -->
                        <div class="mt-6 text-xs text-gray-500 text-center">
                            <i class="fas fa-shield-alt ml-1"></i>
                            معاملات آمنة ومحمية
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">سلة التسوق فارغة</h3>
                <p class="text-gray-600 mb-8">لم تقم بإضافة أي منتجات إلى السلة بعد</p>
                <a href="{{ route('products.index') }}" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                    <i class="fas fa-arrow-right ml-2"></i>
                    ابدأ التسوق
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
