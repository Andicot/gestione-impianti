<?php

namespace App\Enums;

enum RuoliOperatoreEnum: string
{
    case admin = 'admin';
    case azienda_di_servizio = 'azienda_di_servizio';
    case amministratore_condominio = 'amministratore_condominio';
    case condomino = 'condomino';
    case responsabile_impianto = 'responsabile_impianto';

    public function colore()
    {
        return match ($this) {
            self::admin => 'danger',
            self::azienda_di_servizio => 'info',
            self::amministratore_condominio => 'warning',
            self::condomino => 'success',
            self::responsabile_impianto => 'primary',
        };
    }

    public function testo()
    {
        return match ($this) {
            self::admin => 'Admin',
            self::azienda_di_servizio => 'Azienda di Servizio',
            self::amministratore_condominio => 'Amministratore Condominio',
            self::condomino => 'Condomino',
            self::responsabile_impianto => 'Responsabile Impianto',
        };
    }

    public function descrizione()
    {
        return match ($this) {
            self::admin => 'Gestione completa del sistema, assegna responsabili impianto',
            self::azienda_di_servizio => 'Crea amministratori e condomini, gestisce impianti',
            self::amministratore_condominio => 'Gestisce bollette, pagamenti e letture del condominio',
            self::condomino => 'Visualizza bollette, consumi e storico dell\'unità immobiliare',
            self::responsabile_impianto => 'Supervisiona tecnicamente gli impianti assegnati, gestisce dispositivi e anomalie',
        };
    }

    public function permessi()
    {
        return match ($this) {
            self::admin => [
                'gestione_completa_sistema',
                'visualizza_tutte_letture',
                'gestione_responsabili_impianto',
                'gestione_aziende_servizio',
                'statistiche_globali',
                'backup_sistema'
            ],
            self::azienda_di_servizio => [
                'crea_amministratori_condominio',
                'crea_condomini_impianti',
                'visualizza_stato_impianti',
                'gestione_concentratori',
                'report_azienda'
            ],
            self::amministratore_condominio => [
                'inserimento_letture_manuali',
                'registra_bollettini',
                'registra_pagamenti',
                'report_condominio',
                'gestione_tickets',
                'visualizza_storico_condominio'
            ],
            self::responsabile_impianto => [
                'supervisione_impianti_assegnati',
                'gestione_dispositivi_misura',
                'gestione_concentratori_assegnati',
                'verifica_anomalie',
                'manutenzione_dispositivi',
                'report_tecnici',
                'validazione_letture',
                'gestione_tickets_tecnici'
            ],
            self::condomino => [
                'visualizza_bollette',
                'scarica_bollette',
                'visualizza_storico_letture',
                'visualizza_grafici_consumo',
                'apri_tickets',
                'visualizza_letture_per_stanza'
            ]
        };
    }

    public function badge()
    {
        return match ($this) {
            self::admin => 'bg-red-100 text-red-800',
            self::azienda_di_servizio => 'bg-blue-100 text-blue-800',
            self::amministratore_condominio => 'bg-yellow-100 text-yellow-800',
            self::condomino => 'bg-green-100 text-green-800',
            self::responsabile_impianto => 'bg-purple-100 text-purple-800',
        };
    }

    /**
     * Verifica se l'utente può gestire un determinato tipo di utente
     */
    public function puo_gestire(self $tipo_target): bool
    {
        return match ($this) {
            self::admin => true, // Può gestire tutti
            self::azienda_di_servizio => in_array($tipo_target, [
                self::amministratore_condominio,
                self::condomino
            ]),
            self::amministratore_condominio => $tipo_target === self::condomino,
            self::responsabile_impianto => false, // Non gestisce utenti, solo aspetti tecnici
            self::condomino => false, // Non può gestire nessuno
        };
    }

    /**
     * Livello gerarchico (più alto = più privilegi)
     */
    public function livello(): int
    {
        return match ($this) {
            self::admin => 5,
            self::azienda_di_servizio => 4,
            self::responsabile_impianto => 3,
            self::amministratore_condominio => 2,
            self::condomino => 1,
        };
    }




    /**
     * Menu di navigazione per il tipo utente
     */
    public function menu_navigazione(): array
    {
        return match ($this) {
            self::admin => [
                'azienda_servizio',
                'amministratore',
                'impianto',
                'concentratore',
                'dispositivo_misura',
                'responsabile_impianto',
                'documento',
                'importazione',
                'storico_letture'
            ],
            self::azienda_di_servizio => [
                'amministratore',
                'impianto',
                'concentratore',
                'dispositivo_misura',
                'responsabile_impianto',
                'documento',
                'importazione',
                'storico_letture'
            ],
            self::amministratore_condominio => [
                'impianto',
                'bollettino',
                'pagamento',
                'lettura',
                'documento',
                'ticket',
                'storico_letture'
            ],
            self::responsabile_impianto => [
                'miei_impianti',
                'dispositivo_misura',
                'concentratore_gestiti',
                'anomalia',
                'manutenzione',
                'ticket_tecnico',
                'storico_letture'
            ],
            self::condomino => [
                'mie_bollette',
                'miei_consumi',
                'storico_letture',
                'grafici',
                'comunicazione'
            ]};

    }

    /**
     * Determina le aree di competenza tecnica per il responsabile impianto
     */
    public function aree_competenza_tecnica(): array
    {
        return match ($this) {
            self::responsabile_impianto => [
                'supervisione_impianti',
                'manutenzione_dispositivi',
                'gestione_concentratori',
                'verifica_anomalie',
                'validazione_letture',
                'interventi_tecnici',
                'calibrazione_dispositivi',
                'sostituzione_componenti'
            ],
            default => []
        };
    }

    /**
     * Notifiche che deve ricevere il tipo di utente
     */
    public function tipi_notifiche(): array
    {
        return match ($this) {
            self::admin => [
                'sistema_generale',
                'backup_completati',
                'errori_critici',
                'nuove_registrazioni',
                'statistiche_mensili'
            ],
            self::azienda_di_servizio => [
                'nuovi_impianti',
                'report_azienda',
                'problemi_concentratori',
                'amministratori_creati'
            ],
            self::amministratore_condominio => [
                'nuove_bollette',
                'pagamenti_ricevuti',
                'tickets_aperti',
                'letture_completate'
            ],
            self::responsabile_impianto => [
                'anomalie_dispositivi',
                'manutenzioni_programmate',
                'concentratori_offline',
                'dispositivi_guasti',
                'letture_anomale',
                'tickets_tecnici'
            ],
            self::condomino => [
                'nuove_bollette',
                'letture_disponibili',
                'risposte_tickets',
                'scadenze_pagamenti'
            ]
        };
    }
}
