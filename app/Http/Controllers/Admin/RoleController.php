<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->with(['permissions'])
            ->withCount(['permissions', 'users'])
            ->orderBy('name')
            ->get();

        $descriptions = [
            'administrador' => 'Control total del panel, seguridad, configuracion y administracion de encuestas.',
            'encuestador' => 'Acceso limitado a trabajo de campo, carga de respuestas y seguimiento propio.',
            'veedor' => 'Acceso de solo lectura a monitoreo, avances y reportes permitidos.',
            'observador' => 'Rol legado conservado solo para compatibilidad.',
        ];

        return view('admin.roles.index', [
            'roles' => $roles,
            'descriptions' => $descriptions,
        ]);
    }
}
