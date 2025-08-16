<?php

namespace App\Enums;

enum StatoAnomaliaEnum: string
{
    case aperta = 'aperta';
    case in_verifica = 'in_verifica';
    case confermata = 'confermata';
    case falso_positivo = 'falso_positivo';
    case risolta = 'risolta';

    public function testo()
    {
        return match ($this) {
            self::aperta => 'Aperta',
            self::in_verifica => 'In Verifica',
            self::confermata => 'Confermata',
            self::falso_positivo => 'Falso Positivo',
            self::risolta => 'Risolta',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::aperta => 'warning',
            self::in_verifica => 'info',
            self::confermata => 'danger',
            self::falso_positivo => 'secondary',
            self::risolta => 'success',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::aperta => 'exclamation-circle',
            self::in_verifica => 'search',
            self::confermata => 'check-circle',
            self::falso_positivo => 'times-circle',
            self::risolta => 'check-double',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::aperta => 'Anomalia rilevata automaticamente, in attesa di verifica',
            self::in_verifica => 'Anomalia presa in carico da un tecnico per verifica',
            self::confermata => 'Anomalia verificata e confermata dal tecnico',
            self::falso_positivo => 'Anomalia verificata come falso allarme',
            self::risolta => 'Anomalia confermata e risolta con successo',
        };
    }

    public function puo_essere_modificata(): bool
    {
        return match ($this) {
            self::aperta, self::in_verifica, self::confermata => true,
            self::falso_positivo, self::risolta => false,
        };
    }

    public function puo_essere_confermata(): bool
    {
        return match ($this) {
            self::aperta, self::in_verifica => true,
            self::confermata, self::falso_positivo, self::risolta => false,
        };
    }

    public function puo_essere_risolta(): bool
    {
        return match ($this) {
            self::confermata => true,
            self::aperta, self::in_verifica, self::falso_positivo, self::risolta => false,
        };
    }

    public function stati_successivi(): array
    {
        return match ($this) {
            self::aperta => [self::in_verifica, self::confermata, self::falso_positivo],
            self::in_verifica => [self::confermata, self::falso_positivo],
            self::confermata => [self::risolta],
            self::falso_positivo, self::risolta => [],
        };
    }
}
