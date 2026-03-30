@extends('layouts.home')

@section('title', 'Lacak Pesanan - 2DAY')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-cyan-50 to-blue-50 py-12 px-4">
    <div class="max-w-2xl mx-auto">

        <!-- ===== ALERTS ===== -->
        @if(session('error'))
            <div class="mb-8 animate-fade-in-up">
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-2xl flex-shrink-0"></i>
                    <div>
                        <p class="font-semibold">{{ session('error') }}</p>
                        <p class="text-sm text-red-100 mt-1">Silakan periksa kembali nomor invoice dan email Anda</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- ===== HEADER ===== -->
        <div class="text-center mb-12">
            <div class="inline-block mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-200/40 to-blue-200/40 rounded-full blur-3xl"></div>
                    <div class="relative w-20 h-20 bg-gradient-to-br from-cyan-100 to-blue-100 border-2 border-cyan-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-tracking-pin text-4xl text-cyan-600"></i>
                    </div>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-4">
                Lacak Pesanan
            </h1>
            <p class="text-lg text-gray-600 max-w-md mx-auto">
                Masukkan nomor invoice dan email Anda untuk melihat status pesanan
            </p>
        </div>

        <!-- ===== FORM CARD ===== -->
        <div class="bg-white border-2 border-gray-200 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 mb-12">
            
            <form action="{{ route('order.track') }}" method="POST" class="space-y-6">
                @csrf

                <!-- INVOICE NUMBER -->
                <div>
                    <label class="block text-sm font-black text-cyan-600 mb-3 uppercase tracking-widest">
                        <i class="fas fa-file-invoice text-cyan-500 mr-2"></i>Nomor Invoice
                    </label>
                    <input type="text" 
                           name="invoice_number" 
                           required
                           autofocus
                           value="{{ old('invoice_number') }}"
                           placeholder="Contoh: INV20240320-ABCD1234"
                           class="w-full px-6 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium transition-all duration-300 text-lg">
                    <p class="text-sm text-gray-500 mt-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-amber-500"></i>
                        <span>Nomor invoice tersedia di email konfirmasi pesanan Anda</span>
                    </p>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-black text-cyan-600 mb-3 uppercase tracking-widest">
                        <i class="fas fa-envelope text-cyan-500 mr-2"></i>Email
                    </label>
                    <input type="email" 
                           name="email" 
                           required
                           value="{{ old('email') }}"
                           placeholder="email@example.com"
                           class="w-full px-6 py-4 bg-white border-2 border-gray-200 hover:border-cyan-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 rounded-xl text-gray-900 placeholder-gray-400 font-medium transition-all duration-300 text-lg">
                    <p class="text-sm text-gray-500 mt-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <span>Email yang digunakan saat checkout pesanan</span>
                    </p>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                        class="w-full px-8 py-5 bg-gradient-to-r from-cyan-500 via-cyan-400 to-blue-500 hover:from-cyan-600 hover:via-cyan-500 hover:to-blue-600 text-white font-black text-lg rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center justify-center gap-3 uppercase tracking-wider mt-8">
                    <i class="fas fa-search text-xl"></i>
                    <span>Cari Pesanan</span>
                </button>
            </form>
        </div>

        <!-- ===== INFO CARDS ===== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            
            <!-- CARD 1 -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-cyan-300 hover:shadow-lg transition-all duration-300 group">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-cyan-200 transition">
                    <i class="fas fa-file-invoice-dollar text-2xl text-cyan-600"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-2 text-lg">Nomor Invoice</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Format: <code class="bg-gray-100 px-2 py-1 rounded text-cyan-600 font-mono">INV[DATE]-[RANDOM]</code>
                </p>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-cyan-300 hover:shadow-lg transition-all duration-300 group">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-200 transition">
                    <i class="fas fa-envelope text-2xl text-emerald-600"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-2 text-lg">Email Verifikasi</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Gunakan email yang sama saat melakukan checkout
                </p>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-cyan-300 hover:shadow-lg transition-all duration-300 group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                    <i class="fas fa-clock text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-2 text-lg">Update Real-time</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Status pesanan diperbarui secara otomatis setiap saat
                </p>
            </div>

        </div>

        <!-- ===== FAQ SECTION ===== -->
        <div class="bg-white border-2 border-gray-200 rounded-2xl overflow-hidden shadow-md">
            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                    <i class="fas fa-question-circle text-cyan-500"></i>Pertanyaan Umum
                </h2>
            </div>

            <div class="divide-y divide-gray-200">
                
                <!-- FAQ 1 -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-8 py-5 cursor-pointer hover:bg-gray-50 transition font-semibold text-gray-900">
                        <i class="fas fa-chevron-right group-open:rotate-90 transition text-cyan-500"></i>
                        Bagaimana cara mendapatkan nomor invoice?
                    </summary>
                    <div class="px-8 py-4 bg-gray-50 text-gray-700">
                        <p class="mb-3">Nomor invoice akan dikirimkan melalui email konfirmasi pesanan Anda. Anda bisa juga menemukan nomor invoice di:</p>
                        <ul class="space-y-2 ml-4">
                            <li>✓ Email konfirmasi pesanan</li>
                            <li>✓ Halaman terima kasih saat checkout selesai</li>
                            <li>✓ SMS notifikasi (jika aktif)</li>
                        </ul>
                    </div>
                </details>

                <!-- FAQ 2 -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-8 py-5 cursor-pointer hover:bg-gray-50 transition font-semibold text-gray-900">
                        <i class="fas fa-chevron-right group-open:rotate-90 transition text-cyan-500"></i>
                        Berapa lama pesanan diproses?
                    </summary>
                    <div class="px-8 py-4 bg-gray-50 text-gray-700">
                        <div class="space-y-3">
                            <div>
                                <p class="font-semibold text-gray-900">Transfer Bank / E-Wallet:</p>
                                <p>Pesanan langsung diproses setelah pembayaran terverifikasi (2-3 jam)</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">COD (Bayar di Tempat):</p>
                                <p>Pesanan diproses dalam 1-2 hari kerja setelah dikonfirmasi</p>
                            </div>
                        </div>
                    </div>
                </details>

                <!-- FAQ 3 -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-8 py-5 cursor-pointer hover:bg-gray-50 transition font-semibold text-gray-900">
                        <i class="fas fa-chevron-right group-open:rotate-90 transition text-cyan-500"></i>
                        Bagaimana jika pesanan tidak ditemukan?
                    </summary>
                    <div class="px-8 py-4 bg-gray-50 text-gray-700">
                        <p class="mb-3">Jika pesanan tidak ditemukan, pastikan:</p>
                        <ul class="space-y-2 ml-4">
                            <li>✓ Nomor invoice benar (perhatikan huruf besar/kecil)</li>
                            <li>✓ Email yang digunakan sesuai dengan email checkout</li>
                            <li>✓ Tunggu minimal 5 menit setelah checkout (sistem sedang memproses)</li>
                        </ul>
                        <p class="mt-4 text-sm">
                            <strong>Jika masalah berlanjut:</strong> Hubungi customer service kami
                        </p>
                    </div>
                </details>

                <!-- FAQ 4 -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-8 py-5 cursor-pointer hover:bg-gray-50 transition font-semibold text-gray-900">
                        <i class="fas fa-chevron-right group-open:rotate-90 transition text-cyan-500"></i>
                        Bagaimana status pembayaran?
                    </summary>
                    <div class="px-8 py-4 bg-gray-50 text-gray-700">
                        <div class="space-y-2">
                            <div class="flex items-start gap-3">
                                <span class="text-amber-500 font-bold mt-1">⏳</span>
                                <div>
                                    <p class="font-semibold">Pending: </p>
                                    <p>Menunggu pembayaran dari Anda</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-emerald-500 font-bold mt-1">✓</span>
                                <div>
                                    <p class="font-semibold">Success: </p>
                                    <p>Pembayaran berhasil, pesanan sedang diproses</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-red-500 font-bold mt-1">✗</span>
                                <div>
                                    <p class="font-semibold">Failed: </p>
                                    <p>Pembayaran gagal, silakan coba lagi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </details>

            </div>
        </div>

        <!-- ===== CTA BACK ===== -->
        <div class="text-center mt-12">
            <a href="/" class="inline-block px-8 py-4 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-900 font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

@endsection

@push('styles')
    <style>
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
            animation: fade-in-up 0.3s ease-out;
        }

        input::placeholder {
            color: #d1d5db;
        }

        details summary::-webkit-details-marker {
            display: none;
        }

        details summary {
            list-style: none;
        }

        @media print {
            .sticky,
            button,
            a[href*="track"],
            details {
                display: none;
            }
        }
    </style>
@endpush