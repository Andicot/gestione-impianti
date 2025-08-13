<?php

namespace App\Enums;

enum TipoDispositivoEnum: string
{
    case udr = 'udr';
    case contatore_acs = 'contatore_acs';
    case contatore_gas = 'contatore_gas';
    case contatore_kwh = 'contatore_kwh';
    case diretto = 'diretto';
    case contatore_riscaldamento = 'contatore_riscaldamento';
    case sensore_temperatura = 'sensore_temperatura';
    case altro = 'altro';

    public function testo()
    {
        return match ($this) {
            self::udr => 'UDR (Unità di Ripartizione)',
            self::contatore_acs => 'Contatore ACS',
            self::contatore_gas => 'Contatore Gas',
            self::contatore_kwh => 'Contatore KWh',
            self::diretto => 'Contatore Diretto',
            self::contatore_riscaldamento => 'Contatore Riscaldamento',
            self::sensore_temperatura => 'Sensore Temperatura',
            self::altro => 'Altro',
        };
    }

    public function abbreviazione()
    {
        return match ($this) {
            self::udr => 'UDR',
            self::contatore_acs => 'ACS',
            self::contatore_gas => 'GAS',
            self::contatore_kwh => 'KWH',
            self::diretto => 'DIR',
            self::contatore_riscaldamento => 'RISC',
            self::sensore_temperatura => 'TEMP',
            self::altro => 'ALT',
        };
    }

    public function unitaMisura()
    {
        return match ($this) {
            self::udr => 'UDR',
            self::contatore_acs => 'MC',
            self::contatore_gas => 'MC',
            self::contatore_kwh => 'KWH',
            self::diretto => 'UDR',
            self::contatore_riscaldamento => 'MC',
            self::sensore_temperatura => '°C',
            self::altro => 'UM',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::udr => 'gauge',
            self::contatore_acs => 'droplets',
            self::contatore_gas => 'flame',
            self::contatore_kwh => 'zap',
            self::diretto => 'activity',
            self::contatore_riscaldamento => 'thermometer',
            self::sensore_temperatura => 'thermometer-sun',
            self::altro => 'tool',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::udr => 'primary',
            self::contatore_acs => 'info',
            self::contatore_gas => 'warning',
            self::contatore_kwh => 'success',
            self::diretto => 'secondary',
            self::contatore_riscaldamento => 'danger',
            self::sensore_temperatura => 'light',
            self::altro => 'dark',
        };
    }

    public function badge()
    {
        return match ($this) {
            self::udr => 'bg-blue-100 text-blue-800',
            self::contatore_acs => 'bg-cyan-100 text-cyan-800',
            self::contatore_gas => 'bg-yellow-100 text-yellow-800',
            self::contatore_kwh => 'bg-green-100 text-green-800',
            self::diretto => 'bg-gray-100 text-gray-800',
            self::contatore_riscaldamento => 'bg-red-100 text-red-800',
            self::sensore_temperatura => 'bg-orange-100 text-orange-800',
            self::altro => 'bg-purple-100 text-purple-800',
        };
    }

    /**
     * Indica se il dispositivo è utilizzato per la contabilizzazione
     */
    public function contabilizzabile(): bool
    {
        return match ($this) {
            self::udr,
            self::contatore_acs,
            self::contatore_gas,
            self::contatore_kwh,
            self::contatore_riscaldamento,
            self::diretto => true,
            self::sensore_temperatura,
            self::altro => false,
        };
    }

    /**
     * Indica se il dispositivo supporta la lettura automatica
     */
    public function letturaAutomatica(): bool
    {
        return match ($this) {
            self::udr,
            self::contatore_acs,
            self::contatore_gas,
            self::contatore_kwh,
            self::sensore_temperatura,
            self::diretto => true,
            self::altro => false,
        };
    }

    /**
     * Categoria di servizio a cui appartiene il dispositivo
     */
    public function categoria(): string
    {
        return match ($this) {
            self::udr,
            self::contatore_riscaldamento,
            self::sensore_temperatura => 'riscaldamento',
            self::contatore_acs => 'acs',
            self::contatore_gas => 'gas',
            self::contatore_kwh => 'elettrico',
            self::diretto => 'generico',
            self::altro => 'altro',
        };
    }

    /**
     * Frequenza di lettura consigliata
     */
    public function frequenzaLettura(): string
    {
        return match ($this) {
            self::udr,
            self::sensore_temperatura => 'giornaliera',
            self::contatore_acs,
            self::contatore_riscaldamento => 'settimanale',
            self::contatore_gas,
            self::contatore_kwh => 'mensile',
            self::diretto,
            self::altro => 'settimanale',
        };
    }

    /**
     * Verifica se il tipo di dispositivo richiede calibrazione
     */
    public function richiedeCalibrazione(): bool
    {
        return match ($this) {
            self::udr,
            self::sensore_temperatura,
            self::contatore_acs,
            self::contatore_gas,
            self::contatore_kwh => true,
            self::diretto,
            self::contatore_riscaldamento,
            self::altro => false,
        };
    }

    /**
     * Range di valori attesi per il dispositivo
     */
    public function rangeValori(): array
    {
        return match ($this) {
            self::udr => ['min' => 0, 'max' => 9999],
            self::contatore_acs => ['min' => 0, 'max' => 999999],
            self::contatore_gas => ['min' => 0, 'max' => 999999],
            self::contatore_kwh => ['min' => 0, 'max' => 999999],
            self::sensore_temperatura => ['min' => -20, 'max' => 80],
            self::contatore_riscaldamento => ['min' => 0, 'max' => 999999],
            self::diretto => ['min' => 0, 'max' => 9999],
            self::altro => ['min' => 0, 'max' => 999999],
        };
    }
}
