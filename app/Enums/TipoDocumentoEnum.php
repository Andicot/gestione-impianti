<?php

namespace App\Enums;

enum TipoDocumentoEnum: string
{
    case bolletta = 'bolletta';
    case contratto = 'contratto';
    case verbale = 'verbale';
    case comunicazione = 'comunicazione';
    case regolamento = 'regolamento';
    case manuale = 'manuale';
    case relazione_tecnica = 'relazione_tecnica';
    case certificazione = 'certificazione';
    case altro = 'altro';

    public function testo(): string
    {
        return match ($this) {
            self::bolletta => 'Bolletta',
            self::contratto => 'Contratto',
            self::verbale => 'Verbale',
            self::comunicazione => 'Comunicazione',
            self::regolamento => 'Regolamento',
            self::manuale => 'Manuale',
            self::relazione_tecnica => 'Relazione Tecnica',
            self::certificazione => 'Certificazione',
            self::altro => 'Altro',
        };
    }

    public function icona(): string
    {
        return match ($this) {
            self::bolletta => 'fas fa-file-invoice-dollar',
            self::contratto => 'fas fa-handshake',
            self::verbale => 'fas fa-file-alt',
            self::comunicazione => 'fas fa-envelope',
            self::regolamento => 'fas fa-gavel',
            self::manuale => 'fas fa-book',
            self::relazione_tecnica => 'fas fa-cogs',
            self::certificazione => 'fas fa-certificate',
            self::altro => 'fas fa-file',
        };
    }

    public function colore(): string
    {
        return match ($this) {
            self::bolletta => 'warning',
            self::contratto => 'success',
            self::verbale => 'info',
            self::comunicazione => 'primary',
            self::regolamento => 'dark',
            self::manuale => 'secondary',
            self::relazione_tecnica => 'info',
            self::certificazione => 'success',
            self::altro => 'light',
        };
    }
}
