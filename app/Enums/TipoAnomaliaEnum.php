<?php

namespace App\Enums;

enum TipoAnomaliaEnum: string
{
    case lettura_errata = 'lettura_errata';
    case dispositivo_non_comunicante = 'dispositivo_non_comunicante';
    case valore_anomalo = 'valore_anomalo';
    case temperatura_fuori_range = 'temperatura_fuori_range';
    case consumo_anomalo = 'consumo_anomalo';
    case errore_comunicazione = 'errore_comunicazione';
    case batteria_scarica = 'batteria_scarica';
    case manomissione = 'manomissione';
    case altro = 'altro';

    public function testo()
    {
        return match ($this) {
            self::lettura_errata => 'Lettura Errata',
            self::dispositivo_non_comunicante => 'Dispositivo Non Comunicante',
            self::valore_anomalo => 'Valore Anomalo',
            self::temperatura_fuori_range => 'Temperatura Fuori Range',
            self::consumo_anomalo => 'Consumo Anomalo',
            self::errore_comunicazione => 'Errore Comunicazione',
            self::batteria_scarica => 'Batteria Scarica',
            self::manomissione => 'Manomissione',
            self::altro => 'Altro',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::lettura_errata => 'warning',
            self::dispositivo_non_comunicante => 'danger',
            self::valore_anomalo => 'info',
            self::temperatura_fuori_range => 'primary',
            self::consumo_anomalo => 'secondary',
            self::errore_comunicazione => 'danger',
            self::batteria_scarica => 'warning',
            self::manomissione => 'dark',
            self::altro => 'light',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::lettura_errata => 'exclamation-triangle',
            self::dispositivo_non_comunicante => 'unlink',
            self::valore_anomalo => 'chart-line',
            self::temperatura_fuori_range => 'thermometer-half',
            self::consumo_anomalo => 'tachometer-alt',
            self::errore_comunicazione => 'wifi-slash',
            self::batteria_scarica => 'battery-quarter',
            self::manomissione => 'user-secret',
            self::altro => 'question-circle',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::lettura_errata => 'Valore di lettura non coerente o corrotto',
            self::dispositivo_non_comunicante => 'Dispositivo non risponde alle richieste di comunicazione',
            self::valore_anomalo => 'Lettura fuori dai parametri normali di funzionamento',
            self::temperatura_fuori_range => 'Temperatura rilevata oltre i limiti consentiti',
            self::consumo_anomalo => 'Consumo eccessivo o troppo basso rispetto alla media',
            self::errore_comunicazione => 'Problemi nella trasmissione dati tra dispositivo e concentratore',
            self::batteria_scarica => 'Livello batteria dispositivo sotto la soglia minima',
            self::manomissione => 'Possibile tentativo di alterazione o manomissione del dispositivo',
            self::altro => 'Anomalia non classificabile nelle categorie predefinite',
        };
    }

    public function severita_suggerita(): SeveritaAnomaliaEnum
    {
        return match ($this) {
            self::dispositivo_non_comunicante,
            self::manomissione => SeveritaAnomaliaEnum::critica,

            self::lettura_errata,
            self::errore_comunicazione,
            self::batteria_scarica => SeveritaAnomaliaEnum::alta,

            self::valore_anomalo,
            self::temperatura_fuori_range,
            self::consumo_anomalo => SeveritaAnomaliaEnum::media,

            self::altro => SeveritaAnomaliaEnum::bassa,
        };
    }

    public function richiede_intervento_tecnico(): bool
    {
        return match ($this) {
            self::dispositivo_non_comunicante,
            self::errore_comunicazione,
            self::batteria_scarica,
            self::manomissione => true,

            self::lettura_errata,
            self::valore_anomalo,
            self::temperatura_fuori_range,
            self::consumo_anomalo,
            self::altro => false,
        };
    }
}
