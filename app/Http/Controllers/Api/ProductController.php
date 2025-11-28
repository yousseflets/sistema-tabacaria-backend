<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);

        $query = Product::with(['category', 'brand'])->where('active', 1)->where('quantity', '>', 0);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        if ($min = $request->get('min_price')) {
            $query->where('price', '>=', (float) $min);
        }

        if ($max = $request->get('max_price')) {
            $query->where('price', '<=', (float) $max);
        }

        if ($category = $request->get('category_id')) {
            $query->where('category_id', $category);
        }

        if ($brand = $request->get('brand_id')) {
            $query->where('brand_id', $brand);
        }

        // by default we only return active products with quantity > 0
        // allow overriding via explicit 'active' query param
        if (!is_null($active = $request->get('active'))) {
            $query->where('active', (int) $active);
        }

        $allowedSorts = ['name', 'price', 'created_at'];
        $sort = $request->get('sort', 'created_at');
        $direction = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $products = $query->paginate($perPage)->appends($request->except('page'));

        return ProductResource::collection($products)
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function show(Product $product)
    {
        // do not expose inactive or out-of-stock products to public API
        if (! $product->active || $product->quantity <= 0) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return (new ProductResource($product->load(['category','brand'])))
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer',
            'active' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $product = Product::create($data);

        return (new ProductResource($product))
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'nullable|integer',
            'active' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $product->update($data);

        return (new ProductResource($product))
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204)
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }
}
