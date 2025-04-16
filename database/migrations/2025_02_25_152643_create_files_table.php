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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->foreignId('status_id');
            $table->string('version');
            $table->string('comments')->nullable();
            $table->string('responses')->nullable();
            $table->foreignId('record_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Asegura que la columna exista
            $table->foreign('user_id')->references('id')->on('users'); // Clave foránea
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
