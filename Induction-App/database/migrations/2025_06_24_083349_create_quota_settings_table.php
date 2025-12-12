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
        Schema::create('quota_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('induction_phase_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('open_merit');
            $table->string('merit');
            $table->integer('women');
            $table->integer('minority');
            $table->integer('special_persons');
            $table->timestamps();

            $table->foreign('induction_phase_id')
                ->references('id')->on('induction_phases')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('post_id')
                ->references('id')->on('posts')  // Changed from 'post' to 'posts'
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('province_id')
                ->references('id')->on('provinces')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_settings');
    }
};
