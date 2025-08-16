<?php

namespace Database\Seeders;

use App\Enums\RuoliOperatoreEnum;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketRisposta;
use App\Models\UnitaImmobiliare;
use App\Models\Impianto;
use App\Models\DispositivoMisura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('it_IT');

        // Recupera gli utenti esistenti per tipo
        $condomini = User::where('ruolo', RuoliOperatoreEnum::condomino->value)->get();
        $amministratori = User::where('ruolo', RuoliOperatoreEnum::amministratore_condominio->value)->get();
        $responsabili = User::where('ruolo', RuoliOperatoreEnum::responsabile_impianto->value)->get();
        $aziendeServizio = User::where('ruolo', RuoliOperatoreEnum::azienda_di_servizio->value)->get();
        $admins = User::where('ruolo', RuoliOperatoreEnum::admin->value)->get();

        // Recupera entità di supporto
        $unitaImmobiliari = UnitaImmobiliare::senzaFiltroOperatore()->with(['impianto'=>function ($q) {
            $q->senzaFiltroOperatore();
        }])->get();
        $impianti = Impianto::senzaFiltroOperatore()->get();
        $dispositivi = DispositivoMisura::senzaFiltroOperatore()->get();

        if ($condomini->isEmpty() ) {
            $this->command->error('Eseguire prima i seeder per Users per creare i dati necessari.');
            return;
        }

        if ($unitaImmobiliari->isEmpty()) {
            $this->command->error('Eseguire prima i seeder per Impianti per creare i dati necessari.');
            return;
        }

        $tuttiUtenti = collect()
            ->merge($condomini)
            ->merge($amministratori)
            ->merge($responsabili)
            ->merge($aziendeServizio)
            ->merge($admins);

        // Definizione template ticket per categoria
        $templateTicket = [
            'errore_dispositivo' => [
                'titoli' => [
                    'Dispositivo UDR non risponde',
                    'Errore lettura contatore ACS',
                    'Dispositivo offline da giorni',
                    'Valori anomali rilevati'
                ],
                'descrizioni' => [
                    'Il dispositivo UDR installato nel soggiorno non trasmette dati da 3 giorni. Possibile problema di comunicazione.',
                    'Il contatore ACS continua a segnalare errori di lettura. I valori sembrano bloccati.',
                    'Il dispositivo è offline da oltre 72 ore, necessario intervento tecnico urgente.',
                    'I valori rilevati sono molto superiori alla norma, possibile malfunzionamento.'
                ]
            ],
            'letture_anomale' => [
                'titoli' => [
                    'Consumi troppo elevati',
                    'Letture inconsistenti',
                    'Variazioni improvvise nei consumi',
                    'Discrepanza tra periodo precedente'
                ],
                'descrizioni' => [
                    'I consumi di questo mese sono il triplo rispetto al solito, pur non avendo cambiato abitudini.',
                    'Le letture mostrano valori che non corrispondono all\'utilizzo effettivo dell\'appartamento.',
                    'Si è verificata una variazione improvvisa nei consumi senza motivo apparente.',
                    'C\'è una discrepanza significativa rispetto al periodo di contabilizzazione precedente.'
                ]
            ],
            'bollette' => [
                'titoli' => [
                    'Errore nel calcolo bolletta',
                    'Bolletta non ricevuta',
                    'Importo non corrispondente',
                    'Richiesta chiarimenti addebiti'
                ],
                'descrizioni' => [
                    'La bolletta presenta errori nel calcolo dei consumi e nelle quote millesimali.',
                    'Non ho ricevuto la bolletta per il periodo di riferimento, pur essendo in regola con i pagamenti.',
                    'L\'importo addebitato non corrisponde ai consumi effettivi del periodo.',
                    'Richiedo chiarimenti sui vari addebiti presenti in bolletta, non tutti mi sono chiari.'
                ]
            ],
            'manutenzione' => [
                'titoli' => [
                    'Richiesta calibrazione dispositivo',
                    'Sostituzione componente guasto',
                    'Manutenzione preventiva programmata',
                    'Aggiornamento firmware'
                ],
                'descrizioni' => [
                    'È necessaria una calibrazione del dispositivo che presenta scostamenti nelle misurazioni.',
                    'Il dispositivo presenta un componente guasto che necessita sostituzione immediata.',
                    'Programmazione intervento di manutenzione preventiva per i dispositivi dell\'impianto.',
                    'Richiesto aggiornamento firmware per risolvere bug noti del sistema.'
                ]
            ],
            'tecnico' => [
                'titoli' => [
                    'Interferenze radio frequenza',
                    'Problema comunicazione concentratore',
                    'Configurazione errata dispositivo',
                    'Analisi prestazioni sistema'
                ],
                'descrizioni' => [
                    'Si rilevano interferenze radio che compromettono la comunicazione tra dispositivi.',
                    'Il concentratore non riesce a comunicare correttamente con alcuni dispositivi.',
                    'Uno o più dispositivi presentano configurazioni errate che causano malfunzionamenti.',
                    'Richiesta analisi approfondita delle prestazioni complessive del sistema.'
                ]
            ]
        ];

        $categorieDisponibili = ['errore_dispositivo', 'letture_anomale', 'bollette', 'manutenzione', 'tecnico'];
        $prioritaDisponibili = ['bassa', 'media', 'alta', 'urgente'];
        $statiDisponibili = ['aperto', 'in_lavorazione', 'risolto'];

        $ticketsCreati = 0;

        // Crea 10 ticket
        for ($i = 0; $i < 10; $i++) {
            $categoria = $faker->randomElement($categorieDisponibili);
            $template = $templateTicket[$categoria];

            // Seleziona creatore casuale
            $creatore = $tuttiUtenti->random();

            // Determina unità immobiliare e impianto (se applicabile)
            $unitaImmobiliare = null;
            $impianto = null;
            $dispositivo = null;

            if ($categoria === 'errore_dispositivo' || $categoria === 'letture_anomale') {
                $unitaImmobiliare = $unitaImmobiliari->random();
                $impianto = $unitaImmobiliare->impianto;

                // Per errori dispositivo, aggiungi anche il dispositivo
                if ($categoria === 'errore_dispositivo' && $dispositivi->isNotEmpty()) {
                    $dispositiviUnita = $dispositivi->where('unita_immobiliare_id', $unitaImmobiliare->id);
                    if ($dispositiviUnita->isNotEmpty()) {
                        $dispositivo = $dispositiviUnita->random();
                    }
                }
            } elseif ($categoria === 'bollette') {
                // Per le bollette, può essere legato a un'unità immobiliare
                if ($creatore->ruolo === RuoliOperatoreEnum::condomino->value) {
                    $unitaImmobiliare = $unitaImmobiliari->random();
                    $impianto = $unitaImmobiliare->impianto;
                }
            } elseif ($categoria === 'manutenzione' || $categoria === 'tecnico') {
                // Per manutenzione e tecnico, più spesso legato all'impianto
                $impianto = $impianti->random();
            }

            $priorita = $faker->randomElement($prioritaDisponibili);
            $stato = $faker->randomElement($statiDisponibili);

            // Crea il ticket
            $ticket = new Ticket();
            $ticket->titolo = $faker->randomElement($template['titoli']);
            $ticket->descrizione = $faker->randomElement($template['descrizioni']);
            $ticket->priorita = $priorita;
            $ticket->categoria = $categoria;
            $ticket->stato = $stato;
            $ticket->unita_immobiliare_id = $unitaImmobiliare?->id;
            $ticket->impianto_id = $impianto?->id;
            $ticket->dispositivo_id = $dispositivo?->id;
            $ticket->creato_da_id = $creatore->id;
            $ticket->origine = $this->determinaOrigine($creatore->ruolo);

            // Gestione assegnazione e chiusura
            if ($stato === 'in_lavorazione') {
                $ticket->assegnato_a_id = $this->determinaAssegnatario($categoria, $tuttiUtenti);
            } elseif ($stato === 'risolto') {
                $ticket->assegnato_a_id = $this->determinaAssegnatario($categoria, $tuttiUtenti);
                $ticket->chiuso_da_id = $ticket->assegnato_a_id;
                $ticket->data_chiusura = $faker->dateTimeBetween('-30 days', 'now');
                $ticket->note_chiusura = 'Problema risolto con successo. ' . $faker->sentence();
            }

            // Dispositivi multipli coinvolti (occasionalmente)
            if ($categoria === 'errore_dispositivo' && $faker->boolean(20)) {
                $dispositiviCoinvolti = $dispositivi->random(rand(2, 3))->pluck('id')->toArray();
                $ticket->dispositivi_coinvolti = json_encode($dispositiviCoinvolti);
            }

            // Data di creazione realistica
            $ticket->created_at = $faker->dateTimeBetween('-60 days', 'now');
            $ticket->updated_at = $ticket->created_at;

            $ticket->save();

            // Crea risposte per ticket non appena aperti
            if ($stato !== 'aperto') {
                $this->creaRisposteTicket($ticket, $faker, $tuttiUtenti);
            }

            $ticketsCreati++;
        }

        $this->command->info("Seeder Ticket completato:");
        $this->command->info("- Creati {$ticketsCreati} ticket di test");
        $this->command->info("- Distribuite tra varie categorie e utenti");
        $this->command->info("- Aggiunte risposte per ticket in lavorazione/risolti");
    }

    /**
     * Determina l'origine del ticket in base al ruolo dell'utente
     */
    private function determinaOrigine(string $ruolo): string
    {
        return match ($ruolo) {
            RuoliOperatoreEnum::condomino->value => 'condomino',
            RuoliOperatoreEnum::amministratore_condominio->value,
            RuoliOperatoreEnum::azienda_di_servizio->value,
            RuoliOperatoreEnum::responsabile_impianto->value,
            RuoliOperatoreEnum::admin->value => 'amministratore',
            default => 'sistema_automatico'
        };
    }

    /**
     * Determina l'assegnatario appropriato in base alla categoria
     */
    private function determinaAssegnatario(string $categoria, $tuttiUtenti): ?int
    {
        return match ($categoria) {
            'errore_dispositivo', 'manutenzione', 'tecnico' =>
                $tuttiUtenti->where('ruolo', RuoliOperatoreEnum::responsabile_impianto->value)->random()?->id
                ?? $tuttiUtenti->where('ruolo', RuoliOperatoreEnum::azienda_di_servizio->value)->random()?->id,
            'letture_anomale', 'bollette' =>
                $tuttiUtenti->where('ruolo', RuoliOperatoreEnum::amministratore_condominio->value)->random()?->id
                ?? $tuttiUtenti->where('ruolo', RuoliOperatoreEnum::azienda_di_servizio->value)->random()?->id,
            default => $tuttiUtenti->where('ruolo', RuoliOperatoreEnum::admin->value)->random()?->id
        };
    }

    /**
     * Crea risposte realistiche per un ticket
     */
    private function creaRisposteTicket(Ticket $ticket, $faker, $tuttiUtenti): void
    {
        $numeroRisposte = rand(1, 4);
        $dataUltimaRisposta = $ticket->created_at;

        for ($j = 0; $j < $numeroRisposte; $j++) {
            // Determina chi risponde
            if ($j === 0) {
                // Prima risposta: spesso dall'assegnatario o un tecnico
                $autore = $tuttiUtenti->firstWhere('id', $ticket->assegnato_a_id)
                    ?? $tuttiUtenti->where('ruolo', '!=', RuoliOperatoreEnum::condomino->value)->random();
            } else {
                // Risposte successive: mix tra tecnici e richiedente
                $autore = $faker->boolean(70)
                    ? $tuttiUtenti->where('ruolo', '!=', RuoliOperatoreEnum::condomino->value)->random()
                    : $tuttiUtenti->firstWhere('id', $ticket->creato_da_id);
            }

            $risposta = new TicketRisposta();
            $risposta->ticket_id = $ticket->id;
            $risposta->messaggio = $this->generaMessaggioRisposta($ticket->categoria, $j, $faker);
            $risposta->autore_id = $autore->id;
            $risposta->visibile_condomino = $this->determinaVisibilitaCondomino($autore->ruolo);

            // Data progressiva
            $dataUltimaRisposta = $faker->dateTimeBetween($dataUltimaRisposta, 'now');
            $risposta->created_at = $dataUltimaRisposta;
            $risposta->updated_at = $dataUltimaRisposta;

            $risposta->save();
        }
    }

    /**
     * Genera messaggi realistici per le risposte
     */
    private function generaMessaggioRisposta(string $categoria, int $numeroRisposta, $faker): string
    {
        $messaggi = [
            'errore_dispositivo' => [
                'Preso in carico il ticket. Procederemo con la verifica del dispositivo nelle prossime 24 ore.',
                'Effettuato sopralluogo. Rilevato problema di comunicazione radio. Necessaria sostituzione antenna.',
                'Dispositivo sostituito e testato. Il sistema ora funziona correttamente.',
                'Ticket risolto. Monitoreremo le prestazioni nelle prossime settimane.'
            ],
            'letture_anomale' => [
                'Analisi in corso dei dati di consumo. Verificheremo le letture degli ultimi 3 mesi.',
                'Rilevata anomalia nel sistema di lettura. Procederemo con ricalibrazione.',
                'Effettuata correzione dei valori. I nuovi dati saranno disponibili nel prossimo periodo.',
                'Problema risolto. I consumi ora riflettono correttamente l\'utilizzo effettivo.'
            ],
            'bollette' => [
                'Verificheremo i calcoli della bolletta entro 48 ore e vi forniremo riscontro.',
                'Confermato errore nel calcolo. Procederemo con nota di credito.',
                'Emessa bolletta rettificativa. L\'importo corretto è stato calcolato.',
                'Pratica conclusa. La rettifica sarà visibile nel prossimo estratto conto.'
            ],
            'manutenzione' => [
                'Programmato intervento di manutenzione per la prossima settimana.',
                'Intervento completato con successo. Tutti i dispositivi sono stati verificati.',
                'Test post-manutenzione superati. Il sistema è pienamente operativo.',
                'Manutenzione completata. Prossimo controllo programmato tra 6 mesi.'
            ],
            'tecnico' => [
                'Analisi tecnica in corso. Verificheremo configurazioni e parametri di sistema.',
                'Identificata la causa del problema. Procederemo con gli aggiornamenti necessari.',
                'Aggiornamenti applicati e sistema ottimizzato. Prestazioni migliorate.',
                'Intervento tecnico completato. Sistema stabile e performante.'
            ]
        ];

        $messaggiCategoria = $messaggi[$categoria] ?? [
            'Ticket preso in carico.',
            'Analisi in corso.',
            'Intervento effettuato.',
            'Problema risolto.'
        ];

        return $messaggiCategoria[$numeroRisposta] ?? $faker->sentence();
    }

    /**
     * Determina se la risposta è visibile al condomino
     */
    private function determinaVisibilitaCondomino(string $ruolo): bool
    {
        // Risposte tecniche interne potrebbero non essere visibili ai condomini
        return match ($ruolo) {
            RuoliOperatoreEnum::responsabile_impianto->value => rand(1, 10) > 3, // 70% visibili
            RuoliOperatoreEnum::azienda_di_servizio->value => rand(1, 10) > 2, // 80% visibili
            default => true // Altre sempre visibili
        };
    }
}
