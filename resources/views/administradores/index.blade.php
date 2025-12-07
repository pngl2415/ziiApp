@extends('layouts.app')

@section('title', 'Administradores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-person-badge"></i> Gestión de Administradores</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('administradores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Administrador
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(isset($administradores) && count($administradores) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Puesto</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($administradores as $admin)
                        <tr>
                            <td>
                                @php
                                    // Construir URL de la imagen real
                                    $imagenUrl = !empty($admin['imagen'])
                                        ? rtrim(env('FILES_URL'), '/') . '/' . ltrim($admin['imagen'], '/')
                                        : null;
                                @endphp

                                @if($imagenUrl)
                                    <img 
                                        src="{{ $imagenUrl }}"
                                        alt="{{ $admin['nombre'] }}"
                                        class="rounded-circle"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($admin['nombre']) }}&background=0D6EFD&color=fff'">
                                @else
                                    <img 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($admin['nombre']) }}&background=0D6EFD&color=fff"
                                        alt="{{ $admin['nombre'] }}"
                                        class="rounded-circle"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                            </td>

                            <td>{{ $admin['id'] }}</td>
                            <td><strong>{{ $admin['nombre'] }}</strong></td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $admin['puesto'] ?? 'N/A' }}
                                </span>
                            </td>

                            <td>{{ $admin['correo'] }}</td>

                            <td>
                                @if($admin['estado'] == 1)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('administradores.edit', $admin['id']) }}" class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('administradores.destroy', $admin['id']) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de inhabilitar este administrador?')">
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
                <p class="text-muted mt-3">No hay administradores registrados</p>
                <a href="{{ route('administradores.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear primer administrador
                </a>
            </div>

            @endif
        </div>
    </div>
</div>
@endsection
