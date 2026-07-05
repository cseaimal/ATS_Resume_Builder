<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->string('degree')->default('');
            $table->string('field')->default('');
            $table->string('school')->default('');
            $table->string('start_date')->default('');
            $table->string('end_date')->default('');
            $table->string('gpa')->default('');
            $table->string('location')->default('');
            $table->text('description')->default('');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('resume_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
