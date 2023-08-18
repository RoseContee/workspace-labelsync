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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('email');
            $table->integer('membership_id')->nullable();
            $table->decimal('amount');
            $table->integer('period');
            $table->string('unit');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->enum('type', ['start', 'recurring']);
            $table->enum('status', ['pending', 'completed', 'canceled', 'expired']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
