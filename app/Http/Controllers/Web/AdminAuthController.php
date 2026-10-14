<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $credentials = [
            'correo' => $request->correo,
            'password' => $request->contrasena, 
        ];

        if (Auth::guard('admin')->attempt($credentials)) {

            $admin = Auth::guard('admin')->user();

            // Seguridad extra: solo admins
            if ($admin->rol !== 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'correo' => 'No tienes permisos de administrador',
                ]);
            }

            return redirect()->route('admin.moderacion.index');
        }

        return back()->withErrors([
            'correo' => 'Credenciales incorrectas',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
