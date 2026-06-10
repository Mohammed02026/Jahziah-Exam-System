<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'status',        // in_progress | submitted | graded
        'score',
        'started_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => AttemptStatus::class,
            'score' => 'integer',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    // Helpers
    public function isOpen(): bool
    {
        return $this->status === AttemptStatus::InProgress;
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [AttemptStatus::Submitted, AttemptStatus::Graded], true);
    }
}
