<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::updateOrCreate(
            ['name' => 'Data Structures'],
            ['description' => 'Main course for data structures practice and exams.']
        );
    }
}