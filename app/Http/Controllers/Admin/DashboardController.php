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

        return view('admin.dashboard', compact('total', 'active', 'inactive'));
    }
}
