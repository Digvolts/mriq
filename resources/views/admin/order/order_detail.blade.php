@extends('layouts.admin')

@section('title', 'Detail Order — ' . $order->invoice_number)
@section('page_title', 'Detail Order')
@section('page_sub', $order->invoice_number)

@section('content')
@php
    $payConf = [
        'pending'  => ['cls'=>'bg-amber-50 text-amber-700 ring-amber-300',     'dot'=>'bg-amber-400'],
        'paid'     => ['cls'=>'bg-emerald-50 text-emerald-700 ring-emerald-300','dot'=>'bg-emerald-400'],
        'failed'   => ['cls'=>'bg-red-50 text-red-700 ring-red-300',            'dot'=>'bg-red-400'],
        'expired'  => ['cls'=>'bg-slate-100 text-slate-600 ring-slate-300',     'dot'=>'bg-slate-400'],
        'refunded' => ['cls'=>'bg-purple-50 text-purple-700 ring-purple-300',   'dot'=>'bg-purple-400'],
    ];

    $statConf = [
        'pending'    => ['cls'=>'bg-amber-50 text-amber-700 ring-amber-300',      'dot'=>'bg-amber-400'],
        'processing' => ['cls'=>'bg-blue-50 text-blue-700 ring-blue-300',         'dot'=>'bg-blue-500'],
        'shipped'    => ['cls'=>'bg-violet-50 text-violet-700 ring-violet-300',   'dot'=>'bg-violet-500'],
        'delivered'  => ['cls'=>'bg-emerald-50 text-emerald-700 ring-emerald-300','dot'=>'bg-emerald-500'],
        'cancelled'  => ['cls'=>'bg-red-50 text-red-700 ring-red-300',            'dot'=>'bg-red-500'],
    ];

    $statusList = [
        'pending'    => ['label'=>'Pending',    'icon'=>'fa-clock'],
        'processing' => ['label'=>'Processing', 'icon'=>'fa-gear'],
        'shipped'    => ['label'=>'Shipped',    'icon'=>'fa-truck'],
        'delivered'  => ['label'=>'Delivered',  'icon'=>'fa-box-open'],
        'cancelled'  => ['label'=>'Cancelled',  'icon'=>'fa-ban'],
    ];

    $payList = [
        'pending'  => ['label'=>'Pending',  'icon'=>'fa-clock'],
        'paid'     => ['label'=>'Paid',     'icon'=>'fa-check-circle'],
        'failed'   => ['label'=>'Failed',   'icon'=>'fa-times-circle'],
        'expired'  => ['label'=>'Expired',  'icon'=>'fa-hourglass-end'],
        'refunded' => ['label'=>'Refunded', 'icon'=>'fa-undo'],
    ];

    $pc = $payConf[$order->payment_status] ?? ['cls'=>'bg-slate-100 text-slate-500 ring-slate-300','dot'=>'bg-slate-400'];
    $sc = $statConf[$order->status] ?? ['cls'=>'bg-slate-100 text-slate-500 ring-slate-300','dot'=>'bg-slate-400'];
@endphp

<form id="form-edit-order"
      action="{{ route('admin.orders.updateStatus', $order->id) }}"
      method="POST">
    @csrf
    @method('PATCH')
</form>

