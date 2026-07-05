<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sequence_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sequence_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('movement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequence_movements');
    }
};
