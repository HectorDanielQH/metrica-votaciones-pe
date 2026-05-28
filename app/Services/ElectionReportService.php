<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\User;
use App\Models\VoteRecord;
use Carbon\Carbon;
use Throwable;

class ElectionReportService
{
    public function buildActiveSurveyReport(array $filters = []): array
    {
        try {
            $activeSurvey = Survey::query()
                ->where('is_active', true)
                ->where('status', 'activa')
                ->latest('id')
                ->first();
        } catch (Throwable) {
            return $this->emptyReport();
        }

        if (! $activeSurvey) {
            return $this->emptyReport();
        }

        $allVoteRecords = VoteRecord::query()
            ->with(['candidacy', 'surveyor'])
            ->where('survey_id', $activeSurvey->id)
            ->get();

        $surveyorOptions = $allVoteRecords
            ->pluck('surveyor')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn ($surveyor) => (object) [
                'id' => $surveyor->id,
                'name' => $surveyor->name,
            ]);

        $selectedSurveyorId = isset($filters['surveyor_id']) && $filters['surveyor_id'] !== ''
            ? (int) $filters['surveyor_id']
            : null;
        $selectedRespondentType = in_array(($filters['respondent_type'] ?? null), ['docente', 'estudiante'], true)
            ? $filters['respondent_type']
            : null;
        $selectedDateFrom = $this->normalizeDate($filters['date_from'] ?? null, false);
        $selectedDateTo = $this->normalizeDate($filters['date_to'] ?? null, true);

        $voteRecords = $allVoteRecords
            ->when($selectedSurveyorId, fn ($records) => $records->where('surveyor_id', $selectedSurveyorId))
            ->when($selectedRespondentType, fn ($records) => $records->where('respondent_type', $selectedRespondentType))
            ->when($selectedDateFrom, fn ($records) => $records->filter(
                fn ($record) => optional($record->created_at)->gte($selectedDateFrom)
            ))
            ->when($selectedDateTo, fn ($records) => $records->filter(
                fn ($record) => optional($record->created_at)->lte($selectedDateTo)
            ))
            ->values();

        $lastUpdatedAt = $voteRecords
            ->sortByDesc('created_at')
            ->first()?->created_at;

        $studentWeight = (float) $activeSurvey->student_vote_weight;
        $teacherWeight = (float) $activeSurvey->teacher_vote_weight;

        $candidateResults = $voteRecords
            ->groupBy('candidacy_id')
            ->map(function ($records) use ($studentWeight, $teacherWeight) {
                $candidacy = $records->first()->candidacy;
                $studentVotes = $records->where('respondent_type', 'estudiante')->count();
                $teacherVotes = $records->where('respondent_type', 'docente')->count();
                $rawVotes = $records->count();
                $weightedVotes = ($studentVotes * $studentWeight) + ($teacherVotes * $teacherWeight);

                return (object) [
                    'candidacy_id' => $candidacy?->id,
                    'party_name' => $candidacy?->party_name,
                    'party_logo_path' => $candidacy?->party_logo_path,
                    'primary_candidate_name' => $candidacy?->primary_candidate_name,
                    'primary_candidate_photo_path' => $candidacy?->primary_candidate_photo_path,
                    'secondary_candidate_name' => $candidacy?->secondary_candidate_name,
                    'student_votes' => $studentVotes,
                    'teacher_votes' => $teacherVotes,
                    'raw_votes' => $rawVotes,
                    'weighted_votes' => round($weightedVotes, 4),
                    'raw_percentage' => 0,
                    'weighted_percentage' => 0,
                    'position' => null,
                ];
            })
            ->sortByDesc('weighted_votes')
            ->values()
            ->map(function ($result, $index) {
                $result->position = $index + 1;

                return $result;
            });

