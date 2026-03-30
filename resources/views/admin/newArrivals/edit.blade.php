@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 py-8 px-4 md:px-8">
    
    <!-- Header -->
    <div class="max-w-3xl mx-auto mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.newArrivals.index') }}" class="text-purple-600 hover:text-purple-700 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            <i class="fas fa-edit text-purple-600 mr-2"></i>Edit New Arrival
        </h1>
        <p class="text-gray-600">Perbarui informasi New Arrival Anda</p>
        <div class="h-1 w-24 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mt-3"></div>
    </div>

    <div class="max-w-3xl mx-auto">
        
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

        <form action="{{ route('admin.newArrivals.update', $newArival) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Card: Informasi Dasar -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-600 mr-2"></i>Nama New Arrival <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-purple-600 focus:ring-2 focus:ring-purple-100 outline-none transition-all @error('name') border-red-500 @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $newArival->name) }}"
                               placeholder="Contoh: Summer Collection 2024"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card: Gambar -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-pink-600 to-rose-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-image mr-2"></i>Gambar
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Current Image -->
                    @if ($newArival->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($newArival->image))
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-image text-pink-600 mr-2"></i>Gambar Saat Ini
                            </p>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $newArival->image) }}" 
                                     alt="{{ $newArival->name }}"
                                     class="max-w-xs h-auto rounded-lg shadow-md border-2 border-pink-300">
                            </div>
                        </div>
                    @endif

                    <!-- Upload Gambar Baru -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-upload text-pink-600 mr-2"></i>Ganti Gambar
                        </label>
                        
                        <div class="border-2 border-dashed border-pink-300 rounded-lg p-8 text-center cursor-pointer hover:border-pink-600 hover:bg-pink-50 transition-all"
                             onclick="document.getElementById('image').click()">
                            <input type="file" 
                                   class="hidden" 
                                   id="image" 
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt text-4xl text-pink-600 mb-3 block"></i>
                                <p class="text-gray-700 font-semibold mb-1">Klik atau drag gambar baru ke sini</p>
                                <p class="text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF, WebP | Max: 2MB</p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Biarkan kosong jika tidak ingin mengubah gambar
                        </p>

                        @error('image')
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-pink-600 mr-2"></i>Preview Gambar Baru
                            </p>
                            <div class="flex justify-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <img id="previewImg" 
                                     alt="Preview" 
                                     class="max-w-xs h-auto rounded-lg shadow-md border-2 border-pink-300">
                            </div>
                            <button type="button" 
                                    class="mt-2 w-full px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                                    onclick="clearImagePreview()">
                                <i class="fas fa-times mr-2"></i>Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Status -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-toggle-on mr-2"></i>Status
                    </h3>
                </div>

                <div class="p-6">
                    <label class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition-colors group cursor-pointer">
                        <span class="text-gray-700 font-semibold flex items-center gap-2">
                            <i class="fas fa-eye text-emerald-600"></i>
                            Tampilkan New Arrival
                        </span>
                        
                        <!-- Toggle Visual -->
                        <div class="relative inline-block w-14 h-8">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   class="sr-only peer"
                                   {{ old('is_active', $newArival->is_active) ? 'checked' : '' }}>
                            
                            <div class="w-full h-full bg-gray-300 peer-checked:bg-emerald-600 rounded-full transition-all duration-300"></div>
                            <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-6 shadow-md peer-checked:shadow-lg"></div>
                        </div>
                    </label>

                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        New Arrival akan ditampilkan di halaman utama jika toggle aktif
                    </p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95">
                    <i class="fas fa-save mr-2"></i>Update New Arrival
                </button>
                <a href="{{ route('admin.newArrivals.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg hover:bg-gray-400 transition-all text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Image Preview
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
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

    // Clear Image Preview
    function clearImagePreview() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('uploadPlaceholder').style.display = 'block';
    }

    // Drag & Drop
    const dropZone = document.querySelector('.border-dashed');
    if (dropZone) {
        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('border-pink-600', 'bg-pink-50');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-pink-600', 'bg-pink-50');
        });
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-pink-600', 'bg-pink-50');
            document.getElementById('image').files = e.dataTransfer.files;
            previewImage({target: {files: e.dataTransfer.files}});
        });
    }
</script>
@endsection