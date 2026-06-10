<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

   public function lessons()
{
    return $this->hasManyThrough(
        Lesson::class,
        Topic::class,
        'course_id', // FK في topics
        'topic_id',  // FK في lessons
        'id',        // PK في courses
        'id'         // PK في topics
    );
}
}
