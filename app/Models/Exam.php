<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'topic_id',
        'duration_minutes',
        'status',        // draft | published
        'created_by',
        'total_marks',
    ];

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'topic_id' => 'integer',
            'duration_minutes' => 'integer',
            'total_marks' => 'integer',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot(['order', 'marks'])
            ->orderBy('exam_questions.order')
            ->withTimestamps();
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}