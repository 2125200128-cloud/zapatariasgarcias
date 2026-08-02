<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;

class LoginController extends Controller
{
    public function mostrar()
    {
        return view('/login/inicio');
    }

    public function login(Request $request)
    {
        $usuario = $request->input('usuario');
        $password = $request->input('password');

        $credenciales = [
            'usuario' => $usuario,
            'password' => $password,
            'estatus' => 'Activo',
        ];

        if (Auth::guard('empleado')->attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        $empleado = Empleado::where('usuario', $usuario)->first();
        if ($empleado && $empleado->estatus !== 'Activo') {
            return back()->withErrors(['usuario' => 'Esta cuenta está inactiva.'])->onlyInput('usuario');
        }

        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])->onlyInput('usuario');
    }

    public function logout(Request $request)
    {
        Auth::guard('empleado')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
