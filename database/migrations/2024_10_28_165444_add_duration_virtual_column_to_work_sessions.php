<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_sessions', function (Blueprint $table) {
            $table->float('duration')->virtualAs('round((julianday(end) - julianday(start)) * 24 * 3600)');
        });
    }
};
