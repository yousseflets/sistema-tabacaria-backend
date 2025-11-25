<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.login');
});
// Admin routes
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Log;

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.post');

    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::post('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Brands
    Route::get('brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::post('brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::post('brands/{brand}/delete', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    // Protected admin area (controllers enforce session check)
    Route::get('profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Products
    // rota de debug temporária para testar resolução de rotas
    Route::get('debug-import', function () { return response('OK - admin debug route', 200); });
    // rota de debug para checar persistência no DB (temporária)
    Route::get('debug-import-check', function () {
        try {
            $before = \App\Models\Product::count();
            // attempt to create a harmless test product
            $testName = 'DEBUG_PRODUCT_' . time();
            $p = \App\Models\Product::create([
                'name' => $testName,
                'price' => 1.00,
                'quantity' => 1,
                'active' => 1,
            ]);
            $after = \App\Models\Product::count();
            return response()->json(['before' => $before, 'after' => $after, 'created' => $p->id]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('products', [ProductController::class, 'store'])->name('admin.products.store');
    // Import routes must be before the dynamic {product} routes to avoid
    // treating "import" as a {product} parameter and causing a 404.
    Route::get('products/import', [ProductController::class, 'importForm'])->name('admin.products.import.form');
    Route::post('products/import', [ProductController::class, 'import'])->name('admin.products.import');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('admin.products.toggle');
});

// Fallback route to log unmatched requests (temporary for debugging)
Route::fallback(function () {
    try {
        $req = request();
        Log::error('Fallback route hit', [
            'method' => $req->method(),
            'path' => $req->path(),
            'fullUrl' => $req->fullUrl(),
            'query' => $req->query(),
            'headers' => [
                'host' => $req->header('host'),
                'referer' => $req->header('referer'),
                'user-agent' => $req->header('user-agent'),
            ],
        ]);
    } catch (\Throwable $e) {
        // ensure we don't break the response
        Log::error('Error while logging fallback: ' . $e->getMessage());
    }

    return response('Not Found', 404);
});
