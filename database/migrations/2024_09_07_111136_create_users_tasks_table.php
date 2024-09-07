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
        Schema::create('users_tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->string('title');
            $table->text('description');
            $table->enum('priority',['highest','high','medium','low','lowest']);
            $table->unsignedBigInteger('assigned_to')->index();
            $table->foreign('assigned_to')->references('user_id')->on('users')->onDelete('cascade');
            $table->enum('status',['To Do','In progress','Done'])->default('To Do');
            $table->timestamp('due_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tasks');
    }
};
