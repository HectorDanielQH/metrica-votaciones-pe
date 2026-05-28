@extends('adminlte::page')

@section('title', 'Panel del veedor')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="mb-1">Panel del veedor</h1>
            <p class="text-muted mb-0">Monitoreo operativo, estadistico y de tendencia con datos reales de la encuesta activa.</p>
        </div>
        @if ($activeSurvey)
            <div class="mt-3 mt-md-0 text-md-right">
                <div class="small text-muted">Ultima actualizacion</div>
                <strong>{{ optional($lastUpdatedAt)->format('d/m/Y H:i') ?? 'Sin registros' }}</strong>
            </div>
        @endif
    </div>
@stop

@section('content')
    @if (! $activeSurvey)
        <div class="alert alert-warning">
            No existe una encuesta activa para visualizar reportes.
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Filtros del reporte" theme="dark" icon="fas fa-filter">
                    <form method="GET" action="{{ route('observer.dashboard') }}">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label for="surveyor_id">Encuestador</label>
                                    <select id="surveyor_id" name="surveyor_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($surveyorOptions as $surveyor)
                                            <option value="{{ $surveyor->id }}" @selected((int) ($filters['surveyor_id'] ?? 0) === $surveyor->id)>
                                                {{ $surveyor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label for="respondent_type">Segmento</label>
                                    <select id="respondent_type" name="respondent_type" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="estudiante" @selected(($filters['respondent_type'] ?? null) === 'estudiante')>Estudiantes</option>
                                        <option value="docente" @selected(($filters['respondent_type'] ?? null) === 'docente')>Docentes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="date_from">Desde</label>
                                    <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label for="date_to">Hasta</label>
                                    <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                                </div>
                            </div>
                            <div class="col-lg-2 d-flex align-items-end">
                                <div class="form-group w-100 mb-0">
                                    <button type="submit" class="btn btn-primary btn-block mb-2">
                                        <i class="fas fa-search mr-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('observer.dashboard') }}" class="btn btn-outline-secondary btn-block">
                                        <i class="fas fa-undo mr-1"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            @foreach ($summaryCards as $card)
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-{{ $card['theme'] }} elevation-1">
                            <i class="{{ $card['icon'] }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $card['label'] }}</span>
                            <span class="info-box-number">{{ $card['value'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-3">
                <x-adminlte-card title="Encuesta activa" theme="primary" icon="fas fa-poll-h">
                    <p class="mb-2"><strong>{{ $activeSurvey->name }}</strong></p>
                    <p class="mb-2">Estado: <span class="badge badge-success">{{ ucfirst($activeSurvey->status) }}</span></p>
                    <p class="mb-2">Voto estudiante: <strong>{{ number_format((float) $activeSurvey->student_vote_weight, 4) }}</strong></p>
                    <p class="mb-0">Voto docente: <strong>{{ number_format((float) $activeSurvey->teacher_vote_weight, 4) }}</strong></p>
                </x-adminlte-card>
            </div>

            <div class="col-lg-3">
                <x-adminlte-card title="Lectura rapida" theme="warning" icon="fas fa-binoculars">
                    @if ($leaderResult)
                        <div class="mb-3">
                            <div class="text-muted small">Lider actual</div>
                            <h4 class="mb-1">{{ $leaderResult->primary_candidate_name }}</h4>
                            <div class="text-muted">{{ $leaderResult->party_name }}</div>
                        </div>
                    @endif

                    @if ($runnerUpResult)
                        <div class="mb-3">
                            <div class="text-muted small">Segundo lugar</div>
                            <strong>{{ $runnerUpResult->primary_candidate_name }}</strong>
                        </div>
                    @endif

                    <div class="mb-2">
                        <div class="text-muted small">Brecha ponderada</div>
                        <strong>{{ number_format((float) $weightedMargin, 4) }}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Muestra estudiante</div>
                        <strong>{{ number_format((float) $studentShare, 2) }}%</strong>
                    </div>
                    <div>
                        <div class="text-muted small">Muestra docente</div>
                        <strong>{{ number_format((float) $teacherShare, 2) }}%</strong>
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-lg-3">
                <x-adminlte-card title="Seguimiento de {{ $targetCandidateName }}" theme="info" icon="fas fa-user-check">
                    @if ($targetCandidateResult)
                        <div class="mb-2">
                            <div class="text-muted small">Posicion</div>
                            <strong>{{ $targetCandidateResult->position }} lugar</strong>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Partido</div>
                            <strong>{{ $targetCandidateResult->party_name ?: 'Sin partido' }}</strong>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Ponderado</div>
                            <strong>{{ number_format((float) $targetCandidateResult->weighted_votes, 4) }}</strong>
                            <div>{{ number_format((float) $targetCandidateResult->weighted_percentage, 2) }}%</div>
                        </div>
                        <div>
                            <div class="text-muted small">Brecha con el lider</div>
                            <strong>{{ number_format((float) ($targetCandidateGap ?? 0), 4) }}</strong>
                        </div>
                        @if ($targetCandidateSegmentSplit)
                            <hr>
                            <div class="text-muted small mb-1">Composicion del apoyo</div>
                            <div>Estudiantes: <strong>{{ $targetCandidateSegmentSplit->student_votes }}</strong> ({{ number_format((float) $targetCandidateSegmentSplit->student_share, 2) }}%)</div>
                            <div>Docentes: <strong>{{ $targetCandidateSegmentSplit->teacher_votes }}</strong> ({{ number_format((float) $targetCandidateSegmentSplit->teacher_share, 2) }}%)</div>
                        @endif
                    @else
                        <div class="alert alert-light border mb-0">
                            No se encontro un candidato registrado con el nombre {{ $targetCandidateName }}.
                        </div>
                    @endif
                </x-adminlte-card>
            </div>

            <div class="col-lg-3">
                <x-adminlte-card title="Operacion de campo" theme="success" icon="fas fa-clipboard-list">
                    <div class="mb-2">
                        <div class="text-muted small">Encuestadores activos</div>
                        <strong>{{ $activeSurveyorsCount }}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Promedio por encuestador</div>
                        <strong>{{ number_format((float) $averageRecordsPerSurveyor, 2) }}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Encuestador con mayor carga</div>
                        <strong>{{ $topSurveyorResult?->surveyor_name ?? 'Sin datos' }}</strong>
                        @if ($topSurveyorResult)
                            <div>{{ $topSurveyorResult->raw_votes }} registros, {{ number_format((float) $topSurveyorShare, 2) }}%</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-muted small">Hora pico</div>
                        <strong>{{ $peakHour?->full_label ?? 'Sin datos' }}</strong>
                        @if ($peakHour)
                            <div>{{ $peakHour->raw_votes }} registros</div>
                        @endif
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <x-adminlte-card title="Comparativo ponderado por candidato" theme="info" icon="fas fa-chart-bar">
                    <div style="height: 340px;">
                        <canvas id="observerWeightedResultsChart"></canvas>
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-lg-5">
                <x-adminlte-card title="Tendencia de registros por hora" theme="dark" icon="fas fa-chart-line">
                    <div style="height: 340px;">
                        <canvas id="observerTrendChart"></canvas>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Comportamiento horario de registros" theme="dark" icon="fas fa-clock">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Estudiantes</th>
                                    <th>Docentes</th>
                                    <th>Total</th>
                                    <th>Ponderado</th>
                                    <th>Lectura</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hourlyTrend as $hour)
                                    <tr>
                                        <td><strong>{{ $hour->full_label }}</strong></td>
                                        <td>{{ $hour->student_votes }}</td>
                                        <td>{{ $hour->teacher_votes }}</td>
                                        <td>{{ $hour->raw_votes }}</td>
                                        <td>{{ number_format((float) $hour->weighted_votes, 4) }}</td>
                                        <td><span class="badge badge-{{ $hour->theme }}">{{ $hour->intensity_label }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Aun no existen registros horarios para esta encuesta.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Matriz horaria por candidato" theme="secondary" icon="fas fa-th">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    @foreach ($candidateResults as $candidate)
                                        <th>{{ $candidate->primary_candidate_name }}</th>
                                    @endforeach
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hourlyCandidateMatrix as $hourRow)
                                    <tr>
                                        <td><strong>{{ $hourRow->full_label }}</strong></td>
                                        @foreach ($hourRow->candidates as $candidateCell)
                                            <td>{{ $candidateCell->count }}</td>
                                        @endforeach
                                        <td><strong>{{ $hourRow->total }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $candidateResults->count() + 2 }}" class="text-center text-muted py-4">
                                            Aun no existen suficientes registros para construir la matriz horaria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <x-adminlte-card title="Cruce por segmento y candidato" theme="secondary" icon="fas fa-layer-group">
                    <div style="height: 320px;">
                        <canvas id="observerSegmentChart"></canvas>
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-lg-6">
                <x-adminlte-card title="Productividad por encuestador" theme="warning" icon="fas fa-chart-column">
                    <div style="height: 320px;">
                        <canvas id="observerSurveyorChart"></canvas>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <x-adminlte-card title="Observaciones estadisticas" theme="primary" icon="fas fa-lightbulb">
                    @if ($insights->isEmpty())
                        <div class="alert alert-light border mb-0">
                            Todavia no hay suficientes datos para generar observaciones relevantes.
                        </div>
                    @else
                        <ul class="mb-0 pl-3">
                            @foreach ($insights as $insight)
                                <li class="mb-2">{{ $insight }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-adminlte-card>
            </div>

            <div class="col-lg-6">
                <x-adminlte-card title="Riesgos y sesgos detectados" theme="danger" icon="fas fa-exclamation-triangle">
                    @if ($riskIndicators->isEmpty())
                        <div class="alert alert-light border mb-0">
                            No se detectan alertas fuertes en el corte actual. Aun asi conviene seguir monitoreando concentracion y equilibrio de muestra.
                        </div>
                    @else
                        <ul class="mb-0 pl-3">
                            @foreach ($riskIndicators as $risk)
                                <li class="mb-2">{{ $risk }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Que se podria hacer" theme="success" icon="fas fa-tasks">
                    @if ($actions->isEmpty())
                        <div class="alert alert-light border mb-0">
                            La muestra actual no muestra alertas operativas fuertes. Se recomienda mantener seguimiento continuo.
                        </div>
                    @else
                        <ul class="mb-0 pl-3">
                            @foreach ($actions as $action)
                                <li class="mb-2">{{ $action }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Resultados por candidato" theme="primary" icon="fas fa-table">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Partido</th>
                                    <th>Principal</th>
                                    <th>Secundario</th>
                                    <th>Estudiantes</th>
                                    <th>Docentes</th>
                                    <th>Crudo</th>
                                    <th>% Crudo</th>
                                    <th>Ponderado</th>
                                    <th>% Ponderado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidateResults as $result)
                                    <tr>
                                        <td><span class="badge badge-light">{{ $result->position }}</span></td>
                                        <td>{{ $result->party_name }}</td>
                                        <td><strong>{{ $result->primary_candidate_name }}</strong></td>
                                        <td>{{ $result->secondary_candidate_name }}</td>
                                        <td>{{ $result->student_votes }}</td>
                                        <td>{{ $result->teacher_votes }}</td>
                                        <td>{{ $result->raw_votes }}</td>
                                        <td>{{ number_format((float) $result->raw_percentage, 2) }}%</td>
                                        <td><strong>{{ number_format((float) $result->weighted_votes, 4) }}</strong></td>
                                        <td>{{ number_format((float) $result->weighted_percentage, 2) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            Aun no existen votos registrados para esta encuesta.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Reporte detallado de encuestadores" theme="success" icon="fas fa-users">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Encuestador</th>
                                    <th>Semaforo</th>
                                    <th>Estudiantes</th>
                                    <th>Docentes</th>
                                    <th>Total</th>
                                    <th>Ponderado</th>
                                    <th>Candidato dominante</th>
                                    <th>Inicio</th>
                                    <th>Ultimo registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($surveyorResults as $result)
                                    <tr>
                                        <td>
                                            <strong>{{ $result->surveyor_name }}</strong>
                                            <div class="text-muted small">{{ $result->votes_by_candidate ?: 'Sin detalle' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $result->status }}">{{ $result->status_label }}</span>
                                            <div class="text-muted small">{{ $result->status_reason }}</div>
                                        </td>
                                        <td>{{ $result->student_votes }}</td>
                                        <td>{{ $result->teacher_votes }}</td>
                                        <td>{{ $result->raw_votes }}</td>
                                        <td>{{ number_format((float) $result->weighted_votes, 4) }}</td>
                                        <td>
                                            @if ($result->top_candidate_name)
                                                {{ $result->top_candidate_name }}
                                                <div class="text-muted small">{{ $result->top_candidate_votes }} registros, {{ number_format((float) $result->top_candidate_share, 2) }}%</div>
                                            @else
                                                Sin datos
                                            @endif
                                        </td>
                                        <td>{{ optional($result->first_record_at)->format('d/m/Y H:i') ?: 'Sin dato' }}</td>
                                        <td>{{ optional($result->last_record_at)->format('d/m/Y H:i') ?: 'Sin dato' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Aun no existen registros de encuestadores.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>
            </div>
        </div>
    @endif
@stop

@section('js')
    @if ($activeSurvey)
        <script>
            const observerWeightedResultsChart = document.getElementById('observerWeightedResultsChart');
            const observerTrendChart = document.getElementById('observerTrendChart');
            const observerSegmentChart = document.getElementById('observerSegmentChart');
            const observerSurveyorChart = document.getElementById('observerSurveyorChart');

            if (observerWeightedResultsChart) {
                new Chart(observerWeightedResultsChart, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Voto ponderado',
                            data: @json($chartValues),
                            backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#fd7e14', '#6f42c1', '#dc3545'],
                            borderRadius: 10,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                            }
                        }
                    }
                });
            }

            if (observerTrendChart) {
                new Chart(observerTrendChart, {
                    type: 'line',
                    data: {
                        labels: @json($hourlyTrendLabels),
                        datasets: [
                            {
                                label: 'Total registros',
                                data: @json($hourlyTrendTotals),
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.12)',
                                fill: true,
                                tension: 0.35
                            },
                            {
                                label: 'Estudiantes',
                                data: @json($hourlyTrendStudents),
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.12)',
                                fill: false,
                                tension: 0.35
                            },
                            {
                                label: 'Docentes',
                                data: @json($hourlyTrendTeachers),
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25, 135, 84, 0.12)',
                                fill: false,
                                tension: 0.35
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            if (observerSegmentChart) {
                new Chart(observerSegmentChart, {
                    type: 'bar',
                    data: {
                        labels: @json($segmentCandidateLabels),
                        datasets: [
                            {
                                label: 'Estudiantes',
                                data: @json($segmentStudentValues),
                                backgroundColor: '#ffc107',
                                borderRadius: 8,
                            },
                            {
                                label: 'Docentes',
                                data: @json($segmentTeacherValues),
                                backgroundColor: '#198754',
                                borderRadius: 8,
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            if (observerSurveyorChart) {
                new Chart(observerSurveyorChart, {
                    type: 'bar',
                    data: {
                        labels: @json($surveyorProductivityLabels),
                        datasets: [{
                            label: 'Registros',
                            data: @json($surveyorProductivityValues),
                            backgroundColor: '#fd7e14',
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        </script>
    @endif
@stop

@section('plugins.Chartjs', true)
