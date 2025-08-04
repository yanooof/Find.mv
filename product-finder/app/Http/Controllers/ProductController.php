<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('price', 'LIKE', '%' . $query . '%')
            ->paginate(20);

        return view('search', [
            'products' => $products,
            'query' => $query
        ]);
    }
}

