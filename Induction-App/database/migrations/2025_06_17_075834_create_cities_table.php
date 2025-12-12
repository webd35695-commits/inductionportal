<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('district_id')
                  ->constrained(
                      table: 'districts',
                      indexName: 'cities_district_id'
                  )
                  ->cascadeOnDelete();

            $table->string('name');
            $table->boolean('test_center')->default(false);
            $table->timestamps();
            $table->index(['district_id', 'name'], 'cities_district_name_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
