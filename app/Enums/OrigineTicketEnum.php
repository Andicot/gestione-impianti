<?php

namespace App\Enums;

enum OrigineTicketEnum: string
{
    case condomino = 'condomino';
    case amministratore = 'amministratore';
    case sistema_automatico = 'sistema_automatico';

    public function testo()
    {
        return match ($this) {
            self::condomino => 'Condomino',
            self::amministratore => 'Amministratore',
            self::sistema_automatico => 'Sistema Automatico',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::condomino => 'success',
            self::amministratore => 'warning',
            self::sistema_automatico => 'info',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::condomino => 'user',
            self::amministratore => 'user-tie',
            self::sistema_automatico => 'robot',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::condomino => 'Ticket creato direttamente da un condomino',
            self::amministratore => 'Ticket creato da un amministratore di condominio',
            self::sistema_automatico => 'Ticket generato automaticamente dal sistema per anomalie rilevate',
        };
    }

    public function può_essere_modificato_da_creatore(): bool
    {
        return match ($this) {
            self::condomino => true,
            self::amministratore => true,
            self::sistema_automatico => false, // I ticket automatici non possono essere modificati dal "creatore"
        };
    }
}
