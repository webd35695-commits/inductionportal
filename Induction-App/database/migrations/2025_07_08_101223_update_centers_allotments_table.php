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
        Schema::table('centers_allotments', function (Blueprint $table) {
            // Add columns for roll number and center allocation
            $table->unsignedBigInteger('user_id')->after('id');
            $table->unsignedBigInteger('applied_post_id')->after('user_id');
            $table->unsignedBigInteger('center_id')->after('applied_post_id');
            $table->string('roll_number', 50)->unique()->after('center_id');
            $table->enum('status', ['allotted', 'pending', 'cancelled'])->default('pending')->after('roll_number');

            // Add foreign key constraints
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('applied_post_id')
                  ->references('id')->on('applied_posts')
                  ->onDelete('cascade');
            $table->foreign('center_id')
                  ->references('id')->on('centers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centers_allotments', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['applied_post_id']);
            $table->dropForeign(['center_id']);

            // Drop added columns
            $table->dropColumn(['user_id', 'applied_post_id', 'center_id', 'roll_number', 'status']);
        });
    }
};