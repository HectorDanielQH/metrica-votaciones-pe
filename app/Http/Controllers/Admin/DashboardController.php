<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $rolesEnabled = Schema::hasTable('roles') && Schema::hasTable('model_has_roles');

        $stats = [
            [
                'title' => 'Usuarios registrados',
                'value' => User::count(),
                'icon' => 'fas fa-users',
                'theme' => 'primary',
                'route' => route('admin.users.index'),
                'label' => 'Gestionar usuarios',
            ],
            [
                'title' => 'Roles definidos',
                'value' => Schema::hasTable('roles') ? Role::count() : 0,
                'icon' => 'fas fa-user-shield',
                'theme' => 'success',
                'route' => route('admin.roles.index'),
                'label' => 'Ver roles',
            ],
            [
                'title' => 'Permisos activos',
                'value' => Schema::hasTable('permissions') ? Permission::count() : 0,
                'icon' => 'fas fa-key',
                'theme' => 'warning',
                'route' => route('admin.permissions.index'),
                'label' => 'Revisar permisos',
            ],
            [
                'title' => 'Modulos priorizados',
                'value' => 4,
                'icon' => 'fas fa-th-large',
                'theme' => 'info',
                'route' => route('admin.surveys.index'),
                'label' => 'Continuar implementacion',
            ],
        ];

        $roleDistribution = [
            'Administrador' => $rolesEnabled ? User::role('administrador')->count() : 0,
            'Encuestador' => $rolesEnabled ? User::role('encuestador')->count() : 0,
            'Veedor' => $rolesEnabled ? User::role('veedor')->count() : 0,
        ];

        $modules = [
            [
                'title' => 'Usuarios y permisos',
                'description' => 'Alta de cuentas, asignacion de roles y control de acceso para administradores, encuestadores y veedores.',
                'route' => route('admin.users.index'),
                'icon' => 'fas fa-users',
                'theme' => 'primary',
            ],
            [
                'title' => 'Roles y permisos',
                'description' => 'Base de seguridad con Spatie para controlar accesos por capacidad.',
                'route' => route('admin.roles.index'),
                'icon' => 'fas fa-lock',
                'theme' => 'success',
            ],
            [
                'title' => 'Reporte de encuestadores',
                'description' => 'Seguimiento de quienes encuestaron, cuantas personas registraron y para quienes se emitieron votos.',
                'route' => route('admin.reports.index'),
                'icon' => 'fas fa-clipboard-list',
                'theme' => 'warning',
            ],
            [
                'title' => 'Encuestas',
                'description' => 'Diseno, publicacion, objetivos poblacionales y asignaciones futuras.',
                'route' => route('admin.surveys.index'),
                'icon' => 'fas fa-poll-h',
                'theme' => 'info',
            ],
        ];

        $roadmap = [
            'Consolidar la seguridad del sistema con usuarios, roles y permisos.',
            'Fortalecer reportes operativos por encuestador y resultados ponderados.',
            'Implementar CRUD de encuestas con secciones, preguntas y opciones.',
            'Desarrollar el operativo de asignacion a encuestadores y panel de veedores.',
        ];

        return view('admin.dashboard', compact('stats', 'roleDistribution', 'modules', 'roadmap'));
    }
}
