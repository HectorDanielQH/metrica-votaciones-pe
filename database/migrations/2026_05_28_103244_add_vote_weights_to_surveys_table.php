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
        Schema::table('surveys', function (Blueprint $table) {
            $table->decimal('student_vote_weight', 10, 4)->default(1)->after('is_active');
            $table->decimal('teacher_vote_weight', 10, 4)->default(1)->after('student_vote_weight');
        });

        DB::table('surveys')
            ->whereNull('student_vote_weight')
            ->update([
                'student_vote_weight' => 1,
                'teacher_vote_weight' => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'student_vote_weight',
                'teacher_vote_weight',
            ]);
        });
    }
};
