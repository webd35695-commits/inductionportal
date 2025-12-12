<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applied_post_id')->constrained('applied_posts')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending','initiated','paid','failed','cancelled'])->default('pending');
            $table->string('provider')->default('1LINK');
            $table->string('reference_no')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['applied_post_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
