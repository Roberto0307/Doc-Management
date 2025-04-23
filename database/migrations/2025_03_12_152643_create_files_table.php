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
            $table->string('version');
            $table->string('comments')->nullable();
            $table->string('responses')->nullable();

            // Relaciones
            $table->foreignId('status_id')->constrained(); // Asume tabla 'statuses'
            $table->foreignId('record_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Apunta a 'users.id'

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
