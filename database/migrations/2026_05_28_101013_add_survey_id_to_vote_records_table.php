<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vote_records', function (Blueprint $table) {
            $table->foreignId('survey_id')
                ->after('id')
                ->nullable()
                ->constrained('surveys')
                ->cascadeOnDelete();
        });

        $activeSurveyId = DB::table('surveys')
            ->where('is_active', true)
            ->where('status', 'activa')
            ->value('id');

        if (! $activeSurveyId) {
            $activeSurveyId = DB::table('surveys')->insertGetId([
                'name' => 'Encuesta Electoral Activa',
                'status' => 'activa',
                'is_active' => true,
                'starts_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('vote_records')
            ->whereNull('survey_id')
            ->update(['survey_id' => $activeSurveyId]);

        DB::statement('ALTER TABLE vote_records ALTER COLUMN survey_id SET NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vote_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('survey_id');
        });
    }
};
