<?php

namespace App\Enums;

enum StatoConcetratoreEnum: string
{
    case attivo = 'attivo';
    case non_attivo = 'non_attivo';


    public function testo()
    {
        return match ($this) {
            self::attivo => 'Attivo',
            self::non_attivo => 'Non Attivo',

        };
    }

    public function colore()
    {
        return match ($this) {
            self::attivo => 'success',
            self::non_attivo => 'danger',
        };
    }
}
