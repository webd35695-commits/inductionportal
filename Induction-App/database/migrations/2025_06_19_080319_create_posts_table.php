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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('induction_phase_id');
            $table->unsignedBigInteger('post_category_id');
            $table->unsignedBigInteger('qualification_type_id');
            $table->string('name');
            $table->integer('number_post');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->string('post_abbreviation', 10);
            $table->enum('post_gender', ['Male', 'Female', 'Both']);
            $table->tinyInteger('bps')->unsigned();
            $table->text('requirements')->nullable();
            $table->boolean('degree_required')->default(false);
            $table->timestamps();

            $table->foreign('post_category_id')
                ->references('id')->on('post_categories')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('qualification_type_id')
                ->references('id')->on('qualification_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('induction_phase_id')
                ->references('id')->on('induction_phases')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->unique('name');
            $table->unique('post_abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
