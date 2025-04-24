<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->index(); // clave técnica
            $table->string('label');           // nombre visible al usuario (antes: display_name)
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('protected')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
