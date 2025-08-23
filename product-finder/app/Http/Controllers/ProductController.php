<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('main-page');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $store = $request->input('store');

        $products = Product::when($query !== '', fn ($q) =>
                $q->where('name', 'like', "%{$query}%")
            )
            ->when($store, fn ($q) =>
                $q->whereRaw('LOWER(store) = ?', [mb_strtolower($store)])
            )
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->appends(['q' => $query, 'store' => $store]);

        // Build chips from actual DB values to avoid typos
        $stores = Product::select('store')->whereNotNull('store')->distinct()->orderBy('store')->pluck('store');

        return view('search', compact('products', 'query', 'store', 'stores'));
    }
}

