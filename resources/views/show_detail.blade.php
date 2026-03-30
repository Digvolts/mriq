@extends('layouts.detail')

@section('title', $product->name . ' - 2DAY')

@section('content')

<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-3">
        <div class="flex items-center gap-2 text-sm">
            <a href="/" class="text-gray-600 hover:text-gray-900 transition">Home</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @if($product->collection)
                <a href="{{ route('collection.products', $product->collection->id) }}" 
                   class="text-gray-600 hover:text-gray-900 transition">
                    {{ $product->collection->name }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @endif
            <span class="text-gray-900 font-semibold line-clamp-1">{{ $product->name }}</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 md:px-8 mt-4 slide-fade-in">
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 md:px-8 mt-4 slide-fade-in">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

<main class="max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 mb-12">
        
        <!-- GAMBAR PRODUK -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}"
                         class="gallery-main w-full">
                @else
                    <div class="w-full h-96 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- DETAIL PRODUK -->
        <div class="space-y-6">
            <!-- Judul & Rating -->
            <div>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700 text-sm leading-relaxed" id="descriptionBox">{{ $product->description }}</p>
            </div>

<!-- Harga -->
<div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
    <div class="flex items-baseline gap-3 mb-2" id="priceContainer">
        @php
            $firstVariant = $variantData[0] ?? null;
            $hasDiscount = $firstVariant && $firstVariant['discount_price'] && $firstVariant['discount_price'] > 0;
            $displayPrice = $hasDiscount ? $firstVariant['discount_price'] : ($firstVariant['price'] ?? $product->price);
            $originalPrice = $firstVariant['price'] ?? $product->price;
        @endphp
        
        @if($hasDiscount)
            <p class="text-sm text-red-500 line-through font-semibold" id="originalPriceDisplay">
                Rp{{ number_format($originalPrice, 0, ',', '.') }}
            </p>
            <span class="text-4xl md:text-5xl font-bold text-emerald-600" id="productPrice">
                Rp{{ number_format($displayPrice, 0, ',', '.') }}
            </span>
            @php
                $discount = (($originalPrice - $displayPrice) / $originalPrice) * 100;
            @endphp
            <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-bold rounded-md" id="discountBadge">
                -{{ round($discount) }}%
            </span>
        @else
            <span class="text-4xl md:text-5xl font-bold text-gray-900" id="productPrice">
                Rp{{ number_format($originalPrice, 0, ',', '.') }}
            </span>
        @endif
    </div>
