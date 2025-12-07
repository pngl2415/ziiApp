<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $api_url;

    public function __construct()
    {
        $this->api_url = env('API_URL', 'http://localhost:3001/api');
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . session('token'),
            'Accept' => 'application/json'
        ];
    }

    public function index()
    {
        $user = session('user');
        
        // Obtener estadísticas básicas
        try {
            $stats = [
                'total_admins' => 0,
                'total_doctores' => 0,
                'total_pacientes' => 0,
            ];

            // Solo si es admin puede ver todas las estadísticas
            if ($user['role'] === 'admin') {
                $admins = Http::withHeaders($this->getHeaders())
                    ->get($this->api_url . '/administradores');
                
                $doctores = Http::withHeaders($this->getHeaders())
                    ->get($this->api_url . '/doctores');
                
                $pacientes = Http::withHeaders($this->getHeaders())
                    ->get($this->api_url . '/pacientes');

                if ($admins->successful()) {
                    $stats['total_admins'] = count($admins->json());
                }
                if ($doctores->successful()) {
                    $stats['total_doctores'] = count($doctores->json());
                }
                if ($pacientes->successful()) {
                    $stats['total_pacientes'] = count($pacientes->json());
                }
            }

            // CAMBIA AQUÍ: usa 'dashboard' en lugar de 'dashboard.index'
            return view('dashboard', compact('user', 'stats'));
            
        } catch (\Exception $e) {
            return view('dashboard', compact('user'))->with('error', 'Error al cargar estadísticas');
        }
    }
}