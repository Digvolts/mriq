<header class="fixed w-full bg-gray-100 border-b border-gray-200 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-bold text-black">2DAY</div>

        <!-- Nav -->
        <nav class="hidden md:flex gap-8">
            <a href="#" class="hover:text-black font-medium">Home</a>
            <a href="#collections" class="hover:text-black font-medium">Collections</a>
            <a href="#productsSection" class="hover:text-black font-medium">Products</a>
            <a href="#" class="hover:text-black font-medium">About</a>
        </nav>

        <!-- Cart -->
        <div class="flex gap-4 items-center">
            <button class="text-2xl hover:opacity-70">📍</button>
            <div class="relative">
                <button class="text-2xl hover:opacity-70">🛒</button>
                <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    0
                </span>
            </div>
        </div>
    </div>
</header>