<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personel', function (Blueprint $table) {
            $table->id();
            $table->string('nia'); // Pindahkan ke urutan yang diinginkan secara langsung
            $table->string('nama');
            $table->string('jabatan');
            $table->string('no_handphone');
            $table->string('instagram')->nullable();
            $table->string('peran');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('deskripsi')->nullable();
            $table->text('prestasi')->nullable();
            $table->string('masa_bakti')->nullable();
            $table->unsignedBigInteger('galeri_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personel');
    }
};
