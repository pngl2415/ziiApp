@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
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

    <div class="row">
        <!-- FORMULARIO -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <form action="{{ route('pacientes.update', $paciente['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nombre y Correo -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $paciente['nombre']) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico *</label>
                                <input type="email" name="correo"
                                       class="form-control @error('correo') is-invalid @enderror"
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
                                       value="{{ old('fecha_nacimiento', $paciente['fecha_nacimiento']) }}">
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Género</label>
                                <select name="genero" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="Masculino" {{ ($paciente['genero'] ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ ($paciente['genero'] ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ ($paciente['genero'] ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*" id="inputImagen">

                                @if(isset($paciente['imagen']) && $paciente['imagen'] != null)
                                    <small class="text-muted d-block mt-1">
                                        Imagen actual: {{ basename($paciente['imagen']) }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $paciente['direccion']) }}</textarea>
                            </div>
                        </div>

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

        <!-- PREVIEW DE IMAGEN -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-image"></i> Imagen Actual</h6>
                </div>
                <div class="card-body text-center">

                    @php
                        $imagenUrl = null;

                        if (!empty($paciente['imagen'])) {
                            if (strpos($paciente['imagen'], '/uploads') === 0) {
                                $imagenUrl = env('API_URL', 'http://localhost:3001') . $paciente['imagen'];
                            } else {
                                $imagenUrl = asset($paciente['imagen']);
                            }
                        }
                    @endphp

                    @if($imagenUrl)
                        <img id="previewImagen" 
                             src="{{ $imagenUrl }}"
                             alt="Imagen del paciente"
                             class="img-fluid rounded"
                             style="max-height: 300px; object-fit: cover;"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($paciente['nombre']) }}&size=300&background=0D6EFD&color=fff'">
                    @else
                        <div id="previewImagen" class="bg-light rounded p-5">
                            <i class="bi bi-person-circle text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-2">Sin imagen</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('inputImagen')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const preview = document.getElementById('previewImagen');
            if (preview.tagName === 'IMG') {
                preview.src = event.target.result;
            } else {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.alt = "Preview";
                img.className = "img-fluid rounded";
                img.style.maxHeight = "300px";
                img.style.objectFit = "cover";
                img.id = "previewImagen";
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

@endsection
