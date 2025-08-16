<?php

namespace App\Enums;

enum StatoTicketEnum: string
{
    case aperto = 'aperto';
    case in_lavorazione = 'in_lavorazione';
    case risolto = 'risolto';
    case chiuso = 'chiuso';

    public function testo()
    {
        return match ($this) {
            self::aperto => 'Aperto',
            self::in_lavorazione => 'In Lavorazione',
            self::risolto => 'Risolto',
            self::chiuso => 'Chiuso',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::aperto => 'warning',
            self::in_lavorazione => 'info',
            self::risolto => 'success',
            self::chiuso => 'secondary',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::aperto => 'exclamation-circle',
            self::in_lavorazione => 'cog',
            self::risolto => 'check-circle',
            self::chiuso => 'lock',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::aperto => 'Ticket appena creato, in attesa di assegnazione',
            self::in_lavorazione => 'Ticket preso in carico da un operatore',
            self::risolto => 'Problema risolto, in attesa di conferma chiusura',
            self::chiuso => 'Ticket definitivamente chiuso',
        };
    }

    public function puo_essere_modificato(): bool
    {
        return match ($this) {
            self::aperto, self::in_lavorazione => true,
            self::risolto, self::chiuso => false,
        };
    }

    public function puo_ricevere_risposte(): bool
    {
        return match ($this) {
            self::aperto, self::in_lavorazione, self::risolto => true,
            self::chiuso => false,
        };
    }
}
