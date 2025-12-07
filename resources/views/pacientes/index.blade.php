@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-people"></i> Gestion de Pacientes</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Paciente
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(isset($pacientes) && count($pacientes) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha Nacimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente['id'] }}</td>

                            <!-- Imagen -->
                            <td>
                                @php
                                    $imagen = $paciente['imagen'] ?? null;
                                    // Si la ruta ya viene como "/uploads/pacientes/imagen.png"
                                    // debemos agregar el dominio del backend Node
                                    $imagenUrl = $imagen ? 'http://localhost:3001' . $imagen : null;
                                @endphp

                                @if($imagenUrl)
                                    <img src="{{ $imagenUrl }}" 
                                         alt="Foto del paciente"
                                         class="rounded-circle"
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($paciente['nombre']) }}&background=0D6EFD&color=fff"
                                         class="rounded-circle"
                                         style="width: 45px; height: 45px;">
                                @endif
                            </td>

                            <td><strong>{{ $paciente['nombre'] }}</strong></td>
                            <td>{{ $paciente['correo'] }}</td>
                            <td>{{ $paciente['telefono'] ?? 'N/A' }}</td>
                            <td>{{ $paciente['fecha_nacimiento'] ?? 'N/A' }}</td>

                            <!-- Estado -->
                            <td>
                                @if($paciente['estado'] == 1)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pacientes.edit', $paciente['id']) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('pacientes.destroy', $paciente['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de inhabilitar este paciente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Inhabilitar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No hay pacientes registrados</p>
                <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear primer paciente
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
