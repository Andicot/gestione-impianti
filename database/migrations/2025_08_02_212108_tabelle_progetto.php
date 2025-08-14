<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabella Aziende di Servizio
        Schema::create('aziende_servizio', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('ragione_sociale');
            $table->string('codice_fiscale', 16)->nullable();
            $table->string('partita_iva', 11)->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_aziendale')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('cap', 5)->nullable();
            $table->string('citta')->nullable();
            $table->string('cognome_referente')->nullable();
            $table->string('nome_referente')->nullable();
            $table->string('telefono_referente')->nullable();
            $table->string('email_referente')->nullable();
            $table->boolean('attivo')->default(true);
            $table->text('note')->nullable();

        });

        // Tabella Responsabili Impianto
        Schema::create('responsabili_impianto', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cognome');
            $table->string('nome');
            $table->string('codice_fiscale', 16)->nullable();
            $table->string('telefono')->nullable();
            $table->string('cellulare')->nullable();
            $table->string('email')->unique();
            $table->boolean('attivo')->default(true)->index();
            $table->text('note')->nullable();

        });

        // Tabella Amministratori (collegata a users e impianti)
        Schema::create('amministratori', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ragione_sociale')->index();
            $table->string('codice_fiscale', 16)->nullable();
            $table->string('partita_iva', 11)->nullable();
            $table->string('telefono_ufficio')->nullable();
            $table->string('indirizzo_ufficio')->nullable();
            $table->string('cap_ufficio', 5)->nullable();
            $table->string('citta_ufficio')->nullable();
            $table->string('cognome_referente')->nullable();
            $table->string('nome_referente')->nullable();
            $table->string('telefono_referente')->nullable();
            $table->string('email_referente')->nullable();
            $table->boolean('attivo')->default(true)->index();
            $table->text('note')->nullable();
        });


        // Tabella Impianti/Condomini
        Schema::create('impianti', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->foreignId('amministratore_id')->nullable()->constrained('amministratori')->nullOnDelete();
            $table->string('matricola_impianto')->index();
            $table->string('nome_impianto');
            $table->string('indirizzo');
            $table->string('cap', 5);
            $table->string('citta');
            $table->string('stato_impianto', 20)->default('attivo')->index(); // attivo, dismesso
            $table->string('tipologia', 20)->default('condominio')->index(); // condominio, struttura industriale, Struttura civile
            $table->decimal('criterio_ripartizione_numerica', 5, 2)->default(100.00)->comment('Base 1-100');
            $table->decimal('percentuale_quota_fissa', 5, 2)->default(0.00);
            $table->string('servizio')->nullable()->comment('Ripartizione Spese');
            $table->text('note')->nullable();

        });

        // Tabella Concentratori
        Schema::create('concentratori', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->foreignId('impianto_id')->nullable()->constrained('impianti')->nullOnDelete();
            $table->string('marca');
            $table->string('modello');
            $table->string('matricola')->unique();
            $table->string('frequenza_scansione', 20)->default('settimanale'); // giornaliera, settimanale, mensile
            $table->string('stato', 20)->default('attivo')->index(); // attivo, non_attivo
            $table->ipAddress('ip_connessione')->nullable();
            $table->ipAddress('ip_invio_csv')->nullable()->comment('IP a cui il concentratore invia i CSV');
            $table->string('endpoint_csv')->nullable()->comment('Endpoint specifico per ricezione CSV');
            $table->string('token_autenticazione')->nullable()->comment('Token per autenticare invii CSV');
            $table->timestamp('ultima_comunicazione')->nullable();
            $table->timestamp('ultimo_csv_ricevuto')->nullable();
            $table->text('note')->nullable();
        });

        // Tabella Unità Immobiliari
        Schema::create('unita_immobiliari', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->foreignId('impianto_id')->constrained('impianti')->cascadeOnDelete();
            $table->string('scala')->nullable()->index();
            $table->string('piano')->index();
            $table->string('interno');
            $table->string('nominativo_unita')->nullable();
            $table->string('tipologia', 20)->default('appartamento')->index(); // appartamento, box, magazzino, altro

            $table->decimal('millesimi_riscaldamento', 8, 3)->default(0);
            $table->decimal('millesimi_acs', 8, 3)->default(0);
            $table->decimal('metri_quadri', 8, 2)->nullable();
            $table->string('corpo_scaldante')->nullable();
            $table->string('contatore_acs_numero')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

        });

        // Tabella Periodi di Contabilizzazione
        Schema::create('periodi_contabilizzazione', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codice')->unique();
            $table->date('data_inizio')->index();
            $table->date('data_fine')->index();
            $table->foreignId('impianto_id')->constrained('impianti')->cascadeOnDelete();
            $table->string('operatore_letture');
            $table->text('note')->nullable();
            $table->string('file_bolletta')->nullable();
            $table->string('stato', 20)->default('in_corso')->index(); // in_corso, completato, archiviato
        });

        // Tabella Endpoint CSV (per gestire gli IP di ricezione)
        Schema::create('endpoint_csv', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome')->unique();
            $table->string('endpoint_url');
            $table->string('token_sicurezza');
            $table->json('concentratori_autorizzati')->nullable()->comment('Array di ID concentratori autorizzati');
            $table->json('ip_autorizzati')->nullable()->comment('Array di IP autorizzati');
            $table->boolean('attivo')->default(true)->index();
            $table->integer('max_size_mb')->default(10)->comment('Dimensione massima file CSV in MB');
            $table->text('note')->nullable();
            $table->integer('upload_count')->default(0)->comment('Contatore upload ricevuti');
            $table->timestamp('ultimo_upload')->nullable();
        });

        // Tabella Dispositivi di Misura (gestiti automaticamente dal CSV)
        Schema::create('dispositivi_misura', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('azienda_servizio_id')->nullable()->constrained('aziende_servizio')->nullOnDelete();
            $table->string('matricola')->unique();
            $table->string('marca')->nullable();
            $table->string('modello')->nullable();
            $table->string('tipo', 30)->default('udr')->index(); // udr, contatore_acs, contatore_gas, contatore_kwh, diretto
            $table->decimal('offset', 10, 3)->default(0);
            $table->date('data_installazione')->nullable();
            $table->string('stato_dispositivo', 20)->default('attivo')->index(); // attivo, sostituito, guasto
            $table->string('ubicazione')->nullable();
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained('unita_immobiliari')->nullOnDelete();
            $table->foreignId('impianto_id')->nullable()->constrained('impianti')->nullOnDelete();
            $table->foreignId('concentratore_id')->nullable()->constrained('concentratori')->nullOnDelete();
            $table->decimal('ultimo_valore_rilevato', 10, 3)->nullable();
            $table->timestamp('data_ultima_lettura')->nullable()->index();
            $table->boolean('creato_automaticamente')->default(true)->comment('True se creato da importazione CSV');
            $table->text('note')->nullable();
            $table->string('nome_dispositivo')->nullable()->comment('Dal CSV: Nome Dispositivo');
            $table->string('descrizione_1')->nullable()->comment('Dal CSV: Descrizione 1');
            $table->string('descrizione_2')->nullable()->comment('Dal CSV: Descrizione 2');
        });

        // Tabella Importazioni CSV
        Schema::create('importazioni_csv', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome_file');
            $table->string('path_file');
            $table->foreignId('impianto_id')->constrained('impianti')->cascadeOnDelete();
            $table->foreignId('concentratore_id')->nullable()->constrained('concentratori')->nullOnDelete();
            $table->foreignId('periodo_id')->nullable()->constrained('periodi_contabilizzazione')->nullOnDelete();
            $table->foreignId('caricato_da_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_caricamento', 20)->default('manuale')->index(); // manuale, automatico_ip
            $table->ipAddress('ip_mittente')->nullable()->comment('IP del concentratore che ha inviato il CSV');
            $table->integer('righe_totali')->default(0);
            $table->integer('righe_elaborate')->default(0);
            $table->integer('righe_errore')->default(0);
            $table->integer('righe_warning')->default(0);
            $table->integer('righe_info')->default(0);
            $table->integer('dispositivi_nuovi')->default(0)->comment('Dispositivi creati automaticamente');
            $table->string('stato', 20)->default('in_elaborazione')->index(); // in_elaborazione, completato, errore
            $table->json('log_errori')->nullable();
            $table->json('metadata_csv')->nullable()->comment('Dati header CSV come Serial, Nome Impianto, etc.');
            $table->timestamp('data_elaborazione')->nullable()->index();



            // Campo per versione del sistema di log (per future migrazioni)
            $table->string('versione_log', 10)->default('1.0')->comment('Versione sistema di logging');

        });






        // Tabella Anomalie Rilevate (Dati dal campo)
        Schema::create('anomalie_rilevate', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('dispositivo_id')->constrained('dispositivi_misura')->cascadeOnDelete();
            $table->foreignId('impianto_id')->constrained('impianti')->cascadeOnDelete();
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained('unita_immobiliari')->nullOnDelete();
            $table->string('tipo_anomalia', 30)->index(); // lettura_errata, dispositivo_non_comunicante, valore_anomalo, temperatura_fuori_range, consumo_anomalo, errore_comunicazione, batteria_scarica, manomissione, altro
            $table->string('severita', 15)->default('media')->index(); // bassa, media, alta, critica
            $table->text('descrizione');
            $table->json('dati_tecnici')->nullable()->comment('Valori specifici del dispositivo al momento dell\'anomalia');
            $table->decimal('valore_rilevato', 10, 3)->nullable();
            $table->decimal('valore_atteso', 10, 3)->nullable();
            $table->timestamp('data_rilevazione')->index();
            $table->boolean('confermata')->default(false)->comment('Se l\'anomalia è stata verificata da un tecnico');
            $table->foreignId('confermata_da_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('data_conferma')->nullable();
            $table->string('stato', 20)->default('aperta')->index(); // aperta, in_verifica, confermata, falso_positivo, risolta
            $table->text('note_risoluzione')->nullable();
            $table->foreignId('risolta_da_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('data_risoluzione')->nullable();
            $table->foreignId('importazione_csv_id')->nullable()->constrained('importazioni_csv')->nullOnDelete();
        });

        // Tabella Letture Consumi
        Schema::create('letture_consumi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained('unita_immobiliari')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('periodi_contabilizzazione')->cascadeOnDelete();
            $table->foreignId('dispositivo_id')->nullable()->constrained('dispositivi_misura')->nullOnDelete();
            $table->string('tipo_consumo', 20)->index(); // volontario, involontario
            $table->string('categoria', 30)->index(); // riscaldamento, acs, gas, luce, climatizzazione_estiva, climatizzazione_invernale
            $table->string('ambiente')->nullable()->comment('es. bagno, camera 1, cucina');
            $table->decimal('udr_attuale', 10, 3);
            $table->decimal('udr_precedente', 10, 3)->default(0);
            $table->decimal('differenza_consumi', 10, 3)->storedAs('udr_attuale - udr_precedente');
            $table->string('unita_misura', 10)->default('UDR')->comment('UDR, MC, KWH');
            $table->decimal('costo_unitario', 8, 4)->default(0);
            $table->decimal('costo_totale', 10, 2)->storedAs('(udr_attuale - udr_precedente) * costo_unitario');
            $table->boolean('errore')->default(false)->index();
            $table->text('descrizione_errore')->nullable();
            $table->boolean('anomalia')->default(false)->index();
            $table->foreignId('importazione_csv_id')->nullable()->constrained('importazioni_csv')->nullOnDelete();
            $table->date('data_lettura')->index();
            $table->time('ora_lettura')->nullable();
            $table->decimal('comfort_termico_attuale', 5, 2)->nullable();
            $table->decimal('temp_massima_sonde', 5, 2)->nullable();
            $table->date('data_registrazione_temp_max')->nullable();
            $table->decimal('temp_tecnica_tt16', 5, 2)->nullable();
            $table->decimal('comfort_termico_periodo_prec', 5, 2)->nullable();
            $table->decimal('temp_media_calorifero_prec', 5, 2)->nullable();
            $table->decimal('udr_storico_1', 10, 3)->nullable();
            $table->decimal('udr_totali', 10, 3)->nullable();
            $table->timestamp('data_ora_dispositivo')->nullable();
        });

        // Tabella Bollettini
        Schema::create('bollettini', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('unita_immobiliare_id')->constrained('unita_immobiliari')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('periodi_contabilizzazione')->cascadeOnDelete();
            $table->decimal('importo_totale', 10, 2);
            $table->decimal('quota_fissa', 10, 2)->default(0);
            $table->decimal('quota_variabile', 10, 2)->default(0);
            $table->decimal('importo_riscaldamento', 10, 2)->default(0);
            $table->decimal('importo_acs', 10, 2)->default(0);
            $table->decimal('importo_gas', 10, 2)->default(0);
            $table->decimal('importo_luce', 10, 2)->default(0);
            $table->string('pdf_allegato')->nullable();
            $table->string('nome_file_originale')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('dimensione_file')->nullable();
            $table->integer('numero_download')->default(0);
            $table->timestamp('ultimo_download')->nullable();
            $table->foreignId('caricato_da_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('data_caricamento');
            $table->boolean('visualizzato')->default(false)->index();
            $table->timestamp('data_visualizzazione')->nullable();
            $table->string('metodo_pagamento', 20)->nullable()->index(); // bonifico, contanti, carta, altro
            $table->string('iban_condominio')->nullable();
            $table->string('intestatario_conto')->nullable();
            $table->text('note')->nullable();
            $table->string('stato_pagamento', 20)->default('non_pagato')->index(); // non_pagato, pagato, parziale
            $table->decimal('importo_pagato', 10, 2)->default(0);
            $table->date('data_scadenza')->nullable()->index();

        });


        // Tabella Documenti - per gestire documenti generici oltre alle bollette
        Schema::create('documenti', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome_file');
            $table->string('nome_originale');
            $table->string('path_file');
            $table->string('tipo_documento', 30)->index(); // bolletta, contratto, verbale, comunicazione, altro
            $table->string('mime_type', 50);
            $table->unsignedBigInteger('dimensione_file'); // in bytes
            $table->text('descrizione')->nullable();

            // Relazioni
            $table->foreignId('caricato_da_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('impianto_id')->nullable()->constrained('impianti')->nullOnDelete();
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained('unita_immobiliari')->nullOnDelete();
            $table->foreignId('bollettino_id')->nullable()->constrained('bollettini')->nullOnDelete();

            // Gestione visibilità e accesso
            $table->boolean('pubblico')->default(false)->index(); // visibile a tutti i condomini dell'impianto
            $table->boolean('riservato_amministratori')->default(false)->index();
            $table->json('utenti_autorizzati')->nullable()->comment('Array di user_id autorizzati alla visualizzazione');

            // Tracking visualizzazioni
            $table->integer('numero_visualizzazioni')->default(0);
            $table->timestamp('ultima_visualizzazione')->nullable();

            // Metadati
            $table->string('stato', 20)->default('attivo')->index(); // attivo, archiviato, eliminato
            $table->date('data_scadenza')->nullable()->index();
            $table->boolean('notifica_scadenza')->default(false);
            $table->text('note')->nullable();

            // Indici per performance
            $table->index(['tipo_documento', 'stato']);
            $table->index(['impianto_id', 'pubblico']);
            $table->index(['caricato_da_id', 'created_at']);
        });

        // Tabella Visualizzazioni Documenti - per tracciare chi ha visto cosa
        Schema::create('visualizzazioni_documenti', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('documento_id')->constrained('documenti','id','documenti_visualizzazioni__documento_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users','id','visualizzazioni_documenti__user_id')->cascadeOnDelete();
            $table->timestamp('data_visualizzazione')->index();
            $table->ipAddress('ip_address')->nullable();
            $table->boolean('scaricato')->default(false);
            $table->timestamp('data_download')->nullable();

            // Evita duplicazioni per stesso utente/documento nello stesso giorno
            $table->unique(['documento_id', 'user_id', 'data_visualizzazione'],'unique');
            $table->index(['user_id', 'data_visualizzazione']);
        });

        // Tabella Pagamenti
        Schema::create('pagamenti', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('bollettino_id')->constrained('bollettini')->cascadeOnDelete();
            $table->decimal('importo', 10, 2);
            $table->date('data_pagamento')->index();
            $table->string('metodo', 20)->index(); // bonifico, contanti, carta, altro
            $table->string('riferimento_transazione')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('registrato_da_id')->constrained('users')->cascadeOnDelete();
        });

        // Tabella Tickets/Comunicazioni (specifici per errori dispositivi)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titolo');
            $table->text('descrizione');
            $table->string('priorita', 15)->default('media')->index(); // bassa, media, alta, urgente
            $table->string('categoria', 30)->default('errore_dispositivo')->index(); // errore_dispositivo, letture_anomale, bollette, pagamenti, comunicazione_concentratore, manutenzione, tecnico, altro
            $table->string('stato', 20)->default('aperto')->index(); // aperto, in_lavorazione, risolto, chiuso
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained('unita_immobiliari')->nullOnDelete();
            $table->foreignId('impianto_id')->nullable()->constrained('impianti')->nullOnDelete();
            $table->foreignId('dispositivo_id')->nullable()->constrained('dispositivi_misura')->nullOnDelete();
            $table->foreignId('anomalia_id')->nullable()->constrained('anomalie_rilevate')->nullOnDelete();
            $table->json('dispositivi_coinvolti')->nullable()->comment('Array di ID dispositivi se il problema riguarda più dispositivi');
            $table->foreignId('creato_da_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assegnato_a_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('chiuso_da_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('data_chiusura')->nullable();
            $table->text('note_chiusura')->nullable();
            $table->string('origine', 20)->default('condomino')->index(); // condomino, amministratore, sistema_automatico
        });

        // Tabella Risposte Tickets
        Schema::create('ticket_risposte', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->text('messaggio');
            $table->foreignId('autore_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('visibile_condomino')->default(true)->index();
            $table->json('allegati')->nullable();
        });

        // Tabella Log Attività
        Schema::create('log_attivita', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('azione');
            $table->string('tabella_riferimento')->nullable()->index();
            $table->unsignedBigInteger('record_id')->nullable()->index();
            $table->json('dati_precedenti')->nullable();
            $table->json('dati_nuovi')->nullable();
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
        });

        // Tabella Configurazioni Sistema
        Schema::create('configurazioni', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('chiave')->unique();
            $table->text('valore')->nullable();
            $table->string('tipo', 15)->default('string')->index(); // string, integer, boolean, json
            $table->text('descrizione')->nullable();
            $table->boolean('modificabile')->default(true)->index();
        });


        // Modifica tabella users esistente per aggiungere campi specifici
        Schema::table('users', function (Blueprint $table) {
            //$table->string('tipo_utente', 30)->after('ruolo')->index(); // superadmin, admin, amministratore_condominio, condomino
            $table->boolean('attivo')->default(true)->after('extra')->index();
            $table->boolean('notifiche_email')->default(true)->after('attivo');
            $table->boolean('notifiche_whatsapp')->default(false)->after('notifiche_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rimuovi le modifiche alla tabella users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_utente', 'attivo', 'notifiche_email', 'notifiche_whatsapp'
            ]);
        });

        // Drop tabelle in ordine inverso per rispettare le foreign key
        Schema::dropIfExists('configurazioni');
        Schema::dropIfExists('log_attivita');
        Schema::dropIfExists('ticket_risposte');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('pagamenti');
        Schema::dropIfExists('bollettini');
        Schema::dropIfExists('letture_consumi');
        Schema::dropIfExists('anomalie_rilevate');
        Schema::dropIfExists('importazioni_csv');
        Schema::dropIfExists('endpoint_csv');
        Schema::dropIfExists('dispositivi_misura');
        Schema::dropIfExists('periodi_contabilizzazione');
        Schema::dropIfExists('unita_immobiliari');
        Schema::dropIfExists('impianti');
        Schema::dropIfExists('concentratori');
        Schema::dropIfExists('responsabili_impianto');
        Schema::dropIfExists('condomino_unita_immobiliare');
        Schema::dropIfExists('condomini');
        Schema::dropIfExists('amministratori');

    }
};
