<?php

namespace App\Enums;

enum TipologiaImpiantoEnum: string
{
    case condominio = 'condominio';
    case struttura_industriale = 'struttura_industriale';
    case struttura_civile = 'struttura_civile';
    case palazzina = 'palazzina';
    case altro = 'altro';

    public function testo()
    {
        return match ($this) {
            self::condominio => 'Condominio',
            self::struttura_industriale => 'Struttura Industriale',
            self::struttura_civile => 'Struttura Civile',
            self::palazzina => 'Palazzina',
            self::altro => 'Altro',
        };
    }
}
