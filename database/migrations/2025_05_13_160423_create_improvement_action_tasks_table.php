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
        Schema::create('improvement_action_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('improvement_action_id')->constrained();
            $table->string('title');
            $table->text('detail');
            $table->foreignId('responsible_id')->constrained('users');
            $table->date('start_date');
            $table->date('deadline');
            $table->date('actual_start_date');
            $table->date('actual_closing_date');
            /* $table->foreignId('improvement_action_task_status_id')->constrained(); */ // Tambien largo
            $table->unsignedBigInteger('improvement_action_task_status_id');
            $table->foreign('improvement_action_task_status_id', 'iats_id')
                ->references('id')
                ->on('improvement_action_task_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('improvement_action_tasks');
    }
};
