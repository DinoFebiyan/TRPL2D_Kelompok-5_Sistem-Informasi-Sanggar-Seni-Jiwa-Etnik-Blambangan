<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_sanggar', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->text('visi');
            $table->text('misi');
            $table->text('sejarah');
            $table->text('sambutan_pembina');
            $table->foreignId('logo_id')->nullable()->constrained('galeri')->nullOnDelete();
            $table->foreignId('foto_pembina_id')->nullable()->constrained('galeri')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_sanggar');
    }
};