<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->foreignId('project_id')->constrained();
            $table->timestamps();
        });

        Schema::create('session_task', function (Blueprint $table) {
            $table->foreignId('session_id')->constrained();
            $table->foreignId('task_id')->constrained();
        });
    }
};
