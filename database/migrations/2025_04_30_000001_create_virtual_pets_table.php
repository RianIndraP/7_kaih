<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Kaih');
            $table->integer('level')->default(1);
            $table->integer('streak_count')->default(0);
            $table->string('form')->default('egg'); // egg, baby, child, teen, adult, legendary
            $table->integer('happiness')->default(50); // 0-100
            $table->integer('health')->default(50); // 0-100
            $table->boolean('is_alive')->default(true);
            $table->timestamp('last_fed')->nullable();
            $table->json('unlocked_forms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_pets');
    }
};
