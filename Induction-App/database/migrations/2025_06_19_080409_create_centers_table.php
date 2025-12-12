<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
{
    Schema::create('centers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code', 20)->unique();
        $table->text('address');
        $table->foreignId('city_id')->constrained()->onDelete('cascade');
        $table->integer('capacity');
        $table->boolean('is_active')->default(true);
        $table->json('facilities')->nullable(); // AC, parking, etc.
        $table->string('contact_person')->nullable();
        $table->string('contact_phone')->nullable();
        $table->enum('status', ['Active', 'Inactive'])->default('Active');

        $table->timestamps();

        $table->index('city_id');
        $table->index('is_active');

        $table->index('code');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
