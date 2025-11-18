<?php

namespace App\Enums;

enum AdminTypes:int
{
    case ADMIN = 0;
    case SUPERVISOR = 1;
    case CLIENT = 2;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 0,
            self::SUPERVISOR => 1,
            self::CLIENT => 2
        };
    }

    public static function options(): array
    {
        return array_map(fn(self $status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], self::cases());
    }
}
