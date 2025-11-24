<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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

    public function edit()
    {
        $user = User::find(session('admin_user_id'));
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(session('admin_user_id'));
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'Perfil atualizado.');
    }
}
