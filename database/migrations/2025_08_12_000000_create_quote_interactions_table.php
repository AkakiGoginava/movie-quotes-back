<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'comment']);
            $table->text('content')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'quote_id', 'type'], 'unique_user_quote_like');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_interactions');
    }
};
