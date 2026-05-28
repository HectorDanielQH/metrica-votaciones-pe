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
        @foreach ($cards as $card)
            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $card['label'] }}</span>
                        <span class="info-box-number">{{ $card['value'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8">
            <x-adminlte-card title="Resumen del módulo" theme="primary" icon="fas fa-table">
                @if ($items->isEmpty())
                    <div class="alert alert-light border mb-0">
                        {{ $emptyMessage }}
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    @foreach ($columns as $column)
                                        <th>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        @if (isset($item->email))
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ optional($item->created_at)->format('d/m/Y H:i') }}</td>
                                        @elseif (isset($item->permissions_count))
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->guard_name }}</td>
                                            <td>{{ $item->permissions_count }}</td>
                                        @elseif (isset($item->guard_name))
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->guard_name }}</td>
                                            <td>{{ optional($item->created_at)->format('d/m/Y H:i') }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-adminlte-card>
        </div>

        <div class="col-lg-4">
            <x-adminlte-card title="Siguientes pasos" theme="success" icon="fas fa-forward-step">
                <ul class="mb-0 pl-3">
                    @foreach ($nextSteps as $step)
                        <li class="mb-2">{{ $step }}</li>
                    @endforeach
                </ul>
            </x-adminlte-card>
        </div>
    </div>
@stop
