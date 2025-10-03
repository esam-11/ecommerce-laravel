<nav x-data="{ open: false }" class="bg-white shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('products.index') }}" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-shopping-bag ml-2"></i>
                        E-Commerce
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('products.*') ? 'border-blue-500 text-gray-900' : '' }}">
                        <i class="fas fa-box ml-1"></i>
                        Products
                    </a>
                    
                    @auth
                        <a href="{{ route('cart.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('cart.*') ? 'border-blue-500 text-gray-900' : '' }}">
                            <i class="fas fa-shopping-cart ml-1"></i>
                            Cart
                            <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-2" id="cart-count">{{ Auth::user()->cart ? Auth::user()->cart->cartItems->sum('quantity') : 0 }}</span>
                        </a>
                        
                        <a href="{{ route('wishlists.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out {{ request()->routeIs('wishlists.*') ? 'border-blue-500 text-gray-900' : '' }}">
                            <i class="fas fa-heart ml-1"></i>
                            Wishlist
                        </a>
                    @endauth
                </div>
            </div>

            <!-- User Links (Without Dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center space-x-4">
                        <!-- Profile -->
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-user ml-1"></i> Profile
                        </a>

                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-tachometer-alt ml-1"></i> Dashboard
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600">
                                <i class="fas fa-sign-out-alt ml-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Products
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Logout
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Login
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        Register
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[action="{{ route('cart.add') }}"]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.cart_count !== undefined) {
                            document.getElementById('cart-count').textContent = data.cart_count;
                        }
                        alert(data.message || 'Product added to cart successfully!');
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        alert('An error occurred while adding the product to the cart.');
                    });
                });
            });
        });
    </script>
    @endauth
</nav>
