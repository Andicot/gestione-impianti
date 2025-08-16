<?php

namespace App\Enums;

enum CategoriaTicketEnum: string
{
    case errore_dispositivo = 'errore_dispositivo';
    case letture_anomale = 'letture_anomale';
    case bollette = 'bollette';
    case pagamenti = 'pagamenti';
    case comunicazione_concentratore = 'comunicazione_concentratore';
    case manutenzione = 'manutenzione';
    case tecnico = 'tecnico';
    case altro = 'altro';

    public function testo()
    {
        return match ($this) {
            self::errore_dispositivo => 'Errore Dispositivo',
            self::letture_anomale => 'Letture Anomale',
            self::bollette => 'Bollette',
            self::pagamenti => 'Pagamenti',
            self::comunicazione_concentratore => 'Comunicazione Concentratore',
            self::manutenzione => 'Manutenzione',
            self::tecnico => 'Tecnico',
            self::altro => 'Altro',
        };
    }

    public function colore()
    {
        return match ($this) {
            self::errore_dispositivo => 'danger',
            self::letture_anomale => 'warning',
            self::bollette => 'info',
            self::pagamenti => 'success',
            self::comunicazione_concentratore => 'primary',
            self::manutenzione => 'secondary',
            self::tecnico => 'dark',
            self::altro => 'light',
        };
    }

    public function icona()
    {
        return match ($this) {
            self::errore_dispositivo => 'exclamation-triangle',
            self::letture_anomale => 'chart-line',
            self::bollette => 'file-invoice',
            self::pagamenti => 'credit-card',
            self::comunicazione_concentratore => 'wifi',
            self::manutenzione => 'tools',
            self::tecnico => 'cogs',
            self::altro => 'question-circle',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::errore_dispositivo => 'Malfunzionamenti o guasti ai dispositivi di misura',
            self::letture_anomale => 'Valori di lettura fuori norma o inconsistenti',
            self::bollette => 'Problemi relativi a bollettini e fatturazione',
            self::pagamenti => 'Questioni sui pagamenti e transazioni',
            self::comunicazione_concentratore => 'Problemi di comunicazione con i concentratori',
            self::manutenzione => 'Richieste di manutenzione preventiva o correttiva',
            self::tecnico => 'Supporto tecnico generico',
            self::altro => 'Altre tipologie di richieste non categorizzate',
        };
    }

    public function è_tecnico(): bool
    {
        return in_array($this, [
            self::errore_dispositivo,
            self::comunicazione_concentratore,
            self::manutenzione,
            self::tecnico
        ]);
    }

    public function è_amministrativo(): bool
    {
        return in_array($this, [
            self::bollette,
            self::pagamenti,
            self::altro
        ]);
    }

    public function ruolo_assegnazione(): string
    {
        return match ($this) {
            self::errore_dispositivo,
            self::comunicazione_concentratore,
            self::manutenzione,
            self::tecnico => 'responsabile_impianto',

            self::bollette,
            self::pagamenti,
            self::altro => 'amministratore_condominio',

            self::letture_anomale => 'responsabile_impianto', // Può essere sia tecnico che amministrativo
        };
    }

    public function priorita_suggerita(): PrioritaTicketEnum
    {
        return match ($this) {
            self::errore_dispositivo => PrioritaTicketEnum::alta,
            self::comunicazione_concentratore => PrioritaTicketEnum::alta,
            self::letture_anomale => PrioritaTicketEnum::media,
            self::manutenzione => PrioritaTicketEnum::media,
            self::bollette => PrioritaTicketEnum::media,
            self::pagamenti => PrioritaTicketEnum::alta,
            self::tecnico => PrioritaTicketEnum::media,
            self::altro => PrioritaTicketEnum::bassa,
        };
    }
}

