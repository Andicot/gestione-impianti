<?php

namespace App\Enums;

enum TipoConsumoEnum: string
{
    case volontario = 'volontario';
    case involontario = 'involontario';

    public function testo()
    {
        return match ($this) {
            self::volontario => 'Volontario',
            self::involontario => 'Involontario',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::volontario => 'success',
            self::involontario => 'warning',
        };
    }


}
