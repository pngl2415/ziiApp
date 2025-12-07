<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    private $url_productos;

    public function __construct()
    {
        $this->url_productos="http://localhost:3001/api/doctores";
    }

    public function listar()
    {
        try {
            $respuesta = Http::get($this->url_productos);
            dd($respuesta);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los productos: ' . $e->getMessage()], 500);
        } 
    }
}
