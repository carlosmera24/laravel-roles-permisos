<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return view('product', compact('products'));
    }

    public function create()
    {
        return 'Tiene permiso de crear';
    }

    public function show(Product $product)
    {
        return 'Tiene permiso de ver';
    }

    public function edit(Product $product)
    {
        return 'Tiene permiso de editar';
    }

    public function destroy(Product $product)
    {
        return 'Tiene permiso de eliminar';
    }
}
