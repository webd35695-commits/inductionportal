<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quota_settings', function (Blueprint $table) {
            $table->index(['province_id', 'open_merit', 'merit'], 'quota_province_merit_index');
            $table->index(['women', 'special_persons'], 'quota_special_categories_index');
        });
    }

    public function down(): void
    {
        Schema::table('quota_settings', function (Blueprint $table) {
            $table->dropIndex('quota_province_merit_index');
            $table->dropIndex('quota_special_categories_index');
        });
    }
};