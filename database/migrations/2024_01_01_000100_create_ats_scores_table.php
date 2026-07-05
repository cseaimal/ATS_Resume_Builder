<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ats_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_description_id')->unique()->constrained('job_descriptions')->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->json('matched_keywords')->nullable();
            $table->json('missing_keywords')->nullable();
            $table->json('flagged_issues')->nullable();
            $table->json('section_scores')->nullable();
            $table->timestamp('scored_at')->useCurrent();
            $table->timestamps();

            $table->index('resume_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ats_scores');
    }
};
