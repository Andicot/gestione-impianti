<?php

namespace App\Enums;

enum FrequenzaScansioneDispositivoEnum: string
{
    case giornaliera = 'giornaliera';
    case settimanale = 'settimanale';
    case mensile = 'mensile';

    public function testo()
    {
        return match ($this) {
            self::giornaliera => 'Giornaliera',
            self::settimanale => 'Settimanale',
            self::mensile => 'Mensile',
        };
    }
}
