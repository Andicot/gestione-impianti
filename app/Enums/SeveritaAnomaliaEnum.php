<?php

namespace App\Enums;

enum SeveritaAnomaliaEnum: string
{
    case bassa = 'bassa';
    case media = 'media';
    case alta = 'alta';
    case critica = 'critica';

    public function testo()
    {
        return match ($this) {
            self::bassa => 'Bassa',
            self::media => 'Media',
            self::alta => 'Alta',
            self::critica => 'Critica',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::bassa => 'success',
            self::media => 'warning',
            self::alta => 'danger',
            self::critica => 'dark',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::bassa => 'check-circle',
            self::media => 'exclamation-triangle',
            self::alta => 'exclamation-circle',
            self::critica => 'skull-crossbones',
        };
    }

    public function livello(): int
    {
        return match ($this) {
            self::bassa => 1,
            self::media => 2,
            self::alta => 3,
            self::critica => 4,
        };
    }

    public function tempo_risposta_ore(): int
    {
        return match ($this) {
            self::bassa => 168,     // 1 settimana
            self::media => 72,      // 3 giorni
            self::alta => 24,       // 1 giorno
            self::critica => 4,     // 4 ore
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::bassa => 'Anomalia minore che non compromette il funzionamento',
            self::media => 'Anomalia che richiede attenzione in tempi ragionevoli',
            self::alta => 'Anomalia che compromette il corretto funzionamento',
            self::critica => 'Anomalia che blocca il sistema o presenta rischi di sicurezza',
        };
    }

    public function notifica_immediata(): bool
    {
        return match ($this) {
            self::bassa, self::media => false,
            self::alta, self::critica => true,
        };
    }
}
