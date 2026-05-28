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
        if (!Schema::hasColumn('surveys', 'student_vote_weight')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->decimal('student_vote_weight', 10, 4)
                    ->default(1);
            });
        }

        if (!Schema::hasColumn('surveys', 'teacher_vote_weight')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->decimal('teacher_vote_weight', 10, 4)
                    ->default(1);
            });
        }

        DB::table('surveys')
            ->whereNull('student_vote_weight')
            ->update([
                'student_vote_weight' => 1,
            ]);

        DB::table('surveys')
            ->whereNull('teacher_vote_weight')
            ->update([
                'teacher_vote_weight' => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('surveys', 'student_vote_weight')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropColumn('student_vote_weight');
            });
        }

        if (Schema::hasColumn('surveys', 'teacher_vote_weight')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropColumn('teacher_vote_weight');
            });
        }
    }
};