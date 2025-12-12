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
        Schema::create('qualifications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('degree_type_id');
        $table->string('degree_name');
        $table->string('institute_name');
        $table->string('passing_year');
        $table->string('status')->default('Active');
        $table->timestamps();

        // Foreign key constraints
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
              
        $table->foreign('degree_type_id')
              ->references('id')
              ->on('qualification_types')
              ->onDelete('restrict');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};
