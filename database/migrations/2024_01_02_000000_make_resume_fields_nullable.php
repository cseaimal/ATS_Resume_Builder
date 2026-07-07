<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_info', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->string('job_title')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('linkedin')->nullable()->change();
            $table->string('github')->nullable()->change();
            $table->string('website')->nullable()->change();
            $table->text('summary')->nullable()->change();
        });

        Schema::table('education', function (Blueprint $table) {
            $table->string('degree')->nullable()->change();
            $table->string('field')->nullable()->change();
            $table->string('school')->nullable()->change();
            $table->string('start_date')->nullable()->change();
            $table->string('end_date')->nullable()->change();
            $table->string('gpa')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->text('description')->nullable()->change();
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->string('job_title')->nullable()->change();
            $table->string('company')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('start_date')->nullable()->change();
            $table->string('end_date')->nullable()->change();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
            $table->string('proficiency_level')->nullable()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('tech_stack')->nullable()->change();
            $table->string('link')->nullable()->change();
            $table->text('description')->nullable()->change();
        });

        Schema::table('certifications', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('issuer')->nullable()->change();
            $table->string('issue_date')->nullable()->change();
            $table->string('expiry_date')->nullable()->change();
            $table->string('credential_url')->nullable()->change();
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->string('proficiency_level')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reverting this is not necessary as making it nullable is the correct state
    }
};
