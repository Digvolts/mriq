@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8 px-2 md:px-4">
    
    <!-- Header -->
    <div class="w-full mb-8">
        <div class="flex justify-between items-start mb-2 px-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-layer-group text-indigo-600 mr-2"></i>Koleksi
                </h1>
                <p class="text-gray-600">Kelola semua koleksi produk</p>
            </div>
            <a href="{{ route('admin.collections.create') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus"></i>Tambah Koleksi
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

        <!-- Table Card -->
        <div class="mx-2 md:mx-4 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            
            <!-- Table Header Stats -->
            <div class="bg-gradient-to-r from-slate-700 to-slate-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg mb-0">
                    <i class="fas fa-list mr-2"></i>Semua Koleksi
                </h3>
                <div class="text-white text-sm">
                    Total: <span class="font-bold">{{ $collections->total() }} koleksi</span>
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">#</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Icon</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Nama Koleksi</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Produk</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">Status</th>
                            <th class="px-4 py-4 text-center text-xs font-semibold text-gray-700 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($collections as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <!-- ID -->
                                <td class="px-4 py-4">
                                    <small class="text-gray-500 font-medium whitespace-nowrap">#{{ $item->id }}</small>
                                </td>

                                <!-- Icon -->
                                <td class="px-4 py-4">
                                    @if ($item->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->icon))
                                        <img src="{{ asset('storage/' . $item->icon) }}" 
                                             alt="{{ $item->name }}"
                                             class="w-14 h-14 rounded-lg object-cover border-2 border-gray-200 shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Nama -->
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-900 whitespace-nowrap">{{ $item->name }}</p>
                                </td>

                                <!-- Produk Count -->
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 text-sm font-bold rounded-lg whitespace-nowrap">
                                        <i class="fas fa-boxes"></i>{{ $item->products()->count() }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-4">
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
                                <td class="px-4 py-4 sticky right-0 bg-white">
                                    <div class="flex justify-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.collections.edit', $item) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                           title="Edit Koleksi">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.collections.destroy', $item) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus koleksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0"
                                                    title="Hapus Koleksi">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-600 font-semibold text-lg">Belum ada koleksi</p>
                                        <p class="text-gray-500 text-sm mt-1">Buat koleksi pertama Anda sekarang</p>
                                        <a href="{{ route('admin.collections.create') }}" class="mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Tambah Koleksi
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
        @if ($collections->hasPages())
            <div class="mt-6 flex justify-center px-4">
                {{ $collections->links('pagination::tailwind') }}
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