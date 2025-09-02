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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->decimal('grade', 3, 2); // 0.00 a 10.00
            $table->enum('type', ['exam', 'homework', 'project', 'participation', 'final']);
            $table->string('title');
            $table->text('comments')->nullable();
            $table->date('evaluation_date');
            $table->timestamps();
            
            $table->unique(['student_id', 'subject_id', 'type', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
