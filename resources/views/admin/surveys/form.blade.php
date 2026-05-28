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
            <x-adminlte-card title="Parametros de la encuesta" theme="primary" icon="fas fa-sliders-h">
                <form action="{{ $formAction }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nombre de la encuesta</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $survey->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Estado</label>
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="borrador" @selected(old('status', $survey->status) === 'borrador')>Borrador</option>
                                    <option value="activa" @selected(old('status', $survey->status) === 'activa')>Activa</option>
                                    <option value="cerrada" @selected(old('status', $survey->status) === 'cerrada')>Cerrada</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="starts_at">Inicio</label>
                                <input
                                    type="datetime-local"
                                    id="starts_at"
                                    name="starts_at"
                                    value="{{ old('starts_at', optional($survey->starts_at)->format('Y-m-d\TH:i')) }}"
                                    class="form-control @error('starts_at') is-invalid @enderror">
                                @error('starts_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ends_at">Fin</label>
                                <input
                                    type="datetime-local"
                                    id="ends_at"
                                    name="ends_at"
                                    value="{{ old('ends_at', optional($survey->ends_at)->format('Y-m-d\TH:i')) }}"
                                    class="form-control @error('ends_at') is-invalid @enderror">
                                @error('ends_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_vote_weight">Valor del voto estudiantil</label>
                                <input
                                    type="number"
                                    step="0.0001"
                                    min="0.0001"
                                    id="student_vote_weight"
                                    name="student_vote_weight"
                                    value="{{ old('student_vote_weight', $survey->student_vote_weight) }}"
                                    class="form-control @error('student_vote_weight') is-invalid @enderror"
                                    required>
                                @error('student_vote_weight')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teacher_vote_weight">Valor del voto docente</label>
                                <input
                                    type="number"
                                    step="0.0001"
                                    min="0.0001"
                                    id="teacher_vote_weight"
                                    name="teacher_vote_weight"
                                    value="{{ old('teacher_vote_weight', $survey->teacher_vote_weight) }}"
                                    class="form-control @error('teacher_vote_weight') is-invalid @enderror"
                                    required>
                                @error('teacher_vote_weight')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="is_active"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', $survey->is_active))>
                            <label class="custom-control-label" for="is_active">
                                Marcar como encuesta activa
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.surveys.index') }}" class="btn btn-default">Volver</a>
                        <button type="submit" class="btn btn-primary">Guardar configuracion</button>
                    </div>
                </form>
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Ponderacion" theme="warning" icon="fas fa-balance-scale">
                <div class="mb-3">
                    <h6>Ejemplo</h6>
                    <p class="text-muted mb-0">Si un voto de estudiante vale 0.5000 y un voto docente vale 1.0000, el sistema podra ponderar ambos grupos de forma diferenciada.</p>
                </div>
                <div class="mb-3">
                    <h6>Precision</h6>
                    <p class="text-muted mb-0">Se permiten decimales para ajustarse a reglamentos internos o formulas de equivalencia.</p>
                </div>
                <div>
                    <h6>Recomendacion</h6>
                    <p class="text-muted mb-0">Mantener una sola encuesta activa evita mezclar ponderaciones entre procesos electorales distintos.</p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@stop
