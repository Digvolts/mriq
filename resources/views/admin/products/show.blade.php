@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- ===== HEADER ===== -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    <i class="fas fa-box"></i> Produk
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-700 font-semibold">{{ $product->name }}</span>
            </nav>

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas {{ $product->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($product->collection)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                                <i class="fas fa-tag"></i> {{ $product->collection->name }}
                            </span>
                        @endif
                        @if($product->terjual > 100)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                <i class="fas fa-star"></i> Best Seller
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('products.show', $product->id) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                        <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                        <i class="fas fa-pencil"></i> Edit
                    </a>
                    <button onclick="confirmDelete()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    <form id="deleteForm" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- ===== LEFT: IMAGE & QUICK INFO ===== -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Product Image -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-image text-5xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="p-4 bg-gray-50 border-t">
                        <p class="text-xs text-gray-600 text-center">
                            <i class="fas fa-info-circle mr-1"></i> Ukuran: {{ $product->size }}
                        </p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-4">
                    <!-- Stock -->
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Stok</p>
                            <i class="fas fa-box text-blue-500"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $product->stock }}</p>
                        <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all" style="width: {{ min(($product->stock / 100) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">
                            @if($product->stock > 20)
                                <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> Stok Sehat</span>
                            @elseif($product->stock > 0)
                                <span class="text-yellow-600 font-semibold"><i class="fas fa-exclamation-circle"></i> Stok Terbatas</span>
                            @else
                                <span class="text-red-600 font-semibold"><i class="fas fa-times-circle"></i> Stok Habis</span>
                            @endif
                        </p>
                    </div>

                    <!-- Terjual -->
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Terjual</p>
                            <i class="fas fa-chart-line text-purple-500"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $product->terjual }}</p>
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="fas fa-history mr-1"></i> Total sepanjang waktu
                        </p>
                    </div>

                    <!-- Harga -->
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Harga</p>
                            <i class="fas fa-tag text-yellow-500"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        @if($product->discount_price)
                            <p class="text-sm text-green-600 font-semibold mt-2">
                                <i class="fas fa-percent"></i> Diskon: Rp{{ number_format($product->price - $product->discount_price, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    <!-- Warna -->
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Warna</p>
                            <i class="fas fa-palette text-pink-500"></i>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-900">{{ $product->color }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== RIGHT: DETAILS ===== -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- ===== HARGA & DISKON ===== -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-tag text-yellow-500"></i> Harga & Diskon
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Harga Normal -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <p class="text-sm text-blue-600 font-semibold mb-2 uppercase tracking-wide">Harga Normal</p>
                            <p class="text-4xl font-bold text-blue-900">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-blue-700 mt-3">
                                <i class="fas fa-info-circle mr-1"></i> Harga jual regular produk
                            </p>
                        </div>

                        <!-- Harga Diskon -->
                        <div class="bg-gradient-to-br {{ $product->discount_price ? 'from-green-50 to-green-100' : 'from-gray-50 to-gray-100' }} rounded-xl p-6 border {{ $product->discount_price ? 'border-green-200' : 'border-gray-200' }}">
                            <p class="text-sm {{ $product->discount_price ? 'text-green-600' : 'text-gray-600' }} font-semibold mb-2 uppercase tracking-wide">
                                Harga Diskon
                            </p>
                            <p class="text-4xl font-bold {{ $product->discount_price ? 'text-green-900' : 'text-gray-400' }}">
                                {{ $product->discount_price ? 'Rp' . number_format($product->discount_price, 0, ',', '.') : '-' }}
                            </p>
                            @if($product->discount_price)
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <span class="text-green-700">
                                        <i class="fas fa-save mr-1"></i> Hemat
                                    </span>
                                    <span class="font-bold text-green-900">
                                        Rp{{ number_format($product->price - $product->discount_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <p class="text-xs text-gray-600 mt-3">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak ada diskon aktif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ===== SPESIFIKASI ===== -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-sliders text-indigo-500"></i> Spesifikasi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Material -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                            <p class="text-xs text-orange-600 font-semibold mb-2 uppercase">Bahan</p>
                            <p class="text-gray-900 font-semibold">
                                {{ $product->bahan ?? '(Belum diisi)' }}
                            </p>
                        </div>

                        <!-- Style -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                            <p class="text-xs text-purple-600 font-semibold mb-2 uppercase">Style</p>
                            <p class="text-gray-900 font-semibold">
                                {{ $product->style ?? '(Belum diisi)' }}
                            </p>
                        </div>

                        <!-- Printing Design -->
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 border border-pink-200">
                            <p class="text-xs text-pink-600 font-semibold mb-2 uppercase">Printing Design</p>
                            <p class="text-gray-900 font-semibold">
                                {{ $product->printing_design ?? '(Belum diisi)' }}
                            </p>
                        </div>

                        <!-- Bestseller -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                            <p class="text-xs text-yellow-600 font-semibold mb-2 uppercase">Label Bestseller</p>
                            <p class="text-gray-900 font-semibold">
                                {{ $product->keterangan_bestseller ?? '(Tidak ada)' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ===== DESKRIPSI ===== -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-blue-500"></i> Deskripsi Produk
                    </h2>

                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                        <p class="text-gray-700 leading-relaxed text-sm">
                            {{ $product->description ?? '(Belum diisi)' }}
                        </p>
                    </div>
                </div>

                <!-- ===== INFO TAMBAHAN ===== -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Exclusive Merchandise -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-crown text-purple-500"></i> Exclusive Merchandise
                        </h3>
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <p class="text-gray-700 text-sm leading-relaxed">
                                {{ $product->exclusive_mercendise ?? '(Belum diisi)' }}
                            </p>
                        </div>
                    </div>

                    <!-- Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-truck text-green-500"></i> Info Pengiriman
                        </h3>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <p class="text-gray-700 text-sm leading-relaxed">
                                {{ $product->pengiriman ?? '(Belum diisi)' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== FOOTER ACTIONS ===== -->
        <div class="mt-12 flex items-center justify-between">
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 text-gray-700 hover:text-gray-900 font-semibold transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.edit', $product->id) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition shadow-lg hover:shadow-xl">
                    <i class="fas fa-pencil"></i> Edit Produk
                </a>
                <a href="{{ route('products.show', $product->id) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold transition shadow-lg hover:shadow-xl">
                    <i class="fas fa-external-link-alt"></i> Preview Toko
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection