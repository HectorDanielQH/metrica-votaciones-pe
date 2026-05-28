@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div>
        <h1 class="mb-1">{{ $title }}</h1>
        <p class="text-muted mb-0">{{ $subtitle }}</p>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Datos de la candidatura" theme="primary" icon="fas fa-flag">
                <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($formMethod !== 'POST')
                        @method($formMethod)
                    @endif

                    <div class="form-group">
                        <label for="party_name">Nombre del partido</label>
                        <input
                            type="text"
                            id="party_name"
                            name="party_name"
                            value="{{ old('party_name', $candidacy->party_name) }}"
                            class="form-control @error('party_name') is-invalid @enderror"
                            required>
                        @error('party_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="party_logo">Foto o logo del partido</label>
                        <input
                            type="file"
                            id="party_logo"
                            name="party_logo"
                            accept="image/*"
                            class="form-control-file @error('party_logo') is-invalid @enderror"
                            {{ $formMethod === 'POST' ? 'required' : '' }}>
                        @error('party_logo')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="primary_candidate_name">Candidato principal</label>
                                <input
                                    type="text"
                                    id="primary_candidate_name"
                                    name="primary_candidate_name"
                                    value="{{ old('primary_candidate_name', $candidacy->primary_candidate_name) }}"
                                    class="form-control @error('primary_candidate_name') is-invalid @enderror"
                                    required>
                                @error('primary_candidate_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="primary_candidate_photo">Foto del candidato principal</label>
                                <input
                                    type="file"
                                    id="primary_candidate_photo"
                                    name="primary_candidate_photo"
                                    accept="image/*"
                                    class="form-control-file @error('primary_candidate_photo') is-invalid @enderror">
                                @error('primary_candidate_photo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="secondary_candidate_name">Candidato secundario</label>
                                <input
                                    type="text"
                                    id="secondary_candidate_name"
                                    name="secondary_candidate_name"
                                    value="{{ old('secondary_candidate_name', $candidacy->secondary_candidate_name) }}"
                                    class="form-control @error('secondary_candidate_name') is-invalid @enderror"
                                    required>
                                @error('secondary_candidate_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="secondary_candidate_photo">Foto del candidato secundario</label>
                                <input
                                    type="file"
                                    id="secondary_candidate_photo"
                                    name="secondary_candidate_photo"
                                    accept="image/*"
                                    class="form-control-file @error('secondary_candidate_photo') is-invalid @enderror">
                                @error('secondary_candidate_photo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Estado</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="activo" @selected(old('status', $candidacy->status ?: 'activo') === 'activo')>Activo</option>
                            <option value="inactivo" @selected(old('status', $candidacy->status) === 'inactivo')>Inactivo</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.candidacies.index') }}" class="btn btn-default">Volver</a>
                        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                    </div>
                </form>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Estructura sugerida" theme="info" icon="fas fa-circle-info">
                <div class="mb-3">
                    <h6>Partido</h6>
                    <p class="text-muted mb-0">Guarda el nombre oficial y una imagen visible del partido politico.</p>
                </div>
                <div class="mb-3">
                    <h6>Candidato principal</h6>
                    <p class="text-muted mb-0">Registra el nombre completo y su foto si deseas mostrarlo en paneles y reportes.</p>
                </div>
                <div>
                    <h6>Candidato secundario</h6>
                    <p class="text-muted mb-0">Registra el nombre del acompanante de formula y su foto opcional.</p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
