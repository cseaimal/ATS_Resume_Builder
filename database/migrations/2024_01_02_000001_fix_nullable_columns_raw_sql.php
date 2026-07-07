<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Use raw SQL ALTER TABLE statements to make columns nullable.
 * This avoids any potential issues with Doctrine DBAL or Laravel's
 * ->change() method on PostgreSQL. Raw SQL is the most reliable approach.
 */
return new class extends Migration
{
    public function up(): void
    {
        // personal_info table
        DB::statement('ALTER TABLE personal_info ALTER COLUMN full_name DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN full_name DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN job_title DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN job_title DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN email DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN email DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN phone DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN phone DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN location DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN location DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN linkedin DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN linkedin DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN github DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN github DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN website DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN website DROP DEFAULT');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN summary DROP NOT NULL');
        DB::statement('ALTER TABLE personal_info ALTER COLUMN summary DROP DEFAULT');

        // education table
        DB::statement('ALTER TABLE education ALTER COLUMN degree DROP NOT NULL');
        DB::statement('ALTER TABLE education ALTER COLUMN degree DROP DEFAULT');
        DB::statement('ALTER TABLE education ALTER COLUMN field DROP NOT NULL');
        DB::statement('ALTER TABLE education ALTER COLUMN field DROP DEFAULT');
        DB::statement('ALTER TABLE education ALTER COLUMN school DROP NOT NULL');
        DB::statement('ALTER TABLE education ALTER COLUMN school DROP DEFAULT');

        // experiences table
        DB::statement('ALTER TABLE experiences ALTER COLUMN job_title DROP NOT NULL');
        DB::statement('ALTER TABLE experiences ALTER COLUMN job_title DROP DEFAULT');
        DB::statement('ALTER TABLE experiences ALTER COLUMN company DROP NOT NULL');
        DB::statement('ALTER TABLE experiences ALTER COLUMN company DROP DEFAULT');

        // skills table
        DB::statement('ALTER TABLE skills ALTER COLUMN name DROP NOT NULL');
        DB::statement('ALTER TABLE skills ALTER COLUMN name DROP DEFAULT');

        // projects table
        DB::statement('ALTER TABLE projects ALTER COLUMN name DROP NOT NULL');
        DB::statement('ALTER TABLE projects ALTER COLUMN name DROP DEFAULT');

        // certifications table
        DB::statement('ALTER TABLE certifications ALTER COLUMN name DROP NOT NULL');
        DB::statement('ALTER TABLE certifications ALTER COLUMN name DROP DEFAULT');
    }

    public function down(): void
    {
        // Not reversing as making columns nullable is the correct production state
    }
};
