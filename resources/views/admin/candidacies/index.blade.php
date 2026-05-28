@extends('adminlte::page')

@section('title', 'Candidaturas')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="mb-1">Candidaturas</h1>
            <p class="text-muted mb-0">Registra partidos politicos y el binomio principal de candidatos.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.candidacies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Nueva candidatura
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse ($candidacies as $candidacy)
            <div class="col-lg-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $candidacy->party_name }}</h3>
                        <div class="card-tools">
                            <span class="badge badge-{{ $candidacy->status === 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($candidacy->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img
                                    src="{{ asset('storage/' . $candidacy->party_logo_path) }}"
                                    alt="Logo del partido"
                                    class="img-fluid img-thumbnail mb-3"
                                    style="max-height: 180px;">
                                <div class="small text-muted">Logo del partido</div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-sm-6 text-center">
                                        @if ($candidacy->primary_candidate_photo_path)
                                            <img
                                                src="{{ asset('storage/' . $candidacy->primary_candidate_photo_path) }}"
                                                alt="Candidato principal"
                                                class="img-fluid img-thumbnail mb-2"
                                                style="max-height: 140px;">
                                        @endif
                                        <h5 class="mb-1">{{ $candidacy->primary_candidate_name }}</h5>
                                        <p class="text-muted mb-0">Candidato principal</p>
                                    </div>
                                    <div class="col-sm-6 text-center">
                                        @if ($candidacy->secondary_candidate_photo_path)
                                            <img
                                                src="{{ asset('storage/' . $candidacy->secondary_candidate_photo_path) }}"
                                                alt="Candidato secundario"
                                                class="img-fluid img-thumbnail mb-2"
                                                style="max-height: 140px;">
                                        @endif
                                        <h5 class="mb-1">{{ $candidacy->secondary_candidate_name }}</h5>
                                        <p class="text-muted mb-0">Candidato secundario</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.candidacies.edit', $candidacy) }}" class="btn btn-sm btn-outline-primary">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border">
                    Aun no existen candidaturas registradas.
                </div>
            </div>
        @endforelse
    </div>
@stop
