<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET ALL
    public function index()
    {
        return Product::all();
    }

    // GET BY ID
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    // CREATE (admin only)
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product);
    }

    // DELETE
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
