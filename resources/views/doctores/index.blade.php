@extends('layouts.app')

@section('title', 'Doctores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-person-badge"></i> Gestión de Doctores</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Doctor
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(isset($doctores) && count($doctores) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Especialidad</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctores as $doctor)
                        <tr>
                            <!-- Imagen -->
                            <td>
                                @php
                                    $imagenUrl = !empty($doctor['imagen'])
                                        ? rtrim(env('FILES_URL'), '/') . '/' . ltrim($doctor['imagen'], '/')
                                        : null;
                                @endphp

                                @if($imagenUrl)
                                    <img 
                                        src="{{ $imagenUrl }}" 
                                        alt="{{ $doctor['nombre'] }}" 
                                        class="rounded-circle" 
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($doctor['nombre']) }}&background=0D6EFD&color=fff'">
                                @else
                                    <img 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($doctor['nombre']) }}&background=0D6EFD&color=fff" 
                                        alt="{{ $doctor['nombre'] }}" 
                                        class="rounded-circle" 
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                            </td>

                            <td>{{ $doctor['id'] }}</td>
                            <td><strong>{{ $doctor['nombre'] }}</strong></td>
                            <td>{{ $doctor['especialidad'] ?? 'N/A' }}</td>
                            <td>{{ $doctor['cedula'] ?? 'N/A' }}</td>
                            <td>{{ $doctor['correo'] }}</td>
                            <td>{{ $doctor['telefono'] ?? 'N/A' }}</td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('doctores.edit', $doctor['id']) }}" class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('doctores.destroy', $doctor['id']) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de inhabilitar este doctor?')">
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
                <p class="text-muted mt-3">No hay doctores registrados</p>
                <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear primer doctor
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
