<?php

namespace App\Enums;

enum CategoriaConsumoEnum: string
{
    case riscaldamento = 'riscaldamento';
    case acs = 'acs';
    case gas = 'gas';
    case luce = 'luce';
    case climatizzazione_estiva = 'climatizzazione_estiva';
    case climatizzazione_invernale = 'climatizzazione_invernale';

    public function testo()
    {
        return match ($this) {
            self::riscaldamento => 'Riscaldamento',
            self::acs => 'ACS',
            self::gas => 'Gas',
            self::luce => 'Luce',
            self::climatizzazione_estiva => 'Climatizzazione Estiva',
            self::climatizzazione_invernale => 'Climatizzazione Invernale',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::riscaldamento => 'danger',
            self::acs => 'info',
            self::gas => 'warning',
            self::luce => 'primary',
            self::climatizzazione_estiva => 'success',
            self::climatizzazione_invernale => 'secondary',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::riscaldamento => 'thermometer-half',
            self::acs => 'tint',
            self::gas => 'fire',
            self::luce => 'lightbulb',
            self::climatizzazione_estiva => 'snowflake',
            self::climatizzazione_invernale => 'sun',
        };
    }
}
