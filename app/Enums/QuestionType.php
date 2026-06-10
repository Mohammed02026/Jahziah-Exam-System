<?php

namespace App\Enums;

enum QuestionType: string
{
    case MCQ = 'mcq';
    case TrueFalse = 'tf';

    public function label(): string
    {
        return match ($this) {
            self::MCQ => __('app.question_type.mcq'),
            self::TrueFalse => __('app.question_type.tf'),
        };
    }

    public static function options(): array
    {
        return [
            self::MCQ->value => self::MCQ->label(),
            self::TrueFalse->value => self::TrueFalse->label(),
        ];
    }

    public static function values(): array
    {
        return [
            self::MCQ->value,
            self::TrueFalse->value,
        ];
    }
}