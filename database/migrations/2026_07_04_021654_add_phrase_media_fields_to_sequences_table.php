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
        Schema::table('sequences', function (Blueprint $table) {
            $table->string('phrase_video_path')->nullable()->after('featured');
            $table->string('phrase_gif_path')->nullable()->after('phrase_video_path');
            $table->string('phrase_thumbnail_path')->nullable()->after('phrase_gif_path');

            $table->string('phrase_processing_status')
                ->default(\App\Enums\SequenceMediaProcessingStatus::Missing->value)
                ->after('phrase_thumbnail_path');

            $table->text('phrase_processing_error')->nullable()->after('phrase_processing_status');
            $table->timestamp('phrase_processed_at')->nullable()->after('phrase_processing_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sequences', function (Blueprint $table) {
            $table->dropColumn([
                'phrase_video_path',
                'phrase_gif_path',
                'phrase_thumbnail_path',
                'phrase_processing_status',
                'phrase_processing_error',
                'phrase_processed_at',
            ]);
        });
    }
};
