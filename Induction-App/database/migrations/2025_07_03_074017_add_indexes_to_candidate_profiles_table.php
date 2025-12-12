<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('age_relaxations', function (Blueprint $table) {
            $table->index(['user_id'], 'age_relaxation_user_index');
        });
    }

    public function down(): void
    {
        Schema::table('age_relaxations', function (Blueprint $table) {
            $table->dropIndex('age_relaxation_user_index');
        });
    }
};