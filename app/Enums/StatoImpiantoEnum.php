<?php

namespace App\Enums;

enum StatoImpiantoEnum: string
{
    case attivo = 'attivo';
    case non_attivo = 'non_attivo';
    case dismesso = 'dismesso';


    public function testo()
    {
        return match ($this) {
            self::attivo => 'Attivo',
            self::non_attivo => 'Non Attivo',
            self::dismesso => 'Dismesso',

        };
    }

    public function colore()
    {
        return match ($this) {
            self::attivo => 'success',
            self::non_attivo => 'secondary',
            self::dismesso => 'danger',

        };
    }
}
