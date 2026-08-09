<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class LoginController extends ApiFrontController
{
    public function mostrar()
    {
        return view('/login/inicio');
    }

    public function login(Request $request)
    {
        try {
            $payload = $this->client()->login(
                (string) $request->input('usuario', ''),
                (string) $request->input('password', '')
            );
        } catch (RuntimeException $exception) {
            return back()->withErrors(['usuario' => $exception->getMessage()])->onlyInput('usuario');
        }

        $request->session()->put('api_token', $payload['token'] ?? null);
        $request->session()->put('api_user', $payload['user'] ?? null);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        $token = $this->token();
        if ($token) {
            try {
                $this->client()->logout($token);
            } catch (RuntimeException $exception) {            }
        }

        $request->session()->forget(['api_token', 'api_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
