<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:190',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $imagePath = null;

        $product = Product::create([
            'name' => $data['name'],
            'price' => (float)$data['price'],
            'description' => $data['description'],
            'stock' => (int)$data['stock'],
            'image' => $data['image'] ?? $imagePath,
        ]);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:190',
            'price'       => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string',
            'stock'       => 'sometimes|required|integer|min:0',
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (isset($data['image'])) {
            $product->image = $data['image'];
        }

        $product->fill($data);
        $product->save();

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Not found'], 404);

        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
