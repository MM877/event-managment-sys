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
        Schema::create('attendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('events_id')->constrained()->cascadeOnDelete();
            $table->foreignId('users_id')->constrained()->cascadeOnDelete();
            $table->integer('num_tickets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendings');
    }
};