        $surveyorResults = $voteRecords
            ->groupBy('surveyor_id')
            ->map(function ($records, $surveyorId) use ($studentWeight, $teacherWeight) {
                $surveyor = $records->first()?->surveyor ?? User::find($surveyorId);
                $studentVotes = $records->where('respondent_type', 'estudiante')->count();
                $teacherVotes = $records->where('respondent_type', 'docente')->count();
                $rawVotes = $records->count();
                $weightedVotes = ($studentVotes * $studentWeight) + ($teacherVotes * $teacherWeight);

                $votesByCandidate = $records
                    ->groupBy('candidacy_id')
                    ->map(function ($candidateRecords) {
                        $candidate = $candidateRecords->first()->candidacy;

                        return (object) [
                            'name' => $candidate?->primary_candidate_name ?? 'Sin candidato',
                            'count' => $candidateRecords->count(),
                        ];
                    })
                    ->sortByDesc('count')
                    ->values();

                $topCandidate = $votesByCandidate->first();

                return (object) [
                    'surveyor_id' => (int) $surveyorId,
                    'surveyor_name' => $surveyor?->name ?? 'Encuestador no disponible',
                    'student_votes' => $studentVotes,
                    'teacher_votes' => $teacherVotes,
                    'raw_votes' => $rawVotes,
                    'weighted_votes' => round($weightedVotes, 4),
                    'votes_by_candidate' => $votesByCandidate
                        ->map(fn ($item) => sprintf('%s (%s)', $item->name, $item->count))
                        ->implode(', '),
                    'top_candidate_name' => $topCandidate?->name,
                    'top_candidate_votes' => $topCandidate?->count ?? 0,
                    'top_candidate_share' => $rawVotes > 0 && $topCandidate
                        ? round(($topCandidate->count / $rawVotes) * 100, 2)
                        : 0,
                    'student_share' => $rawVotes > 0 ? round(($studentVotes / $rawVotes) * 100, 2) : 0,
                    'teacher_share' => $rawVotes > 0 ? round(($teacherVotes / $rawVotes) * 100, 2) : 0,
                    'first_record_at' => $records->sortBy('created_at')->first()?->created_at,
                    'last_record_at' => $records->sortByDesc('created_at')->first()?->created_at,
                    'status' => 'success',
                    'status_label' => 'Equilibrado',
                    'status_reason' => 'Distribucion y carga dentro del rango esperado.',
                ];
            })
            ->sortByDesc('raw_votes')
            ->values();

        $totalStudentVotes = $voteRecords->where('respondent_type', 'estudiante')->count();
        $totalTeacherVotes = $voteRecords->where('respondent_type', 'docente')->count();
        $totalRawVotes = $voteRecords->count();
        $totalWeightedVotes = round(
            ($totalStudentVotes * $studentWeight) + ($totalTeacherVotes * $teacherWeight),
            4
        );

        $candidateResults = $candidateResults->map(function ($result) use ($totalRawVotes, $totalWeightedVotes) {
            $result->raw_percentage = $totalRawVotes > 0
                ? round(($result->raw_votes / $totalRawVotes) * 100, 2)
                : 0;

            $result->weighted_percentage = $totalWeightedVotes > 0
                ? round(($result->weighted_votes / $totalWeightedVotes) * 100, 2)
                : 0;

            return $result;
        });

        $leader = $candidateResults->first();
        $runnerUp = $candidateResults->skip(1)->first();
        $weightedMargin = $leader && $runnerUp
            ? round($leader->weighted_votes - $runnerUp->weighted_votes, 4)
            : ($leader?->weighted_votes ?? 0);

        $targetCandidateName = 'Dante Salas';
        $targetCandidateResult = $candidateResults->first(function ($result) use ($targetCandidateName) {
            return mb_strtolower((string) $result->primary_candidate_name) === mb_strtolower($targetCandidateName);
        });
        $targetCandidateGap = $targetCandidateResult && $leader
            ? round($leader->weighted_votes - $targetCandidateResult->weighted_votes, 4)
            : null;

