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
        Schema::table('subject_teacher', function (Blueprint $table) {
            // Agregar campos adicionales si no existen
            if (!Schema::hasColumn('subject_teacher', 'academic_period')) {
                $table->string('academic_period')->nullable()->after('subject_id');
            }
            
            if (!Schema::hasColumn('subject_teacher', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->after('academic_period');
            }
            
            if (!Schema::hasColumn('subject_teacher', 'comments')) {
                $table->text('comments')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('subject_teacher', 'max_students')) {
                $table->integer('max_students')->nullable()->after('comments');
            }
            
            // Agregar índices si no existen
            if (!Schema::hasIndex('subject_teacher', ['teacher_id', 'subject_id'])) {
                $table->index(['teacher_id', 'subject_id']);
            }
            
            if (!Schema::hasIndex('subject_teacher', ['academic_period', 'status'])) {
                $table->index(['academic_period', 'status']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['teacher_id', 'subject_id']);
            $table->dropIndex(['academic_period', 'status']);
            
            // Eliminar columnas
            $table->dropColumn(['max_students', 'comments', 'status', 'academic_period']);
        });
    }
};
