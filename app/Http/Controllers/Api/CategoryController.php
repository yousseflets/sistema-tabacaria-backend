<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);

        $query = Category::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', '%'.$search.'%')
                    ->orWhere('descricao', 'like', '%'.$search.'%');
            });
        }

        $allowedSorts = ['nome', 'created_at'];
        $sort = $request->get('sort', 'created_at');
        $direction = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        // optionally include products count
        if ($request->get('with_counts')) {
            $query->withCount('products');
        }

        $categories = $query->paginate($perPage)->appends($request->except('page'));

        return CategoryResource::collection($categories)
            ->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function show(Category $category)
    {
        return (new CategoryResource($category))->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $category = Category::create($data);

        return (new CategoryResource($category))->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $category->update($data);

        return (new CategoryResource($category))->response()
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204)
            ->header('Access-Control-Allow-Origin', 'http://localhost:4200');
    }
}
