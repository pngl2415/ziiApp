@extends('layouts.app')

@section('title', 'Citas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-calendar-check"></i> Gestión de Citas</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('citas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Cita
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(isset($citas) && count($citas) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Fecha y Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                        <tr>
                            <td>{{ $cita['id'] }}</td>
                            <td>
                                <strong>ID: {{ $cita['paciente_id'] }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">ID: {{ $cita['doctor_id'] }}</span>
                            </td>
                            <td>{{ $cita['fecha_hora'] }}</td>
                            <td>
                                @if($cita['estado'] == 1)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Cancelada</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('citas.edit', $cita['id']) }}" class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('citas.destroy', $cita['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
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
                <p class="text-muted mt-3">No hay citas registradas</p>
                <a href="{{ route('citas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear primera cita
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection