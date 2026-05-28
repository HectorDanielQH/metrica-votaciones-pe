@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="mb-1">{{ $title }}</h1>
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-1"></i> Nuevo usuario
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach ($cards as $card)
            <div class="col-lg-3 col-md-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-{{ $card['theme'] }} elevation-1">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $card['label'] }}</span>
                        <span class="info-box-number">{{ $card['value'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Listado de usuarios" theme="primary" icon="fas fa-users">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Verificado</th>
                                <th>Registro</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    $role = $user->primaryRoleName();
                                    $roleThemes = [
                                        'administrador' => 'success',
                                        'encuestador' => 'warning',
                                        'veedor' => 'info',
                                        'observador' => 'secondary',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($role)
                                            <span class="badge badge-{{ $roleThemes[$role] ?? 'secondary' }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @else
                                            <span class="badge badge-light">Sin rol</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge badge-success">Si</span>
                                        @else
                                            <span class="badge badge-warning">No</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-outline-primary">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Todavia no existen usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Roles operativos" theme="info" icon="fas fa-sitemap">
                <div class="mb-3">
                    <h6 class="mb-1">Administrador</h6>
                    <p class="text-muted mb-0">Gestiona seguridad, usuarios, permisos y estructura del sistema.</p>
                </div>
                <div class="mb-3">
                    <h6 class="mb-1">Encuestador</h6>
                    <p class="text-muted mb-0">Trabaja en campo, responde encuestas asignadas y registra entrevistas.</p>
                </div>
                <div>
                    <h6 class="mb-1">Veedor</h6>
                    <p class="text-muted mb-0">Monitorea resultados y avance con acceso de solo lectura.</p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
