@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <div>
        <h1 class="mb-1">Reportes ponderados</h1>
        <p class="text-muted mb-0">Resultados crudos y ponderados segun la normativa interna universitaria.</p>
    </div>
@stop

@section('content')
    @if (! $activeSurvey)
        <div class="alert alert-warning">
            No existe una encuesta activa para generar reportes.
        </div>
    @else
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
            <div class="col-lg-8">
                <x-adminlte-card title="Resultado por candidato" theme="primary" icon="fas fa-table">
                    <div class="mb-3 text-muted">
                        Encuesta activa: <strong>{{ $activeSurvey->name }}</strong>
                        <br>
                        Valor voto estudiante: <strong>{{ number_format((float) $activeSurvey->student_vote_weight, 4) }}</strong>
                        <br>
                        Valor voto docente: <strong>{{ number_format((float) $activeSurvey->teacher_vote_weight, 4) }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Partido</th>
                                    <th>Candidato principal</th>
                                    <th>Secundario</th>
                                    <th>Estudiantes</th>
                                    <th>Docentes</th>
                                    <th>Crudo</th>
                                    <th>Ponderado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidateResults as $result)
                                    <tr>
                                        <td>{{ $result->party_name }}</td>
                                        <td>{{ $result->primary_candidate_name }}</td>
                                        <td>{{ $result->secondary_candidate_name }}</td>
                                        <td>{{ $result->student_votes }}</td>
                                        <td>{{ $result->teacher_votes }}</td>
                                        <td>{{ $result->raw_votes }}</td>
                                        <td><strong>{{ number_format((float) $result->weighted_votes, 4) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Aun no existen votos registrados para esta encuesta.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-lg-4">
                <x-adminlte-card title="Comparativo ponderado" theme="info" icon="fas fa-chart-bar">
                    <div style="height: 360px;">
                        <canvas id="weightedResultsChart"></canvas>
                    </div>
                </x-adminlte-card>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <x-adminlte-card title="Reporte de encuestadores" theme="success" icon="fas fa-users">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Encuestador</th>
                                    <th>Estudiantes</th>
                                    <th>Docentes</th>
                                    <th>Total personas</th>
                                    <th>Total ponderado</th>
                                    <th>Votos para quienes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($surveyorResults as $result)
                                    <tr>
                                        <td>{{ $result->surveyor_name }}</td>
                                        <td>{{ $result->student_votes }}</td>
                                        <td>{{ $result->teacher_votes }}</td>
                                        <td><strong>{{ $result->raw_votes }}</strong></td>
                                        <td>{{ number_format((float) $result->weighted_votes, 4) }}</td>
                                        <td>{{ $result->votes_by_candidate ?: 'Sin registros' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Aun no existen registros de encuestadores para esta encuesta.
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
            const weightedResultsChart = document.getElementById('weightedResultsChart');

            if (weightedResultsChart) {
                new Chart(weightedResultsChart, {
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
        </script>
    @endif
@stop

@section('plugins.Chartjs', true)
