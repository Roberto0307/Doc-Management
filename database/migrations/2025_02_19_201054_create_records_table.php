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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('sub_process_id');
            $table->unsignedInteger('type_id');
            $table->string('code')->unique();
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
        Schema::dropIfExists('records');
    }
};
