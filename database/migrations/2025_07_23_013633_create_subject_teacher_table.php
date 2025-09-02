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
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('academic_period')->nullable(); // Ej: "2024-2025", "Primer Semestre 2024"
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->text('comments')->nullable(); // Comentarios sobre la asignación
            $table->integer('max_students')->nullable(); // Máximo número de estudiantes
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index(['teacher_id', 'subject_id']);
            $table->index(['academic_period', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teacher');
    }
};
