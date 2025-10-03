<x-app-layout>
    <x-slot name="title">قوائم الأمنيات</x-slot>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-heart ml-2"></i>
                قوائم الأمنيات
            </h2>
            <a href="{{ route('wishlists.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-plus ml-1"></i>
                إنشاء قائمة جديدة
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($wishlists->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlists as $wishlist)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $wishlist->name }}</h3>
                                <div class="flex space-x-2">
                                    <a href="{{ route('wishlists.edit', $wishlist) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('wishlists.destroy', $wishlist) }}" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه القائمة؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-box ml-1"></i>
                                {{ $wishlist->wishlistItems->count() }} منتج
                            </div>

                            @if($wishlist->wishlistItems->count() > 0)
                                <div class="space-y-2 mb-4">
                                    @foreach($wishlist->wishlistItems->take(3) as $item)
                                        <div class="flex items-center space-x-2 text-sm">
                                            <div class="w-8 h-8 bg-gray-200 rounded overflow-hidden">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                                 class="w-full h-full object-cover" 
                                                 onerror="this.src='{{ asset('images/product-placeholder.jpg') }}'">
                                            </div>
                                            <span class="text-gray-700 truncate">{{ $item->product->name }}</span>
                                        </div>
                                    @endforeach
                                    @if($wishlist->wishlistItems->count() > 3)
                                        <div class="text-xs text-gray-500">
                                            و {{ $wishlist->wishlistItems->count() - 3 }} منتج آخر...
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-gray-500 mb-4">
                                    لا توجد منتجات في هذه القائمة
                                </div>
                            @endif

                            <div class="flex space-x-2">
                                <a href="{{ route('wishlists.show', $wishlist) }}" 
                                   class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض القائمة
                                </a>
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
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">لا توجد قوائم أمنيات</h3>
                <p class="text-gray-600 mb-8">ابدأ بإنشاء قائمة أمنيات جديدة لحفظ المنتجات المفضلة لديك</p>
                <a href="{{ route('wishlists.create') }}" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء قائمة جديدة
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
