<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\newArrivals;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
       public function index()
    {
        // Get active new arrivals untuk slideshow
        $newArrivals = newArrivals::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get collections
        $collections = Collection::orderBy('name')->get();

        // Get products (grouped by name)
        $products = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Group products by name
        $groupedProducts = $products->groupBy('name')->map(function($group) {
            $firstProduct = $group->first();
            
            // Cari harga diskon termurah
            $cheapestDiscountProduct = $group
                ->where('discount_price', '>', 0)
                ->sortBy('discount_price')
                ->first();
            
            // Fallback ke harga normal termurah
            $cheapestProduct = $cheapestDiscountProduct ?? $group->sortBy('price')->first();
            
            return [
                'first' => $firstProduct,
                'cheapest' => $cheapestProduct,
                'group' => $group,
                'totalStock' => $group->sum('stock'),
                'sizes' => $group->pluck('size')->unique()->values()->toArray(),
            ];
        });

        return view('home', [
            'newArrivals' => $newArrivals,
            'collections' => $collections,
            'products' => $products,
            'groupedProducts' => $groupedProducts,
        ]);
    }

    
  public function search(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(8);

        return view('home', [
            'products' => $products,
        ]);
    }

    public function show($id)
    {
        $product = Product::with('collection')->findOrFail($id);

        // Get all variants
        $variantData = Product::where('name', $product->name)
            ->where('is_active', true)
            ->select('id', 'name', 'size', 'stock', 'price', 'discount_price')
            ->get()
            ->toArray();

        // Available sizes (sorted)
        $availableSizes = Product::where('name', $product->name)
            ->where('is_active', true)
            ->pluck('size')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // ===== STOCK DARI SIZE PALING KECIL =====
        $smallestSizeProduct = Product::where('name', $product->name)
            ->where('is_active', true)
            ->orderBy('size', 'asc')
            ->first();
        
        $displayStock = $smallestSizeProduct ? $smallestSizeProduct->stock : 0;

        // ===== RELATED PRODUCTS =====
        $relatedProducts = Product::where('collection_id', $product->collection_id)
            ->where('name', '!=', $product->name)
            ->where('is_active', true)
            ->get()
            ->groupBy('name')
            ->map(function($group) {
                $cheapestWithDiscount = $group
                    ->where('discount_price', '>', 0)
                    ->sortBy('discount_price')
                    ->first();
                
                return $cheapestWithDiscount ?? $group->sortBy('price')->first();
            })
            ->values()
            ->take(4);

        return view('show_detail', [
            'product' => $product,
            'variantData' => $variantData,
            'availableSizes' => $availableSizes,
            'displayStock' => $displayStock,
            'smallestSizeProduct' => $smallestSizeProduct,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
