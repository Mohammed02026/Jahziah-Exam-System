<?php
// app/Enums/Difficulty.php

namespace App\Enums;

enum Difficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';

    public function label(): string
    {
        $key = 'app.difficulty.' . $this->value;
        $translated = __($key);

        // ✅ Fallback إذا الترجمة غير موجودة
        if ($translated === $key) {
            return match ($this) {
                self::Easy => 'Easy',
                self::Medium => 'Medium',
                self::Hard => 'Hard',
            };
        }

        return $translated;
    }

    /** رقم يفيد في الفرز/التقارير */
    public function weight(): int
    {
        return match ($this) {
            self::Easy => 1,
            self::Medium => 2,
            self::Hard => 3,
        };
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