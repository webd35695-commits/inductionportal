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
        // Add exam_schedule_id
        try {
            Schema::table('center_posts', function (Blueprint $table) {
                $table->foreignId('exam_schedule_id')->nullable()->after('max_applicants')->constrained('exam_schedules')->onDelete('set null');
            });
        } catch (\Throwable $e) {
            // Column likely exists
        }

        // Add assigned_by
        try {
            Schema::table('center_posts', function (Blueprint $table) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            });
        } catch (\Throwable $e) {
            // Column likely exists
        }
        
        // Add index
        try {
            Schema::table('center_posts', function (Blueprint $table) {
                $table->index(['center_id', 'exam_schedule_id']);
            });
        } catch (\Throwable $e) {
            // Index likely exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('center_posts', function (Blueprint $table) {
            // We won't drop them to avoid data loss in this fix migration
            // or we can implement drop if needed, but for a fix it's safer to leave them
        });
    }
};
