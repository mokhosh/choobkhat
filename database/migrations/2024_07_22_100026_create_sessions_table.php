<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->string('notes')->nullable();
            $table->string('state');
            $table->timestamps();
        });
    }
};
