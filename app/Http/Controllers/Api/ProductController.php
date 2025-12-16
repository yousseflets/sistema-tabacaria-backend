<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function categoriaCharutos(Request $request)
    {
        return $this->byCategory($request, 1);
    }
    public function categoriaEssencias(Request $request)
    {
        return $this->byCategory($request, 2);
    }
    public function categoriaFumos(Request $request)
    {
        return $this->byCategory($request, 3);
    }
    public function categoriaAcessorios(Request $request)
    {
        return $this->byCategory($request, 4);
    }
    public function categoriaAluminio(Request $request)
    {
        return $this->byCategory($request, 6);
    }
    public function categoriaCarvao(Request $request)
    {
        return $this->byCategory($request, 7);
    }

    /**
     * Generic category listing endpoint. Use route /api/products/category/{id}
     */
    public function byCategory(Request $request, $categoryId)
    {
        $perPage = (int) $request->get('per_page', 50);
        $q = trim((string) $request->get('q', ''));
        $brandId = $request->get('brand_id');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sort = $request->get('sort', 'name');
        $direction = strtolower($request->get('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = Product::select(['id','name','price','quantity','active','category_id','brand_id','image','created_at','updated_at'])
            ->with(['category' => function($qcat){ $qcat->select('id','nome'); }, 'brand' => function($qbr){ $qbr->select('id','nome'); }])
            ->where('category_id', $categoryId)
            ->where('active', 1)
            ->where('quantity', '>', 0);

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        $allowedSorts = ['name','price','quantity','created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'name';
        $query->orderBy($sort, $direction);

        try {
            $start = microtime(true);
            $products = $query->paginate($perPage)->appends($request->query());
            $duration = (microtime(true) - $start) * 1000;
            logger()->info('API products byCategory', ['category_id' => $categoryId, 'duration_ms' => $duration, 'per_page' => $perPage]);
        } catch (\Throwable $e) {
            logger()->error('API products byCategory failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Erro ao listar produtos'], 500);
        }

        return ProductResource::collection($products)
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
