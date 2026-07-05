<?php

use App\Enums\Aspect;
use App\Enums\Difficulty;
use App\Enums\FacingDirection;
use App\Enums\Gate;
use App\Enums\MovementStatus;
use App\Enums\Realm;
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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('gate');
            $table->string('aspect');
            $table->string('realm');

            $table->string('start_position')->nullable();
            $table->string('end_position')->nullable();

            $table->string('start_facing')->default(FacingDirection::Unknown->value);
            $table->string('end_facing')->default(FacingDirection::Unknown->value);

            $table->string('difficulty')->default(Difficulty::Beginner->value);
            $table->string('status')->default(MovementStatus::Draft->value);

            $table->text('description')->nullable();
            $table->text('teaching_notes')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['gate', 'aspect', 'realm']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
