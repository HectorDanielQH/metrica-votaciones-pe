<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vote_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surveyor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('candidacy_id')->constrained('candidacies')->cascadeOnDelete();
            $table->string('respondent_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_records');
    }
};
