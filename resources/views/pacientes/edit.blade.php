@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-people"></i> Editar Paciente</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('pacientes.update', $paciente['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nombre y Correo -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $paciente['nombre']) }}" required>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo Electrónico *</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $paciente['correo']) }}" required>
                        @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Teléfono y Fecha Nacimiento -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="{{ old('telefono', $paciente['telefono']) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control"
                               value="{{ old('fecha_nacimiento', $paciente['fecha_nacimiento'] ?? '') }}">
                    </div>
                </div>

                <!-- Género e Imagen -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-select">
                            <option value="">Seleccionar...</option>
                            <option value="Masculino" {{ old('genero', $paciente['genero'] ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $paciente['genero'] ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero', $paciente['genero'] ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" name="imagen" class="form-control" accept="image/*">

                        {{-- Mostrar imagen actual --}}
                        @if(!empty($paciente['imagen']))
                            <div class="mt-2">
                                <p class="text-muted mb-1">Imagen actual:</p>

                                @php
                                    $urlImagen = Str::startsWith($paciente['imagen'], 'http')
                                        ? $paciente['imagen']
                                        : 'http://localhost:3001' . $paciente['imagen'];
                                @endphp

                                <img src="{{ $urlImagen }}"
                                     alt="Imagen del paciente"
                                     class="img-thumbnail"
                                     style="width: 140px; height: 140px; object-fit: cover; border-radius: 10px;">
                            </div>
                        @else
                            <div class="mt-2">
                                <p class="text-muted mb-1">Sin imagen, usando avatar:</p>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($paciente['nombre']) }}&background=0D6EFD&color=fff"
                                     class="img-thumbnail"
                                     style="width: 140px; height: 140px; border-radius: 10px;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dirección -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $paciente['direccion'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Actualizar Paciente
                    </button>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
