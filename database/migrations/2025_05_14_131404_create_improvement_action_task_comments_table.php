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
        Schema::create('improvement_action_task_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('improvement_action_task_id');
            $table->foreign('improvement_action_task_id', 'iat_id')
                ->references('id')
                ->on('improvement_action_tasks');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('improvement_action_task_comments');
    }
};
