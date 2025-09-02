<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Relaciona con la tabla de estudiantes
            $table->string('document_type'); // Tipo de documento (convenio, informe, etc.)
            $table->string('file_name'); // Nombre original del archivo
            $table->string('file_path'); // Ruta donde se guarda el archivo
            $table->string('mime_type')->nullable(); // Tipo MIME del archivo
            $table->integer('file_size')->nullable(); // Tamaño del archivo en bytes
            $table->text('comments')->nullable(); // Comentarios adicionales
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Estado del documento
            $table->enum('teacher_status', ['pending', 'approved', 'rejected'])->default('pending'); // Estado del profesor
            $table->text('teacher_comments')->nullable(); // Comentarios del profesor
            $table->enum('coordinator_status', ['pending', 'approved', 'rejected'])->default('pending'); // Estado del coordinador
            $table->text('coordinator_comments')->nullable(); // Comentarios del coordinador
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}

