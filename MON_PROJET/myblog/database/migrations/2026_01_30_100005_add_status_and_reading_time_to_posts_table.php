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
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published'])->default('published')->after('image');
            $table->integer('reading_time')->default(1)->after('status'); // Temps de lecture en minutes
            $table->integer('views_count')->default(0)->after('reading_time'); // Cache du nombre de vues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['status', 'reading_time', 'views_count']);
        });
    }
};
