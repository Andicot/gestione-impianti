<?php

namespace App\Enums;

enum StatoPagamentoBollettinoEnum: string
{
    case non_pagato = 'non_pagato';
    case parziale = 'parziale';
    case pagato = 'pagato';

    public function testo()
    {
        return match ($this) {
            self::non_pagato => 'Non Pagato',
            self::parziale => 'Parziale',
            self::pagato => 'Pagato',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::non_pagato => 'danger',
            self::parziale => 'warning',
            self::pagato => 'success',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::non_pagato => 'times-circle',
            self::parziale => 'clock',
            self::pagato => 'check-circle',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::non_pagato => 'Bollettino non ancora pagato',
            self::parziale => 'Bollettino pagato parzialmente',
            self::pagato => 'Bollettino completamente pagato',
        };
    }
}
