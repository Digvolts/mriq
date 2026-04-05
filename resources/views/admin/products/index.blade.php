@extends('layouts.admin')
@section('title', 'Manajemen Produk')
@section('page_title', 'Manajemen Produk')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8 px-2 md:px-4">
    
    <!-- Header -->
    <div class="w-full mb-8">
        <div class="flex justify-between items-start mb-2 px-4">

            <a href="{{ route('admin.products.create') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus"></i>Tambah Produk
            </a>
        </div>
        <div class="h-1 w-24 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full mt-3 ml-4"></div>
    </div>

    <div class="w-full">
        
        <!-- Success Alert -->
        @if ($message = Session::get('success'))
            <div class="mx-2 md:mx-4 mb-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-lg p-4 shadow-md flex items-start gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-600 text-xl mt-1"></i>
                <div>
                    <h5 class="font-bold text-emerald-900">Sukses!</h5>
                    <p class="text-emerald-800 text-sm">{{ $message }}</p>
                </div>
            </div>
        @endif

        <!-- Table Card - FULL WIDTH -->
        <div class="mx-2 md:mx-4 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            
            <!-- Table Header Stats -->
            <div class="bg-gradient-to-r from-slate-700 to-slate-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-list mr-2"></i>Semua Produk
                </h3>
                <div class="text-white text-sm">
                    Total: <span class="font-bold">{{ $products->total() }} produk</span>
                </div>
            </div>

            <!-- Responsive Table - WIDER -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">#</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Gambar</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Nama</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Koleksi</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Harga</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Diskon</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Stok</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Terjual</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Ukuran</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Status</th>
                            <th class="px-3 py-4 text-center text-xs font-semibold text-gray-700 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($products as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <!-- ID -->
                                <td class="px-3 py-4">
                                    <small class="text-gray-500 font-medium whitespace-nowrap">#{{ $item->id }}</small>
                                </td>

                                <!-- Gambar -->
                                <td class="px-3 py-4">
                                    @if ($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->name }}"
                                             class="w-14 h-14 rounded-lg object-cover border-2 border-gray-200 shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Nama -->
                                <td class="px-3 py-4">
                                    <p class="font-semibold text-gray-900 whitespace-nowrap">{{ Str::limit($item->name, 40) }}</p>
                                    @if ($item->description)
                                        <p class="text-xs text-gray-500 mt-1 max-w-sm truncate">{{ Str::limit($item->description, 50) }}</p>
                                    @endif
                                </td>

                                <!-- Koleksi -->
                                <td class="px-3 py-4">
                                    <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                        {{ $item->collection?->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Harga -->
                                <td class="px-3 py-4">
                                    <div class="font-bold text-gray-900 whitespace-nowrap">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Diskon -->
                                <td class="px-3 py-4">
                                    @if ($item->discount_price)
                                        <div class="space-y-1">
                                            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg whitespace-nowrap">
                                                Rp {{ number_format($item->discount_price, 0, ',', '.') }}
                                            </span>
                                            <p class="text-xs text-gray-500 line-through whitespace-nowrap">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-gray-400 whitespace-nowrap">-</span>
                                    @endif
                                </td>

                                <!-- Stok -->
                                <td class="px-3 py-4">
                                    @if ($item->stock > 10)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-lg whitespace-nowrap">
                                            <i class="fas fa-check-circle"></i>{{ $item->stock }}
                                        </span>
                                    @elseif ($item->stock > 0)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg whitespace-nowrap">
                                            <i class="fas fa-exclamation-circle"></i>{{ $item->stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 text-sm font-bold rounded-lg whitespace-nowrap">
                                            <i class="fas fa-times-circle"></i>Habis
                                        </span>
                                    @endif
                                </td>

                                <!-- Terjual -->
                                <td class="px-3 py-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg whitespace-nowrap">
                                        <i class="fas fa-shopping-bag"></i>{{ $item->terjual }}
                                    </span>
                                </td>

                                <!-- Ukuran -->
                                <td class="px-3 py-4">
                                    <span class="inline-block px-3 py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-lg whitespace-nowrap">
                                        {{ $item->size }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-4">
                                    @if ($item->is_active)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                            <i class="fas fa-circle-check"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                            <i class="fas fa-circle-xmark"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-3 py-4 sticky right-0 bg-white">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <!-- View -->
                                        <a href="{{ route('admin.products.show', $item->id) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                           title="Lihat Detail"
                                           target="_blank">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        <!-- Duplicate -->
                                        <form action="{{ route('admin.products.duplicate', $item->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin duplikat produk ini?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                                    title="Duplikat Produk">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.products.edit', $item) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                           title="Edit Produk">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.products.destroy', $item) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                                    title="Hapus Produk">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-600 font-semibold text-lg">Belum ada produk</p>
                                        <p class="text-gray-500 text-sm mt-1">Tambahkan produk pertama Anda sekarang</p>
                                        <a href="{{ route('admin.products.create') }}" class="mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Tambah Produk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mt-6 flex justify-center px-4">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
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

    /* Sticky Action Column */
    table tbody td:last-child {
        position: sticky;
        right: 0;
        background-color: white;
        box-shadow: -2px 0 4px rgba(0, 0, 0, 0.05);
    }

    table tbody tr:hover td:last-child {
        background-color: #f9fafb;
    }
</style>
@endsection