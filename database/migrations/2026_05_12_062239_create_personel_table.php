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
            $table->string('nama');
            $table->string('jabatan');
            $table->string('no_handphone');
            $table->string('instagram')->nullable();
            $table->string('peran');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('nia')->after('id'); // Nomor Induk Anggota (Wajib)
            $table->text('deskripsi')->nullable()->after('status'); // Deskripsi (Nullable)
            $table->text('prestasi')->nullable()->after('deskripsi'); // Prestasi (Nullable)
            $table->string('masa_bakti')->nullable()->after('prestasi'); // Untuk bergabung sejak / periode / masa bakti (Nullable)
            $table->foreignId('galeri_id')->nullable()->constrained('galeri')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personel');
    }
};
