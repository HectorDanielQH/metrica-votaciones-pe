@extends('adminlte::page')

@section('title', 'Registro de voto')

@section('content_header')
@stop

@section('adminlte_css')
    <style>
        .main-header,
        .main-sidebar,
        .main-footer,
        .content-header {
            display: none !important;
        }

        .content-wrapper,
        .content,
        .content .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100vh !important;
            background: linear-gradient(135deg, #eef5ff 0%, #dbe9ff 45%, #f8fbff 100%);
        }

        .surveyor-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1rem;
        }

        .surveyor-screen {
            display: none;
            width: 100%;
        }

        .surveyor-screen.is-active {
            display: block;
        }

        .horizontal-app-card {
            border: 0;
            border-radius: 24px;
            box-shadow: 0 20px 55px rgba(13, 74, 161, 0.14);
            overflow: hidden;
        }

        .hero-counter {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .hero-box {
            background: #f7faff;
            border: 2px solid #d7e6ff;
            border-radius: 18px;
            padding: 1rem;
            text-align: center;
        }

        .hero-box__value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
            color: #0d47a1;
        }

        .app-btn {
            width: 100%;
            min-height: 118px;
            border-radius: 22px;
            border: 0;
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: .04em;
            transition: transform .15s ease, box-shadow .15s ease;
            box-shadow: 0 16px 32px rgba(13, 74, 161, 0.16);
        }

        .app-btn:hover,
        .app-btn:focus {
            transform: translateY(-2px);
        }

        .app-btn-start {
            background: linear-gradient(135deg, #0050d7 0%, #2f7cff 100%);
            color: #fff;
        }

        .app-btn-docente {
            background: linear-gradient(135deg, #0b7a5c 0%, #25b88d 100%);
            color: #fff;
        }

        .app-btn-estudiante {
            background: linear-gradient(135deg, #d98c00 0%, #ffb020 100%);
            color: #fff;
        }

        .respondent-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .respondent-choice {
            position: relative;
            overflow: hidden;
            text-align: left;
            padding: 1.25rem 1.25rem 1.15rem;
        }

        .respondent-choice::after {
            content: '';
            position: absolute;
            inset: auto -30px -30px auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .respondent-choice__eyebrow {
            display: inline-block;
            margin-bottom: .65rem;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            opacity: .9;
        }

        .respondent-choice__title {
            display: block;
            font-size: 1.7rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: .45rem;
        }

        .respondent-choice__desc {
            display: block;
            max-width: 16rem;
            font-size: .98rem;
            line-height: 1.35;
            opacity: .95;
        }

        .respondent-choice__icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.8rem;
            opacity: .9;
        }

        .candidate-option {
            border: 0;
            width: 100%;
            padding: 0;
            background: transparent;
            text-align: inherit;
        }

        .candidate-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .candidate-card {
            border-radius: 20px;
            border: 3px solid transparent;
            min-height: 100%;
            overflow: hidden;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.10);
            transition: transform .15s ease, border-color .15s ease;
            background: #fff;
        }

        .candidate-option:hover .candidate-card,
        .candidate-option:focus .candidate-card {
            transform: translateY(-3px);
            border-color: #2f7cff;
        }

        .candidate-option:active .candidate-card {
            transform: scale(.985);
        }

        .candidate-card__head {
            background: #edf4ff;
            padding: .9rem 1rem;
            text-align: center;
            font-weight: 800;
            color: #173e7a;
        }

        .candidate-card__body {
            padding: 1rem;
            text-align: center;
        }

        .candidate-logo {
            max-height: 100px;
            object-fit: contain;
        }

        .candidate-avatar {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #edf4ff;
        }

        .screen-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #14335f;
            line-height: 1.1;
        }

        .screen-subtitle {
            font-size: .95rem;
            color: #577093;
        }

        .candidate-meta-title {
            font-size: .9rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            color: #7b8faa;
        }

        .confirm-shell {
            max-width: 720px;
        }

        .confirm-candidate {
            background: #f7faff;
            border: 2px solid #d7e6ff;
            border-radius: 20px;
            padding: 1rem;
        }

        .confirm-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: .85rem;
        }

        .confirm-btn {
            min-height: 72px;
            border-radius: 18px;
            font-size: 1.15rem;
            font-weight: 800;
            border: 0;
        }

        .confirm-btn-back {
            background: #eaf1fb;
            color: #21446f;
        }

        .confirm-btn-save {
            background: linear-gradient(135deg, #0050d7 0%, #2f7cff 100%);
            color: #fff;
        }

        .app-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .ghost-btn {
            min-height: 48px;
            border: 0;
            border-radius: 14px;
            padding: 0 1rem;
            font-weight: 700;
            color: #31557f;
            background: #ebf2fc;
        }

        .logout-btn {
            min-height: 48px;
            border: 0;
            border-radius: 14px;
            padding: 0 1rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #b42318 0%, #e74c3c 100%);
            box-shadow: 0 10px 20px rgba(183, 35, 24, 0.18);
        }

        .logout-form {
            margin: 0;
        }

        @media (max-width: 575.98px) {
            .surveyor-shell {
                padding: .75rem;
            }

            .horizontal-app-card {
                border-radius: 18px;
            }

            .hero-counter {
                grid-template-columns: 1fr;
            }

            .hero-box__value {
                font-size: 1.65rem;
            }

            .app-btn {
                min-height: 104px;
                font-size: 1.35rem;
                border-radius: 18px;
            }

            .respondent-choice {
                min-height: 122px;
                padding: 1rem 1rem 1rem;
            }

            .respondent-choice__title {
                font-size: 1.45rem;
            }

            .respondent-choice__desc {
                font-size: .9rem;
                max-width: 13rem;
            }

            .screen-title {
                font-size: 1.4rem;
            }

            .screen-subtitle {
                font-size: .9rem;
            }

            .candidate-card__body h5 {
                font-size: 1rem;
            }

            .candidate-card__body h6 {
                font-size: .92rem;
            }

            .candidate-logo {
                max-height: 86px;
            }

            .candidate-avatar {
                width: 68px;
                height: 68px;
            }
        }

        @media (min-width: 768px) {
            .confirm-actions {
                grid-template-columns: 1fr 1fr;
            }

            .respondent-grid {
                grid-template-columns: 1fr 1fr;
            }

            .candidate-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 992px) and (orientation: landscape) {
            .surveyor-shell {
                padding: 2rem 2.75rem;
            }

            .start-layout {
                display: grid;
                grid-template-columns: 1.15fr .85fr;
                gap: 1.5rem;
                align-items: center;
            }

            .app-btn {
                min-height: 140px;
                font-size: 1.9rem;
            }

            .respondent-choice__title {
                font-size: 1.95rem;
            }

            .screen-title {
                font-size: 2rem;
            }

            .candidate-logo {
                max-height: 120px;
            }

            .candidate-avatar {
                width: 88px;
                height: 88px;
            }
        }
    </style>
@stop

@section('content')
    <div class="surveyor-shell">
        @if (session('success'))
            <div class="alert alert-success mx-auto mb-4" style="max-width: 1100px; width: 100%;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mx-auto mb-4" style="max-width: 1100px; width: 100%;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="surveyor-screen is-active" data-screen="start">
            <div class="card horizontal-app-card mx-auto" style="max-width: 1100px; width: 100%;">
                <div class="card-body p-4 p-md-5">
                    <div class="app-topbar mb-4">
                        <div class="text-muted small">
                            Encuestador autenticado
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt mr-1"></i> Cerrar sesion
                            </button>
                        </form>
                    </div>

                    <div class="start-layout">
                        <div>
                            <div class="screen-title mb-3">Encuesta activa</div>
                            <p class="screen-subtitle mb-4">
                                {{ $activeSurvey?->name ?? 'No hay encuesta activa disponible.' }}
                            </p>
                            <button
                                type="button"
                                class="app-btn app-btn-start"
                                id="startSurveyButton"
                                {{ $activeSurvey && $candidacies->isNotEmpty() ? '' : 'disabled' }}>
                                INICIAR ENCUESTA
                            </button>
                            @if (! $activeSurvey)
                                <div class="alert alert-warning mt-4 mb-0">
                                    No existe una encuesta activa. Solicita al administrador habilitar una.
                                </div>
                            @elseif ($candidacies->isEmpty())
                                <div class="alert alert-warning mt-4 mb-0">
                                    No existen candidaturas activas para registrar respuestas.
                                </div>
                            @endif
                        </div>

                        <div class="hero-counter mt-4 mt-lg-0">
                            <div class="hero-box">
                                <div class="text-muted mb-2">Personas encuestadas</div>
                                <div class="hero-box__value">{{ $totalCount }}</div>
                            </div>
                            <div class="hero-box">
                                <div class="text-muted mb-2">Registradas hoy</div>
                                <div class="hero-box__value">{{ $todayCount }}</div>
                            </div>
                            <div class="hero-box" style="grid-column: 1 / -1;">
                                <div class="text-muted mb-2">Fecha de registro</div>
                                <div class="hero-box__value" style="font-size: 1.35rem;">
                                    {{ now()->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="surveyor-screen" data-screen="type">
            <div class="card horizontal-app-card mx-auto" style="max-width: 1100px; width: 100%;">
                <div class="card-body p-4 p-md-5">
                    <div class="app-topbar">
                        <button type="button" class="ghost-btn" data-go-screen="start">Volver</button>
                        <span class="text-muted small">Paso 1 de 2</span>
                    </div>
                    <div class="screen-title mb-2">Selecciona el tipo de persona</div>
                    <p class="screen-subtitle mb-4">Elige una opcion para continuar con el registro.</p>
                    <div class="respondent-grid">
                        <div>
                            <button type="button" class="app-btn app-btn-docente respondent-choice js-respondent-choice" data-value="docente">
                                <span class="respondent-choice__eyebrow">Encuesta academica</span>
                                <span class="respondent-choice__title">DOCENTE</span>
                                <span class="respondent-choice__desc">Registrar la respuesta de un profesor o docente.</span>
                                <span class="respondent-choice__icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </span>
                            </button>
                        </div>
                        <div>
                            <button type="button" class="app-btn app-btn-estudiante respondent-choice js-respondent-choice" data-value="estudiante">
                                <span class="respondent-choice__eyebrow">Encuesta academica</span>
                                <span class="respondent-choice__title">ESTUDIANTE</span>
                                <span class="respondent-choice__desc">Registrar la respuesta de un estudiante.</span>
                                <span class="respondent-choice__icon">
                                    <i class="fas fa-user-graduate"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="surveyor-screen" data-screen="candidate">
            <div class="card horizontal-app-card mx-auto" style="max-width: 1280px; width: 100%;">
                <div class="card-body p-4">
                    <div class="app-topbar">
                        <button type="button" class="ghost-btn" data-go-screen="type">Volver</button>
                        <span class="text-muted small">Paso 2 de 2</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start mb-4 flex-column flex-md-row">
                        <div>
                            <div class="screen-title mb-2">Selecciona un candidato</div>
                            <p class="screen-subtitle mb-0">Toca una tarjeta y luego confirma tu seleccion antes de guardar.</p>
                        </div>
                        <div class="text-left text-md-right mt-3 mt-md-0">
                            <div class="candidate-meta-title">Tipo seleccionado</div>
                            <div class="font-weight-bold" id="selectedRespondentTypeLabel">-</div>
                        </div>
                    </div>

                    <form action="{{ route('surveyor.votes.store') }}" method="POST" id="voteRecordForm">
                        @csrf
                        <input type="hidden" name="respondent_type" id="respondentTypeField" value="">
                        <input type="hidden" name="candidacy_id" id="candidacyField" value="">
                    </form>

                    <div class="candidate-grid">
                        @forelse ($candidacies as $candidacy)
                            <div>
                                <button
                                    type="button"
                                    class="candidate-option js-candidate-option"
                                    data-candidacy-id="{{ $candidacy->id }}"
                                    data-party-name="{{ $candidacy->party_name }}"
                                    data-primary-name="{{ $candidacy->primary_candidate_name }}"
                                    data-secondary-name="{{ $candidacy->secondary_candidate_name }}">
                                    <div class="candidate-card">
                                        <div class="candidate-card__head">{{ $candidacy->party_name }}</div>
                                        <div class="candidate-card__body">
                                            <img
                                                src="{{ asset('storage/' . $candidacy->party_logo_path) }}"
                                                alt="Logo del partido"
                                                class="img-fluid img-thumbnail candidate-logo mb-3">

                                            @if ($candidacy->primary_candidate_photo_path)
                                                <img
                                                    src="{{ asset('storage/' . $candidacy->primary_candidate_photo_path) }}"
                                                    alt="Candidato principal"
                                                    class="candidate-avatar mb-2">
                                            @endif
                                            <h5 class="mb-1">{{ $candidacy->primary_candidate_name }}</h5>
                                            <p class="text-muted mb-3">Principal</p>

                                            @if ($candidacy->secondary_candidate_photo_path)
                                                <img
                                                    src="{{ asset('storage/' . $candidacy->secondary_candidate_photo_path) }}"
                                                    alt="Candidato secundario"
                                                    class="candidate-avatar mb-2"
                                                    style="width: 76px; height: 76px;">
                                            @endif
                                            <h6 class="mb-1">{{ $candidacy->secondary_candidate_name }}</h6>
                                            <p class="text-muted mb-0">Secundario</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @empty
                            <div>
                                <div class="alert alert-light border">
                                    No hay candidaturas activas registradas. Solicita al administrador que cargue los candidatos antes de continuar.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="surveyor-screen" data-screen="confirm">
            <div class="card horizontal-app-card confirm-shell mx-auto" style="width: 100%;">
                <div class="card-body p-4 p-md-5">
                    <div class="app-topbar">
                        <button type="button" class="ghost-btn" data-go-screen="candidate">Cambiar candidato</button>
                        <span class="text-muted small">Confirmacion</span>
                    </div>

                    <div class="screen-title mb-2">Confirma tu seleccion</div>
                    <p class="screen-subtitle mb-4">Revisa cuidadosamente antes de guardar para evitar errores de dedo.</p>

                    <div class="confirm-candidate mb-4">
                        <div class="candidate-meta-title mb-2">Tipo de persona</div>
                        <h4 class="mb-3" id="confirmRespondentType">-</h4>

                        <div class="candidate-meta-title mb-2">Votaste por el candidato</div>
                        <h3 class="mb-1" id="confirmPrimaryCandidate">-</h3>
                        <p class="text-muted mb-3" id="confirmPartyName">-</p>

                        <div class="candidate-meta-title mb-2">Candidato secundario</div>
                        <h5 class="mb-0" id="confirmSecondaryCandidate">-</h5>
                    </div>

                    <div class="confirm-actions">
                        <button type="button" class="confirm-btn confirm-btn-back" data-go-screen="candidate">
                            NO, QUIERO CORREGIR
                        </button>
                        <button type="button" class="confirm-btn confirm-btn-save" id="confirmVoteButton">
                            SI, REGISTRAR VOTO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        const screens = {
            start: document.querySelector('[data-screen="start"]'),
            type: document.querySelector('[data-screen="type"]'),
            candidate: document.querySelector('[data-screen="candidate"]'),
            confirm: document.querySelector('[data-screen="confirm"]'),
        };

        const startButton = document.getElementById('startSurveyButton');
        const respondentTypeField = document.getElementById('respondentTypeField');
        const candidacyField = document.getElementById('candidacyField');
        const voteRecordForm = document.getElementById('voteRecordForm');
        const selectedRespondentTypeLabel = document.getElementById('selectedRespondentTypeLabel');
        const respondentButtons = document.querySelectorAll('.js-respondent-choice');
        const candidateButtons = document.querySelectorAll('.js-candidate-option');
        const goScreenButtons = document.querySelectorAll('[data-go-screen]');
        const confirmVoteButton = document.getElementById('confirmVoteButton');
        const confirmRespondentType = document.getElementById('confirmRespondentType');
        const confirmPrimaryCandidate = document.getElementById('confirmPrimaryCandidate');
        const confirmSecondaryCandidate = document.getElementById('confirmSecondaryCandidate');
        const confirmPartyName = document.getElementById('confirmPartyName');
        const logoutForms = document.querySelectorAll('.logout-form');

        let selectedCandidate = null;

        function showScreen(name) {
            Object.values(screens).forEach((screen) => {
                if (!screen) {
                    return;
                }

                screen.classList.remove('is-active');
            });

            screens[name]?.classList.add('is-active');
        }

        startButton?.addEventListener('click', () => {
            showScreen('type');
        });

        goScreenButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.goScreen;
                showScreen(target);
            });
        });

        respondentButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const respondentType = button.dataset.value;
                respondentTypeField.value = respondentType;
                selectedRespondentTypeLabel.textContent = respondentType.toUpperCase();
                showScreen('candidate');
            });
        });

        candidateButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (!respondentTypeField.value) {
                    showScreen('type');
                    return;
                }

                selectedCandidate = {
                    id: button.dataset.candidacyId,
                    partyName: button.dataset.partyName,
                    primaryName: button.dataset.primaryName,
                    secondaryName: button.dataset.secondaryName,
                };

                candidacyField.value = selectedCandidate.id;
                confirmRespondentType.textContent = respondentTypeField.value.toUpperCase();
                confirmPrimaryCandidate.textContent = selectedCandidate.primaryName;
                confirmSecondaryCandidate.textContent = selectedCandidate.secondaryName;
                confirmPartyName.textContent = selectedCandidate.partyName;
                showScreen('confirm');
            });
        });

        confirmVoteButton?.addEventListener('click', () => {
            if (!respondentTypeField.value || !candidacyField.value || !selectedCandidate) {
                showScreen('start');
                return;
            }

            voteRecordForm.submit();
        });

        logoutForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                const confirmed = window.confirm('¿Estas seguro de cerrar sesion?');

                if (!confirmed) {
                    event.preventDefault();
                }
            });
        });
    </script>
@stop