        $studentShare = $totalRawVotes > 0 ? round(($totalStudentVotes / $totalRawVotes) * 100, 2) : 0;
        $teacherShare = $totalRawVotes > 0 ? round(($totalTeacherVotes / $totalRawVotes) * 100, 2) : 0;
        $activeSurveyorsCount = $surveyorResults->count();
        $averageRecordsPerSurveyor = $activeSurveyorsCount > 0
            ? round($totalRawVotes / $activeSurveyorsCount, 2)
            : 0;

        $topSurveyor = $surveyorResults->first();
        $topSurveyorShare = $topSurveyor && $totalRawVotes > 0
            ? round(($topSurveyor->raw_votes / $totalRawVotes) * 100, 2)
            : 0;

        $surveyorResults = $surveyorResults
            ->map(function ($result) use ($averageRecordsPerSurveyor, $topSurveyorShare, $totalRawVotes) {
                $segmentGap = abs($result->student_share - $result->teacher_share);
                $concentrationRisk = $totalRawVotes > 0
                    ? round(($result->raw_votes / $totalRawVotes) * 100, 2)
                    : 0;

                if ($concentrationRisk >= 40 || $result->top_candidate_share >= 80 || $segmentGap >= 60) {
                    $result->status = 'danger';
                    $result->status_label = 'Alto riesgo';
                    $result->status_reason = 'Carga o composicion demasiado concentrada.';

                    return $result;
                }

                if (
                    $concentrationRisk >= 25
                    || $result->top_candidate_share >= 65
                    || $segmentGap >= 35
                    || ($averageRecordsPerSurveyor > 0 && $result->raw_votes >= ($averageRecordsPerSurveyor * 1.5))
                ) {
                    $result->status = 'warning';
                    $result->status_label = 'Vigilar';
                    $result->status_reason = 'Conviene revisar concentracion, productividad o sesgo por segmento.';

                    return $result;
                }

                $result->status = 'success';
                $result->status_label = 'Equilibrado';
                $result->status_reason = 'Distribucion y carga dentro del rango esperado.';

                return $result;
            })
            ->values();

        $hourlyTrend = $voteRecords
            ->groupBy(fn ($record) => optional($record->created_at)->format('Y-m-d H:00:00'))
            ->map(function ($records, $hourSlot) use ($studentWeight, $teacherWeight) {
                $studentVotes = $records->where('respondent_type', 'estudiante')->count();
                $teacherVotes = $records->where('respondent_type', 'docente')->count();
                $parsedHour = Carbon::parse($hourSlot);

                return (object) [
                    'hour_slot' => $hourSlot,
                    'label' => $parsedHour->format('H:i'),
                    'full_label' => $parsedHour->format('d/m/Y H:i'),
                    'student_votes' => $studentVotes,
                    'teacher_votes' => $teacherVotes,
                    'raw_votes' => $records->count(),
                    'weighted_votes' => round(($studentVotes * $studentWeight) + ($teacherVotes * $teacherWeight), 4),
                ];
            })
            ->sortBy('hour_slot')
            ->values();

