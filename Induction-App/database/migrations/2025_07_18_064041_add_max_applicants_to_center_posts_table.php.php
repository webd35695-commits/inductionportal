<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('center_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('max_applicants')->nullable()->after('post_id');
        });
    }

    public function down()
    {
        Schema::table('center_posts', function (Blueprint $table) {
            $table->dropColumn('max_applicants');
        });
    }
};