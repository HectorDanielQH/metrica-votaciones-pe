<?php

namespace App\Http\Controllers\Surveyor;

use App\Http\Controllers\Controller;
use App\Models\Candidacy;
use App\Models\Survey;
use App\Models\VoteRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function create(): View
    {
        $surveyor = Auth::user();
        $activeSurvey = Survey::query()
            ->where('is_active', true)
            ->where('status', 'activa')
            ->latest('id')
            ->first();

        return view('surveyor.votes.create', [
            'activeSurvey' => $activeSurvey,
            'candidacies' => Candidacy::query()
                ->where('status', 'activo')
                ->orderBy('id')
                ->get(),
            'totalCount' => VoteRecord::query()
                ->where('surveyor_id', $surveyor->id)
                ->count(),
            'todayCount' => VoteRecord::query()
                ->where('surveyor_id', $surveyor->id)
                ->whereDate('created_at', now()->toDateString())
                ->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $activeSurvey = Survey::query()
            ->where('is_active', true)
            ->where('status', 'activa')
            ->latest('id')
            ->first();

        if (! $activeSurvey) {
            return redirect()
                ->route('surveyor.votes.create')
                ->withErrors(['survey' => 'No existe una encuesta activa disponible para registrar respuestas.']);
        }

        $validated = $request->validate([
            'respondent_type' => ['required', Rule::in(['estudiante', 'docente'])],
            'candidacy_id' => [
                'required',
                Rule::exists('candidacies', 'id')->where(fn ($query) => $query->where('status', 'activo')),
            ],
        ]);

        VoteRecord::create([
            'survey_id' => $activeSurvey->id,
            'surveyor_id' => Auth::id(),
            'candidacy_id' => $validated['candidacy_id'],
            'respondent_type' => $validated['respondent_type'],
        ]);

        return redirect()
            ->route('surveyor.votes.create')
            ->with('success', 'Respuesta registrada correctamente.');
    }
}
