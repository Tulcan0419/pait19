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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('credits');
            $table->integer('academic_year'); // 1, 2, 3, 4
            $table->integer('semester'); // 1, 2, 3, 4, 5, 6, 7, 8
            $table->enum('curricular_unit', ['basica', 'profesional', 'integracion']); // básica, profesional, integración curricular
            $table->string('career')->default('software'); // software, mechanical, education
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
