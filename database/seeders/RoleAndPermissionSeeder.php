<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.assign_roles',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.assign_permissions',
            'permissions.view',
            'permissions.update',
            'candidacies.view',
            'candidacies.create',
            'candidacies.update',
            'candidacies.delete',
            'territories.view',
            'territories.create',
            'territories.update',
            'territories.delete',
            'surveys.view',
            'surveys.create',
            'surveys.update',
            'surveys.delete',
            'surveys.publish',
            'reports.view',
            'metrics.view',
            'assignments.view_own',
            'interviews.create',
            'interviews.view_own',
            'vote_records.create',
            'vote_records.view_own',
            'observations.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        $adminRole = Role::findOrCreate('administrador', 'web');
        $surveyorRole = Role::findOrCreate('encuestador', 'web');
        $watcherRole = Role::findOrCreate('veedor', 'web');
        $legacyObserverRole = Role::findOrCreate('observador', 'web');

        $adminRole->syncPermissions(Permission::all());
        $surveyorRole->syncPermissions([
            'surveys.view',
            'metrics.view',
            'assignments.view_own',
            'interviews.create',
            'interviews.view_own',
            'vote_records.create',
            'vote_records.view_own',
        ]);
        $watcherRole->syncPermissions([
            'reports.view',
            'metrics.view',
            'observations.view',
        ]);
        $legacyObserverRole->syncPermissions([
            'reports.view',
            'metrics.view',
            'observations.view',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@metricaeducativa.test'],
            [
                'name' => 'Administrador General',
                'password' => 'password',
            ]
        );

        $adminUser->assignRole($adminRole);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
