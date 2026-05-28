<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyController extends Controller
{
    public function index(): View
    {
        $surveys = Survey::query()->orderByDesc('is_active')->orderBy('id')->get();
        $activeSurvey = $surveys->firstWhere('is_active', true);

        return view('admin.surveys.index', [
            'surveys' => $surveys,
            'activeSurvey' => $activeSurvey,
        ]);
    }

    public function edit(Survey $survey): View
    {
        return view('admin.surveys.form', [
            'survey' => $survey,
            'formAction' => route('admin.surveys.update', $survey),
            'title' => 'Configuracion de encuesta',
            'subtitle' => 'Registra el valor ponderado del voto estudiantil y docente segun la normativa interna.',
        ]);
    }

    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:borrador,activa,cerrada'],
            'is_active' => ['nullable', 'boolean'],
            'student_vote_weight' => ['required', 'numeric', 'min:0.0001'],
            'teacher_vote_weight' => ['required', 'numeric', 'min:0.0001'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        if (($validated['is_active'] ?? false)) {
            Survey::query()
                ->where('id', '!=', $survey->id)
                ->update(['is_active' => false]);
        }

        $survey->update([
            'name' => $validated['name'],
            'status' => $validated['status'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'student_vote_weight' => $validated['student_vote_weight'],
            'teacher_vote_weight' => $validated['teacher_vote_weight'],
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()
            ->route('admin.surveys.index')
            ->with('success', 'Configuracion de encuesta actualizada correctamente.');
    }
}
