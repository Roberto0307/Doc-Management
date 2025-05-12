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
        Schema::create('improvement_actions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('process_id')->constrained();
            $table->foreignId('sub_process_id')->constrained();
            $table->foreignId('improvement_action_origin_id')->constrained();
            $table->date('registration_date');
            $table->foreignId('registered_by_id')->constrained('users');
            $table->foreignId('responsible_id')->constrained('users');
            $table->foreignId('improvement_action_status_id')->constrained();
            $table->text('expected_impact');
            $table->date('deadline');
            $table->date('actual_closing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('improvement_actions');
    }
};
