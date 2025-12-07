<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private $api_url;

    public function __construct()
    {
        $this->api_url = env('API_URL', 'http://localhost:3001/api');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
            'entidad' => 'required|in:administrador,doctor'
        ]);

        try {
            $response = Http::post($this->api_url . '/auth/login', [
                'correo' => $request->correo,
                'contrasena' => $request->contrasena,
                'entidad' => $request->entidad
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar que sea admin o doctor
                if (!in_array($data['user']['role'], ['admin', 'doctor'])) {
                    return back()->with('error', 'Acceso denegado. Solo administradores y doctores.');
                }
                
                session([
                    'token' => $data['access_token'],
                    'user' => $data['user']
                ]);

                return redirect()->route('dashboard');
            }

            return back()->with('error', 'Credenciales inválidas')->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->forget(['token', 'user']);
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}