<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderPayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
     public function trackPage()
    {
        return view('order.track');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:50',
            'email' => 'required|email|max:100',
        ]);

        $order = Order::where('invoice_number', $validated['invoice_number'])
            ->where('email', $validated['email'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        return redirect()->route('order.show', $order->invoice_number);
    }

    public function index(Request $request)
    {
        $query = Order::with(['items'])->latest();

        // Filter by payment_status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by invoice or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Summary counts for badges
        $summary = [
            'all'       => Order::count(),
            'pending'   => Order::where('payment_status', 'pending')->count(),
            'paid'      => Order::where('payment_status', 'paid')->count(),
            'processing'=> Order::where('status', 'processing')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.order.index', compact('orders', 'summary'));
    }

    public function adminshow($id)
    {
        $order = Order::with(['items.product', 'payments'])->findOrFail($id);
        return view('admin.order.order_detail', compact('order'));
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
    ]);

    $order = Order::findOrFail($id);
    $oldStatus = $order->status;
    $newStatus = $request->status;

    $updateData = ['status' => $newStatus];

    if ($newStatus === 'shipped' && !$order->shipped_at) {
        $updateData['shipped_at'] = now();
    }

    if ($newStatus === 'delivered' && !$order->delivered_at) {
        $updateData['delivered_at'] = now();

        if ($order->payment_status === 'paid') {
            $order->increaseTerjualIfNeeded();
        }
    }

    if ($newStatus === 'cancelled') {
        $order->restoreStockIfNeeded();
    }

    $order->update($updateData);

    // ✅ fix: pakai route name yang benar
    return redirect()
    ->route('admin.orders.show', $order->id)
        ->with('success', "Status order berhasil diubah dari <b>{$oldStatus}</b> ke <b>{$newStatus}</b>");
}

public function update(Request $request, Order $order)
{
    $validated = $request->validate([
        'status'          => 'required|in:pending,processing,shipped,delivered,cancelled',
        'payment_status'  => 'required|in:pending,paid,failed,expired,refunded',
        'customer_name'   => 'required|string|max:255',
        'email'           => 'required|email|max:255',
        'phone'           => 'nullable|string|max:20',
        'address'         => 'nullable|string|max:500',
        'district_name'   => 'nullable|string|max:100',
        'regency_name'    => 'nullable|string|max:100',
        'province_name'   => 'nullable|string|max:100',
        'admin_note'      => 'nullable|string|max:1000',
    ]);

    // Set shipped_at / delivered_at otomatis
    if ($validated['status'] === 'shipped' && !$order->shipped_at) {
        $validated['shipped_at'] = now();
    }
    if ($validated['status'] === 'delivered' && !$order->delivered_at) {
        $validated['delivered_at'] = now();
    }
    if ($validated['payment_status'] === 'paid' && !$order->paid_at) {
        $validated['paid_at'] = now();
    }

    $order->update($validated);

    return redirect()
        ->route('admin.orders.show', $order->id)
        ->with('success', 'Order <strong>'.$order->invoice_number.'</strong> berhasil diperbarui.');
}

