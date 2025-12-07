@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-calendar-check"></i> Editar Cita</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('citas.update', $cita['id']) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Paciente *</label>
                        <select name="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror" required>
                            <option value="">Seleccionar paciente...</option>
                            @if(isset($pacientes))
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente['id'] }}" 
                                        {{ (old('paciente_id', $cita['paciente_id']) == $paciente['id']) ? 'selected' : '' }}>
                                        {{ $paciente['nombre'] }} - {{ $paciente['correo'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('paciente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Doctor *</label>
                        <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                            <option value="">Seleccionar doctor...</option>
                            @if(isset($doctores))
                                @foreach($doctores as $doctor)
                                    <option value="{{ $doctor['id'] }}" 
                                        {{ (old('doctor_id', $cita['doctor_id']) == $doctor['id']) ? 'selected' : '' }}>
                                        {{ $doctor['nombre'] }} - {{ $doctor['especialidad'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha y Hora *</label>
                        <input type="datetime-local" name="fecha_hora" class="form-control @error('fecha_hora') is-invalid @enderror" 
                               value="{{ old('fecha_hora', date('Y-m-d\TH:i', strtotime($cita['fecha_hora']))) }}" required>
                        @error('fecha_hora')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="1" {{ (old('estado', $cita['estado']) == 1) ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ (old('estado', $cita['estado']) == 0) ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Actualizar Cita
                    </button>
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection