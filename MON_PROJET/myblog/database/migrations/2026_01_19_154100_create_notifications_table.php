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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');// L'utilisateur qui reçoit la notification
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // L'utilisateur qui a déclenché la notification
            $table->string('type'); // 'like', 'comment', etc.
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Le post concerné
            $table->foreignId('comment_id')->nullable()->constrained()->onDelete('cascade'); // Peut être nul pour les notifications de type "like" sur un post
            $table->boolean('is_read')->default(false); // Lu ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
