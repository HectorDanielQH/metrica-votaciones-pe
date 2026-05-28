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
        Schema::create('candidacies', function (Blueprint $table) {
            $table->id();
            $table->string('party_name');
            $table->string('party_logo_path');
            $table->string('primary_candidate_name');
            $table->string('primary_candidate_photo_path')->nullable();
            $table->string('secondary_candidate_name');
            $table->string('secondary_candidate_photo_path')->nullable();
            $table->string('status')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidacies');
    }
};
