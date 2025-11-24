<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            session(['admin_user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Credenciais inválidas.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_user_id');
        return redirect()->route('admin.login');
    }
}
