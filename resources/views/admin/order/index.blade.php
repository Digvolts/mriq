@extends('layouts.admin')

@section('title', 'Manajemen Orders')
@section('page_title', 'Manajemen Orders')
@section('page_sub', 'Kelola & pantau semua transaksi pelanggan')

@section('content')
<div class="space-y-6">

    {{-- ══════════════════════════════════════════
         SUMMARY CARDS — gradient per status
    ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-7 gap-3">
        @php
            $cards = [
                ['label'=>'Total Order', 'key'=>'all',        'icon'=>'fa-layer-group',
                 'grad'=>'from-slate-600 to-slate-800'],
                ['label'=>'Pending',     'key'=>'pending',    'icon'=>'fa-clock',
                 'grad'=>'from-amber-400 to-orange-500'],
                ['label'=>'Paid',        'key'=>'paid',       'icon'=>'fa-circle-check',
                 'grad'=>'from-emerald-400 to-teal-600'],
                ['label'=>'Processing',  'key'=>'processing', 'icon'=>'fa-gear',
                 'grad'=>'from-blue-400 to-blue-600'],
                ['label'=>'Shipped',     'key'=>'shipped',    'icon'=>'fa-truck',
                 'grad'=>'from-violet-400 to-purple-600'],
                ['label'=>'Delivered',   'key'=>'delivered',  'icon'=>'fa-box-open',
                 'grad'=>'from-cyan-400 to-cyan-600'],
                ['label'=>'Cancelled',   'key'=>'cancelled',  'icon'=>'fa-ban',
                 'grad'=>'from-rose-400 to-red-600'],
            ];
        @endphp

        @foreach($cards as $c)
        <div class="bg-gradient-to-br {{ $c['grad'] }} rounded-2xl p-4 text-white shadow-md
                    hover:scale-105 transition-transform cursor-default">
            <div class="flex items-center justify-between mb-2">
                <i class="fa-solid {{ $c['icon'] }} opacity-80 text-sm"></i>
                <span class="text-[10px] font-semibold uppercase tracking-widest opacity-70">
                    {{ $c['label'] }}
                </span>
            </div>
            <div class="text-3xl font-extrabold">{{ $summary[$c['key']] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════
         FILTER PANEL
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-filter text-blue-500 text-xs"></i>
            <span class="text-sm font-semibold text-slate-700">Filter & Pencarian</span>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.orders.index') }}"
                  class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">

                {{-- Search --}}
                <div class="xl:col-span-2">
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2
                                  text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Invoice, nama, email..."
                               class="w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm
                                      bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2
                                      focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                {{-- Payment Status --}}
                <div>
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Payment
                    </label>
                    <select name="payment_status"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm
                                   bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2
                                   focus:ring-blue-100 outline-none transition">
                        <option value="">Semua</option>
                        @foreach(['pending'=>'Pending','paid'=>'Paid','failed'=>'Failed',
                                  'expired'=>'Expired','refunded'=>'Refunded'] as $v => $l)
                        <option value="{{ $v }}" {{ request('payment_status')===$v ? 'selected':'' }}>
                            {{ $l }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Order Status --}}
                <div>
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Status Order
                    </label>
                    <select name="status"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm
                                   bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2
                                   focus:ring-blue-100 outline-none transition">
                        <option value="">Semua</option>
                        @foreach(['pending'=>'Pending','processing'=>'Processing','shipped'=>'Shipped',
                                  'delivered'=>'Delivered','cancelled'=>'Cancelled'] as $v => $l)
                        <option value="{{ $v }}" {{ request('status')===$v ? 'selected':'' }}>
                            {{ $l }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Dari Tanggal
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm
                                  bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2
                                  focus:ring-blue-100 outline-none transition">
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wide">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm
                                  bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2
                                  focus:ring-blue-100 outline-none transition">
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end gap-2 xl:col-span-6">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600
                                   to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white
                                   px-5 py-2.5 rounded-xl text-sm font-semibold shadow transition">
                        <i class="fa-solid fa-filter text-xs"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.orders.index') }}"
                       class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200
                              text-slate-600 px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-rotate-right text-xs"></i> Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         DATA TABLE
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Table Header Bar --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-5 rounded-full bg-gradient-to-b from-blue-500 to-blue-700"></div>
                <h3 class="font-bold text-slate-700">Daftar Pesanan</h3>
                <span class="ml-2 bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $orders->total() }} total
                </span>
            </div>
            <span class="text-xs text-slate-400">
                Halaman {{ $orders->currentPage() }} / {{ $orders->lastPage() }}
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Items</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Payment</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $i => $order)
                    @php
                        $payConf = [
                            'pending'  => ['cls'=>'bg-amber-50 text-amber-700 ring-amber-200',   'dot'=>'bg-amber-400'],
                            'paid'     => ['cls'=>'bg-emerald-50 text-emerald-700 ring-emerald-200','dot'=>'bg-emerald-400'],
                            'failed'   => ['cls'=>'bg-red-50 text-red-700 ring-red-200',           'dot'=>'bg-red-400'],
                            'expired'  => ['cls'=>'bg-slate-100 text-slate-500 ring-slate-200',    'dot'=>'bg-slate-400'],
                            'refunded' => ['cls'=>'bg-purple-50 text-purple-700 ring-purple-200',  'dot'=>'bg-purple-400'],
                        ];
                        $statConf = [
                            'pending'    => ['cls'=>'bg-amber-50 text-amber-700 ring-amber-200',    'dot'=>'bg-amber-400'],
                            'processing' => ['cls'=>'bg-blue-50 text-blue-700 ring-blue-200',       'dot'=>'bg-blue-500'],
                            'shipped'    => ['cls'=>'bg-violet-50 text-violet-700 ring-violet-200', 'dot'=>'bg-violet-500'],
                            'delivered'  => ['cls'=>'bg-emerald-50 text-emerald-700 ring-emerald-200','dot'=>'bg-emerald-500'],
                            'cancelled'  => ['cls'=>'bg-red-50 text-red-700 ring-red-200',          'dot'=>'bg-red-500'],
                        ];
                        $pc = $payConf[$order->payment_status] ?? ['cls'=>'bg-slate-100 text-slate-500 ring-slate-200','dot'=>'bg-slate-400'];
                        $sc = $statConf[$order->status]         ?? ['cls'=>'bg-slate-100 text-slate-500 ring-slate-200','dot'=>'bg-slate-400'];
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition group">
                        {{-- Nomor --}}
                        <td class="px-5 py-4 text-xs text-slate-400 font-medium">
                            {{ $orders->firstItem() + $i }}
                        </td>

                        {{-- Invoice --}}
                        <td class="px-5 py-4">
                            <span class="font-mono text-xs font-semibold text-blue-700 bg-blue-50
                                         px-2 py-1 rounded-lg">
                                {{ $order->invoice_number }}
                            </span>
                        </td>

                        {{-- Customer --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600
                                            flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($order->customer_name,0,1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-700 text-sm">
                                        {{ $order->customer_name }}
                                    </div>
                                    <div class="text-xs text-slate-400">{{ $order->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Items --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold
                                         px-2.5 py-1 rounded-full">
                                {{ $order->items->count() }} item
                            </span>
                        </td>

                        {{-- Total --}}
                        <td class="px-5 py-4 text-right">
                            <span class="font-bold text-slate-800">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Payment Badge --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs
                                         font-semibold ring-1 {{ $pc['cls'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $pc['dot'] }}"></span>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs
                                         font-semibold ring-1 {{ $sc['cls'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-5 py-4">
                            <div class="text-xs font-medium text-slate-700">
                                {{ $order->created_at->format('d M Y') }}
                            </div>
                            <div class="text-[11px] text-slate-400">
                                {{ $order->created_at->format('H:i') }} WIB
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600
                                      to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white
                                      text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm
                                      transition group-hover:shadow-blue-200 group-hover:shadow-md">
                                <i class="fa-solid fa-eye text-[10px]"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                                </div>
                                <p class="font-semibold text-slate-500">Tidak ada order ditemukan</p>
                                <p class="text-xs">Coba ubah filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center
                    justify-between gap-4">
            <p class="text-xs text-slate-500">
                Menampilkan <b>{{ $orders->firstItem() }}</b>–<b>{{ $orders->lastItem() }}</b>
                dari <b>{{ $orders->total() }}</b> order
            </p>
            <div class="[&_.pagination]:flex [&_.pagination]:gap-1
                        [&_.page-link]:px-3 [&_.page-link]:py-1.5 [&_.page-link]:rounded-lg
                        [&_.page-link]:text-xs [&_.page-link]:font-medium [&_.page-link]:border-0
                        [&_.page-link]:text-slate-600 [&_.page-link]:bg-white [&_.page-link]:shadow-sm
                        [&_.page-item.active_.page-link]:bg-blue-600 [&_.page-item.active_.page-link]:text-white">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection