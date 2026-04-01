<?php

use App\Models\Genre;
use App\Models\Song;
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
        Schema::create('genre_song', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Genre::class);
            $table->foreignIdFor(Song::class);

            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');

            $table->unique(['genre_id', 'song_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_song');
    }
};
