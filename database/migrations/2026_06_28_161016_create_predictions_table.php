<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->integer('team_a_score');
            $table->integer('team_b_score');
            $table->string('qualifier_team')->nullable();
            $table->decimal('points', 5, 2)->default(0);
            $table->boolean('is_exact_score')->default(false);
            $table->boolean('is_correct_result')->default(false);
            $table->boolean('is_correct_qualifier')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'game_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
