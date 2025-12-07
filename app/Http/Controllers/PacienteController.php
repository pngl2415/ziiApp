<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PacienteController extends Controller
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
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/pacientes');

            $pacientes = $response->successful() ? $response->json() : [];

            return view('pacientes.index', compact('pacientes'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'contrasena' => 'required|min:6',
            'imagen' => 'nullable|image|max:2048',
        ]);

        try {
            $multipart = $request->except('imagen');
            $multipart['entidad'] = 'pacientes';

            $requestHttp = Http::withHeaders($this->getHeaders());

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $extension = $file->getClientOriginalExtension();

                $nombreArchivo = 'paciente_' . time() . '.' . $extension;
                $multipart['nombreArchivo'] = $nombreArchivo;

                $requestHttp = $requestHttp
                    ->withHeaders(['Content-Type' => 'multipart/form-data'])
                    ->attach('imagen', fopen($file->getPathname(), 'r'), $nombreArchivo);
            }

            $response = $requestHttp->post($this->api_url . '/pacientes', $multipart);

            if ($response->successful()) {
                return redirect()->route('pacientes.index')
                    ->with('success', 'Paciente creado exitosamente');
            }

            $error = $response->json()['error'] ?? 'Error al crear paciente';
            return back()->with('error', $error)->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/pacientes/' . $id);

            if ($response->successful()) {
                $paciente = $response->json();
                return view('pacientes.edit', compact('paciente'));
            }

            return back()->with('error', 'Paciente no encontrado');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email',
        'telefono' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date',
        'direccion' => 'nullable|string',
        'genero' => 'nullable|in:Masculino,Femenino,Otro',
        'imagen' => 'nullable|image|max:2048',
    ]);

    try {
        $data = [
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion' => $request->direccion,
            'genero' => $request->genero,
        ];

        $requestHttp = Http::withHeaders($this->getHeaders());

        // Si hay imagen, construir como multipart
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = 'paciente_' . $id . '_' . time() . '.' . $extension;

            // Construir la petición multipart
            $multipartRequest = $requestHttp->asMultipart();
            
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    $multipartRequest = $multipartRequest->attach($key, $value);
                }
            }
            
            $response = $multipartRequest
                ->attach('imagen', fopen($file->getPathname(), 'r'), $nombreArchivo)
                ->put($this->api_url . '/pacientes/' . $id);
        } else {
            // Sin imagen, enviar como JSON
            $response = $requestHttp->put($this->api_url . '/pacientes/' . $id, $data);
        }

        if ($response->successful()) {
            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente actualizado exitosamente');
        }

        $error = $response->json()['error'] ?? 'Error al actualizar paciente';
        return back()->with('error', $error)->withInput();

    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->api_url . '/pacientes/' . $id . '/inhabilitar');

            if ($response->successful()) {
                return redirect()->route('pacientes.index')
                    ->with('success', 'Paciente inhabilitado exitosamente');
            }

            return back()->with('error', 'Error al inhabilitar paciente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
