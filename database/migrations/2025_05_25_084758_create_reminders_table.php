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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            // $table->string('video_id');
            $table->string('title');
            $table->string('url');
            $table->timestamp('remind_at')->nullable(); // Optional reminder time
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming reminders are linked to users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