<div x-data="{
        mode: 'view',
        loading: false,
        confirmCancel: false,
        selectedStatus: '{{ $order->status }}',
        setMode(m) {
            this.mode = m;
            this.confirmCancel = false;
            if (m === 'view') {
                this.selectedStatus = '{{ $order->status }}';
                this.loading = false;
            }
        }
     }"
     class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- TOP ACTION BAR --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3">
            <button x-show="mode === 'edit'" x-cloak
                    @click="confirmCancel = true"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                           border-2 border-slate-200 bg-white text-slate-600
                           hover:border-slate-300 hover:bg-slate-50
                           text-sm font-semibold transition">
                <i class="fas fa-xmark text-xs"></i>
                <span>Batal</span>
            </button>

            <button x-show="mode === 'edit'" x-cloak
                    type="submit" form="form-edit-order"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                           bg-gradient-to-r from-emerald-500 to-teal-600
                           hover:from-emerald-600 hover:to-teal-700
                           disabled:opacity-60 disabled:cursor-not-allowed
                           text-white text-sm font-semibold shadow transition">
                <span x-show="!loading" class="flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan
                </span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span>Menyimpan...</span>
                </span>
            </button>

        </div>
    </div>

    {{-- CONFIRM CANCEL MODAL --}}
    <div x-show="confirmCancel" x-cloak
         @click.self="confirmCancel = false"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4
                bg-black/50 backdrop-blur-sm">
        <div x-transition
             class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
            <div class="flex gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Batalkan Perubahan?</h3>
                    <p class="text-sm text-slate-500 mt-1">Semua perubahan yang belum disimpan akan hilang.</p>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="confirmCancel = false"
                        type="button"
                        class="flex-1 py-2.5 px-4 rounded-lg border-2 border-slate-200
                               text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Tetap Edit
                </button>
                <button @click="setMode('view')"
                        type="button"
                        class="flex-1 py-2.5 px-4 rounded-lg bg-red-500 hover:bg-red-600
                               text-white text-sm font-semibold shadow transition">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>

    {{-- HERO HEADER --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600">
        <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 rounded-full bg-white/5"></div>

        <div class="relative px-6 py-8 md:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <div x-show="mode === 'edit'" x-cloak
                         class="inline-flex items-center gap-2 bg-amber-400/20 border border-amber-400/40
                                text-amber-100 text-xs font-bold uppercase tracking-wider
                                px-3 py-1.5 rounded-full mb-3">
                        <i class="fas fa-edit text-xs"></i>
                        Mode Edit
                    </div>
                    <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider mb-2">
                        Invoice Number
                    </p>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white font-mono tracking-tight mb-2">
                        {{ $order->invoice_number }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        <i class="far fa-calendar mr-2"></i>
                        Dibuat: {{ $order->created_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 md:gap-4 flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-center">
                        <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1.5">
                            Payment
                        </p>
                        <div class="flex items-center justify-center gap-2 text-white">
                            <span class="w-2 h-2 rounded-full {{ $pc['dot'] }}"></span>
                            <span class="text-sm font-bold">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-center">
                        <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1.5">
                            Status
                        </p>
                        <div class="flex items-center justify-center gap-2 text-white">
                            <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                            <span class="text-sm font-bold">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-center">
                        <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1.5">
                            Total
                        </p>
                        <span class="text-white text-sm font-extrabold">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN GRID: Status Left, Rest Right --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- LEFT COLUMN: STATUS (1/4) --}}
        <div class="lg:col-span-1">

            {{-- ORDER STATUS --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-6"
                 :class="mode === 'edit' ? 'border-2 border-indigo-300' : ''">

                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3"
                     :class="mode === 'edit' ? 'bg-indigo-50/50' : ''">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                        <i class="fas fa-sliders text-orange-500 text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Status Order</h3>
                    <span x-show="mode === 'edit'" x-cloak class="ml-auto bg-indigo-100 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-full">
                        Edit
                    </span>
                </div>

                <div class="p-6">
                    {{-- VIEW --}}
                    <div x-show="mode === 'view'" class="space-y-2">
                        @foreach($statusList as $val => $opt)
                        <div class="flex items-center gap-3 p-3 rounded-lg border-2 transition
                                    {{ $order->status === $val ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 opacity-50' }}">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                        {{ $order->status === $val ? 'border-indigo-500 bg-indigo-500' : 'border-slate-300' }}">
                                @if($order->status === $val)
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                @endif
                            </div>
                            <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0
                                        {{ $order->status === $val ? 'bg-indigo-100' : 'bg-slate-100' }}">
                                <i class="fas {{ $opt['icon'] }} text-xs {{ $order->status === $val ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                            </div>
                            <span class="text-sm font-semibold {{ $order->status === $val ? 'text-indigo-700' : 'text-slate-500' }}">
                                {{ $opt['label'] }}
                            </span>
                            @if($order->status === $val)
                            <span class="ml-auto text-xs font-bold text-indigo-500 uppercase">Aktif</span>
                            @endif
                        </div>
                        @endforeach
                        <button @click="setMode('edit')" type="button"
                                class="w-full mt-4 border-2 border-indigo-200 hover:border-indigo-400 hover:bg-indigo-50
                                       text-indigo-600 py-2.5 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            Ubah Status
                        </button>
                    </div>

                    {{-- EDIT --}}
                    <div x-show="mode === 'edit'" x-cloak class="space-y-2">
                        @foreach($statusList as $val => $opt)
                        <label class="flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all"
                               :class="selectedStatus === '{{ $val }}' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-200'">
                            <input type="radio" name="status" value="{{ $val }}"
                                   form="form-edit-order"
                                   x-model="selectedStatus"
                                   {{ $order->status === $val ? 'checked' : '' }}
                                   class="sr-only">

                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                 :class="selectedStatus === '{{ $val }}' ? 'border-indigo-500 bg-indigo-500' : 'border-slate-300'">
                                <div class="w-2 h-2 rounded-full bg-white" x-show="selectedStatus === '{{ $val }}'"></div>
                            </div>

                            <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0"
                                 :class="selectedStatus === '{{ $val }}' ? 'bg-indigo-100' : 'bg-slate-100'">
                                <i class="fas {{ $opt['icon'] }} text-xs" :class="selectedStatus === '{{ $val }}' ? 'text-indigo-600' : 'text-slate-400'"></i>
                            </div>

                            <span class="text-sm font-semibold" :class="selectedStatus === '{{ $val }}' ? 'text-indigo-700' : 'text-slate-600'">
                                {{ $opt['label'] }}
                            </span>

                            <span class="ml-auto text-xs font-bold text-indigo-500 uppercase" x-show="selectedStatus === '{{ $val }}'">
                                Aktif
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Rest (3/4) --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- PRODUCTS SECTION --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600 text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Produk Dipesan</h3>
                    <span class="ml-auto bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $order->items->count() }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                                 class="w-12 h-12 rounded-lg object-cover border border-slate-200"
                                                 alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                                <i class="fas fa-box text-lg"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-slate-700 truncate">{{ $item->product_name }}</p>
                                            @if($item->variant)
                                                <p class="text-xs text-slate-500 mt-0.5">
                                                    <i class="fas fa-tag mr-1"></i>{{ $item->variant }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-lg">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-600 text-sm">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PRICE BREAKDOWN --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <div class="ml-auto max-w-xs space-y-2">
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tax > 0)
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Pajak</span>
                            <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-bold text-slate-800 text-base
                                    border-t border-slate-200 pt-3 mt-2">
                            <span>Total Bayar</span>
                            <span class="text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRID 2x2 UNTUK INFO SECTIONS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- TIMELINE SECTION --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                            <i class="fas fa-timeline text-violet-600 text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Timeline Pesanan</h3>
                    </div>

                    @php
                        $timeline = [
                            ['label'=>'Order Dibuat',        'time'=>$order->created_at,   'icon'=>'fa-shopping-bag',    'color'=>'from-blue-400 to-blue-600'],
                            ['label'=>'Pembayaran Diterima', 'time'=>$order->paid_at,       'icon'=>'fa-money-bill',      'color'=>'from-emerald-400 to-teal-600'],
                            ['label'=>'Sedang Dikirim',      'time'=>$order->shipped_at,   'icon'=>'fa-truck',           'color'=>'from-violet-400 to-purple-600'],
                            ['label'=>'Pesanan Diterima',    'time'=>$order->delivered_at, 'icon'=>'fa-box-open',        'color'=>'from-cyan-400 to-blue-600'],
                        ];
                    @endphp

                    <div class="space-y-3">
                        @foreach($timeline as $t)
                        @php $isActive = !is_null($t['time']); @endphp
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 shadow
                                            {{ $isActive ? 'bg-gradient-to-br '.$t['color'].' text-white' : 'bg-slate-100 text-slate-300' }}">
                                    <i class="fas {{ $t['icon'] }} text-xs"></i>
                                </div>
                                @if(!$loop->last)
                                <div class="w-0.5 h-6 mt-1.5 {{ $isActive ? 'bg-blue-200' : 'bg-slate-200' }}"></div>
                                @endif
                            </div>
                            <div class="pt-1">
                                <p class="text-xs font-semibold {{ $isActive ? 'text-slate-800' : 'text-slate-400' }}">
                                    {{ $t['label'] }}
                                </p>
                                <p class="text-[11px] {{ $isActive ? 'text-slate-500' : 'text-slate-300' }} mt-0.5">
                                    {{ $isActive ? \Carbon\Carbon::parse($t['time'])->format('d M Y, H:i').' WIB' : 'Belum tercatat' }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- PAYMENT INFO --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <i class="fas fa-credit-card text-emerald-600 text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Info Pembayaran</h3>
                    </div>

                    <div class="p-6 space-y-4">

                        {{-- PAYMENT METHOD --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $order->payment_type_label ?? ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                            </span>
                        </div>

                        <div class="border-t border-slate-100"></div>

                        {{-- PAYMENT STATUS --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</span>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold ring-1 {{ $pc['cls'] }}">
                                <span class="w-2 h-2 rounded-full {{ $pc['dot'] }}"></span>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>

                        @if($order->paid_at)
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibayar</span>
                            <span class="text-sm text-slate-700">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif

                        @if($order->transaction_id)
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                Transaction ID
                            </p>
                            <p class="font-mono text-xs text-slate-700 bg-slate-50 px-3 py-2 rounded-lg
                                      border border-slate-200 break-all">
                                {{ $order->transaction_id }}
                            </p>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- CUSTOMER INFO --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center">
                            <i class="fas fa-user text-cyan-600 text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Informasi Customer</h3>
                    </div>

                    <div class="p-6 space-y-4">

                        {{-- NAME + EMAIL --}}
                        <div class="flex items-start gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600
                                        flex items-center justify-center flex-shrink-0 text-white font-bold shadow">
                                {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 truncate">{{ $order->customer_name }}</p>
                                <p class="text-sm text-slate-500 truncate mt-1">{{ $order->email }}</p>
                            </div>
                        </div>

                        {{-- PHONE --}}
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">No. HP</p>
                            <p class="text-sm text-slate-700">{{ $order->phone }}</p>
                        </div>

                        {{-- ADDRESS --}}
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alamat</p>
                            <p class="text-sm text-slate-700 space-y-1">
                                <span class="block">{{ $order->address }}</span>
                                <span class="block text-slate-500">
                                    {{ implode(', ', array_filter([$order->district_name, $order->regency_name, $order->province_name])) }}
                                </span>
                            </p>
                        </div>

                    </div>
                </div>

                {{-- ADMIN NOTES --}}
                @if($order->admin_note)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                            <i class="fas fa-sticky-note text-slate-600 text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Catatan Admin</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $order->admin_note }}
                        </p>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>

</div>
@endsection