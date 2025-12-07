<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DoctorController extends Controller
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
                ->get($this->api_url . '/doctores');

            if ($response->successful()) {
                $doctores = $response->json();
                return view('doctores.index', compact('doctores'));
            }

            return back()->with('error', 'Error al obtener doctores');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('doctores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'especialidad' => 'required|string',
            'cedula' => 'required|string',
            'telefono' => 'nullable|string',
            'contrasena' => 'required|min:6',
            'imagen' => 'nullable|image|max:2048',
        ]);

        try {
            // Datos del doctor
            $multipart = [
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'especialidad' => $request->especialidad,
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'contrasena' => $request->contrasena,
            ];

            $requestHttp = Http::withHeaders($this->getHeaders());

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'doctor_' . time() . '_1.' . $extension;

                // Campos especiales para multer
                $multipart['entidad'] = 'doctores';
                $multipart['nombreArchivo'] = $nombreArchivo;

                $requestHttp = $requestHttp->attach(
                    'imagen',                     // nombre del campo que espera tu API
                    fopen($file->getPathname(), 'r'), 
                    $nombreArchivo                // nombre final del archivo
                );
            }

            $response = $requestHttp->post($this->api_url . '/doctores', $multipart);

            if ($response->successful()) {
                return redirect()->route('doctores.index')
                    ->with('success', 'Doctor creado exitosamente');
            }

            $error = $response->json()['error'] ?? 'Error al crear doctor';
            return back()->with('error', $error)->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/doctores/' . $id);

            if ($response->successful()) {
                $doctor = $response->json();
                return view('doctores.show', compact('doctor'));
            }

            return back()->with('error', 'Doctor no encontrado');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/doctores/' . $id);

            if ($response->successful()) {
                $doctor = $response->json();
                return view('doctores.edit', compact('doctor'));
            }

            return back()->with('error', 'Doctor no encontrado');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'especialidad' => 'required|string',
            'telefono' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        try {
            $multipart = [
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'especialidad' => $request->especialidad,
                'telefono' => $request->telefono,
            ];

            $requestHttp = Http::withHeaders($this->getHeaders());

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'doctor_' . time() . '_1.' . $extension;

                // Campos especiales para multer
                $multipart['entidad'] = 'doctores';
                $multipart['nombreArchivo'] = $nombreArchivo;

                $requestHttp = $requestHttp->attach(
                    'imagen',
                    fopen($file->getPathname(), 'r'),
                    $nombreArchivo
                );
            }

            $response = $requestHttp->put($this->api_url . '/doctores/' . $id, $multipart);

            if ($response->successful()) {
                return redirect()->route('doctores.index')
                    ->with('success', 'Doctor actualizado exitosamente');
            }

            $error = $response->json()['error'] ?? 'Error al actualizar doctor';
            return back()->with('error', $error)->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->api_url . '/doctores/' . $id . '/inhabilitar');

            if ($response->successful()) {
                return redirect()->route('doctores.index')
                    ->with('success', 'Doctor inhabilitado exitosamente');
            }

            return back()->with('error', 'Error al inhabilitar doctor');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
