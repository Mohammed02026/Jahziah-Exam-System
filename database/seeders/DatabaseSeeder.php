<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleUserSeeder::class,
            CourseSeeder::class,
            TopicSeeder::class,
            DataStructuresSeeder::class,
            DataStructuresTF100Seeder::class,

            // ينسخ الأسئلة للدكتورين a@example.com و t@example.com
            DoctorQuestionCloneSeeder::class,

            // ينشئ اختبارات للدكتورين
            FiftyExamsSeeder::class,
        ]);
    }
}