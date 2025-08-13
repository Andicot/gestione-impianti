<?php

namespace App\Enums;

enum TipoUnitaImmobiliareEnum: string
{
    case appartamento = 'appartamento';
    case box = 'box';
    case magazzino = 'magazzino';
    case ufficio = 'ufficio';
    case negozio = 'negozio';
    case altro = 'altro';

    public function testo()
    {
        return match ($this) {
            self::appartamento => 'Appartamento',
            self::box => 'Box/Garage',
            self::magazzino => 'Magazzino',
            self::ufficio => 'Ufficio',
            self::negozio => 'Negozio',
            self::altro => 'Altro',
        };
    }
}
