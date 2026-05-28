@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <div>
        <h1 class="mb-1">Roles del sistema</h1>
        <p class="text-muted mb-0">Resumen de acceso esperado para administradores, encuestadores y veedores.</p>
    </div>
@stop

@section('content')
    <div class="row">
        @foreach ($roles as $role)
            <div class="col-lg-4">
                <div class="card card-outline card-primary h-100">
                    <div class="card-header">
                        <h3 class="card-title">{{ ucfirst($role->name) }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $descriptions[$role->name] ?? 'Rol sin descripcion registrada.' }}</p>
                        <div class="mb-3">
                            <span class="badge badge-primary">{{ $role->permissions_count }} permisos</span>
                            <span class="badge badge-secondary">{{ $role->users_count }} usuarios</span>
                        </div>
                        <div>
                            @forelse ($role->permissions->sortBy('name') as $permission)
                                <span class="badge badge-light border mb-1">{{ $permission->name }}</span>
                            @empty
                                <span class="text-muted">Este rol aun no tiene permisos asignados.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
