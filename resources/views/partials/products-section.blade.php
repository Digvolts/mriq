<section>
    <div class="mb-6 md:mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
            <span id="sectionTitle">
                <i class="fas fa-heart text-gray-400 mr-2"></i>Produk Pilihan
            </span>
        </h2>
        <div class="h-0.5 w-12 bg-gray-300 mt-2"></div>
    </div>

    <div id="resultsCounter" class="mb-4 text-sm text-gray-600 hidden">
        Menampilkan <span id="resultCount">0</span> dari <span id="totalCount">{{ $groupedProducts->count() }}</span> produk
        <button onclick="clearSearch()" class="ml-3 text-blue-600 hover:text-blue-700 font-semibold">
            <i class="fas fa-times mr-1"></i>Hapus Filter
        </button>
    </div>

    @if($groupedProducts->count() > 0)
        <div id="productsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($groupedProducts as $productName => $data)
                @include('partials.product-card', [
                    'productName' => $productName,
                    'data' => $data
                ])
            @endforeach
        </div>

        <div id="noProductsFound" class="py-12 md:py-16 text-center hidden">
            <i class="fas fa-inbox text-gray-300 text-5xl md:text-6xl mb-4 block"></i>
            <p class="text-gray-500 text-base md:text-lg font-medium">
                Tidak ada produk yang sesuai
            </p>
            <button onclick="clearSearch()" class="mt-4 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition">
                Hapus Filter
            </button>
        </div>

        @if(method_exists($products, 'links'))
            <div class="mt-8 md:mt-12">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
    @else
        <div class="py-12 md:py-16 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl md:text-6xl mb-4 block"></i>
            <p class="text-gray-500 text-base md:text-lg font-medium">
                Belum ada produk tersedia
            </p>
        </div>
    @endif
</section>