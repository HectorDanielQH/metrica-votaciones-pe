<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user?->hasRole('administrador')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->hasRole('encuestador')) {
            return redirect()->route('surveyor.votes.create');
        }

        if ($user?->hasRole('veedor') || $user?->hasRole('observador')) {
            return redirect()->route('observer.dashboard');
        }

        return view('home', [
            'panelTitle' => match (true) {
                $user?->hasRole('encuestador') => 'Inicio de encuestador',
                $user?->hasRole('veedor') || $user?->hasRole('observador') => 'Panel de veedor',
                default => 'Acceso autenticado',
            },
            'panelMessage' => match (true) {
                $user?->hasRole('encuestador') => 'Tu flujo operativo ya redirige directamente a la pantalla de captura.',
                $user?->hasRole('veedor') || $user?->hasRole('observador') => 'Tu panel de monitoreo y reportes se implementara en la siguiente fase. Por ahora el foco quedo puesto en el modulo administrativo.',
                default => 'Tu cuenta inicio sesion correctamente, pero todavia no tiene un panel funcional asignado.',
            },
        ]);
    }
}
