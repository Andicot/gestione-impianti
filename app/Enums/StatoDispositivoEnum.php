<?php

namespace App\Enums;

enum StatoDispositivoEnum: string
{
    case attivo = 'attivo';
    case non_attivo = 'non_attivo';
    case dismesso = 'dismesso';
    case sostituito = 'sostituito';
    case guasto = 'guasto';

    public function testo()
    {
        return match ($this) {
            self::attivo => 'Attivo',
            self::non_attivo => 'Non Attivo',
            self::dismesso => 'Dismesso',
            self::sostituito => 'Sostituito',
            self::guasto => 'Guasto',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::attivo => 'success',
            self::non_attivo => 'secondary',
            self::dismesso, self::guasto => 'danger',
            self::sostituito => 'warning',
        };
    }
}
