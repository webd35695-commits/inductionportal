<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applied_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable()->after('status');
            // If you want to track who created it too:
            $table->unsignedBigInteger('created_by')->nullable()->after('created_at');

            // Optional: foreign key (adjust user table name if needed)
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('applied_posts', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['updated_by', 'created_by']);
        });
    }
};
