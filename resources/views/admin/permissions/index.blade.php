@extends('adminlte::page')

@section('title', 'Permisos')

@section('content_header')
    <div>
        <h1 class="mb-1">Matriz de permisos</h1>
        <p class="text-muted mb-0">Verifica rapidamente que acceso tiene cada rol dentro del sistema.</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-adminlte-card title="Cobertura por modulo" theme="primary" icon="fas fa-key">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Modulo</th>
                                <th>Permiso</th>
                                <th>Administrador</th>
                                <th>Encuestador</th>
                                <th>Veedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissionGroups as $module => $permissions)
                                @foreach ($permissions as $index => $permission)
                                    <tr>
                                        @if ($index === 0)
                                            <td rowspan="{{ $permissions->count() }}" class="align-middle text-uppercase font-weight-bold">
                                                {{ $module }}
                                            </td>
                                        @endif
                                        <td>{{ $permission->name }}</td>
                                        <td class="text-center">
                                            @if ($roles->get('administrador')?->hasPermissionTo($permission))
                                                <span class="badge badge-success">Si</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($roles->get('encuestador')?->hasPermissionTo($permission))
                                                <span class="badge badge-warning">Si</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($roles->get('veedor')?->hasPermissionTo($permission))
                                                <span class="badge badge-info">Si</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
