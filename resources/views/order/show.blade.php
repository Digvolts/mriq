@extends('layouts.home')

@section('title', 'Pesanan - ' . $order->invoice_number)

@section('content')

<div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 py-8 md:py-12">
    <div class="max-w-6xl mx-auto px-4 md:px-6">

        <!-- ===== HEADER SECTION ===== -->
        <div class="mb-12">
            <!-- Status Banner -->
            @if($order->payment_status === 'paid')
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="flex-shrink-0 w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-xl">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black mb-1">Pembayaran Berhasil!</h1>
                                <p class="text-emerald-100 text-lg">Pesanan Anda telah dikonfirmasi dan sedang diproses</p>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-xl rounded-2xl p-4 border border-white border-opacity-20">
                            <p class="text-sm text-emerald-100 font-semibold">NOMOR PESANAN</p>
                            <p class="text-2xl font-black font-mono tracking-wider">{{ $order->invoice_number }}</p>
                        </div>
                    </div>
                </div>
            @elseif($order->payment_status === 'pending')
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 animate-pulse">
                        <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H4.75A2.75 2.75 0 002 4.25v11A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-11A2.75 2.75 0 0015.25 1.5zm-5 5.5a.75.75 0 100-1.5.75.75 0 000 1.5zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="flex-shrink-0 w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-xl animate-pulse">
                                <i class="fas fa-hourglass-half text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black mb-1">Menunggu Pembayaran</h1>
                                <p class="text-amber-100 text-lg">Selesaikan pembayaran untuk melanjutkan</p>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-xl rounded-2xl p-4 border border-white border-opacity-20">
                            <p class="text-sm text-amber-100 font-semibold">NOMOR PESANAN</p>
                            <p class="text-2xl font-black font-mono tracking-wider">{{ $order->invoice_number }}</p>
                        </div>
                    </div>
                </div>
            
            @else
                <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-3xl p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.707 1.293a1 1 0 00-1.414 0L8.5 5.086 3.707 1.293a1 1 0 00-1.414 1.414L7.086 6.5 3.293 10.293a1 1 0 101.414 1.414L8.5 7.914l4.793 4.793a1 1 0 001.414-1.414L9.914 6.5l3.793-3.793a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="flex-shrink-0 w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-xl">
                                <i class="fas fa-exclamation-circle text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black mb-1">Pembayaran Gagal</h1>
                                <p class="text-red-100 text-lg">Silakan coba lagi dengan metode pembayaran lain</p>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-xl rounded-2xl p-4 border border-white border-opacity-20">
                            <p class="text-sm text-red-100 font-semibold">NOMOR PESANAN</p>
                            <p class="text-2xl font-black font-mono tracking-wider">{{ $order->invoice_number }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

            <!-- ===== LEFT SECTION (2 COLS) ===== -->
            <div class="lg:col-span-2 space-y-6">

                <!-- ORDER ITEMS CARD -->
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b-2 border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">Item Pesanan</h2>
                            <p class="text-sm text-gray-500">{{ count($order->items) }} produk</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($order->items as $item)
                            <div class="group hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 p-4 rounded-2xl transition-all duration-300 border border-transparent hover:border-cyan-200">
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ $item->product_name }}"
                                                 class="w-28 h-28 object-cover rounded-2xl bg-gray-100 border-2 border-gray-200 group-hover:border-cyan-300 group-hover:shadow-md transition-all">
                                        @else
                                            <div class="w-28 h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl border-2 border-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-grow">
                                        <h3 class="font-black text-gray-900 text-lg mb-3 group-hover:text-cyan-600 transition-colors">
                                            {{ $item->product_name }}
                                        </h3>
                                        
                                        <div class="flex flex-wrap gap-4 mb-3">
                                            <div class="bg-gray-100 rounded-xl px-3 py-1.5">
                                                <p class="text-xs text-gray-600 font-semibold">SIZE</p>
                                                <p class="text-sm font-black text-cyan-600 uppercase">{{ $item->size }}</p>
                                            </div>
                                            <div class="bg-gray-100 rounded-xl px-3 py-1.5">
                                                <p class="text-xs text-gray-600 font-semibold">JUMLAH</p>
                                                <p class="text-sm font-black text-gray-900">{{ $item->quantity }} pcs</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex-shrink-0 text-right">
                                        @if($item->price < $item->original_price)
                                            <p class="text-sm text-gray-400 line-through mb-1 font-medium">
                                                Rp{{ number_format($item->original_price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-2xl font-black bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent mb-2">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        @else
                                            <p class="text-2xl font-black text-gray-900 mb-2">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-600">
                                            Subtotal: <span class="text-cyan-600 font-bold">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-500">
                                <i class="fas fa-box-open text-5xl mb-4 opacity-20"></i>
                                <p>Tidak ada produk</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- CUSTOMER & SHIPPING INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Customer Info -->
                    <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900">Penerima</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Nama</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Email</p>
                                <p class="text-sm font-medium text-cyan-600 break-all">{{ $order->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Telepon</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-pin text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900">Pengiriman</h3>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-4 border border-orange-200">
                                <div class="flex flex-col"> <!-- gunakan flex-col agar vertical, atau tambahkan flex-wrap -->
                                    <p class="break-words">{{ $order->address }}</p>
                                </div>                       
                                 <div class="space-y-1 text-gray-700 text-sm">
                                    <p>📍 {{ $order->district_name }}, {{ $order->regency_name }}</p>
                                    <p>📍 Provinsi {{ $order->province_name }}</p>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-3 border border-green-200 flex items-center gap-2">
                                <i class="fas fa-truck text-green-600 text-lg"></i>
                                <span class="text-sm font-semibold text-green-900">Pengiriman Gratis ke Seluruh Indonesia</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ===== RIGHT SECTION (1 COL) ===== -->
            <div class="space-y-6">

                <!-- SUMMARY CARD -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-8 shadow-2xl border border-gray-200">
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-8 pb-6 border-b-2 border-gray-100">
                        <i class="fas fa-receipt text-cyan-600 mr-2"></i>Ringkasan
                    </h3>

                    <!-- Breakdown -->
                    <div class="space-y-4 mb-6 pb-6 border-b-2 border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Subtotal</span>
                            <span class="font-bold text-gray-900">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Pengiriman</span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1">
                                <i class="fas fa-check text-sm"></i>GRATIS
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Pajak</span>
                            <span class="font-bold text-gray-500">-</span>
                        </div>
                    </div>

                    <!-- TOTAL AMOUNT -->
                    <div class="bg-gradient-to-br from-cyan-500 via-blue-500 to-blue-600 rounded-2xl p-6 mb-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 opacity-10 w-32 h-32">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155.03.299.076.43.14a.764.764 0 00-.502 1.394c.159-.076.294-.177.41-.295a.744.744 0 00.093-1.239zm2.133 2.27a.765.765 0 10-1.086-1.086.765.765 0 001.086 1.086zM6.461 12.612a.767.767 0 001.086-1.086.767.767 0 00-1.086 1.086zM12 20a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-cyan-100 font-bold uppercase tracking-widest mb-2">Total Pembayaran</p>
                        <p class="text-4xl font-black text-white">
                            Rp{{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- STATUS BADGES -->
                    <div class="space-y-3">
                        <!-- Payment Status -->
                        <div>
                            <p class="text-xs text-gray-600 font-bold uppercase tracking-widest mb-2">Status Pembayaran</p>
@if($order->payment_status === 'paid')
    <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-3.5 text-emerald-900 text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-check-circle text-lg"></i>
        <span>Pembayaran Berhasil</span>
    </div>
@elseif($order->payment_status === 'pending')
    <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-3.5 text-amber-900 text-sm font-semibold flex items-center gap-2 animate-pulse">
        <i class="fas fa-hourglass-half"></i>
        <span>Menunggu Pembayaran</span>
    </div>
@elseif($order->payment_status === 'expired')
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3.5 text-red-900 text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <span>Pembayaran Expired</span>
    </div>
@elseif($order->payment_status === 'cancelled')
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3.5 text-red-900 text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-ban text-lg"></i>
        <span>Pembayaran Dibatalkan</span>
    </div>
@else
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3.5 text-red-900 text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-times-circle"></i>
        <span>Pembayaran Gagal</span>
    </div>
@endif
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <p class="text-xs text-gray-600 font-bold uppercase tracking-widest mb-2">Metode Pembayaran</p>
                     <div>
    <p class="text-xs text-gray-600 font-bold uppercase tracking-widest mb-2">Tipe Pembayaran</p>
    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-3.5 text-blue-900 text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-credit-card"></i>
        <span>{{ $order->payment_type_label }}</span>
    </div>
</div>
                        </div>
                    </div>

                </div>



            </div>

        </div>

        <!-- ===== ACTION SECTION ===== -->
        <div class="mt-12 pt-8 border-t-2 border-gray-200">
            @if($order->snap_token && $order->payment_status === 'pending')
    <div class="space-y-4">
        <button id="pay-button" type="button" class="w-full px-8 py-5 bg-gradient-to-r from-cyan-500 via-cyan-400 to-blue-600 hover:from-cyan-600 hover:via-cyan-500 hover:to-blue-700 text-white font-black text-lg rounded-2xl shadow-2xl transition-all duration-300 flex items-center justify-center gap-3 uppercase tracking-wide">
            <i class="fas fa-lock-open text-2xl"></i>
            <span>Lanjutkan Pembayaran</span>
            <i class="fas fa-arrow-right text-xl"></i>
        </button>

        <button type="button"
                onclick="openChangePaymentModal()"
                class="w-full px-6 py-4 bg-white border-2 border-cyan-300 hover:border-cyan-500 text-cyan-700 font-bold rounded-2xl transition-all">
            Ubah Metode Pembayaran
        </button>
    </div>
@elseif(in_array($order->payment_status, ['expired', 'failed', 'cancelled']))
    <button type="button"
            onclick="regeneratePayment()"
            class="w-full px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-2xl transition-all">
        Buat Ulang Pembayaran
    </button>
@endif
        </div>

    </div>
</div>

<!-- ===== MODAL UBAH METODE PEMBAYARAN ===== -->
<div id="changePaymentModal"
     class="hidden fixed inset-0 z-50">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeChangePaymentModal()"></div>

    <!-- Panel -->
    <div class="relative min-h-full flex items-end md:items-center justify-center p-3 sm:p-5">
        <div class="w-full max-w-2xl bg-white rounded-[28px] shadow-2xl overflow-hidden animate-slide-up md:animate-scale-up">
            
            <!-- Header -->
            <div class="relative px-6 sm:px-8 pt-6 sm:pt-8 pb-5 border-b border-slate-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-wallet"></i>
                            Payment Update
                        </span>
                        <h2 class="mt-3 text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            Ubah Metode Pembayaran
                        </h2>
                        <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-xl">
                            Pilih kategori pembayaran yang Anda inginkan. Setelah itu, Anda akan diarahkan ke halaman pembayaran Midtrans untuk memilih metode final.
                        </p>
                    </div>

                    <button onclick="closeChangePaymentModal()"
                            class="shrink-0 w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition"
                            type="button"
                            aria-label="Tutup modal">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 sm:px-8 py-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    
                    <button type="button"
                            class="payment-option group text-left w-full rounded-3xl border-2 border-slate-200 bg-white p-5 transition hover:border-cyan-400 hover:shadow-lg"
                            data-method="bank_transfer">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-base font-extrabold text-slate-900">Transfer Bank</h3>
                                    <span class="payment-check hidden w-7 h-7 rounded-full bg-cyan-600 text-white items-center justify-center text-xs">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">
                                    BCA, BNI, BRI, Mandiri Virtual Account
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Populer</span>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Mudah dicek</span>
                                </div>
                            </div>
                        </div>
                    </button>

                    <button type="button"
                            class="payment-option group text-left w-full rounded-3xl border-2 border-slate-200 bg-white p-5 transition hover:border-cyan-400 hover:shadow-lg"
                            data-method="ewallet">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-base font-extrabold text-slate-900">E-Wallet</h3>
                                    <span class="payment-check hidden w-7 h-7 rounded-full bg-cyan-600 text-white items-center justify-center text-xs">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">
                                    GoPay, ShopeePay, dan dompet digital lain
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Instan</span>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Praktis</span>
                                </div>
                            </div>
                        </div>
                    </button>

                    <button type="button"
                            class="payment-option group text-left w-full rounded-3xl border-2 border-slate-200 bg-white p-5 transition hover:border-cyan-400 hover:shadow-lg"
                            data-method="card">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-base font-extrabold text-slate-900">Kartu Kredit / Debit</h3>
                                    <span class="payment-check hidden w-7 h-7 rounded-full bg-cyan-600 text-white items-center justify-center text-xs">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">
                                    Visa, Mastercard, dan kartu debit tertentu
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Fleksibel</span>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Aman</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Info panel -->
                <div class="rounded-3xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-amber-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="font-bold">Catatan</p>
                            <p class="mt-1 leading-6">
                                Pilihan di sini membantu mengarahkan preferensi Anda. Pemilihan metode final tetap dilakukan pada popup Midtrans setelah token pembayaran diperbarui.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 sm:px-8 pb-6 sm:pb-8">
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button id="cancelChangeBtn"
                            type="button"
                            onclick="closeChangePaymentModal()"
                            class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold transition">
                        Batal
                    </button>

                    <button id="confirmChangeBtn"
                            type="button"
                            onclick="confirmChangePayment()"
                            class="w-full sm:flex-1 px-6 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-black text-sm sm:text-base shadow-xl transition flex items-center justify-center gap-3">
                        <i class="fas fa-repeat"></i>
                        <span id="confirmChangeBtnText">Lanjutkan dan Pilih Ulang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MIDTRANS SCRIPT ===== -->
@if($order->snap_token)
    <script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        let currentSnapToken = @json($order->snap_token);
        let isProcessing = false;
        let selectedPaymentMethod = 'bank_transfer';

        const payButton = document.getElementById('pay-button');
        const modalEl = document.getElementById('changePaymentModal');
        const confirmChangeBtn = document.getElementById('confirmChangeBtn');
        const confirmChangeBtnText = document.getElementById('confirmChangeBtnText');

        document.querySelectorAll('.payment-option').forEach((button) => {
            button.addEventListener('click', function () {
                selectedPaymentMethod = this.dataset.method;
                setActivePaymentOption(this);
            });
        });

        function setActivePaymentOption(activeButton) {
            document.querySelectorAll('.payment-option').forEach((button) => {
                button.classList.remove('border-cyan-500', 'bg-cyan-50/50', 'shadow-lg', 'ring-4', 'ring-cyan-100');

                const check = button.querySelector('.payment-check');
                if (check) {
                    check.classList.add('hidden');
                    check.classList.remove('inline-flex');
                }
            });

            activeButton.classList.add('border-cyan-500', 'bg-cyan-50/50', 'shadow-lg', 'ring-4', 'ring-cyan-100');

            const activeCheck = activeButton.querySelector('.payment-check');
            if (activeCheck) {
                activeCheck.classList.remove('hidden');
                activeCheck.classList.add('inline-flex');
            }
        }

        // set default active
        const defaultOption = document.querySelector('.payment-option[data-method="bank_transfer"]');
        if (defaultOption) {
            setActivePaymentOption(defaultOption);
        }

        payButton?.addEventListener('click', function () {
            if (isProcessing || !currentSnapToken) return;
            isProcessing = true;
            updateButtonState();
            payWithSnap(currentSnapToken);
        });

function payWithSnap(snapToken) {
    snap.pay(snapToken, {
        onSuccess: function(result) {
            isProcessing = false;
            updateButtonState();

            setTimeout(() => {
                window.location.href = @json(route('order.show', $order->invoice_number));
            }, 2500);
        },
        onPending: function(result) {
            isProcessing = false;
            updateButtonState();

            setTimeout(() => {
                window.location.href = @json(route('order.show', $order->invoice_number));
            }, 1200);
        },
        onError: function(result) {
            isProcessing = false;
            updateButtonState();
            alert('Pembayaran gagal atau dibatalkan.');
        },
        onClose: function() {
            isProcessing = false;
            updateButtonState();
        }
    });
}

        function openChangePaymentModal() {
            if (isProcessing) return;
            modalEl?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeChangePaymentModal() {
            if (isProcessing) return;
            modalEl?.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function updateButtonState() {
            if (confirmChangeBtn) {
                confirmChangeBtn.disabled = isProcessing;
                confirmChangeBtn.classList.toggle('opacity-70', isProcessing);
                confirmChangeBtn.classList.toggle('cursor-not-allowed', isProcessing);
            }

            if (confirmChangeBtnText) {
                confirmChangeBtnText.textContent = isProcessing
                    ? 'Memproses Pembayaran...'
                    : 'Lanjutkan dan Pilih Ulang';
            }

            if (payButton) {
                payButton.disabled = isProcessing;
                payButton.classList.toggle('opacity-70', isProcessing);
                payButton.classList.toggle('cursor-not-allowed', isProcessing);
            }
        }

        async function confirmChangePayment() {
            if (isProcessing) return;

            isProcessing = true;
            updateButtonState();

            try {
                const response = await fetch(@json(route('order.refresh-payment', $order->invoice_number)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        preferred_method: selectedPaymentMethod
                    })
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal memperbarui pembayaran');
                }

                currentSnapToken = data.snap_token;
                closeChangePaymentModal();
                payWithSnap(currentSnapToken);
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan');
            } finally {
                isProcessing = false;
                updateButtonState();
            }
        }

        async function regeneratePayment() {
            if (isProcessing) return;

            isProcessing = true;
            updateButtonState();

            try {
                const response = await fetch(@json(route('order.regenerate-payment', $order->invoice_number)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal membuat ulang pembayaran');
                }

                currentSnapToken = data.snap_token;
                payWithSnap(currentSnapToken);
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan');
            } finally {
                isProcessing = false;
                updateButtonState();
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeChangePaymentModal();
            }
        });
    </script>
@endif
<script>
    function printOrder() {
        window.print();
    }
</script>

@endsection

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scale-up {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(48px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in { animation: fade-in 0.25s ease-out; }
    .animate-scale-up { animation: scale-up 0.25s ease-out; }
    .animate-slide-up { animation: slide-up 0.25s ease-out; }

    .payment-option {
        outline: none;
    }

    .payment-option:focus-visible {
        box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.18);
    }

    @media print {
        header, footer, button, [onclick], #changePaymentModal, [class*="action"] {
            display: none !important;
        }

        body {
            background: white !important;
        }
    }
</style>
@endpush