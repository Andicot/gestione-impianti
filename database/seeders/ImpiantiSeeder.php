<?php

namespace Database\Seeders;

use App\Enums\RuoliOperatoreEnum;
use App\Models\Comune;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AziendaServizio;
use App\Models\Amministratore;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ImpiantiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupera le aziende di servizio e gli amministratori esistenti
        $aziendeServizio = AziendaServizio::senzaFiltroOperatore()->where('attivo', true)->get();
        $amministratori = Amministratore::senzaFiltroOperatore()->where('attivo', true)->get();

        if ($aziendeServizio->isEmpty() || $amministratori->isEmpty()) {
            $this->command->error('Eseguire prima AziendaServizioSeeder per creare aziende e amministratori.');
            return;
        }

        // Crea concentratori per gli impianti
        $concentratori = $this->createConcentratori();

        // Crea gli impianti
        $impianti = $this->createImpianti($aziendeServizio, $amministratori, $concentratori);

        // Crea unità immobiliari per ogni impianto
        $this->createUnitaImmobiliari($impianti);

        // Crea condomini e associali alle unità immobiliari
        $this->createCondomini($impianti);

        // Crea periodi di contabilizzazione
        $this->createPeriodiContabilizzazione($impianti);

        // Crea dispositivi di misura
        $this->createDispositiviMisura($impianti);

        $this->command->info('Seeder Impianti completato:');
        $this->command->info('- Creati 3 concentratori');
        $this->command->info('- Creati 6 impianti/condomini');
        $this->command->info('- Create 63 unità immobiliari');
        $this->command->info('- Creati 25 condomini');
        $this->command->info('- Creati 12 periodi di contabilizzazione');
        $this->command->info('- Creati dispositivi di misura');
    }

    /**
     * Crea i concentratori
     */
    private function createConcentratori(): array
    {
        $concentratori = [];

        $concentratoriData = [
            [
                'azienda_servizio_id' => AziendaServizio::where('attivo', true)->inRandomOrder()->first()->id,
                'marca' => 'SINAPSI',
                'modello' => 'EQUOBOX-PRO',
                'matricola' => 'SN16140120',
                'frequenza_scansione' => 'settimanale',
                'stato' => 'attivo',
                'ip_connessione' => '192.168.1.100',
                'ip_invio_csv' => '10.0.0.50',
                'endpoint_csv' => '/api/csv/upload',
                'token_autenticazione' => Str::random(32),
                'ultima_comunicazione' => Carbon::now()->subHours(2),
                'ultimo_csv_ricevuto' => Carbon::now()->subHours(3),
                'note' => 'Concentratore principale per condominio Via degli Artisti'
            ],
            [
                'azienda_servizio_id' => AziendaServizio::where('attivo', true)->inRandomOrder()->first()->id,

                'marca' => 'SINAPSI',
                'modello' => 'EQUOBOX-LITE',
                'matricola' => 'SN16140121',
                'frequenza_scansione' => 'giornaliera',
                'stato' => 'attivo',
                'ip_connessione' => '192.168.1.101',
                'ip_invio_csv' => '10.0.0.51',
                'endpoint_csv' => '/api/csv/upload',
                'token_autenticazione' => Str::random(32),
                'ultima_comunicazione' => Carbon::now()->subMinutes(30),
                'ultimo_csv_ricevuto' => Carbon::now()->subHours(1),
                'note' => 'Concentratore per palazzine residenziali'
            ],
            [
                'azienda_servizio_id' => AziendaServizio::where('attivo', true)->inRandomOrder()->first()->id,

                'marca' => 'TECHEM',
                'modello' => 'SMART-RADIO',
                'matricola' => 'TH22050315',
                'frequenza_scansione' => 'mensile',
                'stato' => 'attivo',
                'ip_connessione' => '192.168.1.102',
                'ip_invio_csv' => '10.0.0.52',
                'endpoint_csv' => '/api/csv/upload',
                'token_autenticazione' => Str::random(32),
                'ultima_comunicazione' => Carbon::now()->subDays(2),
                'ultimo_csv_ricevuto' => Carbon::now()->subDays(3),
                'note' => 'Concentratore per edifici di nuova costruzione'
            ]
        ];

        foreach ($concentratoriData as $data) {
            $concentratore = DB::table('concentratori')->insertGetId(array_merge($data, [
                'created_at' => Carbon::now()->subMonths(rand(1, 6)),
                'updated_at' => Carbon::now()
            ]));
            $concentratori[] = $concentratore;
        }

        return $concentratori;
    }

    /**
     * Crea gli impianti
     */
    private function createImpianti($aziendeServizio, $amministratori, $concentratori): array
    {
        $impianti = [];

        $impiantiData = [
            [
                'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Condominio Via degli Artisti',
                'indirizzo' => 'Via degli Artisti, 25-27',
                'cap' => '50100',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'condominio',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 30.00,
                'servizio' => 'Ripartizione Spese Riscaldamento e ACS',
                'note' => 'Condominio di 18 unità immobiliari con impianto centralizzato gas metano',
                'unita_count' => 18
            ],
            [ 'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Palazzina Corso Francia',
                'indirizzo' => 'Corso Francia, 156',
                'cap' => '10138',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'condominio',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 25.00,
                'servizio' => 'Ripartizione Spese Riscaldamento',
                'note' => 'Palazzina residenziale di 8 unità con riscaldamento centralizzato',
                'unita_count' => 8
            ],
            [ 'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Condominio Via Roma',
                'indirizzo' => 'Via Roma, 89',
                'cap' => '10123',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'condominio',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 35.00,
                'servizio' => 'Ripartizione Completa',
                'note' => 'Condominio con servizi integrati: riscaldamento, ACS e illuminazione',
                'unita_count' => 12
            ],
            [ 'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Residenza Green Park',
                'indirizzo' => 'Via Nizza, 234',
                'cap' => '10126',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'condominio',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 20.00,
                'servizio' => 'Riscaldamento e ACS',
                'note' => 'Condominio eco-sostenibile con pannelli solari e caldaia a condensazione',
                'unita_count' => 15
            ],
            [ 'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Palazzo Storico Centro',
                'indirizzo' => 'Via Garibaldi, 45',
                'cap' => '10122',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'struttura_civile',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 40.00,
                'servizio' => 'Solo Riscaldamento',
                'note' => 'Palazzo storico ristrutturato con impianto tradizionale',
                'unita_count' => 6
            ],
            [ 'matricola_impianto' => Str::random(10),
                'nome_impianto' => 'Villette a Schiera Collina',
                'indirizzo' => 'Via Po, 156',
                'cap' => '10124',
                'citta' => Comune::inRandomOrder()->first()->id,
                'stato_impianto' => 'attivo',
                'tipologia' => 'struttura_civile',
                'criterio_ripartizione_numerica' => 100.00,
                'percentuale_quota_fissa' => 15.00,
                'servizio' => 'Servizi Completi',
                'note' => 'Complesso di 4 villette con impianti indipendenti ma gestione centralizzata',
                'unita_count' => 4
            ]
        ];

        // Distribuisci gli impianti tra aziende di servizio e amministratori
        $aziendaIndex = 0;
        $adminIndex = 0;

        foreach ($impiantiData as $index => $data) {
            $unitaCount = $data['unita_count'];
            unset($data['unita_count']);

            $azienda = $aziendeServizio[$aziendaIndex % $aziendeServizio->count()];
            $amministratore = $amministratori[$adminIndex % $amministratori->count()];
            $concentratore = $concentratori[$index % count($concentratori)];

            // Non esiste il campo codice_impianto nella migrazione
            $impiantoId = DB::table('impianti')->insertGetId(array_merge($data, [
                'azienda_servizio_id' => $azienda->id,
                'amministratore_id' => $amministratore->id,
                'created_at' => Carbon::now()->subMonths(rand(1, 12)),
                'updated_at' => Carbon::now()
            ]));

            $impianti[] = [
                'id' => $impiantoId,
                'data' => array_merge($data, ['unita_count' => $unitaCount, 'concentratore_id' => $concentratore])
            ];

            $aziendaIndex++;
            $adminIndex++;
        }

        return $impianti;
    }

    /**
     * Crea le unità immobiliari
     */
    /**
     * Crea le unità immobiliari
     */
    private function createUnitaImmobiliari($impianti): void
    {
        $faker = Faker::create('it_IT');

        foreach ($impianti as $impianto) {
            $unitaCount = $impianto['data']['unita_count'];
            $scale = $unitaCount > 10 ? ['A', 'B'] : ['A'];
            $piani = ['T', '1', '2', '3', '4'];

            $unitaCreate = 0;
            $scalaIndex = 0;

            // Recupera l'azienda_servizio_id dell'impianto
            $impiantoData = DB::table('impianti')->where('id', $impianto['id'])->first();
            $aziendaServizioId = $impiantoData->azienda_servizio_id;

            while ($unitaCreate < $unitaCount) {
                foreach ($piani as $piano) {
                    if ($unitaCreate >= $unitaCount) break;

                    $interniPerPiano = $unitaCount > 15 ? rand(2, 4) : rand(1, 3);

                    for ($interno = 1; $interno <= $interniPerPiano; $interno++) {
                        if ($unitaCreate >= $unitaCount) break;

                        $scala = $scale[$scalaIndex % count($scale)];
                        $numeroInterno = $piano === 'T' ? $interno : (($piano - 1) * 10 + $interno);

                        $tipologia = $this->getTipologiaUnita($piano, $interno);
                        $metratura = $this->getMetratura($tipologia);
                        $millesimi = $this->calcolaMillesimi($metratura, $unitaCount);

                        $codiceUnivoco = Str::random(6);

                        DB::table('unita_immobiliari')->insert([
                            'impianto_id' => $impianto['id'],
                            'azienda_servizio_id' => $aziendaServizioId, // AGGIUNTO QUESTO CAMPO
                            'scala' => $scala,
                            'piano' => $piano,
                            'interno' => (string)$numeroInterno,
                            'nominativo_unita' => $faker->lastName . ' ' . $faker->firstName,
                            'millesimi_riscaldamento' => $millesimi['riscaldamento'],
                            'millesimi_acs' => $millesimi['acs'],
                            'metri_quadri' => $metratura,
                            'tipologia' => $tipologia,
                            'corpo_scaldante' => $this->getCorpoScaldante($tipologia),
                            'contatore_acs_numero' => $tipologia === 'appartamento' ? 'ACS' . rand(1000, 9999) : null,
                            'created_at' => Carbon::now()->subMonths(rand(1, 6)),
                            'updated_at' => Carbon::now()
                        ]);

                        $unitaCreate++;
                    }
                }
                $scalaIndex++;
            }
        }
    }

    /**
     * Determina la tipologia dell'unità immobiliare
     */
    private function getTipologiaUnita($piano, $interno): string
    {
        if ($piano === 'T') {
            return rand(1, 10) > 7 ? 'box' : 'magazzino';
        }
        return 'appartamento';
    }

    /**
     * Calcola la metratura in base alla tipologia
     */
    private function getMetratura($tipologia): float
    {
        switch ($tipologia) {
            case 'appartamento':
                return rand(45, 120) + (rand(0, 9) / 10);
            case 'box':
                return rand(15, 30) + (rand(0, 9) / 10);
            case 'magazzino':
                return rand(8, 25) + (rand(0, 9) / 10);
            default:
                return 60.0;
        }
    }

    /**
     * Calcola i millesimi in base alla metratura
     */
    private function calcolaMillesimi($metratura, $unitaTotali): array
    {
        $baseMillesimi = ($metratura / 70) * (1000 / $unitaTotali);

        return [
            'riscaldamento' => round($baseMillesimi + rand(-5, 5), 3),
            'acs' => round($baseMillesimi * 0.8 + rand(-3, 3), 3)
        ];
    }

    /**
     * Determina il corpo scaldante
     */
    private function getCorpoScaldante($tipologia): ?string
    {
        if ($tipologia !== 'appartamento') return null;

        $corpi = ['Radiatori in alluminio', 'Radiatori in ghisa', 'Termoconvettori',
            'Pannelli radianti', 'Ventilconvettori'];

        return $corpi[array_rand($corpi)];
    }

    /**
     * Crea i condomini e li associa alle unità immobiliari
     */
    private function createCondomini($impianti): void
    {
        $faker = Faker::create('it_IT');

        // Recupera tutte le unità immobiliari di tipo appartamento
        $unitaAppartamenti = DB::table('unita_immobiliari')
            ->where('tipologia', 'appartamento')
            ->get();

        foreach ($unitaAppartamenti as $unita) {
            // Crea l'utente condomino
            $userCondomino = User::create([
                'nome' => $faker->firstName,
                'cognome' => $faker->lastName,
                'email' => $faker->unique()->email,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'telefono' => $faker->phoneNumber,
                'ruolo' => RuoliOperatoreEnum::condomino->value,
                'attivo' => true,
                'notifiche_email' => $faker->boolean(70),
                'notifiche_whatsapp' => $faker->boolean(40),
                'ultimo_accesso' => Carbon::now()->subDays(rand(0, 30)),
                'created_at' => Carbon::now()->subMonths(rand(1, 6)),
                'updated_at' => Carbon::now()
            ]);
            DB::table('unita_immobiliari')->where('id', $unita->id)->update(['user_id' => $userCondomino->id]);
            $userCondomino->assignRole(RuoliOperatoreEnum::condomino->value);


        }
    }

    /**
     * Crea i periodi di contabilizzazione
     */
    private function createPeriodiContabilizzazione($impianti): void
    {
        foreach ($impianti as $impianto) {
            // Crea periodo attuale (in corso)
            DB::table('periodi_contabilizzazione')->insert([
                'codice' => 'CONT' . str_pad($impianto['id'], 3, '0', STR_PAD_LEFT) . '2025',
                'data_inizio' => '2024-10-01',
                'data_fine' => '2025-04-30',
                'impianto_id' => $impianto['id'],
                'operatore_letture' => 'Sistema Automatico',
                'note' => 'Periodo di riscaldamento 2024-2025',
                'file_bolletta' => null,
                'stato' => 'in_corso',
                'created_at' => Carbon::parse('2024-10-01'),
                'updated_at' => Carbon::now()
            ]);

            // Crea periodo precedente (completato)
            DB::table('periodi_contabilizzazione')->insert([
                'codice' => 'CONT' . str_pad($impianto['id'], 3, '0', STR_PAD_LEFT) . '2024',
                'data_inizio' => '2023-10-01',
                'data_fine' => '2024-04-30',
                'impianto_id' => $impianto['id'],
                'operatore_letture' => 'Letture Manuali',
                'note' => 'Periodo di riscaldamento 2023-2024 - Completato',
                'file_bolletta' => 'bollette_2024_imp_' . $impianto['id'] . '.pdf',
                'stato' => 'completato',
                'created_at' => Carbon::parse('2023-10-01'),
                'updated_at' => Carbon::parse('2024-05-15')
            ]);
        }
    }

    /**
     * Crea i dispositivi di misura per ogni unità immobiliare
     */
    private function createDispositiviMisura($impianti): void
    {
        $marche = ['SINAPSI', 'TECHEM', 'ISTA', 'DIEHL', 'MADDALENA'];
        $modelli = ['UDR-SMART', 'RADIO-HEAT', 'COMPACT-UDR', 'ECO-READER', 'THERMO-COUNT'];

        foreach ($impianti as $impianto) {
            $unitaImpianto = DB::table('unita_immobiliari')
                ->where('impianto_id', $impianto['id'])
                ->get();

            foreach ($unitaImpianto as $unita) {
                // Crea dispositivo UDR per riscaldamento (sempre presente per appartamenti)
                if ($unita->tipologia === 'appartamento') {
                    $matricola = $this->generaMatricola('udr');
                    $marca = $marche[array_rand($marche)];
                    $modello = $modelli[array_rand($modelli)];

                    DB::table('dispositivi_misura')->insert([
                        'azienda_servizio_id' => rand(1, 3),
                        'matricola' => $matricola,
                        'nome_dispositivo' => $unita->nominativo_unita,
                        'descrizione_1' => 'UDR',
                        'descrizione_2' => null,
                        'marca' => $marca,
                        'modello' => $modello,
                        'tipo' => 'udr',
                        'offset' => rand(0, 10) / 10,
                        'data_installazione' => Carbon::now()->subMonths(rand(6, 36))->format('Y-m-d'),
                        'stato_dispositivo' => 'attivo',
                        'ubicazione' => $this->getUbicazione('udr', $unita->tipologia),
                        'unita_immobiliare_id' => $unita->id,
                        'impianto_id' => $impianto['id'],
                        'concentratore_id' => $impianto['data']['concentratore_id'],
                        'ultimo_valore_rilevato' => $this->getValoreIniziale('udr'),
                        'data_ultima_lettura' => Carbon::now()->subHours(rand(1, 72)),
                        'creato_automaticamente' => false,
                        'note' => 'Dispositivo installato durante setup impianto',
                        'created_at' => Carbon::now()->subMonths(rand(1, 6)),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }
    }

    /**
     * Genera una matricola univoca per il dispositivo
     */
    private function generaMatricola($tipo): string
    {
        $prefissi = [
            'udr' => '68',
            'contatore_acs' => '72',
            'contatore_gas' => '75',
            'contatore_kwh' => '78'
        ];

        $prefisso = $prefissi[$tipo] ?? '69';
        $numero = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        return $prefisso . $numero;
    }

    /**
     * Determina l'ubicazione del dispositivo
     */
    private function getUbicazione($tipo, $tipologia): string
    {
        if ($tipologia !== 'appartamento') {
            return 'Locale tecnico';
        }

        switch ($tipo) {
            case 'udr':
                $ubicazioni = ['Soggiorno', 'Camera principale', 'Cucina', 'Corridoio'];
                return $ubicazioni[array_rand($ubicazioni)];
            case 'contatore_acs':
                return 'Bagno principale';
            case 'contatore_gas':
                return 'Cucina';
            case 'contatore_kwh':
                return 'Quadro elettrico';
            default:
                return 'Non specificata';
        }
    }

    /**
     * Genera un valore iniziale realistico per il dispositivo
     */
    private function getValoreIniziale($tipo): float
    {
        switch ($tipo) {
            case 'udr':
                return rand(50, 500) / 10; // da 5.0 a 50.0
            case 'contatore_acs':
                return rand(1000, 5000) / 100; // da 10.00 a 50.00 m³
            case 'contatore_gas':
                return rand(500, 2000) / 100; // da 5.00 a 20.00 m³
            case 'contatore_kwh':
                return rand(1000, 8000) / 100; // da 10.00 a 80.00 kWh
            default:
                return 0.0;
        }
    }
}
