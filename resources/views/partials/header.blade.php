<header class="sticky top-0 z-50 bg-white border-b border-gray-100 backdrop-blur-sm bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-3 md:py-4 flex justify-between items-center">
        <!-- Logo di kiri -->
        <a href="/" class="flex items-center gap-2 group cursor-pointer">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-black rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-lg">2D</span>
            </div>
            <div>
                <div class="font-bold text-lg md:text-xl text-black">2DAY</div>
                <div class="text-xs text-gray-500">For Today</div>
            </div>
        </a>

        <!-- Bagian kanan: Lacak Pesanan + Cart -->
        <div class="flex items-center gap-4">
            <!-- Lacak Pesanan -->
            <a href="{{ route('order.track.page') }}" 
               class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition font-medium text-sm flex items-center gap-2 group">
                <i class="fas fa-map-marker-alt group-hover:text-cyan-500 transition"></i>
                Lacak Pesanan
            </a>

            <!-- Cart -->
            <a href="{{ route('cart.view') }}" class="relative group">
                <div class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-shopping-bag text-xl text-gray-700"></i>
                </div>
                @php
                    $cartCount = count(session('cart', []));
                @endphp
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</header>