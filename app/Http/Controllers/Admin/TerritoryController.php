<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TerritoryController extends Controller
{
    public function index()
    {
        return view('admin.modules.index', [
            'title' => 'Territorios',
            'subtitle' => 'Panel base para estructurar zonas, distritos, municipios y otras unidades territoriales.',
            'cards' => [
                ['label' => 'Modelo sugerido', 'value' => 'Jerárquico'],
                ['label' => 'Tabla objetivo', 'value' => 'territories'],
                ['label' => 'Estado', 'value' => 'Preparado'],
            ],
            'items' => collect(),
            'columns' => ['Nombre', 'Tipo', 'Padre'],
            'emptyMessage' => 'Todavía no existe el catálogo territorial. Este módulo ya quedó listo para conectarlo a la futura migración.',
            'nextSteps' => [
                'Crear tabla jerárquica de territorios.',
                'Registrar tipos territoriales y códigos únicos.',
                'Conectar territorios con encuestas y asignaciones.',
            ],
        ]);
    }
}
