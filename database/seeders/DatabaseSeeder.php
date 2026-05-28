<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        Survey::query()->update(['is_active' => false]);

        $activeSurvey = Survey::firstOrCreate(
            ['name' => 'Encuesta Electoral Activa'],
            [
                'status' => 'activa',
                'is_active' => true,
                'starts_at' => now(),
            ]
        );

        $activeSurvey->update([
            'status' => 'activa',
            'is_active' => true,
            'student_vote_weight' => $activeSurvey->student_vote_weight ?: 1,
            'teacher_vote_weight' => $activeSurvey->teacher_vote_weight ?: 1,
            'starts_at' => $activeSurvey->starts_at ?? now(),
        ]);

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
        ])->assignRole('administrador');

        User::firstOrCreate([
            'email' => 'encuestador@metricaeducativa.test',
        ], [
            'name' => 'Encuestador Demo',
            'password' => 'password',
        ])->assignRole('encuestador');

        User::firstOrCreate([
            'email' => 'veedor@metricaeducativa.test',
        ], [
            'name' => 'Veedor Demo',
            'password' => 'password',
        ])->assignRole('veedor');
    }
}
