<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_management', function (Blueprint $table) {
            $table->id();
            
            // Penguncian Website
            $table->boolean('is_locked')->default(false);
            $table->text('lock_message')->nullable();
            
            // Pesan Update Fitur
            $table->date('update_message_expiry_date')->nullable();
            $table->text('update_message_siswa')->nullable();
            $table->text('update_message_guru')->nullable();
            $table->text('update_message_kepala_sekolah')->nullable();
            
            $table->timestamps();
        });
        
        // Insert default record
        DB::table('website_management')->insert([
            'is_locked' => false,
            'lock_message' => null,
            'update_message_expiry_date' => null,
            'update_message_siswa' => null,
            'update_message_guru' => null,
            'update_message_kepala_sekolah' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('website_management');
    }
};
