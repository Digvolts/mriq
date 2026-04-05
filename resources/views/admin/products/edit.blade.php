@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8 px-4 md:px-8">
    
    <!-- Header -->
    <div class="max-w-5xl mx-auto mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-700 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            <i class="fas fa-edit text-indigo-600 mr-2"></i>Edit Produk
        </h1>
        <p class="text-gray-600">{{ $product->name }}</p>
        <div class="h-1 w-24 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-full mt-3"></div>
    </div>

    <div class="max-w-5xl mx-auto">
        
        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg p-4 shadow-md">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-bold text-red-900 mb-2">
                            <i class="fas fa-times-circle mr-2"></i>Terjadi Kesalahan
                        </h5>
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

        <form action="{{ route('admin.products.update', $product->id) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="productForm"
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: INFORMASI DASAR -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Nama Produk -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-indigo-600 mr-2"></i>Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all @error('name') border-red-500 @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $product->name) }}" 
                               required
                               placeholder="Masukkan nama produk">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-indigo-600 mr-2"></i>Deskripsi
                        </label>
                        <textarea class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all @error('description') border-red-500 @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Deskripsikan produk Anda">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Koleksi -->
                    <div>
                        <label for="collection_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-layer-group text-indigo-600 mr-2"></i>Koleksi <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all @error('collection_id') border-red-500 @enderror" 
                                id="collection_id" 
                                name="collection_id" 
                                required>
                            <option value="">-- Pilih Koleksi --</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" 
                                        {{ old('collection_id', $product->collection_id) == $collection->id ? 'selected' : '' }}>
                                    {{ $collection->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('collection_id')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

<!-- SECTION 2: DETAIL PRODUK -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
        <h3 class="text-white font-bold text-lg mb-0">
            <i class="fas fa-palette mr-2"></i>Detail Produk
        </h3>
    </div>

    <div class="p-6 space-y-5">
        <!-- Ukuran -->
        <div>
            <label for="size" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-ruler text-purple-600 text-sm"></i>
                </div>
                Ukuran <span class="text-red-500">*</span>
            </label>
            <select class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-purple-600 focus:ring-2 focus:ring-purple-100 outline-none transition-all bg-white hover:border-purple-300 @error('size') border-red-500 @enderror" 
                    id="size" 
                    name="size" 
                    required>
                <option value="">-- Pilih Ukuran --</option>
                @foreach(['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL'] as $sizeOption)
                    <option value="{{ $sizeOption }}" 
                            {{ old('size', $product->size ?? '') == $sizeOption ? 'selected' : '' }}>
                        {{ $sizeOption }}
                    </option>
                @endforeach
            </select>
            @error('size')
                <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Grid 2 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Bahan -->
            <div>
                <label for="bahan" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-shirt text-purple-600 text-sm"></i>
                    </div>
                    Bahan
                </label>
                <input type="text" 
                       class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-purple-600 focus:ring-2 focus:ring-purple-100 outline-none transition-all hover:border-purple-300" 
                       id="bahan" 
                       name="bahan" 
                       placeholder="Contoh: Cotton Combed 20s"
                       value="{{ old('bahan', $product->bahan ?? '') }}">
                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Bahan utama produk</p>
            </div>

            <!-- Style -->
            <div>
                <label for="style" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-pen-fancy text-purple-600 text-sm"></i>
                    </div>
                    Style
                </label>
                <input type="text" 
                       class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-purple-600 focus:ring-2 focus:ring-purple-100 outline-none transition-all hover:border-purple-300" 
                       id="style" 
                       name="style" 
                       placeholder="Contoh: Regular Fit, Preshrink"
                       value="{{ old('style', $product->style ?? '') }}">
                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Gaya/potongan produk</p>
            </div>
        </div>

        <!-- Printing Design -->
        <div>
            <label for="printing_design" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-paint-brush text-purple-600 text-sm"></i>
                </div>
                Printing Design
            </label>
            <textarea class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-purple-600 focus:ring-2 focus:ring-purple-100 outline-none transition-all hover:border-purple-300 resize-none" 
                      id="printing_design" 
                      name="printing_design" 
                      rows="3"
                      placeholder="Deskripsi desain print/logo/grafis pada produk...">{{ old('printing_design', $product->printing_design ?? '') }}</textarea>
            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Detail desain cetak yang ada pada produk</p>
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-600 rounded-lg">
            <p class="text-sm text-gray-700">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                <span class="font-semibold">Tips:</span> Isi detail produk dengan akurat agar pembeli mendapatkan informasi yang jelas tentang spesifikasi produk Anda.
            </p>
        </div>
    </div>
</div>

            <!-- SECTION 3: HARGA & STOK -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-money-bill-wave mr-2"></i>Harga & Stok
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Harga Normal -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-emerald-600 mr-2"></i>Harga Normal (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 outline-none transition-all @error('price') border-red-500 @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}" 
                                       step="0.01"
                                       required
                                       placeholder="0"
                                       onchange="updateDiscount()">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga Diskon -->
                        <div>
                            <label for="discount_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-percent text-emerald-600 mr-2"></i>Harga Diskon (Rp)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 outline-none transition-all @error('discount_price') border-red-500 @enderror" 
                                       id="discount_price" 
                                       name="discount_price" 
                                       value="{{ old('discount_price', $product->discount_price) }}" 
                                       step="0.01"
                                       placeholder="0"
                                       onchange="updateDiscount()">
                                @error('discount_price')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Stok -->
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-warehouse text-emerald-600 mr-2"></i>Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 outline-none transition-all @error('stock') border-red-500 @enderror" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   min="0"
                                   required
                                   placeholder="0">
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Discount Info -->
                    <div id="discountInfo" class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg hidden">
                        <p class="text-sm text-emerald-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Diskon: <span id="discountPercentage" class="font-bold">0</span>% | 
                            Hemat: <span id="discountAmount" class="font-bold">Rp 0</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: MERCHANDISE & PENJUALAN -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-gift mr-2"></i>Merchandise & Penjualan
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Exclusive Merchandise -->
                    <div>
                        <label for="exclusive_mercendise" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-box text-amber-600 mr-2"></i>Exclusive Merchandise
                        </label>
                        <textarea class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-amber-600 focus:ring-2 focus:ring-amber-100 outline-none transition-all" 
                                  id="exclusive_mercendise" 
                                  name="exclusive_mercendise" 
                                  rows="3"
                                  placeholder="Deskripsi exclusive merchandise">{{ old('exclusive_mercendise', $product->exclusive_mercendise) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Terjual -->
                        <div>
                            <label for="terjual" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-shopping-bag text-amber-600 mr-2"></i>Jumlah Terjual
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-amber-600 focus:ring-2 focus:ring-amber-100 outline-none transition-all" 
                                   id="terjual" 
                                   name="terjual" 
                                   value="{{ old('terjual', $product->terjual) }}" 
                                   min="0"
                                   placeholder="0">
                        </div>

                        <!-- Keterangan Bestseller -->
                        <div>
                            <label for="keterangan_bestseller" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-star text-amber-600 mr-2"></i>Keterangan Bestseller
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-amber-600 focus:ring-2 focus:ring-amber-100 outline-none transition-all" 
                                   id="keterangan_bestseller" 
                                   name="keterangan_bestseller" 
                                   value="{{ old('keterangan_bestseller', $product->keterangan_bestseller) }}"
                                   placeholder="Contoh: Bestseller, Hot Item">
                        </div>
                    </div>

                    <!-- Pengiriman -->
                    <div>
                        <label for="pengiriman" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-truck text-amber-600 mr-2"></i>Informasi Pengiriman
                        </label>
                        <textarea class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-amber-600 focus:ring-2 focus:ring-amber-100 outline-none transition-all" 
                                  id="pengiriman" 
                                  name="pengiriman" 
                                  rows="2"
                                  placeholder="Deskripsi pengiriman">{{ old('pengiriman', $product->pengiriman) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: GAMBAR PRODUK -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-image mr-2"></i>Gambar Produk
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Gambar Saat Ini -->
                    @if($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm font-semibold text-blue-900 mb-3">
                                <i class="fas fa-check-circle mr-2"></i>Gambar Saat Ini
                            </p>
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="max-w-xs h-auto rounded-lg shadow-md border-2 border-blue-300">
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Gambar tidak ditemukan
                            </p>
                        </div>
                    @endif

                    <!-- Upload Gambar Baru -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-upload text-cyan-600 mr-2"></i>Upload Gambar Baru
                        </label>
                        
                        <div class="border-2 border-dashed border-cyan-300 rounded-lg p-6 text-center cursor-pointer hover:border-cyan-600 hover:bg-cyan-50 transition-all"
                             onclick="document.getElementById('image').click()">
                            <input type="file" 
                                   class="hidden" 
                                   id="image" 
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt text-3xl text-cyan-600 mb-2 block"></i>
                                <p class="text-gray-700 font-semibold mb-1">Klik atau drag gambar ke sini</p>
                                <p class="text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF, WebP | Max: 2MB</p>
                            </div>
                        </div>

                        @error('image')
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-cyan-600 mr-2"></i>Preview Gambar Baru
                            </p>
                            <div class="flex justify-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <img id="previewImg" 
                                     alt="Preview" 
                                     class="max-w-xs h-auto rounded-lg shadow-md border-2 border-cyan-300">
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

            <!-- SECTION 6: STATUS -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-rose-600 to-pink-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg mb-0">
                        <i class="fas fa-toggle-on mr-2"></i>Status
                    </h3>
                </div>

               <div class="p-6">
    <!-- Toggle Switch dengan Status Text -->
    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors group">
        <div class="flex items-center gap-2">
            <i class="fas fa-circle-check text-rose-600"></i>
            <div>
                <p class="text-gray-700 font-semibold">Produk Aktif / Tampil di Toko</p>
            </div>
        </div>
        
        <!-- Toggle Visual -->
        <div class="relative inline-block w-14 h-8 cursor-pointer" onclick="toggleSwitch()">
            <input type="checkbox" 
                   id="is_active" 
                   name="is_active" 
                   value="1"
                   class="sr-only peer"
                   onchange="updateStatus()"
                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            
            <div class="w-full h-full bg-gray-300 peer-checked:bg-green-600 rounded-full transition-all duration-300"></div>
            <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-6 shadow-md peer-checked:shadow-lg"></div>
        </div>
    </div>

    <p class="text-xs text-gray-500 mt-3">
        <i class="fas fa-info-circle mr-1"></i>
        Klik toggle untuk mengaktifkan atau menonaktifkan produk di toko online
    </p>
</div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105 active:scale-95">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('admin.products.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg hover:bg-gray-400 transition-all text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    // Update Discount Info
    function updateDiscount() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const discount = parseFloat(document.getElementById('discount_price').value) || 0;
        const discountInfo = document.getElementById('discountInfo');
        
        if (discount > 0 && price > discount) {
            const percentage = Math.round(((price - discount) / price) * 100);
            const saving = price - discount;
            
            document.getElementById('discountPercentage').textContent = percentage;
            document.getElementById('discountAmount').textContent = 'Rp ' + saving.toLocaleString('id-ID');
            discountInfo.classList.remove('hidden');
        } else {
            discountInfo.classList.add('hidden');
        }
    }

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

    // Initialize discount info on load
    window.addEventListener('load', updateDiscount);
    function toggleSwitch() {
        const checkbox = document.getElementById('is_active');
        checkbox.checked = !checkbox.checked;
        updateStatus();
    }


</script>
@endsection