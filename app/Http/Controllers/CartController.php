<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\Environment\Console;

class CartController extends Controller
{
    private function normalizeCart($cart)
    {
        $normalized = [];
        
        foreach ($cart as $key => $item) {
            if (!isset($item['product_id']) && isset($item['id'])) {
                $item['product_id'] = $item['id'];
            }
            
            if (!isset($item['product_id'])) {
                continue;
            }
            
            $normalized[$key] = $item;
        }
        
        return $normalized;
    }

    public function add(Request $request, $productId)
    {
        $validated = $request->validate([
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1|max:999',
        ]);

        $baseProduct = Product::findOrFail($productId);
        
        $product = Product::where('name', $baseProduct->name)
            ->where('size', $validated['size'])
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return back()->with('error', 'Varian produk tidak ditemukan!');
        }

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak cukup untuk ukuran ' . strtoupper($validated['size']) . '!');
        }

        $cart = session()->get('cart', []);
        $cart = $this->normalizeCart($cart);

        $cartKey = $product->id . '_' . $validated['size'];
        $price = $product->discount_price > 0 ? $product->discount_price : $product->price;

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Stok tidak cukup! Max: ' . $product->stock . ' unit untuk ukuran ' . strtoupper($validated['size']));
            }

            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'size' => $validated['size'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'original_price' => $product->price,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang! (' . strtoupper($validated['size']) . ')');
    }

    public function update(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        $cart = $this->normalizeCart($cart);

        if (!isset($cart[$cartKey])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang!');
        }

        $item = $cart[$cartKey];
        $product = Product::findOrFail($item['product_id']);

        $action = $request->input('action');
        $currentQty = $item['quantity'];

        if ($action === 'decrease' && $currentQty > 1) {
            $cart[$cartKey]['quantity'] = $currentQty - 1;
        } elseif ($action === 'increase' && $currentQty < 999) {
            if ($product->stock < $currentQty + 1) {
                return back()->with('error', 'Stok tidak cukup! Max: ' . $product->stock . ' unit');
            }
            $cart[$cartKey]['quantity'] = $currentQty + 1;
        }

        session()->put('cart', $cart);
        return back();
    }

    public function remove($cartKey)
    {
        $cart = session()->get('cart', []);
        $cart = $this->normalizeCart($cart);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            return back()->with('success', 'Produk dihapus dari keranjang!');
        }

        return back()->with('error', 'Item tidak ditemukan!');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function view(Request $request)
    {
        $cart = session()->get('cart', []);
        $cart = $this->normalizeCart($cart);

        $total = 0;
        $originalTotal = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $originalTotal += $item['original_price'] * $item['quantity'];
        }

        $savings = $originalTotal - $total;

        $provinces = $this->getProvinces();
        $regencies = [];
        $districts = [];
        $selectedDistrict = $request->input('district_id', old('district_id'));
        $selectedProvince = $request->input('province_id', old('province_id'));
        $selectedRegency = $request->input('regency_id', old('regency_id'));

        if ($selectedProvince) {
            $regencies = $this->getRegencies($selectedProvince);
        }

        if ($selectedRegency) {
            $districts = $this->getDistricts($selectedRegency);
        }

        return view('cart.index', [
            'cart' => $cart,
            'total' => $total,
            'originalTotal' => $originalTotal,
            'savings' => $savings,
            'provinces' => $provinces,
            'regencies' => $regencies,
            'districts' => $districts,
            'selectedProvince' => $selectedProvince,
            'selectedRegency' => $selectedRegency,
            'selectedDistrict' => $selectedDistrict,

        ]);
    }

public function getProvinces()
{
    return Cache::remember('wilayah_provinces', 86400, function () {
        try {
            $url = 'https://wilayah.id/api/provinces.json';
            $response = Http::retry(3, 200)->timeout(10)->get($url);

            if ($response->failed()) {
                return [];
            }

            $responseData = $response->json();
            return $responseData['data']['data'] ?? $responseData['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    });
}

public function getRegencies($provinceCode)
{
    return Cache::remember("wilayah_regencies_{$provinceCode}", 86400, function () use ($provinceCode) {
        try {
            $url = "https://wilayah.id/api/regencies/{$provinceCode}.json";
            $response = Http::retry(3, 200)->timeout(10)->get($url);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();
            return $data['data']['data'] ?? $data['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    });
}

public function getDistricts($regencyCode)
{
    return Cache::remember("wilayah_districts_{$regencyCode}", 86400, function () use ($regencyCode) {
        try {
            $url = "https://wilayah.id/api/districts/{$regencyCode}.json";
            $response = Http::retry(3, 200)->timeout(10)->get($url);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();
            return $data['data']['data'] ?? $data['data'] ?? $data ?? [];
        } catch (\Exception $e) {
            return [];
        }
    });
}

public function ajaxRegencies($provinceCode)
{
    return response()->json($this->getRegencies($provinceCode));
}

public function ajaxDistricts($regencyCode)
{
    return response()->json($this->getDistricts($regencyCode));
}

public function checkout(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string',
        'province_id' => 'required|string',
        'regency_id' => 'required|string',
        'district_id' => 'required|string',
        'address' => 'required|string|max:1000',
    ]);
$validated['province_name'] = $province['name'] ?? '';
    $validated['regency_name'] = $regency['name'] ?? '';
    $validated['district_name'] = $district['name'] ?? '';
    $cart = $this->normalizeCart(session()->get('cart', []));

    if (empty($cart)) {
        return back()->with('error', 'Keranjang kosong.');
    }

    $orderService = new \App\Services\OrderService();
    $paymentService = new \App\Services\PaymentService();

    $order = $orderService->createFromCart($cart, $validated);
    $paymentService->create($order);
    Mail::to($validated['email'])->send(new OrderInvoiceMail($order));

    session()->forget('cart');

    return redirect()->route('order.show', $order->invoice_number);
}

    public function show($invoiceNumber)
    {
        $order = Order::where('invoice_number', $invoiceNumber)
            ->with(['items', 'payments'])
            ->firstOrFail();   
        return view('cart.checkout', [
            'order' => $order,
        ]);
    }
}