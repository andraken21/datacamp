<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
    if (!Schema::hasTable('track')) {
        Schema::create('track', function (Blueprint $table) {
            $table->id('track_id');
            $table->string('jenis_track');
            $table->string('nama_track');
            $table->string('slug')->unique();
            $table->string('url')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('teknologi')->nullable();
            $table->integer('durasi_jam')->nullable();
            $table->integer('total_kursus')->nullable();
            $table->integer('total_proyek')->nullable();
            $table->integer('total_asesmen')->nullable();
            $table->integer('total_peserta')->nullable();
            $table->timestamps();
        });
    }
}
    public function down(): void {
        Schema::dropIfExists('track');
    }
};