@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
            <p class="text-muted">Bienvenido, {{ $user['correo'] }}</p>
        </div>
    </div>

    @if(isset($stats))
    <div class="row g-4 mb-4">
        <!-- Card Administradores -->
        @if($user['role'] === 'admin')
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="bi bi-person-badge text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Administradores</h6>
                            <h3 class="mb-0">{{ $stats['total_admins'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('administradores.index') }}" class="btn btn-sm btn-outline-primary">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Card Doctores -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="bi bi-heart-pulse text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Doctores</h6>
                            <h3 class="mb-0">{{ $stats['total_doctores'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('doctores.index') }}" class="btn btn-sm btn-outline-success">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pacientes -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pacientes</h6>
                            <h3 class="mb-0">{{ $stats['total_pacientes'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-outline-info">
                            Ver todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Accesos rápidos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-grid"></i> Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($user['role'] === 'admin')
                        <div class="col-md-3">
                            <a href="{{ route('administradores.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle"></i><br>
                                Nuevo Administrador
                            </a>
                        </div>
                        @endif
                        
                        <div class="col-md-3">
                            <a href="{{ route('doctores.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-plus-circle"></i><br>
                                Nuevo Doctor
                            </a>
                        </div>
                        
                        <div class="col-md-3">
                            <a href="{{ route('pacientes.create') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-plus-circle"></i><br>
                                Nuevo Paciente
                            </a>
                        </div>
                        
                        <div class="col-md-3">
                            <a href="{{ route('citas.create') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-calendar-plus"></i><br>
                                Nueva Cita
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection