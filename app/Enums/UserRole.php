<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Instructor = 'instructor';
    case Student = 'student';

    public function label(): string
    {
        return match ($this) {
            self::Admin => __('app.roles.admin'),
            self::Instructor => __('app.roles.instructor'),
            self::Student => __('app.roles.student'),
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
}