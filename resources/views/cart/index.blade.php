@extends('layouts.home')

@section('title', 'Keranjang - 2DAY')

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- ===== ALERTS ===== -->
    @if(session('success'))
        <div class="fixed top-20 left-4 z-50 max-w-sm animate-fade-in-up">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-20 left-4 z-50 max-w-sm animate-fade-in-up">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-2xl"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="fixed top-20 left-4 z-50 max-w-sm animate-fade-in-up">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-2xl shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                    <span class="font-semibold">Validasi Gagal!</span>
                </div>
                <ul class="ml-9 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- ===== MAIN CONTENT ===== -->
    <div class="pt-5 pb-12">
        @if(count($cart) > 0)

            <!-- ===== HERO SECTION ===== -->
            <div class="max-w-7xl mx-auto px-4 py-12 text-center mb-12">
                <h1 class="text-7xl font-black text-gray-900 mb-4">
                    Keranjang Belanja
                </h1>
                <p class="text-xl text-gray-600">
                    Siap untuk checkout? Tinjau pesanan Anda di bawah
                </p>
            </div>

            <!-- ===== MASONRY LAYOUT ===== -->
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    
                    <!-- ===== LEFT: CHECKOUT FORM (2 COLS) ===== -->
                    <div class="lg:col-span-2 order-2 lg:order-1">
                        <form action="{{ route('cart.checkout') }}" method="POST" class="sticky top-24" id="checkoutForm">
                            @csrf
                            
                            <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-200 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                                <div class="mb-8">
                                    <h2 class="text-3xl font-black text-gray-900 mb-2">
                                        <i class="fas fa-user-circle text-cyan-500 mr-3"></i>Checkout
                                    </h2>
                                    <p class="text-gray-600">Lengkapi data penerima pesanan</p>
                                </div>

                                <!-- FORM FIELDS -->
                                <div class="space-y-4">
                                    <!-- NAMA -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">👤 Nama Lengkap</label>
                                        <input type="text" 
                                               name="customer_name" 
                                               required
                                               value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                               placeholder="Siapa nama Anda?"
                                               class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium transition-all duration-300 @error('customer_name') border-red-500 @enderror">
                                        @error('customer_name')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- EMAIL -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">✉️ Email</label>
                                        <input type="email" 
                                               name="email" 
                                               required
                                               value="{{ old('email', auth()->user()->email ?? '') }}"
                                               placeholder="email@example.com"
                                               class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium transition-all duration-300 @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- PHONE -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">📱 Telepon</label>
                                        <div class="relative">
                                            <span class="absolute left-5 top-4 text-gray-700 font-bold text-lg">+62</span>
                                            <input type="tel" 
                                                   name="phone" 
                                                   required
                                                   value="{{ old('phone') }}"
                                                   placeholder="8xxxxxxxxxx"
                                                   maxlength="12"
                                                   inputmode="numeric"
                                                   pattern="[0-9]{9,12}"
                                                   class="w-full pl-16 pr-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium transition-all duration-300 @error('phone') border-red-500 @enderror">
                                        </div>
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- PROVINSI -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">🏠 Provinsi</label>

                                        <select name="province_id"
                                                id="provinceSelect"
                                                class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 font-medium transition-all duration-300 appearance-none cursor-pointer" required>
                                            <option value="">-- Pilih Provinsi --</option>

                                            @foreach($provinces as $province)
                                                <option value="{{ $province['code'] }}"
                                                    {{ old('province_id', $selectedProvince) == $province['code'] ? 'selected' : '' }}>
                                                    {{ $province['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- KABUPATEN/KOTA -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">🏘️ Kabupaten/Kota</label>

                                        <select name="regency_id"
                                                id="regencySelect"
                                                class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 font-medium transition-all duration-300 appearance-none cursor-pointer" required>
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                        </select>

                                        @error('regency_id')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- KECAMATAN -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">🗺️ Kecamatan</label>

                                        <select name="district_id"
                                                id="districtSelect"
                                                required
                                                class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 font-medium transition-all duration-300 appearance-none cursor-pointer" required>
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>

                                        @error('district_id')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <!-- ADDRESS -->
                                    <div>
                                        <label class="block text-sm font-bold text-cyan-600 mb-2 uppercase tracking-widest">📍 Alamat Detail</label>
                                        <textarea name="address" 
                                                  rows="3"
                                                  required
                                                  placeholder="Jalan, No. Rumah, RT/RW"
                                                  class="w-full px-5 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium resize-none transition-all duration-300 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="text-red-500 text-xs mt-2"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- SUBMIT BUTTON -->
                                <button type="submit" 
                                        class="w-full mt-8 px-8 py-5 bg-gradient-to-r from-cyan-500 via-cyan-400 to-blue-500 hover:from-cyan-600 hover:via-cyan-500 hover:to-blue-600 text-white font-black text-lg rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wider">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Lanjutkan Checkout</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ===== RIGHT: ITEMS + SUMMARY (2 COLS) ===== -->
                    <div class="lg:col-span-2 order-1 lg:order-2 space-y-6">
                        
                        <!-- ===== ITEMS LIST ===== -->
                        <div class="space-y-3">
                            <h2 class="text-2xl font-black text-gray-900 px-2">
                                <i class="fas fa-list text-cyan-500 mr-2"></i>Produk Anda
                            </h2>
                            
                            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                @foreach($cart as $cartKey => $item)
                                    @php
                                        $productId = $item['product_id'] ?? $item['id'] ?? null;
                                        if (!$productId) continue;

                                        $itemPrice = $item['price'] ?? 0;
                                        $itemOriginalPrice = $item['original_price'] ?? $itemPrice;
                                        $hasDiscount = $itemPrice < $itemOriginalPrice;
                                        $itemSubtotal = $itemPrice * $item['quantity'];
                                        $discountPercent = $hasDiscount ? (($itemOriginalPrice - $itemPrice) / $itemOriginalPrice) * 100 : 0;
                                    @endphp
                                    
                                    <div class="cart-item-mini bg-white border-2 border-gray-200 rounded-2xl p-4 hover:border-cyan-400 hover:shadow-lg transition-all duration-300 group relative">
                                        <div class="flex gap-3">
                                            <!-- IMAGE -->
                                            <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 border-2 border-gray-200 group-hover:border-cyan-400 relative">
                                                @if($item['image'] && \Illuminate\Support\Facades\Storage::disk('public')->exists($item['image']))
                                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                                         alt="{{ $item['name'] }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-300"></i>
                                                    </div>
                                                @endif
                                                @if($hasDiscount)
                                                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg">
                                                        -{{ round($discountPercent) }}%
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- DETAILS -->
                                            <div class="flex-grow min-w-0">
                                                <h4 class="text-sm font-bold text-gray-900 truncate">{{ $item['name'] }}</h4>
                                                <p class="text-xs text-gray-500">Size: <span class="text-cyan-600 font-bold">{{ strtoupper($item['size']) }}</span></p>
                                                
                                                <div class="flex items-center gap-2 mt-2">
                                                    @if($hasDiscount)
                                                        <span class="text-xs text-gray-400 line-through">Rp{{ number_format($itemOriginalPrice, 0, ',', '.') }}</span>
                                                        <span class="text-sm font-bold text-cyan-600">Rp{{ number_format($itemPrice, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-sm font-bold text-gray-900">Rp{{ number_format($itemPrice, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- QTY + REMOVE -->
                                            <div class="flex flex-col items-end justify-between gap-2">
                                                <form action="{{ route('cart.remove', $cartKey) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors text-sm font-bold p-1" onclick="return confirm('Hapus produk ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('cart.update', $cartKey) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <div class="flex items-center gap-1 bg-gray-100 border-2 border-gray-200 rounded-lg p-1">
                                                        <button type="submit" name="action" value="decrease" class="w-6 h-6 bg-gray-200 hover:bg-cyan-400 text-gray-700 rounded text-sm font-bold">−</button>
                                                        <input type="number" 
                                                            name="quantity" 
                                                            value="{{ $item['quantity'] }}" 
                                                            min="1" 
                                                            max="999"
                                                            readonly
                                                            class="w-10 h-6 bg-transparent text-gray-900 text-sm text-center font-bold focus:outline-none border-none">
                                                        <button type="submit" name="action" value="increase" class="w-6 h-6 bg-gray-200 hover:bg-cyan-400 text-gray-700 rounded text-sm font-bold">+</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- SUBTOTAL -->
                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center text-sm">
                                            <span class="text-gray-600 font-medium">Subtotal:</span>
                                            <span class="font-bold text-cyan-600">Rp{{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ===== ORDER SUMMARY ===== -->
                        <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <h3 class="text-xl font-black text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-receipt text-cyan-500"></i>Ringkasan Pesanan
                            </h3>

                            <div class="space-y-3 mb-4 pb-4 border-b-2 border-gray-200">
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span><i class="fas fa-box text-gray-400 mr-2"></i>Subtotal</span>
                                    <span class="font-semibold">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span><i class="fas fa-truck text-emerald-500 mr-2"></i>Pengiriman</span>
                                    <span class="text-emerald-600 font-semibold">✓ GRATIS</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span><i class="fas fa-percent text-gray-400 mr-2"></i>Pajak</span>
                                    <span class="font-semibold text-gray-500">-</span>
                                </div>

                                @if($savings > 0)
                                    <div class="flex justify-between text-sm p-3 bg-emerald-50 border-2 border-emerald-200 rounded-lg">
                                        <span class="text-emerald-700 font-semibold"><i class="fas fa-fire mr-1"></i>Hemat</span>
                                        <span class="text-emerald-700 font-black">Rp{{ number_format($savings, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- TOTAL -->
                            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border-2 border-cyan-200 rounded-xl p-4 mb-4">
                                <p class="text-xs text-gray-600 mb-1 uppercase tracking-widest font-bold">Total Pembayaran</p>
                                <p class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600">
                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="space-y-2">
                                <a href="/" class="block text-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 hover:text-cyan-600 border-2 border-gray-200 hover:border-cyan-400 rounded-xl font-semibold transition-all duration-300">
                                    <i class="fas fa-arrow-left mr-2"></i>Lanjut Belanja
                                </a>
                                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-red-50 hover:bg-red-100 text-red-600 border-2 border-red-200 hover:border-red-400 rounded-xl font-semibold transition-all duration-300">
                                        <i class="fas fa-trash-alt mr-2"></i>Kosongkan Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        @else
            <!-- ===== EMPTY STATE ===== -->
            <div class="flex flex-col items-center justify-center min-h-screen text-center px-4">
                <div class="inline-block mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-200/40 to-blue-200/40 rounded-full blur-3xl"></div>
                        <div class="relative w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-50 border-2 border-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-6xl text-cyan-500"></i>
                        </div>
                    </div>
                </div>

                <h1 class="text-6xl font-black text-gray-900 mb-4">Keranjang Kosong</h1>
                <p class="text-xl text-gray-600 max-w-md mb-8">
                    Belum ada produk di keranjang Anda. Jelajahi koleksi produk favorit kami sekarang!
                </p>

                <a href="/" class="px-12 py-5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-black text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

</div>

@endsection

@push('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
        }

        /* ===== SCROLLBAR ===== */
        div::-webkit-scrollbar {
            width: 8px;
        }

        div::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 9999px;
        }

        div::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #22d3ee, #3b82f6);
            border-radius: 9999px;
        }

        div::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #06b6d4, #2563eb);
        }

        /* ===== INPUTS ===== */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        textarea,
        select {
            transition: all 0.3s;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* ===== DISABLED STYLES ===== */
        select:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* ===== FOCUS EFFECTS ===== */
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            ring: 4px rgba(6, 182, 212, 0.1);
            border-color: #06b6d4;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const provinceSelect = document.getElementById('provinceSelect');
    const regencySelect = document.getElementById('regencySelect');
    const districtSelect = document.getElementById('districtSelect');

    const selectedProvince = @json(old('province_id', $selectedProvince ?? ''));
    const selectedRegency = @json(old('regency_id', $selectedRegency ?? ''));
    const selectedDistrict = @json(old('district_id', $selectedDistrict ?? ''));

    const regencyBaseUrl = @json(route('cart.ajax.regencies', ['provinceCode' => '__ID__']));
    const districtBaseUrl = @json(route('cart.ajax.districts', ['regencyCode' => '__ID__']));

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    function fillSelect(select, items, selectedValue = '') {
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.code;
            option.textContent = item.name;

            if (selectedValue && selectedValue == item.code) {
                option.selected = true;
            }

            select.appendChild(option);
        });

        select.disabled = false;
    }

    async function loadRegencies(provinceCode, selectedValue = '') {
        resetSelect(regencySelect, '-- Pilih Kabupaten/Kota --');
        resetSelect(districtSelect, '-- Pilih Kecamatan --');

        if (!provinceCode) return;

        try {
            const url = regencyBaseUrl.replace('__ID__', provinceCode);
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Gagal mengambil data kabupaten/kota');
            }

            const data = await response.json();
            fillSelect(regencySelect, data, selectedValue);
        } catch (error) {
            console.error('Gagal load regencies:', error);
        }
    }

    async function loadDistricts(regencyCode, selectedValue = '') {
        resetSelect(districtSelect, '-- Pilih Kecamatan --');

        if (!regencyCode) return;

        try {
            const url = districtBaseUrl.replace('__ID__', regencyCode);
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Gagal mengambil data kecamatan');
            }

            const data = await response.json();
            fillSelect(districtSelect, data, selectedValue);
        } catch (error) {
            console.error('Gagal load districts:', error);
        }
    }

    provinceSelect.addEventListener('change', async function () {
        await loadRegencies(this.value);
    });

    regencySelect.addEventListener('change', async function () {
        await loadDistricts(this.value);
    });

    if (selectedProvince) {
        await loadRegencies(selectedProvince, selectedRegency);
    } else {
        resetSelect(regencySelect, '-- Pilih Kabupaten/Kota --');
        resetSelect(districtSelect, '-- Pilih Kecamatan --');
    }

    if (selectedRegency) {
        await loadDistricts(selectedRegency, selectedDistrict);
    }
});
</script>
@endpush