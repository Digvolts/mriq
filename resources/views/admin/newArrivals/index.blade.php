@extends('layouts.admin')
@section('title', 'Manajemen New Arrivals')
@section('page_title', 'Manajemen New Arrivals')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 py-8 px-4 md:px-8">
    
    <!-- Header -->
    <div class="max-w-5xl mx-auto mb-8">
        <div class="flex justify-between items-center mb-2">
            <a href="{{ route('admin.newArrivals.create') }}" 
               class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus"></i>Tambah Baru
            </a>
        </div>
        <div class="h-1 w-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mt-3"></div>
    </div>

    <div class="max-w-5xl mx-auto">

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

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-semibold">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-2 text-center">Foto</div>
                    <div class="col-span-5">Nama</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-2 text-center">Aksi</div>
                </div>

                <!-- Table Rows -->
                <div class="divide-y divide-gray-100">
                    @foreach ($newArrivals as $index => $item)
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-purple-50 transition-colors duration-200 group">
                            
                            <!-- No -->
                            <div class="col-span-1 text-center text-sm font-bold text-gray-400">
                                {{ $newArrivals->firstItem() + $loop->index }}
                            </div>

                            <!-- Image -->
                            <div class="col-span-2 flex justify-center">
                                @if ($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                         alt="{{ $item->name }}"
                                         class="w-16 h-16 object-cover rounded-xl shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Name & Date -->
                            <div class="col-span-5">
                                <p class="font-semibold text-gray-800 text-sm">{{ $item->name }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <!-- Status Badge -->
                            <div class="col-span-2 flex justify-center">
                                @if ($item->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-circle-check"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-circle-xmark"></i>Nonaktif
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-span-2 flex items-center justify-center gap-2">
                                <!-- Edit -->
                                <a href="{{ route('admin.newArrivals.edit', $item) }}" 
                                   class="p-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-500 hover:text-white transition-all duration-200"
                                   title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.newArrivals.destroy', $item) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus New Arrival ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-500 hover:text-white transition-all duration-200"
                                            title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Table Footer: Total -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-sm text-gray-500">
                    Total: <span class="font-semibold text-gray-700">{{ $newArrivals->total() }}</span> data
                </div>
            </div>

            <!-- Pagination -->
            @if ($newArrivals->hasPages())
                <div class="flex justify-center mt-6">
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
                    <a href="{{ route('admin.newArrivals.create') }}" 
                       class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Buat New Arrival Pertama
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection