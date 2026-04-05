@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-4 md:p-8">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-chart-line text-indigo-600 mr-3"></i>Dashboard Admin
                </h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt"></i> {{ now()->format('l, d F Y') }}
                </p>
            </div>
        </div>
        <div class="h-1 w-24 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full"></div>
    </div>

    <!-- ROW 1: Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Produk -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-transparent rounded-full -mr-20 -mt-20 opacity-50"></div>
                
                <div class="relative p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Produk</p>
                            <h3 class="text-4xl font-bold text-gray-900">
                                {{ \App\Models\Product::count() }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-boxes text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-blue-600 text-sm font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ \App\Models\Product::where('is_active', true)->count() }} aktif
                    </p>
                </div>
            </div>
        </div>

        <!-- Stok Habis -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-400 rounded-2xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-red-100 to-transparent rounded-full -mr-20 -mt-20 opacity-50"></div>
                
                <div class="relative p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Stok Habis</p>
                            <h3 class="text-4xl font-bold text-gray-900">
                                {{ \App\Models\Product::where('stock', 0)->count() }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-600 to-red-400 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-red-600 text-sm font-semibold">
                        <i class="fas fa-alert-circle mr-2"></i>
                        Perlu diisi stok
                    </p>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-400 rounded-2xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-amber-100 to-transparent rounded-full -mr-20 -mt-20 opacity-50"></div>
                
                <div class="relative p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Stok Menipis</p>
                            <h3 class="text-4xl font-bold text-gray-900">
                                {{ \App\Models\Product::whereBetween('stock', [1, 5])->count() }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-600 to-amber-400 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-hourglass-end text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-amber-600 text-sm font-semibold">
                        <i class="fas fa-info-circle mr-2"></i>
                        1-5 unit
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Terjual -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-2xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-emerald-100 to-transparent rounded-full -mr-20 -mt-20 opacity-50"></div>
                
                <div class="relative p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Terjual</p>
                            <h3 class="text-4xl font-bold text-gray-900">
                                {{ \App\Models\Product::sum('terjual') }}
                            </h3>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-shopping-bag text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-emerald-600 text-sm font-semibold">
                        <i class="fas fa-chart-line mr-2"></i>
                        Unit terjual
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Inventory Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Produk Stok Habis / Menipis -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4">
                <h5 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-alert-circle mr-2"></i>Inventory Alert
                </h5>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->orderBy('stock')->take(5)->get();
                    @endphp

                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ Str::limit($product->name, 30) }}</p>
                                <p class="text-xs text-gray-600 mt-1">SKU: #{{ $product->id }}</p>
                            </div>
                            <span class="px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg">
                                {{ $product->stock }} unit
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fas fa-check-circle text-emerald-500 text-2xl mb-2 block"></i>
                            <p class="text-gray-600 font-semibold">Semua stok aman!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top 5 Best Seller -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                <h5 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-fire mr-2"></i>Top 5 Best Seller
                </h5>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $bestSellers = \App\Models\Product::where('terjual', '>', 0)
                            ->orderByDesc('terjual')
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse ($bestSellers as $index => $product)
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ Str::limit($product->name, 25) }}</p>
                                </div>
                            </div>
                            <span class="px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-lg">
                                {{ $product->terjual }} terjual
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fas fa-chart-bar text-gray-300 text-2xl mb-2 block"></i>
                            <p class="text-gray-600 font-semibold">Belum ada penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 3: Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Ukuran Populer -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h5 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-ruler mr-2"></i>Ukuran Populer
                </h5>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $sizes = \App\Models\Product::select('size')
                            ->groupBy('size')
                            ->selectRaw('size, COUNT(*) as count')
                            ->orderByDesc('count')
                            ->get();
                    @endphp

                    @forelse ($sizes as $size)
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">{{ $size->size }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-purple-600 to-pink-600" 
                                         style="width: {{ ($size->count / $sizes->sum('count') * 100) }}%"></div>
                                </div>
                                <span class="font-bold text-gray-900 w-8 text-right">{{ $size->count }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Koleksi Terbaik -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4">
                <h5 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-layer-group mr-2"></i>Koleksi Terbaik
                </h5>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $collections = \App\Models\Collection::with('products')
                            ->get()
                            ->map(function($col) {
                                return [
                                    'name' => $col->name,
                                    'total' => $col->products->count()
                                ];
                            })
                            ->sortByDesc('total')
                            ->take(5);
                    @endphp

                    @forelse ($collections as $collection)
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">{{ $collection['name'] }}</span>
                            <span class="px-3 py-1 bg-cyan-100 text-cyan-700 font-bold rounded-lg">
                                {{ $collection['total'] }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Produk dengan Diskon -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-rose-600 to-red-600 px-6 py-4">
                <h5 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-percent mr-2"></i>Produk Diskon
                </h5>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $discountedProducts = \App\Models\Product::where('discount_price', '!=', null)
                            ->get()
                            ->map(function($prod) {
                                $disc = (($prod->price - $prod->discount_price) / $prod->price * 100);
                                return [
                                    'name' => $prod->name,
                                    'discount' => round($disc, 0)
                                ];
                            })
                            ->sortByDesc('discount')
                            ->take(5);
                    @endphp

                    @forelse ($discountedProducts as $product)
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700 truncate">{{ Str::limit($product['name'], 20) }}</span>
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 font-bold rounded-lg">
                                {{ $product['discount'] }}%
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada diskon</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
            <h5 class="text-white font-bold text-lg mb-0">
                <i class="fas fa-link mr-2"></i>Quick Links
            </h5>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <a href="{{ route('admin.products.index') }}" 
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-blue-200 bg-blue-50 hover:bg-blue-600 hover:border-blue-600 transition-all duration-300">
                    <i class="fas fa-list text-blue-600 group-hover:text-white text-lg"></i>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-white">Kelola Produk</p>
                        <p class="text-xs text-gray-600 group-hover:text-blue-100">Lihat semua</p>
                    </div>
                </a>

                <a href="{{ route('admin.products.create') }}" 
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-emerald-200 bg-emerald-50 hover:bg-emerald-600 hover:border-emerald-600 transition-all duration-300">
                    <i class="fas fa-plus text-emerald-600 group-hover:text-white text-lg"></i>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-white">Tambah Produk</p>
                        <p class="text-xs text-gray-600 group-hover:text-emerald-100">Baru</p>
                    </div>
                </a>

                <a href="{{ route('admin.collections.index') }}" 
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-cyan-200 bg-cyan-50 hover:bg-cyan-600 hover:border-cyan-600 transition-all duration-300">
                    <i class="fas fa-layer-group text-cyan-600 group-hover:text-white text-lg"></i>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-white">Koleksi</p>
                        <p class="text-xs text-gray-600 group-hover:text-cyan-100">Atur</p>
                    </div>
                </a>

                <a href="{{ route('admin.newArrivals.index') }}" 
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-amber-200 bg-amber-50 hover:bg-amber-600 hover:border-amber-600 transition-all duration-300">
                    <i class="fas fa-star text-amber-600 group-hover:text-white text-lg"></i>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-white">New Arrivals</p>
                        <p class="text-xs text-gray-600 group-hover:text-amber-100">Terbaru</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    * {
        @apply transition-all duration-300 ease-out;
    }

    html {
        scroll-behavior: smooth;
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection