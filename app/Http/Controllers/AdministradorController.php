<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdministradorController extends Controller
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

    // ======================================================
    // =============== LISTAR ADMINISTRADORES ===============
    // ======================================================
    public function index()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/administradores');

            if ($response->successful()) {
                $administradores = $response->json();
                return view('administradores.index', compact('administradores'));
            }

            return back()->with('error', 'Error al obtener administradores');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ======================================================
    // ===================== CREATE =========================
    // ======================================================
    public function create()
    {
        return view('administradores.create');
    }

    // ======================================================
    // ====================== STORE =========================
    // ======================================================
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'puesto' => 'required|string',
            'contrasena' => 'required|min:6',
            'imagen' => 'nullable|image|max:2048'
        ]);

        try {

            // Construcción del multipart para enviar a Node/Multer
            $multipart = [
                [
                    'name' => 'nombre',
                    'contents' => $request->nombre
                ],
                [
                    'name' => 'correo',
                    'contents' => $request->correo
                ],
                [
                    'name' => 'puesto',
                    'contents' => $request->puesto
                ],
                [
                    'name' => 'contrasena',
                    'contents' => $request->contrasena
                ]
            ];

            // 👉 Si se envió imagen, agregarla al multipart
            if ($request->hasFile('imagen')) {
                $multipart[] = [
                    'name' => 'imagen',
                    'contents' => fopen($request->file('imagen')->getPathname(), 'r'),
                    'filename' => $request->file('imagen')->getClientOriginalName()
                ];
            }

            $response = Http::withHeaders($this->getHeaders())
                ->asMultipart()
                ->post($this->api_url . '/administradores', $multipart);

            if ($response->successful()) {
                return redirect()->route('administradores.index')
                    ->with('success', 'Administrador creado exitosamente');
            }

            return back()->with('error', $response->json()['error'] ?? 'Error al crear administrador');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ======================================================
    // ======================= SHOW =========================
    // ======================================================
    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/administradores/' . $id);

            if ($response->successful()) {
                $administrador = $response->json();
                return view('administradores.show', compact('administrador'));
            }

            return back()->with('error', 'Administrador no encontrado');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ======================================================
    // ======================= EDIT =========================
    // ======================================================
    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->api_url . '/administradores/' . $id);

            if ($response->successful()) {
                $administrador = $response->json();
                return view('administradores.edit', compact('administrador'));
            }

            return back()->with('error', 'Administrador no encontrado');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ======================================================
    // ====================== UPDATE ========================
    // ======================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'puesto' => 'required|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        try {

            $multipart = [
                [
                    'name' => 'nombre',
                    'contents' => $request->nombre
                ],
                [
                    'name' => 'correo',
                    'contents' => $request->correo
                ],
                [
                    'name' => 'puesto',
                    'contents' => $request->puesto
                ]
            ];

            if ($request->hasFile('imagen')) {
                $multipart[] = [
                    'name' => 'imagen',
                    'contents' => fopen($request->file('imagen')->getPathname(), 'r'),
                    'filename' => $request->file('imagen')->getClientOriginalName()
                ];
            }

            $response = Http::withHeaders($this->getHeaders())
                ->asMultipart()
                ->put($this->api_url . '/administradores/' . $id, $multipart);

            if ($response->successful()) {
                return redirect()->route('administradores.index')
                    ->with('success', 'Administrador actualizado exitosamente');
            }

            return back()->with('error', 'Error al actualizar administrador')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // ======================================================
    // ====================== DELETE ========================
    // ======================================================
    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->api_url . '/administradores/' . $id . '/inhabilitar');

            if ($response->successful()) {
                return redirect()->route('administradores.index')
                    ->with('success', 'Administrador inhabilitado exitosamente');
            }

            return back()->with('error', 'Error al inhabilitar administrador');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