        $peakHour = $hourlyTrend->sortByDesc('raw_votes')->first();
        $hourlyTrend = $hourlyTrend
            ->map(function ($hour) use ($peakHour) {
                $hour->intensity = 'media';
                $hour->intensity_label = 'Actividad media';
                $hour->theme = 'secondary';

                if ($hour->raw_votes === 0) {
                    $hour->intensity = 'baja';
                    $hour->intensity_label = 'Sin actividad';
                    $hour->theme = 'light';

                    return $hour;
                }

                if ($peakHour && $peakHour->raw_votes > 0) {
                    $ratio = $hour->raw_votes / $peakHour->raw_votes;

                    if ($ratio >= 0.75) {
                        $hour->intensity = 'alta';
                        $hour->intensity_label = 'Alta actividad';
                        $hour->theme = 'success';
                    } elseif ($ratio <= 0.35) {
                        $hour->intensity = 'baja';
                        $hour->intensity_label = 'Baja actividad';
                        $hour->theme = 'warning';
                    }
                }

                return $hour;
            })
            ->values();
        $hourlyCandidateMatrix = $hourlyTrend
            ->map(function ($hour) use ($voteRecords, $candidateResults) {
                $recordsForHour = $voteRecords->filter(function ($record) use ($hour) {
                    return optional($record->created_at)->format('Y-m-d H:00:00') === $hour->hour_slot;
                });

                $candidates = $candidateResults
                    ->map(function ($candidate) use ($recordsForHour) {
                        $count = $recordsForHour->where('candidacy_id', $candidate->candidacy_id)->count();

                        return (object) [
                            'candidate_name' => $candidate->primary_candidate_name,
                            'count' => $count,
                        ];
                    })
                    ->values();

                return (object) [
                    'full_label' => $hour->full_label,
                    'total' => $hour->raw_votes,
                    'candidates' => $candidates,
                ];
            })
            ->values();
        $segmentCandidateBreakdown = $candidateResults
            ->map(fn ($result) => (object) [
                'candidate_name' => $result->primary_candidate_name,
                'student_votes' => $result->student_votes,
                'teacher_votes' => $result->teacher_votes,
            ])
            ->values();
        $surveyorProductivity = $surveyorResults
            ->map(fn ($result) => (object) [
                'surveyor_name' => $result->surveyor_name,
                'raw_votes' => $result->raw_votes,
                'weighted_votes' => $result->weighted_votes,
            ])
            ->values();
        $riskIndicators = collect();

        $insights = collect();
        $actions = collect();

        if ($leader) {
            $insights->push(sprintf(
                'Lider actual: %s con %.2f%% del voto ponderado.',
                $leader->primary_candidate_name,
                $leader->weighted_percentage
            ));
        }

        if ($runnerUp) {
            $insights->push(sprintf(
                'La diferencia ponderada entre el primer y segundo lugar es de %s puntos.',
                number_format($weightedMargin, 4)
            ));
        }

        if ($peakHour) {
            $insights->push(sprintf(
                'La hora con mayor productividad fue %s con %s registros.',
                $peakHour->full_label,
                $peakHour->raw_votes
            ));
        }

        if ($studentShare > $teacherShare) {
            $insights->push(sprintf(
                'La muestra actual esta dominada por estudiantes (%.2f%%).',
                $studentShare
            ));
            $actions->push('Se podria reforzar la captacion de docentes para equilibrar la muestra y reducir sesgo por segmento.');
        } elseif ($teacherShare > $studentShare) {
            $insights->push(sprintf(
                'La muestra actual tiene mayor peso de docentes (%.2f%%).',
                $teacherShare
            ));
            $actions->push('Se podria ampliar la cobertura estudiantil para acercar la muestra a una distribucion mas balanceada.');
        }

        if ($topSurveyor && $topSurveyorShare >= 40) {
            $insights->push(sprintf(
                'El encuestador con mayor carga es %s con %.2f%% de los registros.',
                $topSurveyor->surveyor_name,
                $topSurveyorShare
            ));
            $actions->push('Se podria redistribuir la carga entre encuestadores para reducir dependencia operativa de una sola persona.');
            $riskIndicators->push(sprintf(
                'Concentracion operativa alta: %s acumula %.2f%% de los registros filtrados.',
                $topSurveyor->surveyor_name,
                $topSurveyorShare
            ));
        }

        if ($targetCandidateResult) {
            if ($targetCandidateResult->position === 1) {
                $insights->push(sprintf(
                    '%s se encuentra en primer lugar segun los registros filtrados.',
                    $targetCandidateName
                ));
            } else {
                $actions->push(sprintf(
                    '%s aparece en %s lugar. Conviene observar si la brecha responde a muestra insuficiente, zonas no cubiertas o diferencias por segmento.',
                    $targetCandidateName,
                    $targetCandidateResult->position
                ));
            }
        }

        $targetCandidateSegmentSplit = null;

