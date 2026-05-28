<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Métrica Electoral | Panel de Intención de Voto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/sin_fondo.webp') }}" type="image/webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fafbfe] text-slate-800 font-sans antialiased min-h-screen relative overflow-x-hidden selection:bg-blue-500 selection:text-white">

    <!-- Glowing tech-aura background blobs -->
    <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] rounded-full bg-gradient-to-tr from-sky-400/20 via-blue-500/10 to-indigo-500/15 filter blur-[130px] -z-10 pointer-events-none"></div>
    <div class="absolute top-[30%] right-[-10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-indigo-400/10 via-purple-500/10 to-blue-500/15 filter blur-[130px] -z-10 pointer-events-none"></div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/75 border-b border-slate-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative group">
                    <div class="absolute -inset-1.5 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl blur-md opacity-25 group-hover:opacity-40 transition duration-300"></div>
                    <img class="relative w-10 h-10 object-contain rounded-xl bg-white p-1 shadow-sm border border-slate-200/50" src="{{ asset('images/sin_fondo.webp') }}" alt="Métrica logo">
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-lg leading-tight tracking-tight bg-gradient-to-r from-blue-700 via-indigo-650 to-blue-900 bg-clip-text text-transparent">Métrica Electoral</span>
                    <span class="text-[0.68rem] uppercase tracking-wider font-extrabold text-blue-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Panel Público
                    </span>
                </div>
            </div>
            
            <!-- Reload Data Button -->
            <button onclick="window.location.reload()" class="relative group overflow-hidden rounded-xl px-5 py-2.5 bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold text-xs tracking-wider uppercase shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                <span class="relative z-10 flex items-center gap-1.5">
                    <i class="fas fa-sync-alt group-hover:rotate-180 transition-transform duration-500"></i> Recargar Datos
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-sky-650 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Hero Section -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-blue-900 text-white shadow-2xl border border-slate-800/40 p-6 md:p-8 mb-8">
            <!-- Tech Grid Overlay Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
            <div class="absolute bottom-[-100px] right-[-100px] w-72 h-72 rounded-full bg-blue-500/20 filter blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start md:items-center gap-5">
                    <img class="w-16 h-16 md:w-20 md:h-20 object-contain rounded-2xl bg-white/10 border border-white/20 p-2.5 backdrop-blur-md shadow-lg" src="{{ asset('images/met-ed.webp') }}" alt="Métrica logo">
                    <div>
                        <h1 class="font-extrabold text-2xl md:text-4xl tracking-tight leading-tight mb-2">
                            Intención de Voto <span class="bg-gradient-to-r from-sky-400 to-blue-300 bg-clip-text text-transparent">Universitario</span>
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base max-w-2xl font-medium leading-relaxed">
                            Visualización pública y en tiempo real de encuestas institucionales desarrolladas por Métrica Educativa. Este panel muestra resultados agregados, ponderaciones y avances de campo.
                        </p>
                    </div>
                </div>

                @if ($activeSurvey)
                    <div class="flex flex-wrap md:flex-col items-start gap-2.5 self-start md:self-center bg-white/5 border border-white/10 backdrop-blur-md p-4 rounded-2xl">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[0.62rem] font-black uppercase tracking-wider bg-sky-500 text-slate-950">Encuesta Activa</span>
                            <span class="text-sm font-bold text-slate-200">{{ $activeSurvey->name }}</span>
                        </div>
                        <div class="h-[1px] w-full bg-white/10 hidden md:block"></div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                            <i class="far fa-clock"></i> Actualizado: {{ optional($lastUpdatedAt)->format('d/m/Y H:i') ?? 'Sin registros' }}
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (! $activeSurvey)
            <!-- Case when no active survey exists -->
            <section class="rounded-3xl border border-slate-200/60 bg-white/80 backdrop-blur-md shadow-xl p-12 text-center">
                <div class="max-w-md mx-auto flex flex-col items-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-4 border border-slate-200/50 shadow-inner">
                        <i class="fas fa-poll-h"></i>
                    </div>
                    <h3 class="font-extrabold text-lg text-slate-800 mb-1">Sin encuesta activa publicada</h3>
                    <p class="text-slate-500 text-sm mb-0 leading-relaxed font-medium">
                        Actualmente no existe una encuesta activa publicada para visualización general en este portal público. Por favor, regrese más tarde.
                    </p>
                </div>
            </section>
        @else
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- COLUMN LEFT: Leader Spotlight Card (span 4) -->
                <aside class="lg:col-span-4 flex flex-col gap-8">
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/80 bg-white/95 p-5 shadow-[0_24px_70px_rgba(15,23,42,0.14)] transition-all duration-300 hover:shadow-[0_28px_90px_rgba(15,23,42,0.18)] flex flex-col justify-between min-h-[460px] md:p-6">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-sky-200/40 blur-3xl"></div>
                        <div class="pointer-events-none absolute -bottom-8 -left-8 h-28 w-28 rounded-full bg-indigo-100/70 blur-3xl"></div>
                        
                        
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3.5 py-1.5 text-[0.68rem] font-extrabold uppercase tracking-[0.18em] text-blue-700 shadow-sm">
                                    <i class="fas fa-crown text-[9px] text-blue-500"></i> LÍDER ACTUAL
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-2.5 py-1 text-[0.68rem] font-bold text-emerald-700">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span> En tendencia
                                </span>
                            </div>

                            @if ($leaderResult)
                                @php
                                    $totalVotes = max(($leaderResult->student_votes + $leaderResult->teacher_votes), 1);
                                    $studentPct = round(($leaderResult->student_votes / $totalVotes) * 100);
                                    $teacherPct = round(($leaderResult->teacher_votes / $totalVotes) * 100);
                                    $leaderShare = round(($leaderResult->raw_votes / max(($summaryCards[0]['value'] ?? 0), 1)) * 100);
                                @endphp

                                <div class="relative overflow-hidden rounded-[1.75rem] bg-gradient-to-b from-slate-200 via-white to-slate-100 shadow-[0_20px_45px_rgba(15,23,42,0.16)] ring-1 ring-slate-200/80">
                                    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.18)_1px,transparent_1px)] bg-[size:22px_100%] opacity-70"></div>
                                    @if (! empty($leaderResult->party_logo_path))
                                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                                            <img
                                                class="h-full w-full object-cover opacity-[0.08] saturate-0"
                                                src="{{ asset('storage/' . $leaderResult->party_logo_path) }}"
                                                alt="{{ $leaderResult->party_name }}"
                                            >
                                        </div>
                                    @endif

                                    <div class="relative z-10 h-[38rem] overflow-hidden md:h-[44rem]">
                                        @if (! empty($leaderResult->primary_candidate_photo_path))
                                            <img
                                                class="h-full w-full object-cover object-top"
                                                src="{{ asset('storage/' . $leaderResult->primary_candidate_photo_path) }}"
                                                alt="{{ $leaderResult->primary_candidate_name }}"
                                            >
                                        @else
                                            <div class="flex h-full items-center justify-center bg-gradient-to-b from-slate-200 via-slate-100 to-white text-slate-400">
                                                <i class="fas fa-user text-6xl"></i>
                                            </div>
                                        @endif

                                        <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-slate-950/20 to-transparent"></div>
                                        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-slate-950 via-slate-950/65 to-transparent"></div>

                                        <div class="absolute left-4 top-4 rounded-full bg-white/88 px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-[0.18em] text-slate-700 shadow-md backdrop-blur-sm">
                                            Lider actual
                                        </div>

                                        <div class="absolute bottom-4 left-4 right-4">
                                            <div class="inline-flex w-full max-w-full flex-col rounded-[1.4rem] bg-white/14 px-4 py-4 text-white shadow-[0_18px_40px_rgba(15,23,42,0.22)] ring-1 ring-white/20 backdrop-blur-md">
                                                <div class="flex items-center justify-between gap-3 rounded-2xl bg-emerald-500/85 px-3 py-3 shadow-sm ring-1 ring-emerald-300/50">
                                                    <span class="flex items-center gap-2 text-sm font-black uppercase tracking-[0.16em] text-white">
                                                        <i class="fas fa-user-graduate text-xs text-emerald-100"></i>
                                                        Estudiantes
                                                    </span>
                                                    <span class="text-right">
                                                        <strong class="block font-mono text-2xl font-black leading-none">{{ $leaderResult->student_votes }}</strong>
                                                        <span class="text-[0.68rem] font-bold uppercase tracking-[0.14em] text-emerald-50">{{ $studentPct }}%</span>
                                                    </span>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between gap-3 rounded-2xl bg-orange-500/90 px-3 py-3 shadow-sm ring-1 ring-orange-300/60">
                                                    <span class="flex items-center gap-2 text-sm font-black uppercase tracking-[0.16em] text-white">
                                                        <i class="fas fa-chalkboard-teacher text-xs text-orange-100"></i>
                                                        Docentes
                                                    </span>
                                                    <span class="text-right">
                                                        <strong class="block font-mono text-2xl font-black leading-none">{{ $leaderResult->teacher_votes }}</strong>
                                                        <span class="text-[0.68rem] font-bold uppercase tracking-[0.14em] text-orange-50">{{ $teacherPct }}%</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden mt-5 rounded-[1.6rem] border border-slate-200/80 bg-slate-50/80 p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-extrabold text-slate-700">Composicion del apoyo</h3>
                                            <p class="text-xs text-slate-500">Distribucion del liderazgo por segmento</p>
                                        </div>
                                        <span class="rounded-full bg-white px-3 py-1 text-[0.68rem] font-bold text-slate-500 shadow-sm">Base: {{ $totalVotes }}</span>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-slate-100">
                                            <div class="mb-2 flex items-center justify-between text-sm">
                                                <span class="flex items-center gap-2 font-bold text-slate-700">
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                                                        <i class="fas fa-user-graduate text-xs"></i>
                                                    </span>
                                                    Estudiantes
                                                </span>
                                                <span class="text-right">
                                                    <strong class="block font-mono text-slate-800">{{ $leaderResult->student_votes }}</strong>
                                                    <span class="text-[0.68rem] font-bold text-slate-400">{{ $studentPct }}%</span>
                                                </span>
                                            </div>
                                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-amber-100">
                                                <div class="h-full rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-orange-400" style="width: {{ $studentPct }}%"></div>
                                            </div>
                                        </div>

                                        <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-slate-100">
                                            <div class="mb-2 flex items-center justify-between text-sm">
                                                <span class="flex items-center gap-2 font-bold text-slate-700">
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
                                                        <i class="fas fa-chalkboard-teacher text-xs"></i>
                                                    </span>
                                                    Docentes
                                                </span>
                                                <span class="text-right">
                                                    <strong class="block font-mono text-slate-800">{{ $leaderResult->teacher_votes }}</strong>
                                                    <span class="text-[0.68rem] font-bold text-slate-400">{{ $teacherPct }}%</span>
                                                </span>
                                            </div>
                                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-emerald-100">
                                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500" style="width: {{ $teacherPct }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden flex-col items-center text-center mt-10">
                                    <h2 class="font-extrabold text-xl text-slate-800 tracking-tight leading-snug">{{ $leaderResult->primary_candidate_name }}</h2>
                                    <p class="text-xs uppercase tracking-wider font-extrabold text-slate-400 mt-1 mb-6 px-4 truncate max-w-full">
                                        {{ $leaderResult->party_name ?: 'Candidatura Registrada' }}
                                    </p>
                                </div>

                                <!-- Dynamic breakdown of leader votes -->
                                <div class="hidden space-y-4 bg-slate-50/80 border border-slate-100 rounded-2xl p-4">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                                            <span class="flex items-center gap-1.5"><i class="fas fa-user-graduate text-amber-500"></i> Estudiantes</span>
                                            <span class="font-mono text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200/50">{{ $leaderResult->student_votes }}</span>
                                        </div>
                                        <!-- Mini progress bar -->
                                        @php
                                            $totalVotes = max(($leaderResult->student_votes + $leaderResult->teacher_votes), 1);
                                            $studentPct = round(($leaderResult->student_votes / $totalVotes) * 100);
                                        @endphp
                                        <div class="w-full h-2 rounded-full bg-slate-200 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500" style="width: {{ $studentPct }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                                            <span class="flex items-center gap-1.5"><i class="fas fa-chalkboard-teacher text-emerald-500"></i> Docentes</span>
                                            <span class="font-mono text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200/50">{{ $leaderResult->teacher_votes }}</span>
                                        </div>
                                        @php
                                            $teacherPct = round(($leaderResult->teacher_votes / $totalVotes) * 100);
                                        @endphp
                                        <div class="w-full h-2 rounded-full bg-slate-200 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600" style="width: {{ $teacherPct }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex-grow flex items-center justify-center py-12">
                                    <div class="text-center max-w-xs">
                                        <i class="fas fa-chart-pie text-slate-300 text-3xl mb-3"></i>
                                        <p class="text-slate-400 text-sm font-semibold leading-relaxed">Aún no existen votos suficientes para establecer una tendencia de liderazgo.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($leaderResult)
                            <div class="mt-5 flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-3 text-sm shadow-sm">
                                <span class="font-semibold text-slate-500">Lectura actual del panel publico</span>
                                <span class="font-mono font-black text-slate-900">{{ $leaderResult->raw_votes }} votos</span>
                            </div>
                        @endif
                    </div>
                </aside>

                <!-- COLUMN RIGHT: KPI Summary Cards & Graphic Trend (span 8) -->
                <section class="lg:col-span-8 flex flex-col gap-8">
                    
                    <!-- General Summary KPIs row -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        
                        <!-- KPI Card: Total -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/50 bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-5 shadow-lg group hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/10 group-hover:scale-110 transition duration-300"></div>
                            <div class="flex items-center justify-between mb-3.5">
                                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-100">Votos Registrados</span>
                                <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center text-xs"><i class="fas fa-clipboard-check"></i></div>
                            </div>
                            <h3 class="font-mono font-black text-3xl tracking-tight leading-none mb-1">{{ $summaryCards[0]['value'] ?? 0 }}</h3>
                            <p class="text-[0.68rem] text-slate-200 font-semibold uppercase tracking-wider flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Telemetría de campo</p>
                        </div>

                        <!-- KPI Card: Students -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white p-5 shadow-md group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-slate-50 group-hover:scale-110 transition duration-300"></div>
                            <div class="flex items-center justify-between mb-3.5">
                                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Estudiantes</span>
                                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-xs text-amber-500"><i class="fas fa-user-graduate"></i></div>
                            </div>
                            <h3 class="font-mono font-black text-3xl tracking-tight leading-none text-slate-800 mb-1">{{ $summaryCards[1]['value'] ?? 0 }}</h3>
                            <p class="text-[0.68rem] text-slate-400 font-bold uppercase tracking-wider">Estamento Alumnado</p>
                        </div>

                        <!-- KPI Card: Teachers -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white p-5 shadow-md group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-slate-50 group-hover:scale-110 transition duration-300"></div>
                            <div class="flex items-center justify-between mb-3.5">
                                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Docentes</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-xs text-emerald-500"><i class="fas fa-chalkboard-teacher"></i></div>
                            </div>
                            <h3 class="font-mono font-black text-3xl tracking-tight leading-none text-slate-800 mb-1">{{ $summaryCards[2]['value'] ?? 0 }}</h3>
                            <p class="text-[0.68rem] text-slate-400 font-bold uppercase tracking-wider">Estamento Profesorado</p>
                        </div>

                    </div>

                    <!-- Graphic Trend Chart Card -->
                    <div class="rounded-3xl border border-slate-200/60 bg-white shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 rounded-full text-[0.68rem] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/50 flex items-center gap-1.5">
                                <i class="fas fa-chart-bar text-[9px] text-blue-500"></i> TENDENCIA DE VOTO PONDERADO
                            </span>
                            <span class="text-xs text-slate-400 font-bold flex items-center gap-1.5"><i class="fas fa-circle text-[6px] text-blue-500"></i> Encuesta activa</span>
                        </div>

                        <div class="relative w-full h-[320px] bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
                            <canvas id="publicTrendChart"></canvas>
                        </div>
                    </div>

                </section>

                <!-- ROW 3: Full Detailed Results Table (span 12) -->
                <article class="lg:col-span-12 rounded-3xl border border-slate-200/60 bg-white shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[0.68rem] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center gap-1.5">
                            <i class="fas fa-table text-[9px] text-indigo-500"></i> TABLA DE POSICIONES GENERAL
                        </span>
                        <span class="text-xs text-slate-400 font-bold hidden sm:inline-block">Padrón agregado electoral</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-400 text-[0.72rem] font-extrabold uppercase tracking-wider">
                                    <th class="py-4 px-6 text-center w-20">Rango</th>
                                    <th class="py-4 px-4">Candidato / Fórmula</th>
                                    <th class="py-4 px-4">Organización Política</th>
                                    <th class="py-4 px-4 text-center">Votos Estudiantes</th>
                                    <th class="py-4 px-4 text-center">Votos Docentes</th>
                                    <th class="py-4 px-6 text-center w-36">Total Crudo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse ($candidateResults as $index => $result)
                                    <tr class="hover:bg-blue-50/15 transition duration-150">
                                        <td class="py-4 px-6 text-center">
                                            @php
                                                $rankClass = 'bg-slate-100 text-slate-600';
                                                if ($index === 0) $rankClass = 'bg-gradient-to-tr from-amber-400 to-amber-500 text-slate-950 font-black shadow-sm border border-amber-300';
                                                elseif ($index === 1) $rankClass = 'bg-gradient-to-tr from-slate-200 to-slate-300 text-slate-800 font-black';
                                                elseif ($index === 2) $rankClass = 'bg-gradient-to-tr from-orange-200 to-orange-300 text-orange-950 font-black';
                                            @endphp
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-mono font-bold {{ $rankClass }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3.5">
                                                @if (! empty($result->party_logo_path))
                                                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200/60 p-1 flex items-center justify-center shadow-sm overflow-hidden flex-shrink-0">
                                                        <img class="max-w-full max-h-full object-contain" src="{{ asset('storage/' . $result->party_logo_path) }}" alt="{{ $result->party_name }}">
                                                    </div>
                                                @endif
                                                <div class="flex flex-col gap-0.5">
                                                    <strong class="font-extrabold text-slate-800 tracking-tight text-sm md:text-base">{{ $result->primary_candidate_name }}</strong>
                                                    <span class="text-xs text-slate-400 font-semibold">Vicepresidente: {{ $result->secondary_candidate_name ?: 'No registrado' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-bold text-xs uppercase tracking-wider text-slate-500">
                                            {{ $result->party_name ?: 'Sin organización' }}
                                        </td>
                                        <td class="py-4 px-4 text-center font-mono font-extrabold text-slate-600 text-sm">
                                            {{ $result->student_votes }}
                                        </td>
                                        <td class="py-4 px-4 text-center font-mono font-extrabold text-slate-600 text-sm">
                                            {{ $result->teacher_votes }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-block font-mono font-black text-slate-900 bg-slate-50 border border-slate-200/50 rounded-xl py-1 px-3 text-sm">
                                                {{ $result->raw_votes }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 px-6 text-center">
                                            <div class="max-w-xs mx-auto flex flex-col items-center">
                                                <i class="far fa-folder-open text-slate-300 text-2xl mb-2"></i>
                                                <span class="text-slate-400 text-xs font-semibold">Aún no existen votos registrados en esta encuesta activa.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-400 font-medium flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <span class="flex items-center gap-1.5"><i class="fas fa-info-circle text-blue-500"></i> Los resultados reflejan el cómputo crudo y agregados por estamentos de la encuesta activa.</span>
                        <span class="font-mono bg-white px-2 py-1 rounded border border-slate-200/40 text-[10px] text-slate-400">TELEMETRY SECURE SHA-256 v4.1</span>
                    </div>
                </article>

            </div>
        @endif

    </main>

    <footer class="w-full bg-slate-950 text-slate-500 py-8 border-t border-slate-900 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-medium">
            <div class="flex items-center gap-2">
                <img class="w-6 h-6 object-contain filter grayscale opacity-45" src="{{ asset('images/sin_fondo.webp') }}" alt="Métrica logo">
                <span>© {{ date('Y') }} Métrica Educativa. Todos los derechos reservados.</span>
            </div>
            <div class="flex items-center gap-6">
                <span class="hover:text-slate-300 cursor-pointer">Seguridad de Datos</span>
                <span class="hover:text-slate-300 cursor-pointer">Términos de Servicio</span>
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Servidor Conectado</span>
            </div>
        </div>
    </footer>

    @if ($activeSurvey)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const publicTrendChartCtx = document.getElementById('publicTrendChart');

                if (publicTrendChartCtx) {
                    const ctx = publicTrendChartCtx.getContext('2d');
                    
                    // Create beautiful gradient colors for high-tech look
                    const primaryGrad = ctx.createLinearGradient(0, 0, 0, 300);
                    primaryGrad.addColorStop(0, 'rgba(37, 99, 235, 0.85)'); // Blue-600
                    primaryGrad.addColorStop(1, 'rgba(59, 130, 246, 0.25)'); // Blue-500

                    const borderGradient = ctx.createLinearGradient(0, 0, 0, 300);
                    borderGradient.addColorStop(0, '#3b82f6');
                    borderGradient.addColorStop(1, '#93c5fd');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($chartLabels),
                            datasets: [{
                                label: 'Voto ponderado',
                                data: @json($chartValues),
                                backgroundColor: primaryGrad,
                                borderColor: borderGradient,
                                borderWidth: 1.5,
                                borderRadius: {
                                    topLeft: 12,
                                    topRight: 12,
                                    bottomLeft: 4,
                                    bottomRight: 4
                                },
                                borderSkipped: false
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    titleFont: {
                                        family: 'Outfit',
                                        weight: 'bold',
                                        size: 13
                                    },
                                    bodyFont: {
                                        family: 'JetBrains Mono',
                                        size: 12
                                    },
                                    padding: 12,
                                    cornerRadius: 12,
                                    borderColor: 'rgba(255,255,255,0.08)',
                                    borderWidth: 1
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#e2e8f0',
                                        drawTicks: false
                                    },
                                    ticks: {
                                        font: {
                                            family: 'JetBrains Mono',
                                            size: 11
                                        },
                                        color: '#94a3b8'
                                    },
                                    border: {
                                        dash: [5, 5]
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            family: 'Outfit',
                                            weight: 'bold',
                                            size: 12
                                        },
                                        color: '#64748b'
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endif
</body>
</html>
