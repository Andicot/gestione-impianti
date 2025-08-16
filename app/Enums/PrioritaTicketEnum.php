<?php

namespace App\Enums;

enum PrioritaTicketEnum: string
{
    case bassa = 'bassa';
    case media = 'media';
    case alta = 'alta';
    case urgente = 'urgente';

    public function testo()
    {
        return match ($this) {
            self::bassa => 'Bassa',
            self::media => 'Media',
            self::alta => 'Alta',
            self::urgente => 'Urgente',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::bassa => 'success',
            self::media => 'warning',
            self::alta => 'danger',
            self::urgente => 'dark',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::bassa => 'arrow-down',
            self::media => 'minus',
            self::alta => 'arrow-up',
            self::urgente => 'fire',
        };
    }

    public function livello(): int
    {
        return match ($this) {
            self::bassa => 1,
            self::media => 2,
            self::alta => 3,
            self::urgente => 4,
        };
    }

    public function tempo_risposta_ore(): int
    {
        return match ($this) {
            self::bassa => 72,      // 3 giorni
            self::media => 24,      // 1 giorno
            self::alta => 8,        // 8 ore
            self::urgente => 2,     // 2 ore
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::bassa => 'Problema non urgente, può attendere alcuni giorni',
            self::media => 'Problema standard da risolvere in tempi normali',
            self::alta => 'Problema importante che richiede attenzione prioritaria',
            self::urgente => 'Emergenza da risolvere immediatamente',
        };
    }
}
