@extends('layouts.home')

@section('title', $collection->name . ' - Koleksi')

@section('content')
<div class="min-h-screen bg-gray-50">

    <!-- ===== HERO BANNER ===== -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16 px-4 relative overflow-hidden">
        
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-500 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto relative">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('home') }}#kategori" class="hover:text-white transition-colors">Kategori</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white font-semibold">{{ $collection->name }}</span>
            </nav>

            <!-- Collection Info -->
            <div class="flex items-center gap-6">
                
                {{-- Icon --}}
                <div class="w-20 h-20 md:w-24 md:h-24 bg-white bg-opacity-10 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white border-opacity-20 flex-shrink-0">
                    @if($collection->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($collection->icon))
                        <img src="{{ asset('storage/' . $collection->icon) }}"
                             alt="{{ $collection->name }}"
                             class="w-12 h-12 md:w-14 md:h-14 object-contain">
                    @else
                        <i class="fas fa-box text-white text-3xl"></i>
                    @endif
                </div>

                {{-- Title --}}
                <div>
                    <p class="text-gray-400 text-sm font-semibold uppercase tracking-widest mb-1">Koleksi</p>
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2">{{ $collection->name }}</h1>
                    <p class="text-gray-300 text-sm">
                        <i class="fas fa-box-open mr-1"></i>
                        <span class="font-bold text-white">{{ $products->total() }}</span> produk tersedia
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ===== SIDEBAR ===== -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    
                    <h3 class="font-black text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-th text-purple-500"></i>
                        Semua Kategori
                    </h3>

                    <div class="space-y-1">
                        @foreach($collections as $col)
                            <a href="{{ route('collection.products', $col->id) }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                   {{ $col->id === $collection->id 
                                       ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-md' 
                                       : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                
                                {{-- Icon --}}
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $col->id === $collection->id ? 'bg-white bg-opacity-20' : 'bg-gray-100 group-hover:bg-gray-200' }}">
                                    @if($col->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($col->icon))
                                        <img src="{{ asset('storage/' . $col->icon) }}"
                                             alt="{{ $col->name }}"
                                             class="w-5 h-5 object-contain">
                                    @else
                                        <i class="fas fa-box text-xs
                                            {{ $col->id === $collection->id ? 'text-white' : 'text-gray-400' }}"></i>
                                    @endif
                                </div>

                                <span class="text-sm font-semibold truncate">{{ $col->name }}</span>

                                @if($col->id === $collection->id)
                                    <i class="fas fa-chevron-right text-xs ml-auto"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- ===== PRODUCT GRID ===== -->
            <div class="flex-1">

                @if($products->count() > 0)

                    <!-- Sort & Count Bar -->
                    <div class="flex items-center justify-between mb-6 bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">
                            Menampilkan 
                            <span class="font-bold text-gray-900">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                            dari
                            <span class="font-bold text-gray-900">{{ $products->total() }}</span>
                            produk
                        </p>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-filter text-purple-400"></i>
                            <span class="font-semibold text-gray-700">{{ $collection->name }}</span>
                        </div>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <a href="{{ route('products.show', $product->id) }}"
                               class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                <!-- Image -->
                                <div class="relative aspect-square overflow-hidden bg-gray-50">
                                    @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-4xl text-gray-200"></i>
                                        </div>
                                    @endif

                                    <!-- Badge New -->
                                    @if($product->created_at->diffInDays(now()) <= 7)
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold rounded-lg shadow">
                                                NEW
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Quick view overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                        <span class="px-4 py-2 bg-white text-gray-900 text-xs font-bold rounded-xl shadow-lg opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                            <i class="fas fa-eye mr-1"></i>Lihat Detail
                                        </span>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-4">
                                    <p class="text-xs text-purple-500 font-semibold mb-1">{{ $collection->name }}</p>
                                    <h3 class="font-bold text-gray-900 text-sm line-clamp-2 mb-2 group-hover:text-purple-600 transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="flex items-center justify-between">
                                        <p class="font-black text-gray-900 text-base">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        @if(isset($product->stock) && $product->stock <= 0)
                                            <span class="text-xs text-red-500 font-semibold">Habis</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-10 flex justify-center">
                            {{ $products->links('pagination::tailwind') }}
                        </div>
                    @endif

                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-box-open text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2">Belum Ada Produk</h3>
                        <p class="text-gray-500 text-sm mb-6">Produk untuk koleksi <span class="font-bold text-gray-700">{{ $collection->name }}</span> belum tersedia.</p>
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                            <i class="fas fa-arrow-left"></i>Kembali ke Beranda
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection