<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CitaController extends Controller
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

    /**
     * Mostrar lista de todas las citas
     */
    public function index()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/citas');

            if ($response->successful()) {
                $citas = $response->json();
                return view('citas.index', compact('citas'));
            }

            return back()->with('error', 'Error al obtener citas');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear nueva cita
     */
    public function create()
    {
        try {
            // Obtener lista de doctores
            $doctoresResponse = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/doctores');

            // Obtener lista de pacientes
            $pacientesResponse = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/pacientes');

            if ($doctoresResponse->successful() && $pacientesResponse->successful()) {
                $doctores = $doctoresResponse->json();
                $pacientes = $pacientesResponse->json();
                return view('citas.create', compact('doctores', 'pacientes'));
            }

            return back()->with('error', 'Error al cargar datos para crear cita');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nueva cita en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'fecha_hora' => 'required|date',
            'estado' => 'nullable|in:0,1'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->api_url . '/citas', [
                    'paciente_id' => $request->paciente_id,
                    'doctor_id' => $request->doctor_id,
                    'fecha_hora' => $request->fecha_hora,
                    'estado' => $request->estado ?? 1
                ]);

            if ($response->successful()) {
                return redirect()->route('citas.index')
                    ->with('success', 'Cita creada exitosamente');
            }

            $error = $response->json()['error'] ?? 'Error al crear cita';
            return back()->with('error', $error)->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mostrar detalles de una cita específica
     */
    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/citas/' . $id);

            if ($response->successful()) {
                $cita = $response->json();
                return view('citas.show', compact('cita'));
            }

            return back()->with('error', 'Cita no encontrada');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar una cita
     */
    public function edit($id)
    {
        try {
            $citaResponse = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/citas/' . $id);

            $doctoresResponse = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/doctores');

            $pacientesResponse = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/pacientes');

            if ($citaResponse->successful() && $doctoresResponse->successful() && $pacientesResponse->successful()) {
                $cita = $citaResponse->json();
                $doctores = $doctoresResponse->json();
                $pacientes = $pacientesResponse->json();
                return view('citas.edit', compact('cita', 'doctores', 'pacientes'));
            }

            return back()->with('error', 'Cita no encontrada o error al cargar datos');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar una cita existente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'fecha_hora' => 'required|date',
            'estado' => 'nullable|in:0,1'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put($this->api_url . '/citas/' . $id, [
                    'paciente_id' => $request->paciente_id,
                    'doctor_id' => $request->doctor_id,
                    'fecha_hora' => $request->fecha_hora,
                    'estado' => $request->estado ?? 1
                ]);

            if ($response->successful()) {
                return redirect()->route('citas.index')
                    ->with('success', 'Cita actualizada exitosamente');
            }

            return back()->with('error', 'Error al actualizar cita')->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Eliminar una cita
     */
    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->api_url . '/citas/' . $id);

            if ($response->successful()) {
                return redirect()->route('citas.index')
                    ->with('success', 'Cita eliminada exitosamente');
            }

            return back()->with('error', 'Error al eliminar cita');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado de una cita (activa/inactiva)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:0,1'
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->patch($this->api_url . '/citas/' . $id . '/estado', [
                    'estado' => $request->estado
                ]);

            if ($response->successful()) {
                return redirect()->route('citas.index')
                    ->with('success', 'Estado de la cita actualizado');
            }

            return back()->with('error', 'Error al actualizar estado');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
