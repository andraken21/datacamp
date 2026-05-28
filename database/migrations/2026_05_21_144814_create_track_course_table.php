<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('track_course')) {
            Schema::create('track_course', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('track_id');
                $table->unsignedBigInteger('course_id');
                $table->integer('urutan_kursus')->default(0);
                $table->timestamps();

                $table->foreign('track_id')->references('track_id')->on('track')->onDelete('cascade');
                $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
                $table->unique(['track_id', 'course_id']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('track_course');
    }
};