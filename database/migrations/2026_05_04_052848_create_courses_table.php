<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category');
            $table->string('difficulty')->default('Pemula');
            $table->integer('duration_hours')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('students_count')->default(0);
            $table->string('instructor')->nullable();
            $table->string('thumbnail_color')->default('#1a1060');
            $table->string('icon_text', 5)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(true);
            $table->foreignId('tool_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('courses');
    }
};