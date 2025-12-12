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
        Schema::table('user_spouses', function (Blueprint $table) {
            $table->string('useHusbandDomicile')->nullable()->after('city_id');
            $table->string('spouse_cnic')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_spouses', function (Blueprint $table) {
            $table->dropColumn('useHusbandDomicile');
            $table->integer('spouse_cnic')->change();
        });
    }
};
