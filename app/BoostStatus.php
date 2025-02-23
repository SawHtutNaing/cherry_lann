<?php

namespace App;

enum BoostStatus: int
{
    case Charge = 1;
    case Refund = 2;


    public function label(): string
    {
        return match ($this) {
            self::Charge => 'Charge',
            self::Refund => 'Refund',
        };
    }
}
