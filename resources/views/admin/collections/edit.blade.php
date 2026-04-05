@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8 px-4 md:px-8">
    
    <!-- Header -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.collections.index') }}" class="text-indigo-600 hover:text-indigo-700 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            <i class="fas fa-layer-group text-indigo-600 mr-2"></i>Edit Koleksi
        </h1>
        <p class="text-gray-600">Perbarui informasi koleksi</p>
        <div class="h-1 w-24 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full mt-3"></div>
    </div>

    <div class="max-w-4xl mx-auto">
        
        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg p-4 shadow-md">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1"></i>
                    <div class="flex-1">
                        <h5 class="font-bold text-red-900 mb-2">Terjadi Kesalahan</h5>
                        <ul class="space-y-1 text-red-800 text-sm">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.collections.update', $collection) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: INFORMASI DASAR -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Koleksi
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Nama Koleksi -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-indigo-600 mr-2"></i>Nama Koleksi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all @error('name') border-red-500 @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $collection->name) }}"
                               placeholder="Contoh: Summer Collection"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ICON/GAMBAR -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-image mr-2"></i>Icon Koleksi
                    </h3>
                </div>

                <div class="p-6">
                    <!-- Current Icon Display -->
                    @if ($collection->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($collection->icon))
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image-portrait text-purple-600 mr-2"></i>Icon Saat Ini
                            </p>
                            <div class="flex items-center gap-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <img src="{{ asset('storage/' . $collection->icon) }}" 
                                     alt="{{ $collection->name }}"
                                     class="w-20 h-20 rounded-lg object-cover border-2 border-purple-300">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $collection->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Klik di bawah untuk mengganti icon</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Icon -->
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-upload text-purple-600 mr-2"></i>Ganti Icon/Gambar
                    </label>
                    
                    <div class="border-2 border-dashed border-purple-300 rounded-lg p-8 text-center cursor-pointer hover:border-purple-600 hover:bg-purple-50 transition-all"
                         onclick="document.getElementById('icon').click()">
                        <input type="file" 
                               class="hidden" 
                               id="icon" 
                               name="icon"
                               accept="image/*"
                               onchange="previewIcon(event)">
                        
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-purple-600 mb-3 block"></i>
                            <p class="text-gray-700 font-semibold mb-1">Klik atau drag icon ke sini</p>
                            <p class="text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah</p>
                        </div>
                    </div>

                    @error('icon')
                        <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror

                    <!-- Icon Preview -->
                    <div id="iconPreview" class="mt-4 hidden">
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-purple-600 mr-2"></i>Preview Icon Baru
                        </p>
                        <div class="flex justify-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <img id="previewImg" 
                                 alt="Preview" 
                                 class="max-w-xs h-auto rounded-lg shadow-md border-2 border-purple-300">
                        </div>
                        <button type="button" 
                                class="mt-2 w-full px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                                onclick="clearIconPreview()">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: STATUS -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-toggle-on mr-2"></i>Status Koleksi
                    </h3>
                </div>

                <div class="p-6">
                    <!-- Toggle Switch -->
                    <label class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition-colors group cursor-pointer">
                        <span class="text-gray-700 font-semibold flex items-center gap-2">
                            <i class="fas fa-eye text-emerald-600"></i>
                            Tampilkan Koleksi
                        </span>
                        
                        <!-- Toggle Visual -->
                        <div class="relative inline-block w-14 h-8">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   class="sr-only peer"
                                   {{ old('is_active', $collection->is_active) ? 'checked' : '' }}>
                            
                            <div class="w-full h-full bg-gray-300 peer-checked:bg-emerald-600 rounded-full transition-all duration-300"></div>
                            <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-6 shadow-md peer-checked:shadow-lg"></div>
                        </div>
                    </label>

                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Koleksi akan ditampilkan di toko jika toggle aktif
                    </p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95">
                    <i class="fas fa-save mr-2"></i>Update Koleksi
                </button>
                <a href="{{ route('admin.collections.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg hover:bg-gray-400 transition-all text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Icon Preview
    function previewIcon(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('iconPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    // Clear Icon Preview
    function clearIconPreview() {
        document.getElementById('icon').value = '';
        document.getElementById('iconPreview').classList.add('hidden');
        document.getElementById('uploadPlaceholder').style.display = 'block';
    }

    // Drag & Drop
    const dropZone = document.querySelector('.border-dashed');
    if (dropZone) {
        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('border-purple-600', 'bg-purple-50');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-purple-600', 'bg-purple-50');
        });
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-purple-600', 'bg-purple-50');
            document.getElementById('icon').files = e.dataTransfer.files;
            previewIcon({target: {files: e.dataTransfer.files}});
        });
    }
</script>
@endsection