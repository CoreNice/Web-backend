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
    // List untuk admin
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        // Pastikan image_url tersedia (via accessor)
        return response()->json($products);
    }

    // Store new product (multipart/form-data support)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:190',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'stock'       => 'required|integer|min:0', // Added stock validation
            'image'       => 'nullable|file|image|max:5120' // max 5MB, optional
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $imagePath = null;

        // handle image upload - save with original filename (no prefix, no randomization)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $filename = $originalName; // Use original filename without randomization
            // simpan di disk 'public/products'
            $path = $file->storeAs('products', $filename, 'public');
            $imagePath = $originalName; // Store only filename
        }

        $product = Product::create([
            'name' => $data['name'],
            'price' => (float)$data['price'],
            'description' => $data['description'],
            'stock' => (int)$data['stock'],
            'image' => $imagePath,
        ]);

        return response()->json($product, 201);
    }

    // show product
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($product);
    }

    // update product (multipart/form-data)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:190',
            'price'       => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string',
            'stock'       => 'sometimes|required|integer|min:0', // Added stock validation
            'image'       => 'nullable|file|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // handle image replacement
        if ($request->hasFile('image')) {
            // hapus image lama jika ada
            if ($product->image) {
                $oldImagePath = 'products/' . $product->image;
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $filename = $originalName; // Use original filename
            $path = $file->storeAs('products', $filename, 'public');
            $data['image'] = $originalName; // Store only filename
        }

        $product->fill($data);
        $product->save();

        return response()->json($product);
    }

    // delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Not found'], 404);

        // delete image file
        if ($product->image) {
            $imagePath = 'products/' . $product->image;
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
