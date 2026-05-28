<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metrica Educativa | Intencion de voto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/sin_fondo.webp') }}" type="image/webp">
    <style>
        :root {
            --bg: #f4f6f8;
            --panel: #ffffff;
            --ink: #17324d;
            --muted: #6a7b8d;
            --line: #dce3ea;
            --primary: #0f5cc0;
            --success: #169b62;
            --warning: #c68a08;
            --danger: #cf3f3f;
            --shadow: 0 18px 45px rgba(15, 36, 63, 0.09);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(15, 92, 192, 0.12), transparent 32%),
                radial-gradient(circle at top right, rgba(22, 155, 98, 0.10), transparent 30%),
                var(--bg);
            color: var(--ink);
        }

        .shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        .hero {
            background: linear-gradient(135deg, #0d2b49 0%, #0f5cc0 52%, #20a4f3 100%);
            color: #fff;
            border-radius: 28px;
            padding: 28px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: auto -40px -40px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: clamp(1.9rem, 4vw, 3rem);
            line-height: 1.05;
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .hero-logo {
            width: 86px;
            height: 86px;
            object-fit: contain;
            padding: 8px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .hero-copy {
            position: relative;
            z-index: 1;
        }

        .hero p {
            margin: 0;
            max-width: 760px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 1rem;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            position: relative;
            z-index: 1;
        }

        .hero-pill {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 0.95rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 18px;
            margin-top: 22px;
        }

        .card {
            background: var(--panel);
            border: 1px solid rgba(220, 227, 234, 0.9);
            border-radius: 24px;
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .span-12 { grid-column: span 12; }
        .span-8 { grid-column: span 8; }
        .span-4 { grid-column: span 4; }
        .span-3 { grid-column: span 3; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .leader-name {
            margin: 0;
            font-size: clamp(1.7rem, 2.5vw, 2.4rem);
        }

        .leader-party {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 1rem;
        }

        .leader-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .leader-header {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .party-logo {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 6px;
            box-shadow: 0 10px 24px rgba(15, 36, 63, 0.08);
        }

        .stat-block {
            border-radius: 18px;
            padding: 16px;
            background: #f7fafc;
            border: 1px solid var(--line);
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 800;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .kpi {
            padding: 18px;
            border-radius: 20px;
            color: #fff;
        }

        .kpi h3,
        .kpi p {
            margin: 0;
        }

        .kpi p {
            font-size: 0.92rem;
            opacity: 0.9;
        }

        .kpi h3 {
            margin-top: 8px;
            font-size: 1.9rem;
        }

        .kpi.primary { background: linear-gradient(135deg, #0f5cc0, #1e88ff); }
        .kpi.success { background: linear-gradient(135deg, #13885b, #1eb980); }
        .kpi.warning { background: linear-gradient(135deg, #c27d00, #f2b51f); }
        .kpi.info { background: linear-gradient(135deg, #304760, #557ea8); }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            font-size: 0.95rem;
        }

        thead th {
            color: var(--muted);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr:hover {
            background: rgba(15, 92, 192, 0.04);
        }

        .rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #eef4fb;
            color: var(--primary);
            font-weight: 800;
        }

        .footnote {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .empty {
            padding: 42px 18px;
            text-align: center;
            color: var(--muted);
        }

        .candidate {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .candidate strong {
            font-size: 1rem;
        }

        .candidate-logo {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 4px;
            flex-shrink: 0;
        }

        .candidate-copy {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        @media (max-width: 1080px) {
            .span-8,
            .span-4,
            .span-3 {
                grid-column: span 12;
            }

            .kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .shell {
                padding: 16px 12px 32px;
            }

            .hero,
            .card {
                border-radius: 22px;
                padding: 20px;
            }

            .hero-brand {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .hero-logo {
                width: 74px;
                height: 74px;
            }

            .kpis,
            .leader-stats {
                grid-template-columns: 1fr;
            }

            .leader-header,
            .candidate {
                align-items: flex-start;
            }

            th,
            td {
                padding: 12px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div class="hero-brand">
                <img class="hero-logo" src="{{ asset('images/met-ed.webp') }}" alt="Metrica Educativa">
                <div class="hero-copy">
                    <h1>Intencion de voto universitario</h1>
                    <p>
                        Seguimiento publico de encuestas realizadas por Metrica Educativa. Este panel muestra resultados agregados,
                        tendencia actual y avance de la encuesta activa.
                    </p>
                </div>
            </div>
            @if ($activeSurvey)
                <div class="hero-meta">
                    <div class="hero-pill">Encuesta activa: <strong>{{ $activeSurvey->name }}</strong></div>
                    <div class="hero-pill">Actualizado: <strong>{{ optional($lastUpdatedAt)->format('d/m/Y H:i') ?? 'Sin registros' }}</strong></div>
                </div>
            @endif
        </section>

        @if (! $activeSurvey)
            <section class="card span-12" style="margin-top: 22px;">
                <div class="empty">
                    No existe una encuesta activa publicada para visualizacion general.
                </div>
            </section>
        @else
            <section class="grid">
                <article class="card span-4">
                    <div class="eyebrow">Lider actual</div>
                    @if ($leaderResult)
                        <div class="leader-header">
                            @if (! empty($leaderResult->party_logo_path))
                                <img class="party-logo" src="{{ asset('storage/' . $leaderResult->party_logo_path) }}" alt="{{ $leaderResult->party_name }}">
                            @endif
                            <div>
                                <h2 class="leader-name">{{ $leaderResult->primary_candidate_name }}</h2>
                                <p class="leader-party">{{ $leaderResult->party_name ?: 'Candidatura registrada' }}</p>
                            </div>
                        </div>

                        <div class="leader-stats">
                            <div class="stat-block">
                                <div class="stat-label">Votos estudiantes</div>
                                <div class="stat-value">{{ $leaderResult->student_votes }}</div>
                            </div>
                            <div class="stat-block">
                                <div class="stat-label">Votos docentes</div>
                                <div class="stat-value">{{ $leaderResult->teacher_votes }}</div>
                            </div>
                        </div>
                    @else
                        <div class="empty">Todavia no existen datos suficientes para definir una tendencia.</div>
                    @endif
                </article>

                <article class="card span-8">
                    <div class="eyebrow">Resumen general</div>
                    <div class="kpis">
                        <div class="kpi primary">
                            <p>Votos registrados</p>
                            <h3>{{ $summaryCards[0]['value'] ?? 0 }}</h3>
                        </div>
                        <div class="kpi warning">
                            <p>Estudiantes</p>
                            <h3>{{ $summaryCards[1]['value'] ?? 0 }}</h3>
                        </div>
                        <div class="kpi success">
                            <p>Docentes</p>
                            <h3>{{ $summaryCards[2]['value'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <div style="margin-top: 20px; height: 340px;">
                        <canvas id="publicTrendChart"></canvas>
                    </div>
                </article>

                <article class="card span-12">
                    <div class="eyebrow">Tabla de votaciones</div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Partido</th>
                                    <th>Candidato principal</th>
                                    <th>Candidato secundario</th>
                                    <th>Votos estudiantes</th>
                                    <th>Votos docentes</th>
                                    <th>Total crudo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidateResults as $index => $result)
                                    <tr>
                                        <td><span class="rank">{{ $index + 1 }}</span></td>
                                        <td>{{ $result->party_name ?: 'Sin partido' }}</td>
                                        <td>
                                            <div class="candidate">
                                                @if (! empty($result->party_logo_path))
                                                    <img class="candidate-logo" src="{{ asset('storage/' . $result->party_logo_path) }}" alt="{{ $result->party_name }}">
                                                @endif
                                                <div class="candidate-copy">
                                                    <strong>{{ $result->primary_candidate_name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $result->secondary_candidate_name ?: 'No registrado' }}</td>
                                        <td>{{ $result->student_votes }}</td>
                                        <td>{{ $result->teacher_votes }}</td>
                                        <td>{{ $result->raw_votes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty">Aun no existen votos registrados para la encuesta activa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="footnote">
                        Panel publico de intencion de voto con datos agregados de la encuesta activa.
                    </div>
                </article>
            </section>
        @endif
    </main>

    @if ($activeSurvey)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const publicTrendChart = document.getElementById('publicTrendChart');

            if (publicTrendChart) {
                new Chart(publicTrendChart, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Voto ponderado',
                            data: @json($chartValues),
                            backgroundColor: ['#0f5cc0', '#169b62', '#f2b51f', '#e15d2a', '#6b52cc', '#d14545'],
                            borderRadius: 12,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
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
        </script>
    @endif
</body>
</html>
