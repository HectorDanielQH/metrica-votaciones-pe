<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $roles = Schema::hasTable('roles')
            ? Role::query()->orderBy('name')->get()
            : collect();

        $cards = [
            ['label' => 'Total usuarios', 'value' => User::count(), 'theme' => 'primary'],
            ['label' => 'Administradores', 'value' => $roles->isNotEmpty() ? User::role('administrador')->count() : 0, 'theme' => 'success'],
            ['label' => 'Encuestadores', 'value' => $roles->isNotEmpty() ? User::role('encuestador')->count() : 0, 'theme' => 'warning'],
            ['label' => 'Veedores', 'value' => $roles->isNotEmpty() ? User::role('veedor')->count() : 0, 'theme' => 'info'],
        ];

        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', [
            'title' => 'Usuarios y acceso',
            'subtitle' => 'Administra cuentas internas y define si cada persona operara como administrador, encuestador o veedor.',
            'cards' => $cards,
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'title' => 'Crear usuario',
            'subtitle' => 'Registra una nueva cuenta interna y asignale un rol operativo.',
            'user' => new User(),
            'roles' => Role::query()->orderBy('name')->get(),
            'selectedRole' => null,
            'submitLabel' => 'Crear usuario',
            'formAction' => route('admin.users.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['administrador', 'encuestador', 'veedor'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        $user->load('roles');

        return view('admin.users.form', [
            'title' => 'Editar usuario',
            'subtitle' => 'Actualiza los datos de la cuenta y su rol dentro del sistema.',
            'user' => $user,
            'roles' => Role::query()->orderBy('name')->get(),
            'selectedRole' => $user->primaryRoleName(),
            'submitLabel' => 'Guardar cambios',
            'formAction' => route('admin.users.update', $user),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['administrador', 'encuestador', 'veedor'])],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
