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
        Schema::create('improvement_action_completion_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('improvement_action_completion_id');
            $table->foreign('improvement_action_completion_id', 'iac_id')
                ->references('id')
                ->on('improvement_action_completions');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('improvement_action_completion_files');
    }
};
