@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 py-8 px-4 md:px-8">
    
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-sparkles text-purple-600 mr-2"></i>New Arrivals
                </h1>
                <p class="text-gray-600">Kelola koleksi produk terbaru Anda</p>
            </div>
            <a href="{{ route('admin.newArrivals.create') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus"></i>Tambah Baru
            </a>
        </div>
        <div class="h-1 w-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mt-3"></div>
    </div>

    <div class="max-w-7xl mx-auto">
        
        <!-- Success Alert -->
        @if ($message = Session::get('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-lg p-4 shadow-md flex items-start gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-600 text-xl mt-1"></i>
                <div>
                    <h5 class="font-bold text-emerald-900">Sukses!</h5>
                    <p class="text-emerald-800 text-sm">{{ $message }}</p>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        @if ($newArrivals->count() > 0)
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($newArrivals as $item)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
                        
                        <!-- Image Container -->
                        <div class="relative h-64 overflow-hidden bg-gray-100">
                            @if ($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}" 
                                     alt="{{ $item->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                </div>
                            @endif

                            <!-- Badge -->
                            <div class="absolute top-3 right-3">
                                @if ($item->is_active)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-circle-check"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-circle-xmark"></i>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 space-y-4">
                            <!-- Nama -->
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $item->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-2 border-t border-gray-100">
                                <!-- Edit -->
                                <a href="{{ route('admin.newArrivals.edit', $item) }}" 
                                   class="flex-1 px-4 py-2 bg-amber-100 text-amber-700 font-semibold rounded-lg hover:bg-amber-600 hover:text-white transition-all duration-200 text-center text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-edit"></i>Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.newArrivals.destroy', $item) }}" 
                                      method="POST" 
                                      class="flex-1"
                                      onsubmit="return confirm('Yakin ingin menghapus New Arrival ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-600 hover:text-white transition-all duration-200 text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-trash"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($newArrivals->hasPages())
                <div class="flex justify-center mt-8">
                    {{ $newArrivals->links('pagination::tailwind') }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 rounded-full bg-purple-100 flex items-center justify-center mb-6">
                        <i class="fas fa-sparkles text-4xl text-purple-600"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada New Arrival</h3>
                    <p class="text-gray-600 mb-6 max-w-md">Mulai buat New Arrival pertama Anda untuk menarik perhatian pelanggan dengan produk-produk terbaru</p>
                    
                    <a href="{{ route('admin.newArrivals.create') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Buat New Arrival Pertama
                    </a>
                </div>
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
</style>
@endsection