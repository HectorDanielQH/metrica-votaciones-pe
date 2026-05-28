@extends('adminlte::page')

@section('title', 'Panel administrativo')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="mb-1">Panel de administrador</h1>
            <p class="text-muted mb-0">Base operativa de "Metrica Educativa" para seguridad, configuracion y seguimiento electoral.</p>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        @foreach ($stats as $stat)
            <div class="col-lg-3 col-md-6 col-12">
                <x-adminlte-small-box
                    :title="(string) $stat['value']"
                    :text="$stat['title']"
                    :icon="$stat['icon']"
                    :theme="$stat['theme']"
                    :url="$stat['route']"
                    :url-text="$stat['label']" />
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Modulos priorizados" theme="primary" icon="fas fa-th-large" maximizable>
                <div class="row">
                    @foreach ($modules as $module)
                        <div class="col-md-6">
                            <div class="card card-outline card-{{ $module['theme'] }}">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="{{ $module['icon'] }} mr-2"></i>{{ $module['title'] }}
                                    </h5>
                                    <p class="card-text mt-3 text-muted">{{ $module['description'] }}</p>
                                    <a href="{{ $module['route'] }}" class="btn btn-sm btn-outline-{{ $module['theme'] }}">
                                        Abrir modulo
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Hoja de ruta inmediata" theme="success" icon="fas fa-list">
                <ol class="pl-3 mb-0">
                    @foreach ($roadmap as $step)
                        <li class="mb-3">{{ $step }}</li>
                    @endforeach
                </ol>
            </x-adminlte-card>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <x-adminlte-card title="Distribucion actual por rol" theme="info" icon="fas fa-chart-pie">
                <div style="height: 320px;">
                    <canvas id="rolesChart"></canvas>
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-lg-5">
            <x-adminlte-card title="Estado del panel" theme="warning" icon="fas fa-shield-alt">
                <div class="timeline">
                    <div>
                        <i class="fas fa-check bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-lock mr-1"></i>Activo</span>
                            <h3 class="timeline-header">Acceso administrativo protegido por rol</h3>
                            <div class="timeline-body">
                                El area <strong>/admin</strong> ya quedo aislada mediante autenticacion y middleware de Spatie.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-check bg-primary"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-sitemap mr-1"></i>Estructurado</span>
                            <h3 class="timeline-header">Reportes y seguridad conectados</h3>
                            <div class="timeline-body">
                                Usuarios, roles, candidaturas, encuestas y reportes operativos ya tienen navegacion funcional.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-hourglass-half bg-warning"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-code-branch mr-1"></i>Siguiente fase</span>
                            <h3 class="timeline-header">Expansion de analitica</h3>
                            <div class="timeline-body">
                                Falta profundizar reportes por fechas, panel del veedor y consolidado final ponderado.
                            </div>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('js')
    <script>
        const rolesChartContext = document.getElementById('rolesChart');

        if (rolesChartContext) {
            new Chart(rolesChartContext, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($roleDistribution)),
                    datasets: [{
                        data: @json(array_values($roleDistribution)),
                        backgroundColor: ['#0d6efd', '#20c997', '#ffc107'],
                        borderWidth: 0
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
@stop

@section('plugins.Chartjs', true)
