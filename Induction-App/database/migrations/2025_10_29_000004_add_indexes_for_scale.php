<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->index(['sender_id', 'status']);
            $table->index('created_at');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->index('induction_phase_id');
            $table->index('post_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropIndex(['supports_sender_id_status_index']);
            $table->dropIndex(['supports_created_at_index']);
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['posts_induction_phase_id_index']);
            $table->dropIndex(['posts_post_category_id_index']);
        });
    }
};
