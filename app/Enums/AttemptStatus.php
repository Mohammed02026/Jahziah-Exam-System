<?php
// app/Enums/AttemptStatus.php

namespace App\Enums;

enum AttemptStatus: string
{
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
    case Graded = 'graded';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => __('app.attempt_status.in_progress'),
            self::Submitted => __('app.attempt_status.submitted'),
            self::Graded => __('app.attempt_status.graded'),
        };
    }

    public function isOpen(): bool
    {
        return $this === self::InProgress;
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::Submitted, self::Graded], true);
    }

    /** @return array<string,string> value => label */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_map(fn(self $e) => $e->value, self::cases());
    }

    public static function fromString(?string $value, ?self $default = null): ?self
    {
        if ($value === null || $value === '') return $default;
        return self::tryFrom(strtolower(trim($value))) ?? $default;
    }
}
