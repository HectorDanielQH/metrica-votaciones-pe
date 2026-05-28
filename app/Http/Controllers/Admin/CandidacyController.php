<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidacy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidacyController extends Controller
{
    public function index(): View
    {
        return view('admin.candidacies.index', [
            'candidacies' => Candidacy::query()->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.candidacies.form', [
            'candidacy' => new Candidacy(),
            'formAction' => route('admin.candidacies.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Registrar candidatura',
            'title' => 'Registrar candidatura',
            'subtitle' => 'Carga la informacion del partido y del binomio principal.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'party_name' => ['required', 'string', 'max:255'],
            'party_logo' => ['required', 'image', 'max:2048'],
            'primary_candidate_name' => ['required', 'string', 'max:255'],
            'primary_candidate_photo' => ['nullable', 'image', 'max:2048'],
            'secondary_candidate_name' => ['required', 'string', 'max:255'],
            'secondary_candidate_photo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:activo,inactivo'],
        ]);

        Candidacy::create([
            'party_name' => $validated['party_name'],
            'party_logo_path' => $request->file('party_logo')->store('candidacies', 'public'),
            'primary_candidate_name' => $validated['primary_candidate_name'],
            'primary_candidate_photo_path' => $request->file('primary_candidate_photo')?->store('candidacies', 'public'),
            'secondary_candidate_name' => $validated['secondary_candidate_name'],
            'secondary_candidate_photo_path' => $request->file('secondary_candidate_photo')?->store('candidacies', 'public'),
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.candidacies.index')
            ->with('success', 'Candidatura registrada correctamente.');
    }

    public function edit(Candidacy $candidacy): View
    {
        return view('admin.candidacies.form', [
            'candidacy' => $candidacy,
            'formAction' => route('admin.candidacies.update', $candidacy),
            'formMethod' => 'PUT',
            'submitLabel' => 'Guardar cambios',
            'title' => 'Editar candidatura',
            'subtitle' => 'Actualiza la informacion del partido y de sus candidatos.',
        ]);
    }

    public function update(Request $request, Candidacy $candidacy): RedirectResponse
    {
        $validated = $request->validate([
            'party_name' => ['required', 'string', 'max:255'],
            'party_logo' => ['nullable', 'image', 'max:2048'],
            'primary_candidate_name' => ['required', 'string', 'max:255'],
            'primary_candidate_photo' => ['nullable', 'image', 'max:2048'],
            'secondary_candidate_name' => ['required', 'string', 'max:255'],
            'secondary_candidate_photo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:activo,inactivo'],
        ]);

        $data = [
            'party_name' => $validated['party_name'],
            'primary_candidate_name' => $validated['primary_candidate_name'],
            'secondary_candidate_name' => $validated['secondary_candidate_name'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('party_logo')) {
            $data['party_logo_path'] = $request->file('party_logo')->store('candidacies', 'public');
        }

        if ($request->hasFile('primary_candidate_photo')) {
            $data['primary_candidate_photo_path'] = $request->file('primary_candidate_photo')->store('candidacies', 'public');
        }

        if ($request->hasFile('secondary_candidate_photo')) {
            $data['secondary_candidate_photo_path'] = $request->file('secondary_candidate_photo')->store('candidacies', 'public');
        }

        $candidacy->update($data);

        return redirect()
            ->route('admin.candidacies.index')
            ->with('success', 'Candidatura actualizada correctamente.');
    }
}
