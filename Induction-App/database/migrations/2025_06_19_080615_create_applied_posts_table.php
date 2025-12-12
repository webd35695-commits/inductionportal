<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applied_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('preferred_city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('alternative_city_id')->nullable()->constrained('cities')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved','reverted','held', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['user_id', 'post_id'], 'unique_user_post_application');
            $table->index(['status', 'applied_at']);
            $table->index('user_id');
            $table->index('post_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('applied_posts');
    }
};
