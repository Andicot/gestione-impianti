<?php

namespace App\Enums;

enum IconeEnum: string
{
    case dashboard = 'fas fa-tachometer-alt';  // OK




    case azienda_servizio = 'fas fa-briefcase';
    case amministratore = 'fas fa-user-shield';  // Più appropriato per un admin
    case impianto = 'fas fa-building';
    case concentratore = 'fas fa-network-wired';  // Rappresenta meglio la connettività
    case dispositivo_misura = 'fas fa-thermometer-half';  // Termometro per misurazioni
    case responsabile_impianto = 'fas fa-user-gear';  // OK
    case mio_condominio = 'fas fa-building-user';  // Nuova icona specifica
    case bollettino = 'fas fa-file-invoice-dollar';  // Aggiunto dollaro per pagamenti
    case pagamento = 'fas fa-money-bill-wave';  // Più esplicito
    case concentratore_gestiti = 'fas fa-sitemap';  // Gerarchia di dispositivi
    case anomalia = 'fas fa-exclamation-circle';  // Più neutro
    case manutenzione = 'fas fa-toolbox';  // Più completo di tools
    case mie_bollette = 'fas fa-file-contract';  // Specifico per bollette
    case miei_consumi = 'fas fa-chart-pie';  // Rappresenta meglio i consumi
    case storico_letture = 'fas fa-history';  // Più intuitivo
    case grafici = 'fas fa-chart-area';  // Grafico ad area più rappresentativo
    case documento = 'fas fa-file-signature';  // Documento importante
    case importazione = 'fas fa-file-import';  // Nuova icona specifica
    case ticket = 'fas fa-headset';  // Supporto/assistenza
    case comunicazione = 'fas fa-mail-bulk';  // Comunicazioni di massa
    case registri = 'fas fa-book';  // Registri come libro
    case backup = 'fas fa-server';  // Più tecnico
    case log_viewer = 'fas fa-search-plus';  // Analisi log

    public function render($fontSize = 'fs-2', $altreClassi = null): string
    {
        return "<i class=\"{$this->value} $fontSize $altreClassi\"></i>";
    }

    public function classe(): string
    {
        return $this->value;
    }
}