        if ($targetCandidateResult) {
            $targetCandidateSegmentSplit = (object) [
                'student_votes' => $targetCandidateResult->student_votes,
                'teacher_votes' => $targetCandidateResult->teacher_votes,
                'student_share' => $targetCandidateResult->raw_votes > 0
                    ? round(($targetCandidateResult->student_votes / $targetCandidateResult->raw_votes) * 100, 2)
                    : 0,
                'teacher_share' => $targetCandidateResult->raw_votes > 0
                    ? round(($targetCandidateResult->teacher_votes / $targetCandidateResult->raw_votes) * 100, 2)
                    : 0,
            ];
        }

        if ($totalRawVotes < 25) {
            $insights->push('La muestra todavia es pequena. Se recomienda prudencia al interpretar tendencias tempranas.');
            $actions->push('Se podria aumentar la cantidad de registros antes de consolidar lecturas publicas o internas de tendencia.');
            $riskIndicators->push('Tamano muestral limitado: la base actual todavia es reducida para lecturas concluyentes.');
        }

        if ($hourlyTrend->count() <= 2 && $totalRawVotes > 0) {
            $actions->push('Se podria ampliar la cobertura horaria del operativo para observar mejor la estabilidad de la intencion de voto a lo largo de la jornada.');
            $riskIndicators->push('Serie horaria insuficiente: la captura aun no permite comparar varios tramos de la jornada con comodidad.');
        }

        if (abs($studentShare - $teacherShare) >= 20) {
            $riskIndicators->push(sprintf(
                'Desequilibrio por segmento: la diferencia entre estudiantes y docentes es de %.2f puntos porcentuales.',
                abs($studentShare - $teacherShare)
            ));
        }

        if ($leader && $leader->weighted_percentage >= 50) {
            $insights->push(sprintf(
                '%s supera el 50%% del voto ponderado en el corte filtrado.',
                $leader->primary_candidate_name
            ));
        }

        if ($leader && $runnerUp && $weightedMargin <= 5) {
            $riskIndicators->push('Competencia cerrada: la brecha entre primero y segundo lugar es reducida.');
        }

        if ($targetCandidateResult && $targetCandidateResult->position > 1 && $targetCandidateGap !== null) {
            $riskIndicators->push(sprintf(
                '%s se mantiene por detras del lider con una brecha ponderada de %s.',
                $targetCandidateName,
                number_format($targetCandidateGap, 4)
            ));
        }

