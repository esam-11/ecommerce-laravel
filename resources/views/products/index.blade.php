<x-app-layout>
    <x-slot name="title">المنتجات</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-box ml-2"></i>
            المنتجات
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Admin Actions -->
        @if(auth()->check() && auth()->user()->is_admin)
            <div class="mb-6 flex justify-end">
                <a href="{{ route('products.create') }}" class="btn btn-success bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة منتج جديد
                </a>
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="ابحث عن منتج..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">الفئة</label>
                        <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory Filter -->
                    <div>
                        <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-1">الفئة الفرعية</label>
                        <select name="subcategory" id="subcategory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">جميع الفئات الفرعية</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">ترتيب حسب</label>
                        <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>الأحدث</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>السعر</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="btn btn-primary bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search ml-2"></i>
                        بحث
                    </button>

                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times ml-1"></i>
                        مسح الفلاتر
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Product Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-70 object-cover"
                             onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            {{ $product->description }}
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
                               class="btn btn-primary bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <i class="fas fa-eye ml-1"></i>
                                            عرض التفاصيل
                                        </a>
                                        @if(auth()->check() && auth()->user()->admin)
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="btn btn-warning bg-yellow-500 text-white px-3 py-2 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                            @auth
                                @if(!auth()->user()->is_admin)
                                    <form method="POST" action="{{ route('cart.add') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                                class="btn btn-success bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus ml-1"></i>
                                            أضف للسلة
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-secondary bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 text-sm">
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
                    <p class="text-gray-500">لم يتم العثور على منتجات تطابق معايير البحث</p>
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