</div>

            <!-- Pilih Ukuran -->
            @if(!empty($availableSizes))
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <i class="fas fa-ruler text-blue-600 mr-2"></i>Pilih Ukuran <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach($availableSizes as $index => $size)
                            <button class="variant-option @if($index === 0) selected @endif" 
                                    onclick="selectSize(this)" 
                                    data-size="{{ $size }}"
                                    id="size-{{ $size }}">
                                <span class="text-sm font-semibold">{{ $size }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Stok dari Size Paling Kecil -->
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div>
                    <p class="text-2xl font-bold text-emerald-600">
                        <span id="currentStock">{{ $displayStock }}</span> unit
                    </p>
                </div>
                <div class="text-right" id="stockStatus">
                    @if($displayStock > 0)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full inline-block">
                            <i class="fas fa-check-circle mr-1"></i>Dapat Dibeli
                        </span>
                        @if($displayStock <= 5)
                            <p class="text-xs text-red-600 mt-2 font-semibold">
                                <i class="fas fa-warning mr-1"></i>Stok Terbatas!
                            </p>
                        @endif
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full inline-block">
                            <i class="fas fa-times-circle mr-1"></i>Stok Habis
                        </span>
                    @endif
                </div>
            </div>

            <!-- Jumlah Pembelian -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 font-semibold">Jumlah Pembelian:</span>
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button type="button" 
                            onclick="decreaseQty()" 
                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 font-semibold text-lg">
                        −
                    </button>
                    <input type="number" 
                           id="quantity" 
                           value="1" 
                           min="1" 
                           max="{{ $displayStock }}"
                           class="w-16 text-center border-0 focus:outline-none font-semibold text-lg"
                           readonly>
                    <button type="button" 
                            onclick="increaseQty()" 
                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 font-semibold text-lg">
                        +
                    </button>
                </div>
                <span class="text-xs text-gray-500 font-semibold" id="stockInfo">
                    <i class="fas fa-info-circle mr-1"></i>Max: <span id="maxStockDisplay">{{ $displayStock }}</span> unit
                </span>
            </div>

            <!-- Form Pembelian -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="size" id="sizeInput" value="{{ $availableSizes[0] ?? $product->size }}">
                <input type="hidden" name="quantity" id="quantityInput" value="1">

                <div class="grid grid-cols-1 gap-3">
                    <button type="submit" 
                            id="cartBtn"
                            class="py-3 border-2 border-gray-900 text-gray-900 font-semibold rounded-lg hover:bg-gray-50 flex items-center justify-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $displayStock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                        <span>Tambah ke Keranjang</span>
                    </button>
                </div>
            </form>

            <!-- Error Alert -->
            <div id="errorAlert" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span id="errorMessage"></span>
            </div>

            <!-- Info Box -->
            <div class="space-y-3 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-shield-alt text-emerald-600 mt-1 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">Garansi Resmi</p>
                        <p class="text-xs text-emerald-800">Produk original bergaransi</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-undo text-emerald-600 mt-1 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">30 Hari Garansi Uang Kembali</p>
                        <p class="text-xs text-emerald-800">Tidak puas? Kembalikan tanpa pertanyaan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS DESKRIPSI & SPESIFIKASI -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-12">
        <div class="border-b border-gray-200 flex">
            <button class="px-6 py-4 font-semibold text-gray-900 border-b-2 border-gray-900 cursor-pointer transition hover:text-gray-700" 
                    onclick="switchTab(this, 'description')">
                <i class="fas fa-file-text mr-2"></i>Deskripsi
            </button>
            <button class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-900 cursor-pointer transition" 
                    onclick="switchTab(this, 'specs')">
                <i class="fas fa-list mr-2"></i>Spesifikasi
            </button>
        </div>

        <div id="description" class="tab-content p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Tentang Produk</h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                <p class="leading-relaxed" id="descriptionTab">{{ $product->description }}</p>
            </div>
        </div>

        <div id="specs" class="tab-content p-6 hidden">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Produk</h3>
            <div class="space-y-3">
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Nama Produk</span>
                        <span class="font-semibold text-gray-900">{{ $product->name }}</span>
                    </div>
                </div>
                @if($product->collection)
                    <div class="spec-item">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Kategori</span>
                            <span class="font-semibold text-gray-900">{{ $product->collection->name }}</span>
                        </div>
                    </div>
                @endif
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Bahan</span>
                        <span class="font-semibold text-gray-900" id="bahanSpec">{{ $product->bahan ?? '-' }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Style</span>
                        <span class="font-semibold text-gray-900" id="styleSpec">{{ $product->style ?? '-' }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Printing Design</span>
                        <span class="font-semibold text-gray-900" id="printingSpec">{{ $product->printing_design ?? '-' }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Exclusive Merchandise</span>
                        <span class="font-semibold text-gray-900" id="merchandiseSpec">{{ $product->exclusive_mercendise ?? '-' }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Pengiriman</span>
                        <span class="font-semibold text-gray-900" id="pengirimanSpec">{{ $product->pengiriman ?? '-' }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Ukuran Tersedia</span>
                        <span class="font-semibold text-gray-900">{{ implode(', ', $availableSizes) }}</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Total Stok</span>
                        <span class="font-semibold text-emerald-600" id="totalStockSpec">{{ $displayStock }} unit</span>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Harga</span>
                        <span class="font-semibold text-gray-900" id="hargaSpec">
                            @if($product->discount_price && $product->discount_price > 0)
                                Rp{{ number_format($product->discount_price, 0, ',', '.') }}
                            @else
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUK SERUPA -->
    @if($relatedProducts->count() > 0)
        <section>
            <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                    <i class="fas fa-box text-gray-400 mr-2"></i>Produk Serupa
                </h2>
                <div class="h-0.5 w-12 bg-gray-300 mt-2"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($relatedProducts as $related)
                    <div class="product-card bg-white rounded-xl overflow-hidden hover:shadow-lg">
                        <div class="relative h-56 md:h-64 bg-gray-100 overflow-hidden group">
                            @if($related->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($related->image))
                                <img src="{{ asset('storage/' . $related->image) }}" 
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i class="fas fa-image text-3xl text-gray-400"></i>
                                </div>
                            @endif

                            @if($related->stock > 0 && $related->is_active)
                                <span class="absolute top-3 left-3 bg-emerald-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            @else
                                <span class="absolute top-3 left-3 bg-gray-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-lock mr-1"></i>Habis
                                </span>
                            @endif
                        </div>

                        <div class="p-4">
                            @if($related->collection)
                                <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide font-medium">
                                    {{ $related->collection->name }}
                                </p>
                            @endif

                            <h3 class="font-semibold text-sm md:text-base text-gray-900 line-clamp-2 mb-2">
                                {{ $related->name }}
                            </h3>

                            @if($related->discount_price && $related->discount_price > 0)
                                <div class="space-y-1 mb-3">
                                    <p class="text-xs text-red-500 line-through font-semibold">
                                        Rp{{ number_format($related->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-lg md:text-xl font-bold text-emerald-600">
                                        Rp{{ number_format($related->discount_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            @else
                                <p class="text-lg md:text-xl font-bold text-gray-900 mb-3">
                                    Rp{{ number_format($related->price, 0, ',', '.') }}
                                </p>
                            @endif

                            <a href="{{ route('products.show', $related->id) }}" 
                               class="w-full px-4 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition font-medium text-sm text-center block">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</main>

@endsection

@push('scripts')
    <script >
    // ===== VARIANT DATA =====
const variantData = @json($variantData ?? []);
const availableSizes = @json($availableSizes ?? []);

let selectedSize = availableSizes.length > 0 ? availableSizes[0] : "";
let selectedQuantity = 1;

console.log("Variant Data:", variantData);
console.log("Available Sizes:", availableSizes);

// ===== INITIALIZE ON PAGE LOAD =====
document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM Loaded - Initializing...");
    
    if (selectedSize && variantData.length > 0) {
        document.getElementById('sizeInput').value = selectedSize;
        updateAllDisplays();
        markFirstSizeAsSelected();
    }
});

// ===== MARK FIRST SIZE AS SELECTED =====
function markFirstSizeAsSelected() {
    document.querySelectorAll('.variant-option').forEach((btn, index) => {
        if (index === 0) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });
}

// ===== SELECT SIZE =====
function selectSize(element) {
    selectedSize = element.getAttribute('data-size');
    console.log("Selected Size:", selectedSize);
    
    // Update hidden input
    document.getElementById('sizeInput').value = selectedSize;
    
    // Update button styles
    document.querySelectorAll('.variant-option').forEach(btn => {
        btn.classList.remove('selected');
    });
    element.classList.add('selected');
    
    // Update all displays
    updateAllDisplays();
}

// ===== UPDATE ALL DISPLAYS =====
function updateAllDisplays() {
    updatePriceDisplay();
    updateStockDisplay();
    updateSpecDisplay();
    resetQuantity();
}

// ===== UPDATE PRICE DISPLAY =====
function updatePriceDisplay() {
    const selected = variantData.find(v => v.size === selectedSize);
    
    if (!selected) {
        console.warn("Variant not found for size:", selectedSize);
        return;
    }
    
    const price = parseFloat(selected.price);
    const discountPrice = parseFloat(selected.discount_price);
    
    console.log("Price Update:", { price, discountPrice });
    
    const priceContainer = document.getElementById('priceContainer');
    const productPriceEl = document.getElementById('productPrice');
    
    if (discountPrice && discountPrice > 0) {
        const discount = ((price - discountPrice) / price) * 100;
        
        // Perbarui seluruh container
        priceContainer.innerHTML = `
            <p class="text-sm text-red-500 line-through font-semibold" id="originalPriceDisplay">
                Rp${price.toLocaleString('id-ID')}
            </p>
            <span class="text-4xl md:text-5xl font-bold text-emerald-600" id="productPrice">
                Rp${discountPrice.toLocaleString('id-ID')}
            </span>
            <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-bold rounded-md" id="discountBadge">
                -${Math.round(discount)}%
            </span>
        `;
    } else {
        // Perbarui seluruh container
        priceContainer.innerHTML = `
            <span class="text-4xl md:text-5xl font-bold text-gray-900" id="productPrice">
                Rp${price.toLocaleString('id-ID')}
            </span>
        `;
    }
    
    // Update spec harga
    const hargaSpec = document.getElementById('hargaSpec');
    if (hargaSpec) {
        if (discountPrice && discountPrice > 0) {
            hargaSpec.textContent = `Rp${discountPrice.toLocaleString('id-ID')}`;
        } else {
            hargaSpec.textContent = `Rp${price.toLocaleString('id-ID')}`;
        }
    }
}

// ===== UPDATE STOCK DISPLAY =====
function updateStockDisplay() {
    const selected = variantData.find(v => v.size === selectedSize);
    
    if (!selected) {
        console.warn("Variant not found for size:", selectedSize);
        return;
    }
    
    const stock = parseInt(selected.stock);
    console.log("Stock Update:", { size: selectedSize, stock });
    
    document.getElementById('currentStock').textContent = stock;
    document.getElementById('maxStockDisplay').textContent = stock;
    
    const cartBtn = document.getElementById('cartBtn');
    const stockStatus = document.getElementById('stockStatus');
    const quantityInput = document.getElementById('quantity');
    
    // Update max attribute
    quantityInput.max = stock;
    
    if (stock > 0) {
        // Enable button
        cartBtn.disabled = false;
        cartBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
        
        // Update status message
        let statusHTML = `
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full inline-block">
                <i class="fas fa-check-circle mr-1"></i>Dapat Dibeli
            </span>
        `;
        
        if (stock <= 5) {
            statusHTML += `
                <p class="text-xs text-red-600 mt-2 font-semibold">
                    <i class="fas fa-warning mr-1"></i>Stok Terbatas!
                </p>
            `;
        }
        
        stockStatus.innerHTML = statusHTML;
        
        // Reset quantity if exceeds stock
        if (selectedQuantity > stock) {
            selectedQuantity = 1;
            updateQuantityDisplay();
        }
    } else {
        // Disable button
        cartBtn.disabled = true;
        cartBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
        
        // Update status message
        stockStatus.innerHTML = `
            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full inline-block">
                <i class="fas fa-times-circle mr-1"></i>Stok Habis
            </span>
        `;
        
        selectedQuantity = 1;
        updateQuantityDisplay();
    }
    
    // Update total stock spec
    const totalStockSpec = document.getElementById('totalStockSpec');
    if (totalStockSpec) {
        totalStockSpec.textContent = `${stock} unit`;
    }
}

// ===== UPDATE SPEC DISPLAY =====
function updateSpecDisplay() {
    const selected = variantData.find(v => v.size === selectedSize);
    
    if (!selected) return;
    
    if (document.getElementById('bahanSpec')) {
        document.getElementById('bahanSpec').textContent = selected.bahan || '-';
    }
    if (document.getElementById('styleSpec')) {
        document.getElementById('styleSpec').textContent = selected.style || '-';
    }
    if (document.getElementById('printingSpec')) {
        document.getElementById('printingSpec').textContent = selected.printing_design || '-';
    }
    if (document.getElementById('merchandiseSpec')) {
        document.getElementById('merchandiseSpec').textContent = selected.exclusive_mercendise || '-';
    }
    if (document.getElementById('pengirimanSpec')) {
        document.getElementById('pengirimanSpec').textContent = selected.pengiriman || '-';
    }
}

// ===== INCREASE QUANTITY =====
function increaseQty() {
    const selected = variantData.find(v => v.size === selectedSize);
    const maxStock = selected ? parseInt(selected.stock) : 1;
    
    if (selectedQuantity < maxStock) {
        selectedQuantity++;
        updateQuantityDisplay();
    }
}

// ===== DECREASE QUANTITY =====
function decreaseQty() {
    if (selectedQuantity > 1) {
        selectedQuantity--;
        updateQuantityDisplay();
    }
}

// ===== UPDATE QUANTITY DISPLAY =====
function updateQuantityDisplay() {
    document.getElementById('quantity').value = selectedQuantity;
    document.getElementById('quantityInput').value = selectedQuantity;
}

// ===== RESET QUANTITY =====
function resetQuantity() {
    selectedQuantity = 1;
    updateQuantityDisplay();
}

// ===== SWITCH TABS =====
function switchTab(button, tabName) {
    // Remove active state from all buttons
    document.querySelectorAll('[onclick*="switchTab"]').forEach(btn => {
        btn.classList.remove('text-gray-900', 'border-b-2', 'border-gray-900');
        btn.classList.add('text-gray-600', 'hover:text-gray-900');
    });
    
    // Add active state to clicked button
    button.classList.remove('text-gray-600', 'hover:text-gray-900');
    button.classList.add('text-gray-900', 'border-b-2', 'border-gray-900');
    
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Show selected tab
    const tabElement = document.getElementById(tabName);
    if (tabElement) {
        tabElement.classList.remove('hidden');
    }
}

// ===== VALIDATE FORM =====
function validateForm() {
    if (!selectedSize) {
        showError('Pilih ukuran terlebih dahulu!');
        return false;
    }
    
    if (selectedQuantity < 1) {
        showError('Jumlah minimal 1!');
        return false;
    }
    
    const selected = variantData.find(v => v.size === selectedSize);
    if (!selected || parseInt(selected.stock) < selectedQuantity) {
        showError('Stok tidak cukup!');
        return false;
    }
    
    return true;
}

// ===== SHOW ERROR =====
function showError(message) {
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    
    if (!errorAlert || !errorMessage) {
        alert(message);
        return;
    }
    
    errorMessage.textContent = message;
    errorAlert.classList.remove('hidden');
    
    setTimeout(() => {
        errorAlert.classList.add('hidden');
    }, 3000);
}

// ===== FORM VALIDATION ON SUBMIT =====
const form = document.getElementById('addToCartForm');
if (form) {
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
    });
}</script>
@endpush