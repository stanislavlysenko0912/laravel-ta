<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seeders', function (Blueprint $table) {
            $table->id();
            $table->string('seeder');
            $table->integer('batch');
            $table->timestamp('run_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seeders');
    }
};
