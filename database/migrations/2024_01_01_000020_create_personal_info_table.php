<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name')->default('');
            $table->string('job_title')->default('');
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->string('location')->default('');
            $table->string('linkedin')->default('');
            $table->string('github')->default('');
            $table->string('website')->default('');
            $table->text('summary')->default('');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_info');
    }
};