        return [
            'activeSurvey' => $activeSurvey,
            'lastUpdatedAt' => $lastUpdatedAt,
            'summaryCards' => [
                ['label' => 'Votos registrados', 'value' => $totalRawVotes, 'theme' => 'primary', 'icon' => 'fas fa-vote-yea'],
                ['label' => 'Votos estudiantes', 'value' => $totalStudentVotes, 'theme' => 'warning', 'icon' => 'fas fa-user-graduate'],
                ['label' => 'Votos docentes', 'value' => $totalTeacherVotes, 'theme' => 'success', 'icon' => 'fas fa-chalkboard-teacher'],
                ['label' => 'Total ponderado', 'value' => number_format($totalWeightedVotes, 4), 'theme' => 'info', 'icon' => 'fas fa-balance-scale'],
            ],
            'candidateResults' => $candidateResults,
            'surveyorResults' => $surveyorResults,
            'chartLabels' => $candidateResults->map(fn ($result) => $result->primary_candidate_name)->all(),
            'chartValues' => $candidateResults->map(fn ($result) => $result->weighted_votes)->all(),
            'leaderResult' => $leader,
            'runnerUpResult' => $runnerUp,
            'weightedMargin' => $weightedMargin,
            'studentShare' => $studentShare,
            'teacherShare' => $teacherShare,
            'insights' => $insights,
            'actions' => $actions,
            'targetCandidateName' => $targetCandidateName,
            'targetCandidateResult' => $targetCandidateResult,
            'targetCandidateGap' => $targetCandidateGap,
            'surveyorOptions' => $surveyorOptions,
            'filters' => [
                'surveyor_id' => $selectedSurveyorId,
                'respondent_type' => $selectedRespondentType,
                'date_from' => $selectedDateFrom?->format('Y-m-d'),
                'date_to' => $selectedDateTo?->format('Y-m-d'),
            ],
            'activeSurveyorsCount' => $activeSurveyorsCount,
            'averageRecordsPerSurveyor' => $averageRecordsPerSurveyor,
            'topSurveyorResult' => $topSurveyor,
            'topSurveyorShare' => $topSurveyorShare,
            'hourlyTrend' => $hourlyTrend,
            'hourlyTrendLabels' => $hourlyTrend->pluck('label')->all(),
            'hourlyTrendTotals' => $hourlyTrend->pluck('raw_votes')->all(),
            'hourlyTrendStudents' => $hourlyTrend->pluck('student_votes')->all(),
            'hourlyTrendTeachers' => $hourlyTrend->pluck('teacher_votes')->all(),
            'peakHour' => $peakHour,
            'hourlyCandidateMatrix' => $hourlyCandidateMatrix,
            'segmentCandidateBreakdown' => $segmentCandidateBreakdown,
            'segmentCandidateLabels' => $segmentCandidateBreakdown->pluck('candidate_name')->all(),
            'segmentStudentValues' => $segmentCandidateBreakdown->pluck('student_votes')->all(),
            'segmentTeacherValues' => $segmentCandidateBreakdown->pluck('teacher_votes')->all(),
            'surveyorProductivity' => $surveyorProductivity,
            'surveyorProductivityLabels' => $surveyorProductivity->pluck('surveyor_name')->all(),
            'surveyorProductivityValues' => $surveyorProductivity->pluck('raw_votes')->all(),
            'riskIndicators' => $riskIndicators,
            'targetCandidateSegmentSplit' => $targetCandidateSegmentSplit,
        ];
    }

    private function normalizeDate(?string $date, bool $endOfDay): ?Carbon
    {
        if (! $date) {
            return null;
        }

        try {
            $parsedDate = Carbon::parse($date);

            return $endOfDay ? $parsedDate->endOfDay() : $parsedDate->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    private function emptyReport(): array
    {
        return [
            'activeSurvey' => null,
            'lastUpdatedAt' => null,
            'summaryCards' => [],
            'candidateResults' => collect(),
            'surveyorResults' => collect(),
            'chartLabels' => [],
            'chartValues' => [],
            'leaderResult' => null,
            'runnerUpResult' => null,
            'weightedMargin' => 0,
            'studentShare' => 0,
            'teacherShare' => 0,
            'insights' => collect(),
            'actions' => collect(),
            'targetCandidateName' => 'Dante Salas',
            'targetCandidateResult' => null,
            'targetCandidateGap' => null,
            'surveyorOptions' => collect(),
            'filters' => [
                'surveyor_id' => null,
                'respondent_type' => null,
                'date_from' => null,
                'date_to' => null,
            ],
            'activeSurveyorsCount' => 0,
            'averageRecordsPerSurveyor' => 0,
            'topSurveyorResult' => null,
            'topSurveyorShare' => 0,
            'hourlyTrend' => collect(),
            'hourlyTrendLabels' => [],
            'hourlyTrendTotals' => [],
            'hourlyTrendStudents' => [],
            'hourlyTrendTeachers' => [],
            'peakHour' => null,
            'hourlyCandidateMatrix' => collect(),
            'segmentCandidateBreakdown' => collect(),
            'segmentCandidateLabels' => [],
            'segmentStudentValues' => [],
            'segmentTeacherValues' => [],
            'surveyorProductivity' => collect(),
            'surveyorProductivityLabels' => [],
            'surveyorProductivityValues' => [],
            'riskIndicators' => collect(),
            'targetCandidateSegmentSplit' => null,
        ];
    }
}
