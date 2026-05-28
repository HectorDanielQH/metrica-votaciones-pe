@extends('adminlte::page')

@section('title', 'Registro de Voto')

@section('content_header')
@stop

@section('adminlte_css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        /* Hide AdminLTE default UI layout elements to create a standalone full-screen web app */
        .main-header,
        .main-sidebar,
        .main-footer,
        .content-header {
            display: none !important;
        }

        :root {
            --bg-color: #f4f6fa;
            --card-bg: #ffffff;
            --primary: #0f5cc0;
            --primary-grad: linear-gradient(135deg, #0d2b49 0%, #0f5cc0 52%, #20a4f3 100%);
            --success-grad: linear-gradient(135deg, #0b7a5c 0%, #1eb980 100%);
            --warning-grad: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 10px 30px rgba(15, 36, 63, 0.04);
            --flutter-blue: #02569b;
            --flutter-sky: #0175c2;
        }

        body {
            font-family: 'Outfit', sans-serif !important;
            background-color: var(--bg-color) !important;
            color: var(--text-color) !important;
        }

        .content-wrapper,
        .content,
        .content .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100vh !important;
            background: transparent !important;
        }

        /* APK / Flutter mobile simulator view shell (Uniform Light Theme) */
        .surveyor-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1rem;
            max-width: 560px; /* Wider to fit 3-column banner grid, still centered */
            margin: 0 auto;
            background-color: var(--bg-color);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        /* Hide menus/headers during Voter Kiosk Mode for security and simplicity */
        .kiosk-mode .app-topbar,
        .kiosk-mode .step-indicator-bar {
            display: none !important;
        }

        /* Flutter-like AppBar design */
        .app-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.5rem 0;
            margin-bottom: 1.25rem;
            border-bottom: 0;
        }

        .app-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .app-brand__logo {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(2, 86, 155, 0.2);
            object-fit: contain;
        }

        .app-brand__title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--flutter-sky);
            margin: 0;
            letter-spacing: -0.02em;
        }

        /* Material 3 Step Progress Bar */
        .step-indicator-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin: 0 auto 1.5rem;
            padding: 0 0.25rem;
            position: relative;
        }

        .step-dot {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .step-dot .icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .step-dot span {
            margin-top: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            transition: all 0.3s ease;
        }

        .step-line {
            flex-grow: 1;
            height: 3px;
            background: var(--border-color);
            margin: -20px 6px 0;
            z-index: 1;
            border-radius: 2px;
            position: relative;
        }

        .step-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: var(--flutter-sky);
            transition: width 0.4s ease;
        }

        .step-line.filled::after {
            width: 100%;
        }

        /* Active & Completed States */
        .step-dot.active .icon-wrap {
            background: linear-gradient(135deg, var(--flutter-sky) 0%, var(--flutter-blue) 100%);
            border-color: transparent;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(1, 117, 194, 0.25);
        }

        .step-dot.active span {
            color: var(--flutter-sky);
            font-weight: 800;
        }

        .step-dot.completed .icon-wrap {
            background: #e0f2fe;
            border-color: #bae6fd;
            color: var(--flutter-sky);
        }

        .step-dot.completed span {
            color: var(--text-color);
        }

        /* Screen containers */
        .surveyor-screen {
            display: none;
            width: 100%;
            animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .surveyor-screen.is-active {
            display: block;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Material 3 Card Styling */
        .flutter-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color) !important;
            border-radius: 28px !important;
            box-shadow: var(--shadow) !important;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .screen-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text-color);
            line-height: 1.2;
            margin-bottom: 0.45rem;
            letter-spacing: -0.01em;
        }

        .screen-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            font-weight: 400;
        }

        /* Welcome section */
        .welcome-hero {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
            padding: 0 4px;
        }

        .welcome-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #ebf5ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--flutter-sky);
            font-size: 1.35rem;
            border: 1px solid var(--border-color);
        }

        .welcome-text h2 {
            font-size: 1.3rem;
            font-weight: 800;
            margin: 0;
            color: var(--text-color);
            letter-spacing: -0.01em;
        }

        .welcome-text p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Stats in Material 3 Card layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .stat-card__icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 0.5rem;
        }

        .stat-card__icon.blue {
            background: #e0f2fe;
            color: #0284c7;
        }

        .stat-card__icon.purple {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .stat-card__value {
            font-size: 1.85rem;
            font-weight: 900;
            color: var(--text-color);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-card__label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Flutter APK style Extended Top Action Button */
        .btn-giant {
            width: 100%;
            min-height: 72px;
            border-radius: 24px;
            border: 0;
            font-size: 1.35rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(2, 86, 155, 0.2);
            transition: all 0.2s ease;
            color: #fff;
            background: linear-gradient(135deg, var(--flutter-sky) 0%, var(--flutter-blue) 100%);
            letter-spacing: 0.02em;
        }

        .btn-giant:hover,
        .btn-giant:focus {
            box-shadow: 0 12px 28px rgba(2, 86, 155, 0.3);
            color: #fff;
            outline: none;
        }

        .btn-giant:active {
            transform: scale(0.96);
        }

        .btn-giant:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        /* Respondent type screen */
        .respondent-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .respondent-btn {
            border: 0;
            border-radius: 24px;
            padding: 1.75rem 1.25rem;
            text-align: center;
            transition: all 0.2s ease;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            min-height: 160px;
        }

        .respondent-btn::after {
            content: '';
            position: absolute;
            inset: auto -20px -20px auto;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .respondent-btn:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        }

        .respondent-btn:active {
            transform: scale(0.96);
        }

        .respondent-btn--docente {
            background: var(--success-grad);
        }

        .respondent-btn--estudiante {
            background: var(--warning-grad);
        }

        .respondent-btn__icon {
            font-size: 2.75rem;
            margin-bottom: 0.75rem;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .respondent-btn__title {
            font-size: 1.5rem;
            font-weight: 900;
            margin: 0 0 0.25rem;
            letter-spacing: 0.02em;
        }

        .respondent-btn__desc {
            font-size: 0.85rem;
            opacity: 0.9;
            margin: 0;
            max-width: 220px;
            line-height: 1.3;
        }

        /* Candidate list screen */
        .selection-indicator-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .selection-indicator-tag.docente {
            background: #e6f4ea;
            color: #137333;
            border: 1px solid rgba(19, 115, 51, 0.2);
        }

        .selection-indicator-tag.estudiante {
            background: #fef7e0;
            color: #b06000;
            border: 1px solid rgba(176, 96, 0, 0.2);
        }

        .candidate-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 420px) {
            .candidate-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.75rem;
            }
        }

        .candidate-card-btn {
            border: 0;
            background: transparent;
            padding: 0;
            width: 100%;
            text-align: left;
        }

        /* BANNER-STYLE CANDIDATE CARD — Full image visible */
        .candidate-card {
            background: #ffffff !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 18px !important;
            overflow: hidden !important;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow) !important;
            transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .candidate-card-btn:hover .candidate-card,
        .candidate-card-btn:focus .candidate-card {
            transform: translateY(-4px) scale(1.02);
            border-color: var(--flutter-sky) !important;
            box-shadow: 0 12px 25px rgba(1, 117, 194, 0.14) !important;
        }

        .candidate-card-btn:active .candidate-card {
            transform: scale(0.97);
        }

        /* Banner image area — shows the FULL candidate photo/banner */
        .candidate-card__banner {
            width: 100%;
            aspect-ratio: 3 / 4;
            background-color: #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .candidate-card__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            display: block;
            transition: transform 0.4s ease;
        }

        .candidate-card-btn:hover .candidate-card__img {
            transform: scale(1.05);
        }

        .candidate-card__image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 2rem;
            gap: 4px;
        }

        .candidate-card__image-placeholder span {
            font-size: 0.55rem;
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* Party logo seal overlaid on the banner image */
        .candidate-card__party-logo-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            z-index: 3;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: #fff;
            padding: 2px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .candidate-card__party-logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Info section below the banner */
        .candidate-card__info-banner {
            padding: 0.6rem 0.5rem 0.55rem;
            text-align: center;
            background: #fff;
        }

        .candidate-card__party-name {
            font-size: 0.55rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .candidate-card__primary-name {
            font-size: 0.8rem;
            font-weight: 850;
            color: var(--text-color);
            margin: 0 0 1px;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .candidate-card__secondary-name {
            font-size: 0.55rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.35rem;
        }

        /* VOTAR action pill */
        .candidate-card__action-btn {
            background: var(--primary-grad);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-block;
            width: 90%;
            margin: 0 auto;
            box-shadow: 0 3px 8px rgba(15, 92, 192, 0.15);
            transition: all 0.2s ease;
        }

        .candidate-card-btn:hover .candidate-card__action-btn {
            background: linear-gradient(135deg, #20a4f3 0%, #0f5cc0 100%);
            box-shadow: 0 4px 12px rgba(15, 92, 192, 0.25);
            transform: scale(1.03);
        }

        @media (max-width: 420px) {
            .candidate-card__primary-name {
                font-size: 0.72rem;
            }
            .candidate-card__party-name,
            .candidate-card__secondary-name {
                font-size: 0.5rem;
            }
            .candidate-card__action-btn {
                font-size: 0.6rem;
                padding: 4px 8px;
            }
            .candidate-card__party-logo-badge {
                width: 26px;
                height: 26px;
            }
        }

        /* Confirm Screen Receipt styling (Uniform Light Theme) */
        .confirm-wrapper {
            width: 100%;
        }

        .digital-ticket {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 1.75rem;
            box-shadow: var(--shadow);
            position: relative;
            margin-bottom: 1.25rem;
        }

        .digital-ticket__header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px dashed var(--border-color);
        }

        .success-seal {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f0fdf4;
            color: #16a34a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.1);
        }

        .digital-ticket__title {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
            color: var(--text-color);
        }

        .ticket-item {
            margin-bottom: 1rem;
        }

        .ticket-item__label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .ticket-item__value {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-color);
        }

        .ticket-item__badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 800;
        }

        .ticket-item__badge.docente {
            background: #e6f4ea;
            color: #137333;
            border: 1px solid rgba(19, 115, 51, 0.2);
        }

        .ticket-item__badge.estudiante {
            background: #fef7e0;
            color: #b06000;
            border: 1px solid rgba(176, 96, 0, 0.2);
        }

        .confirm-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .btn-action-primary {
            min-height: 60px;
            border-radius: 20px;
            font-size: 1.15rem;
            font-weight: 800;
            border: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #fff;
            background: var(--success-grad);
            box-shadow: 0 6px 18px rgba(22, 163, 74, 0.2);
            transition: all 0.2s ease;
        }

        .btn-action-primary:hover,
        .btn-action-primary:focus {
            box-shadow: 0 8px 22px rgba(22, 163, 74, 0.3);
            color: #fff;
            outline: none;
        }

        .btn-action-primary:active {
            transform: scale(0.96);
        }

        .btn-action-secondary {
            min-height: 56px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 700;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-action-secondary:hover {
            background: #f8fafc;
        }

        .btn-action-secondary:active {
            transform: scale(0.97);
        }

        /* Navigation elements & alerts */
        .ghost-back-btn {
            background: #e2e8f0;
            border: 0;
            color: var(--text-color);
            font-weight: 800;
            border-radius: 12px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .ghost-back-btn:hover {
            background: #cbd5e1;
        }

        .top-action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .logout-btn-outline {
            background: transparent;
            border: 1px solid rgba(225, 29, 72, 0.2);
            border-radius: 12px;
            padding: 6px 12px;
            font-weight: 800;
            color: #e11d48;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .logout-btn-outline:hover {
            background: rgba(225, 29, 72, 0.05);
            border-color: #e11d48;
        }

        .alert {
            border-radius: 20px;
            border: 0;
            padding: 0.85rem 1.1rem;
        }

        .alert-success {
            background-color: #f0fdf4 !important;
            color: #16a34a !important;
            border-left: 4px solid #16a34a !important;
        }

        .alert-danger {
            background-color: #fef2f2 !important;
            color: #dc2626 !important;
            border-left: 4px solid #dc2626 !important;
        }

        .alert-warning {
            background-color: #fffbeb !important;
            color: #d97706 !important;
            border-left: 4px solid #d97706 !important;
        }

        /* Force responsive text hidden on small screens */
        @media (max-width: 360px) {
            .step-dot span {
                display: none;
            }
        }
    </style>
@stop

@section('content')
    <div class="surveyor-shell">
        
        <!-- Standalone Flutter Appbar -->
        <div class="app-topbar">
            <div class="app-brand">
                <img class="app-brand__logo" src="{{ asset('images/met-ed.webp') }}" alt="Métrica logo">
                <h1 class="app-brand__title">Métrica Electoral</h1>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn-outline">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Error:</strong>
                <ul class="mb-0 mt-1 pl-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Flutter Step Progress Bar -->
        <div class="step-indicator-bar">
            <div class="step-dot active" id="dot-start" data-go-screen="start">
                <div class="icon-wrap"><i class="fas fa-home"></i></div>
                <span>Inicio</span>
            </div>
            <div class="step-line" id="line-start"></div>
            <div class="step-dot" id="dot-type" data-go-screen="type">
                <div class="icon-wrap"><i class="fas fa-user-friends"></i></div>
                <span>Estamento</span>
            </div>
            <div class="step-line" id="line-type"></div>
            <div class="step-dot" id="dot-candidate" data-go-screen="candidate">
                <div class="icon-wrap"><i class="fas fa-user-tie"></i></div>
                <span>Candidato</span>
            </div>
            <div class="step-line" id="line-candidate"></div>
            <div class="step-dot" id="dot-confirm" data-go-screen="confirm">
                <div class="icon-wrap"><i class="fas fa-check-circle"></i></div>
                <span>Confirmar</span>
            </div>
        </div>

        <!-- SCREEN: START (DASHBOARD) -->
        <div class="surveyor-screen is-active" data-screen="start">
            <div class="welcome-hero">
                <div class="welcome-avatar">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="welcome-text">
                    <h2>¡Hola, {{ Auth::user()->name }}! 👋</h2>
                    <p>Listo en campo para recolectar datos</p>
                </div>
            </div>

            <!-- PRIMARY HIGH ACCESS ACTION BUTTON AT THE TOP (FLUTTER STYLE) -->
            <div class="mb-3">
                <button
                    type="button"
                    class="btn-giant"
                    id="startSurveyButton"
                    {{ $activeSurvey && $candidacies->isNotEmpty() ? '' : 'disabled' }}>
                    <i class="fas fa-plus-circle"></i> NUEVO REGISTRO
                </button>
            </div>

            <!-- SURVEY DETAILS CARD (FLUTTER FLAT CARD) -->
            <div class="card horizontal-app-card flutter-card">
                <div class="card-body p-4">
                    <div class="screen-title mb-1" style="font-size: 1.15rem;">
                        <i class="fas fa-poll text-primary mr-1"></i> Encuesta Activa
                    </div>
                    <p class="screen-subtitle mb-0" style="font-size: 0.9rem;">
                        {{ $activeSurvey?->name ?? 'No hay encuesta activa disponible.' }}
                    </p>

                    @if (! $activeSurvey)
                        <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-info-circle mr-1"></i> No existe una encuesta activa.
                        </div>
                    @elseif ($candidacies->isEmpty())
                        <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-info-circle mr-1"></i> No existen candidaturas disponibles.
                        </div>
                    @endif
                </div>
            </div>

            <!-- MY DAILY PERFORMANCE CARD (FLUTTER FLAT CARD) -->
            <div class="card horizontal-app-card flutter-card">
                <div class="card-body p-4">
                    <div class="screen-title mb-3" style="font-size: 1.15rem;">
                        <i class="fas fa-chart-line text-success mr-1"></i> Mi Rendimiento
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-card__icon blue">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="stat-card__value">{{ $totalCount }}</div>
                            <div class="stat-card__label">Histórico</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card__icon purple">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-card__value">{{ $todayCount }}</div>
                            <div class="stat-card__label">De hoy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCREEN: TYPE (RESPONDENT TYPE) -->
        <div class="surveyor-screen" data-screen="type">
            <div class="top-action-bar">
                <button type="button" class="ghost-back-btn" data-go-screen="start">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <span class="text-muted font-weight-bold" style="font-size: 0.85rem;">Paso 1 de 2</span>
            </div>

            <div class="screen-title text-center">Tipo de persona</div>
            <p class="screen-subtitle text-center">Elige la condición del encuestado para continuar.</p>

            <div class="respondent-grid">
                <button type="button" class="respondent-btn respondent-btn--docente js-respondent-choice" data-value="docente">
                    <span class="respondent-btn__icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </span>
                    <span class="respondent-btn__title">DOCENTE</span>
                    <span class="respondent-btn__desc">Voto de profesor o personal docente de la institución.</span>
                </button>

                <button type="button" class="respondent-btn respondent-btn--estudiante js-respondent-choice" data-value="estudiante">
                    <span class="respondent-btn__icon">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                    <span class="respondent-btn__title">ESTUDIANTE</span>
                    <span class="respondent-btn__desc">Voto de alumno o estudiante matriculado.</span>
                </button>
            </div>
        </div>

        <!-- SCREEN: CANDIDATE (Voter-Facing Ballot box mode - Clean Uniform Light Theme) -->
        <div class="surveyor-screen" data-screen="candidate">
            <div class="top-action-bar">
                <button type="button" class="ghost-back-btn" data-go-screen="type">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <span class="text-muted font-weight-bold" style="font-size: 0.85rem;">Elección Universitaria</span>
            </div>

            <div class="d-flex flex-column align-items-center mb-3 text-center">
                <div class="screen-title">Selecciona tu Candidato</div>
                <p class="screen-subtitle mb-2">Toca sobre el candidato o lista de tu preferencia.</p>
                
                <div class="selection-indicator-tag" id="respondentTypeBadge">
                    <i class="fas fa-user mr-1"></i> <span id="selectedRespondentTypeLabel">-</span>
                </div>
            </div>

            <!-- Main submission form -->
            <form action="{{ route('surveyor.votes.store') }}" method="POST" id="voteRecordForm">
                @csrf
                <input type="hidden" name="respondent_type" id="respondentTypeField" value="">
                <input type="hidden" name="candidacy_id" id="candidacyField" value="">
            </form>

            <div class="candidate-grid">
                @forelse ($candidacies as $candidacy)
                    @php
                        $photoUrl = !empty($candidacy->primary_candidate_photo_path) 
                            ? asset('storage/' . $candidacy->primary_candidate_photo_path) 
                            : null;
                    @endphp
                    <button
                        type="button"
                        class="candidate-card-btn js-candidate-option"
                        data-candidacy-id="{{ $candidacy->id }}"
                        data-party-name="{{ $candidacy->party_name }}"
                        data-primary-name="{{ $candidacy->primary_candidate_name }}"
                        data-secondary-name="{{ $candidacy->secondary_candidate_name }}">
                        
                        <div class="candidate-card">
                            <!-- Full Banner image area -->
                            <div class="candidate-card__banner">
                                <!-- Party logo seal -->
                                <div class="candidate-card__party-logo-badge">
                                    <img
                                        src="{{ asset('storage/' . $candidacy->party_logo_path) }}"
                                        alt="Logo"
                                        class="candidate-card__party-logo-img">
                                </div>

                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $candidacy->primary_candidate_name }}" class="candidate-card__img">
                                @else
                                    <div class="candidate-card__image-placeholder">
                                        <i class="fas fa-user-circle"></i>
                                        <span>Sin Foto</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info section below banner -->
                            <div class="candidate-card__info-banner">
                                <div class="candidate-card__party-name">
                                    {{ $candidacy->party_name }}
                                </div>
                                <h4 class="candidate-card__primary-name">
                                    {{ $candidacy->primary_candidate_name }}
                                </h4>
                                <div class="candidate-card__secondary-name">
                                    {{ $candidacy->secondary_candidate_name }}
                                </div>
                                <div class="candidate-card__action-btn">
                                    <span>Votar</span>
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No hay candidaturas registradas.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SCREEN: CONFIRM (Voter confirmation receipt - Clean Uniform Light Theme) -->
        <div class="surveyor-screen" data-screen="confirm">
            <div class="top-action-bar">
                <button type="button" class="ghost-back-btn" data-go-screen="candidate">
                    <i class="fas fa-arrow-left"></i> Modificar
                </button>
                <span class="text-muted font-weight-bold" style="font-size: 0.85rem;">Elección Universitaria</span>
            </div>

            <div class="confirm-wrapper">
                <div class="digital-ticket">
                    <div class="digital-ticket__header">
                        <div class="success-seal">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="digital-ticket__title">¿Confirmas tu decisión?</h3>
                        <p class="text-muted small mt-1">Por favor, verifica tus datos antes de enviar tu voto.</p>
                    </div>

                    <div class="ticket-item">
                        <div class="ticket-item__label">Categoría Estamento</div>
                        <div id="confirmRespondentTypeBadge" class="ticket-item__badge">-</div>
                    </div>

                    <div class="ticket-item">
                        <div class="ticket-item__label">Organización Política</div>
                        <div class="ticket-item__value" id="confirmPartyName">-</div>
                    </div>

                    <div class="ticket-item">
                        <div class="ticket-item__label">Candidatura Elegida</div>
                        <div class="ticket-item__value" id="confirmPrimaryCandidate" style="color: var(--flutter-sky); font-size: 1.3rem; margin-bottom: 2px;">-</div>
                        <div class="text-muted small" id="confirmSecondaryCandidate">-</div>
                    </div>
                </div>

                <div class="confirm-actions">
                    <button type="button" class="btn-action-primary" id="confirmVoteButton">
                        <i class="fas fa-save"></i> SÍ, CONFIRMAR MI VOTO
                    </button>
                    <button type="button" class="btn-action-secondary" data-go-screen="candidate">
                        <i class="fas fa-undo"></i> Cambiar selección
                    </button>
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
        const respondentTypeBadge = document.getElementById('respondentTypeBadge');
        
        const respondentButtons = document.querySelectorAll('.js-respondent-choice');
        const candidateButtons = document.querySelectorAll('.js-candidate-option');
        const goScreenButtons = document.querySelectorAll('[data-go-screen]');
        const confirmVoteButton = document.getElementById('confirmVoteButton');
        
        const confirmRespondentTypeBadge = document.getElementById('confirmRespondentTypeBadge');
        const confirmPrimaryCandidate = document.getElementById('confirmPrimaryCandidate');
        const confirmSecondaryCandidate = document.getElementById('confirmSecondaryCandidate');
        const confirmPartyName = document.getElementById('confirmPartyName');
        const logoutForms = document.querySelectorAll('.logout-form');

        let selectedCandidate = null;

        function showScreen(name) {
            Object.values(screens).forEach((screen) => {
                if (!screen) return;
                screen.classList.remove('is-active');
            });

            screens[name]?.classList.add('is-active');
            
            // Toggle kiosk-mode on surveyor-shell if entering candidate or confirm screen (Voter Kiosk Mode)
            const shell = document.querySelector('.surveyor-shell');
            if (name === 'candidate' || name === 'confirm') {
                shell.classList.add('kiosk-mode');
            } else {
                shell.classList.remove('kiosk-mode');
            }
            
            // Scroll surveyor shell back to top on step transitions
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Update step dots & lines
            const steps = ['start', 'type', 'candidate', 'confirm'];
            const activeIndex = steps.indexOf(name);
            
            steps.forEach((stepName, index) => {
                const dot = document.getElementById(`dot-${stepName}`);
                if (dot) {
                    dot.classList.remove('active', 'completed');
                    if (index === activeIndex) {
                        dot.classList.add('active');
                    } else if (index < activeIndex) {
                        dot.classList.add('completed');
                    }
                }
            });
            
            // Fill lines
            for (let i = 0; i < steps.length - 1; i++) {
                const line = document.getElementById(`line-${steps[i]}`);
                if (line) {
                    if (i < activeIndex) {
                        line.classList.add('filled');
                    } else {
                        line.classList.remove('filled');
                    }
                }
            }
        }

        startButton?.addEventListener('click', () => {
            showScreen('type');
        });

        goScreenButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.goScreen;
                
                // Block navigation if prerequisites are not met
                if (target === 'candidate' && !respondentTypeField.value) {
                    showScreen('type');
                    return;
                }
                if (target === 'confirm' && (!respondentTypeField.value || !candidacyField.value)) {
                    if (!respondentTypeField.value) {
                        showScreen('type');
                    } else {
                        showScreen('candidate');
                    }
                    return;
                }
                
                showScreen(target);
            });
        });

        respondentButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const respondentType = button.dataset.value;
                respondentTypeField.value = respondentType;
                
                // Style badge dynamically
                selectedRespondentTypeLabel.textContent = respondentType.toUpperCase();
                respondentTypeBadge.className = 'selection-indicator-tag ' + respondentType;
                
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
                
                // Populate confirm ticket
                const typeText = respondentTypeField.value.toUpperCase();
                confirmRespondentTypeBadge.textContent = typeText;
                confirmRespondentTypeBadge.className = 'ticket-item__badge ' + respondentTypeField.value;
                
                confirmPrimaryCandidate.textContent = selectedCandidate.primaryName;
                confirmSecondaryCandidate.textContent = 'Fórmula: ' + selectedCandidate.primaryName + ' + ' + selectedCandidate.secondaryName;
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
                const confirmed = window.confirm('¿Estás seguro de cerrar sesión?');
                if (!confirmed) {
                    event.preventDefault();
                }
            });
        });
    </script>
@stop
