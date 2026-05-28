@extends('adminlte::page')

@section('title', 'Encuestas')

@section('content_header')
    <div>
        <h1 class="mb-1">Encuestas y ponderacion</h1>
        <p class="text-muted mb-0">Configura la encuesta activa y define cuanto vale el voto de estudiantes y docentes.</p>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-poll"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Encuestas registradas</span>
                    <span class="info-box-number">{{ $surveys->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-toggle-on"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Encuesta activa</span>
                    <span class="info-box-number">{{ $activeSurvey?->name ?? 'Sin definir' }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-balance-scale"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Ponderacion vigente</span>
                    <span class="info-box-number">
                        E {{ number_format((float) ($activeSurvey?->student_vote_weight ?? 0), 4) }}
                        / D {{ number_format((float) ($activeSurvey?->teacher_vote_weight ?? 0), 4) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Listado de encuestas" theme="primary" icon="fas fa-list">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Encuesta</th>
                                <th>Estado</th>
                                <th>Activa</th>
                                <th>Voto estudiante</th>
                                <th>Voto docente</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $survey->status === 'activa' ? 'success' : ($survey->status === 'cerrada' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($survey->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($survey->is_active)
                                            <span class="badge badge-success">Si</span>
                                        @else
                                            <span class="badge badge-light">No</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format((float) $survey->student_vote_weight, 4) }}</td>
                                    <td>{{ number_format((float) $survey->teacher_vote_weight, 4) }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.surveys.edit', $survey) }}" class="btn btn-xs btn-outline-primary">
                                            Configurar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No existen encuestas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Normativa interna" theme="info" icon="fas fa-circle-info">
                <div class="mb-3">
                    <h6>Voto estudiantil</h6>
                    <p class="text-muted mb-0">Registra cuanto representa un voto emitido por un estudiante.</p>
                </div>
                <div class="mb-3">
                    <h6>Voto docente</h6>
                    <p class="text-muted mb-0">Registra cuanto representa un voto emitido por un docente.</p>
                </div>
                <div>
                    <h6>Aplicacion</h6>
                    <p class="text-muted mb-0">Esta configuracion queda asociada a la encuesta activa y luego podra usarse en reportes ponderados.</p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
