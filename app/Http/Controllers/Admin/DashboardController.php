<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_user_id')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $total = Product::count();
        $active = Product::where('active', true)->count();
        $inactive = $total - $active;

        // Últimos produtos
        $latestProducts = Product::orderBy('created_at', 'desc')->limit(6)->get();

        $months = [];
        $counts = [];
        $now = \Carbon\Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $label = $m->format('M/Y');
            $start = $m->copy()->startOfMonth()->toDateTimeString();
            $end = $m->copy()->endOfMonth()->toDateTimeString();
            $months[] = $label;
            $counts[] = Product::whereBetween('created_at', [$start, $end])->count();
        }

        $categories = \App\Models\Category::withSum('products', 'quantity')
            ->orderByDesc('products_sum_quantity')
            ->get(['id', 'nome']);

        $categoryNames = $categories->pluck('nome')->map(function ($n) { return \Illuminate\Support\Str::limit($n, 60); })->toArray();
        $categoryTotals = $categories->map(function ($c) { return (int) ($c->products_sum_quantity ?? 0); })->toArray();

        return view('admin.dashboard', compact('total', 'active', 'inactive', 'latestProducts', 'months', 'counts', 'categoryNames', 'categoryTotals'));
    }
}
