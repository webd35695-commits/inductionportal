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
        Schema::table('qualifications', function (Blueprint $table) {
            $table->string('grade')->nullable();
            $table->string('cgpa')->nullable();
            $table->foreignId('qualification_category_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualifications', function (Blueprint $table) {
            $table->dropForeign(['qualification_category_id']);
            $table->dropColumn(['grade', 'cgpa', 'qualification_category_id']);
        });
    }
};
