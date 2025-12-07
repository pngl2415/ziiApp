@extends('layouts.app')

@section('title', 'Editar Administrador')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-person-badge"></i> Editar Administrador</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('administradores.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('administradores.update', $administrador['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $administrador['nombre']) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Puesto *</label>
                                <input type="text" name="puesto" class="form-control @error('puesto') is-invalid @enderror"
                                       value="{{ old('puesto', $administrador['puesto']) }}" required>
                                @error('puesto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico *</label>
                                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                       value="{{ old('correo', $administrador['correo']) }}" required>
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*" id="inputImagen">
                                <small class="text-muted">
                                    @if(isset($administrador['imagen']))
                                        Imagen actual: {{ basename($administrador['imagen']) }}
                                    @else
                                        Sin imagen actual
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Administrador
                            </button>
                            <a href="{{ route('administradores.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Vista previa -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-image"></i> Imagen Actual</h6>
                </div>
                <div class="card-body text-center">

                    @php
                        // Construir la URL correcta de la imagen desde la API
                        $imagenUrl = null;

                        if (!empty($administrador['imagen'])) {

                            // Si ya viene con /uploads, usar FILES_URL directamente
                            if (strpos($administrador['imagen'], '/uploads') === 0) {
                                $imagenUrl = rtrim(env('FILES_URL'), '/') . $administrador['imagen'];
                            }

                            // Cualquier otro caso (por si viniera sin slash)
                            else {
                                $imagenUrl = rtrim(env('FILES_URL'), '/') . '/' . ltrim($administrador['imagen'], '/');
                            }
                        }
                    @endphp

                    @if($imagenUrl)
                        <img src="{{ $imagenUrl }}"
                             alt="{{ $administrador['nombre'] }}"
                             class="img-fluid rounded"
                             id="previewImagen"
                             style="max-height: 300px; object-fit: cover;"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($administrador['nombre']) }}&size=300&background=0D6EFD&color=fff'">
                    @else
                        <div class="bg-light rounded p-5" id="previewImagen">
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
// =========================
// Preview de nueva imagen
// =========================
document.getElementById('inputImagen')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('previewImagen');

            if (preview.tagName === 'IMG') {
                preview.src = event.target.result;
            } else {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'img-fluid rounded';
                img.id = 'previewImagen';
                img.style.maxHeight = '300px';
                img.style.objectFit = 'cover';
                preview.replaceWith(img);
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

@endsection
