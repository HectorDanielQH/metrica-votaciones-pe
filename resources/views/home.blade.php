@extends('adminlte::page')

@section('title', $panelTitle ?? 'Panel')

@section('content_header')
    <h1>{{ $panelTitle ?? 'Panel' }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Acceso autenticado</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-0">{{ $panelMessage ?? __('You are logged in!') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
