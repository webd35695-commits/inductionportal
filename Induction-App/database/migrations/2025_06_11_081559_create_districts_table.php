<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();

            // Foreign key to provinces table
            $table->foreignId('province_id')
                  ->constrained(
                      table: 'provinces',
                      indexName: 'districts_province_id'
                  )
                  ->cascadeOnDelete();

            $table->string('name');
              $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Composite index
            $table->index(['province_id', 'name'], 'districts_province_name_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
