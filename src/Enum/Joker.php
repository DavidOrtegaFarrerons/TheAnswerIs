<?php

namespace App\Enum;

enum Joker : string
{
    case FIFTY_FIFTY = '5050';
    case ROULETTE    = 'roulette';
    case PHONE       = 'phone';

    case MAGE       = 'mage';

    public function label(): string
    {
        return match ($this) {
            self::FIFTY_FIFTY => '50 / 50',
            self::ROULETTE    => 'Ruleta',
            self::PHONE       => 'Llamada',
            self::MAGE        => 'Mago',
        };
    }

    public static function choices(): array
    {
        return array_combine(
            array_map(fn(Joker $c) => $c->label(), self::cases()),
            array_column(self::cases(), 'value')
        );
    }
}
