<?php

use App\Enums\MediaProcessingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_media_assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('movement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('raw_video_path');
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();

            $table->decimal('trim_start_seconds', 8, 3)->nullable();
            $table->decimal('trim_end_seconds', 8, 3)->nullable();

            $table->string('clean_video_path')->nullable();
            $table->string('gif_path')->nullable();
            $table->string('thumbnail_path')->nullable();

            $table->string('processing_status')
                ->default(MediaProcessingStatus::Uploaded->value);

            $table->text('processing_error')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->unique('movement_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_media_assets');
    }
};