public function show($invoiceNumber)
{
    $order = Order::with(['items', 'payments'])
        ->where('invoice_number', $invoiceNumber)
        ->firstOrFail();

    try {
        if ($order->transaction_id) {
            $midtrans = new MidtransService();
            $transactionDetails = $midtrans->getTransactionDetails($order->transaction_id);

            if ($transactionDetails) {
                if (in_array($transactionDetails->status, ['settlement', 'capture'])) {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_type' => $transactionDetails->payment_type ?? $order->payment_type,
                        'status' => 'processing',
                        'paid_at' => $order->paid_at ?? now(),
                    ]);
                } elseif ($transactionDetails->status === 'pending') {
                    $order->update([
                        'payment_status' => 'pending',
                        'payment_type' => $transactionDetails->payment_type ?? $order->payment_type,
                    ]);
                } elseif ($transactionDetails->status === 'expire') {
                    $order->update([
                        'payment_status' => 'expired',
                    ]);
                } elseif ($transactionDetails->status === 'cancel') {
                    $order->update([
                        'payment_status' => 'cancelled',
                        'status' => 'cancelled',
                    ]);
                } elseif ($transactionDetails->status === 'deny') {
                    $order->update([
                        'payment_status' => 'failed',
                    ]);
                }

                $latestPayment = $order->payments()
                    ->where('transaction_id', $order->transaction_id)
                    ->latest('id')
                    ->first();

                if ($latestPayment) {
                    $latestPayment->update([
                        'payment_type' => $transactionDetails->payment_type ?? $latestPayment->payment_type,
                        'midtrans_response' => $transactionDetails->raw ?? null,
                    ]);
                }
            }
        }
    } catch (\Exception $e) {
        \Log::warning('Failed to sync Midtrans status', [
            'invoice' => $invoiceNumber,
            'message' => $e->getMessage(),
        ]);
    }

    $order->refresh();

    return view('order.show', compact('order'));
}

    public function refreshPayment(Request $request, $invoiceNumber)
    {
        $validated = $request->validate([
            'preferred_method' => 'nullable|in:bank_transfer,ewallet,qris,card',
        ]);

        try {
            $order = Order::where('invoice_number', $invoiceNumber)
                ->where('payment_status', 'pending')
                ->with(['items', 'payments'])
                ->firstOrFail();

            $midtransService = new MidtransService();

            if ($order->transaction_id) {
                $midtransService->cleanupTransaction($order->transaction_id);

                $oldPayment = $order->payments()
                    ->where('transaction_id', $order->transaction_id)
                    ->latest('id')
                    ->first();

                if ($oldPayment && $oldPayment->status === 'pending') {
                    $oldPayment->update([
                        'status' => 'failed',
                    ]);
                }
            }

            $transaction = $midtransService->createSnapTransaction(
                $order,
                null,
                $validated['preferred_method'] ?? null
            );

            $order->update([
                'snap_token' => $transaction['snap_token'],
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'paid_at' => null,
            ]);

            OrderPayment::create([
                'order_id' => $order->id,
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'status' => 'pending',
                'amount' => $order->total_price,
                'midtrans_response' => [
                    'preferred_method' => $validated['preferred_method'] ?? null,
                    'enabled_payments' => $transaction['enabled_payments'] ?? [],
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pilihan pembayaran berhasil diperbarui.',
                'snap_token' => $transaction['snap_token'],
                'preferred_method' => $validated['preferred_method'] ?? null,
                'enabled_payments' => $transaction['enabled_payments'] ?? [],
            ]);
        } catch (\Exception $e) {
            \Log::error('Refresh payment error', [
                'invoice' => $invoiceNumber,
                'preferred_method' => $request->input('preferred_method'),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui pembayaran.',
            ], 500);
        }
    }

    public function regeneratePayment(Request $request, $invoiceNumber)
    {
        $validated = $request->validate([
            'preferred_method' => 'nullable|in:bank_transfer,ewallet,qris,card',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::where('invoice_number', $invoiceNumber)
                ->whereIn('payment_status', ['expired', 'cancelled', 'failed'])
                ->with(['items', 'payments'])
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($order->items as $item) {
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Produk {$item->product_name} tidak ditemukan.");
                }

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok {$item->product_name} tidak cukup untuk pembayaran ulang.");
                }
            }

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->decrement('stock', $item->quantity);
            }

            $midtransService = new MidtransService();

            $transaction = $midtransService->createSnapTransaction(
                $order,
                null,
                $validated['preferred_method'] ?? null
            );

            $order->update([
                'payment_status' => 'pending',
                'status' => 'pending',
                'payment_type' => null,
                'snap_token' => $transaction['snap_token'],
                'transaction_id' => $transaction['transaction_id'],
                'paid_at' => null,
                'stock_restored_at' => null,
            ]);

            OrderPayment::create([
                'order_id' => $order->id,
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'status' => 'pending',
                'amount' => $order->total_price,
                'midtrans_response' => [
                    'preferred_method' => $validated['preferred_method'] ?? null,
                    'enabled_payments' => $transaction['enabled_payments'] ?? [],
                ],
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil dibuat ulang.',
                'snap_token' => $transaction['snap_token'],
                'redirect' => route('order.show', $order->invoice_number),
                'preferred_method' => $validated['preferred_method'] ?? null,
                'enabled_payments' => $transaction['enabled_payments'] ?? [],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Regenerate payment error', [
                'invoice' => $invoiceNumber,
                'preferred_method' => $request->input('preferred_method'),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

public function handleNotification(Request $request)
{
    $payload = $request->all();

    DB::beginTransaction();

    try {
        $midtrans = new MidtransService();

        if (!$midtrans->validateSignature($payload)) {
            DB::rollBack();
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionId = $payload['order_id'];
        $status = $payload['transaction_status'];
        $paymentType = $payload['payment_type'] ?? null;

        $order = Order::with(['items'])
            ->where('transaction_id', $transactionId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            DB::commit();
            return response()->json(['message' => 'OK']);
        }

        switch ($status) {

            case 'settlement':
            case 'capture':
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_type' => $paymentType,
                    'paid_at' => now(),
                ]);

                event(new \App\Events\OrderPaid($order));
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);

                event(new \App\Events\OrderExpired($order));
                break;

            case 'pending':
                $order->update([
                    'payment_status' => 'pending',
                    'payment_type' => $paymentType,
                ]);
                break;
        }

        DB::commit();

        return response()->json(['message' => 'OK']);

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Webhook error', [
            'message' => $e->getMessage()
        ]);

        return response()->json(['message' => 'Error'], 500);
    }
}
    public function status($invoiceNumber)
{
    $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();

    return response()->json([
        'payment_status' => $order->payment_status,
        'status' => $order->status,
        'payment_type' => $order->payment_type,
    ]);
}
}
