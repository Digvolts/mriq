<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
class ProductController extends Controller
{
     public function showWelcomePage()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }

    public function show(Product $product)
    {
        $detail = Product::with('collection')->findOrFail($product->id);
        return view('admin.products.show', compact('product'));
    }

    public function index()
    {
        $products = Product::with('collection')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $collections = Collection::where('is_active', true)->get();
        return view('admin.products.create', compact('collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
          'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exclusive_mercendise' => 'nullable|string',
            'bahan' => 'nullable|string',
            'style' => 'nullable|string',
            'printing_design' => 'nullable|string',
            'terjual' => 'nullable|integer|min:0',
            'keterangan_bestseller' => 'nullable|string|max:255',
            'pengiriman' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'size' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'collection_id' => 'required|exists:collections,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }
    public function duplicate($id)
{
    try {
        $product = Product::findOrFail($id);
        
        // Duplicate product
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->save();
        
        // Duplicate variants jika ada
        if ($product->variants()->exists()) {
            foreach ($product->variants as $variant) {
                $newVariant = $variant->replicate();
                $newVariant->product_id = $newProduct->id;
                $newVariant->save();
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diduplikat!");
            
    } catch (\Exception $e) {
        return redirect()->route('admin.products.index')
            ->with('error', 'Gagal menduplikat produk: ' . $e->getMessage());
    }
}
    public function edit(Product $product)
    {
        $collections = Collection::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'collections'));
    }

  public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exclusive_mercendise' => 'nullable|string',
            'bahan' => 'nullable|string',
            'style' => 'nullable|string',
            'printing_design' => 'nullable|string',
            'terjual' => 'nullable|integer|min:0',
            'keterangan_bestseller' => 'nullable|string|max:255',
            'pengiriman' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'size' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'collection_id' => 'required|exists:collections,id',
            'is_active' => 'boolean',
        ]);
        $validated['price'] = round($request->price);
        $validated['discount_price'] = $request->filled('discount_price')
    ? round($request->discount_price)
    : null;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }


    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'size'     => 'required|in:S,M,L,XL,2XL,3XL,4XL',
            'color'    => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $cart    = session()->get('cart', []);
        $cartKey = $id . '_' . $request->size . '_' . $request->color;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
            $cart[$cartKey]['price']     = $product->price * $cart[$cartKey]['quantity'];
        } else {
            $cart[$cartKey] = [
                'name'     => $product->name,
                'price'    => $product->price * $request->quantity,
                'quantity' => $request->quantity,
                'image'    => $product->image,
                'size'     => $request->size,
                'color'    => $request->color,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('welcome')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function removeFromCart($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return redirect()->route('welcome')->with('success', 'Item berhasil dihapus.');
    }

    public function updateCartQuantity(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $product     = Product::where('name', $cart[$cartKey]['name'])->first();
            $hargaSatuan = $product ? $product->price : 0;
            $action      = $request->input('action');

            if ($action === 'increase') {
                $cart[$cartKey]['quantity']++;
            } elseif ($action === 'decrease') {
                if ($cart[$cartKey]['quantity'] > 1) {
                    $cart[$cartKey]['quantity']--;
                } else {
                    unset($cart[$cartKey]);
                    session()->put('cart', $cart);
                    return redirect()->route('welcome');
                }
            }

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['price'] = $hargaSatuan * $cart[$cartKey]['quantity'];
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('welcome');
    }

 
}
