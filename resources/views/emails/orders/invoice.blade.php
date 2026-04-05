{{-- resources/views/emails/orders/invoice.blade.php --}}

<x-mail::message>

{{-- ===== HEADER ===== --}}
# 🛍️ Terima Kasih Sudah Berbelanja!

Halo, **{{ $order->customer_name }}**!

Pesanan Anda telah berhasil kami terima. Berikut adalah ringkasan pesanan Anda.

---

{{-- ===== INVOICE NUMBER ===== --}}
<x-mail::panel>
📋 **NOMOR INVOICE**
# {{ $order->invoice_number }}

📅 Tanggal: **{{ $order->created_at->format('d M Y, H:i') }} WIB**
</x-mail::panel>

---

{{-- ===== ORDER ITEMS ===== --}}
## 🛒 Detail Produk

<x-mail::table>
| Produk | Ukuran | Qty | Harga | Subtotal |
|:-------|:------:|:---:|------:|---------:|
@foreach($order->orderItems as $item)
| {{ $item->product_name }} | {{ strtoupper($item->size) }} | {{ $item->quantity }} | Rp{{ number_format($item->price, 0, ',', '.') }} | Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }} |
@endforeach
</x-mail::table>

---

{{-- ===== SUMMARY ===== --}}
<x-mail::panel>
💰 **RINGKASAN PEMBAYARAN**

| | |
|:--|--:|
| Subtotal | Rp{{ number_format($order->total_amount, 0, ',', '.') }} |
| Pengiriman | **GRATIS** |
| **Total** | **Rp{{ number_format($order->total_amount, 0, ',', '.') }}** |
</x-mail::panel>

---

{{-- ===== SHIPPING ADDRESS ===== --}}
## 📍 Alamat Pengiriman

**{{ $order->customer_name }}**
{{ $order->phone }}
{{ $order->address }}
{{ $order->district_name }}, {{ $order->regency_name }}
{{ $order->province_name }}

---

{{-- ===== STATUS ===== --}}
## 📦 Status Pesanan

@if($order->payment_status === 'paid')
✅ **Pembayaran telah dikonfirmasi** — Pesanan sedang diproses
@elseif($order->payment_status === 'pending')
⏳ **Menunggu Pembayaran** — Segera selesaikan pembayaran Anda
@else
❌ **Pembayaran Gagal** — Silakan coba lagi
@endif

---

{{-- ===== CTA BUTTON ===== --}}
<x-mail::button :url="route('order.show', $order->id)" color="success">
    🔍 Cek Status Pesanan
</x-mail::button>

---

Jika ada pertanyaan, silakan hubungi kami di **support@2day.com**

Salam hangat,
**Tim 2DAY Store** 🛍️

<x-mail::subcopy>
Email ini dikirim otomatis ke {{ $order->email }}. Harap jangan membalas email ini.
</x-mail::subcopy>

</x-mail::message>