<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'ecommerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="font-sans antialiased bg-gray-50" style="font-family: 'Cairo', sans-serif;">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm border-b">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            <main class="py-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">About Store</h3>
                            <p class="text-gray-300">An online store specialized in selling high quality products</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white">Products</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">About Us</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">Contact Us</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-300 hover:text-white">Shipping & Delivery</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">Return Policy</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                        <p>&copy; {{ date('Y') }} All rights reserved</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
