@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div>
        <h1 class="mb-1">{{ $title }}</h1>
        <p class="text-muted mb-0">{{ $subtitle }}</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Datos del usuario" theme="primary" icon="fas fa-id-card">
                <form action="{{ $formAction }}" method="POST">
                    @csrf
                    @if ($formMethod !== 'POST')
                        @method($formMethod)
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre completo</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Correo electronico</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Contrasena</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    {{ $formMethod === 'POST' ? 'required' : '' }}>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                @if ($formMethod !== 'POST')
                                    <small class="form-text text-muted">Dejalo vacio si no deseas cambiarla.</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmar contrasena</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    {{ $formMethod === 'POST' ? 'required' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Rol principal</label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $role)
                                @if (in_array($role->name, ['administrador', 'encuestador', 'veedor']))
                                    <option value="{{ $role->name }}" @selected(old('role', $selectedRole) === $role->name)>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                            Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Guia de roles" theme="info" icon="fas fa-circle-info">
                <div class="mb-3">
                    <h6>Administrador</h6>
                    <p class="text-muted mb-0">Gestion total del sistema y acceso completo al panel.</p>
                </div>
                <div class="mb-3">
                    <h6>Encuestador</h6>
                    <p class="text-muted mb-0">Perfil de campo con acceso futuro a encuestas asignadas y captura de respuestas.</p>
                </div>
                <div>
                    <h6>Veedor</h6>
                    <p class="text-muted mb-0">Perfil de consulta para monitoreo, avance y reportes de solo lectura.</p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
