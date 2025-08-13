<?php

namespace Database\Seeders;

use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AziendaServizio;
use App\Models\Amministratore;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AziendaServizioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea l'utente per l'azienda di servizio
        $user = User::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'email' => 'admin@energyservice.it',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telefono' => '+39 011 123456',
            'ruolo' => RuoliOperatoreEnum::azienda_di_servizio->value,
            'attivo' => true,
            'notifiche_email' => true,
            'notifiche_whatsapp' => false,
            'ultimo_accesso' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assegna il ruolo all'utente
        $user->assignRole(RuoliOperatoreEnum::azienda_di_servizio->value);

        // Crea l'azienda di servizio
        $azienda = AziendaServizio::create([
            'user_id' => $user->id,
            'ragione_sociale' => 'Energy Service S.r.l.',
            'codice_fiscale' => 'ENRSRV80A01L219X',
            'partita_iva' => '12345678901',
            'telefono' => '+39 011 123456',
            'email_aziendale' => 'info@energyservice.it',
            'indirizzo' => 'Via Roma, 123',
            'cap' => '10100',
            'citta' => 'Torino',
            'cognome_referente' => 'Rossi',
            'nome_referente' => 'Mario',
            'telefono_referente' => '+39 335 1234567',
            'email_referente' => 'mario.rossi@energyservice.it',
            'attivo' => true,
            'note' => 'Azienda specializzata nella gestione e monitoraggio di impianti di riscaldamento condominiali.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crea 2 amministratori per Energy Service
        $this->createAmministratori($azienda, [
            [
                'nome' => 'Francesco',
                'cognome' => 'Romano',
                'email' => 'f.romano@condominiocentro.it',
                'ragione_sociale' => 'Studio Romano - Amministrazioni Condominiali',
                'codice_fiscale' => 'RMNFNC75A15L219K',
                'partita_iva' => '11223344556',
                'telefono_ufficio' => '+39 011 234567',
                'indirizzo_ufficio' => 'Via Garibaldi, 45',
                'cap_ufficio' => '10122',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Romano',
                'nome_referente' => 'Francesco',
                'telefono_referente' => '+39 334 1122334',
                'email_referente' => 'francesco.romano@gmail.com',
                'note' => 'Amministratore con esperienza ventennale nella gestione condominiali zona centro.'
            ],
            [
                'nome' => 'Silvia',
                'cognome' => 'Martinelli',
                'email' => 's.martinelli@admincond.it',
                'ragione_sociale' => 'Martinelli & Associati S.r.l.',
                'codice_fiscale' => 'MRTSLL82B20L219M',
                'partita_iva' => '22334455667',
                'telefono_ufficio' => '+39 011 345678',
                'indirizzo_ufficio' => 'Corso Vittorio Emanuele II, 78',
                'cap_ufficio' => '10121',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Martinelli',
                'nome_referente' => 'Silvia',
                'telefono_referente' => '+39 347 2233445',
                'email_referente' => 'silvia.martinelli@outlook.com',
                'note' => 'Specializzata in condomini di nuova costruzione e riqualificazione energetica.'
            ]
        ]);

        // Crea una seconda azienda di servizio di esempio
        $user2 = User::create([
            'nome' => 'Laura',
            'cognome' => 'Bianchi',
            'email' => 'admin@termoidraulica.it',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telefono' => '+39 011 987654',
            'ruolo' => RuoliOperatoreEnum::azienda_di_servizio->value,
            'attivo' => true,
            'notifiche_email' => true,
            'notifiche_whatsapp' => true,
            'ultimo_accesso' => Carbon::now()->subDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user2->assignRole(RuoliOperatoreEnum::azienda_di_servizio->value);

        $azienda2 = AziendaServizio::create([
            'user_id' => $user2->id,
            'ragione_sociale' => 'Termoidraulica Piemonte S.n.c.',
            'codice_fiscale' => 'TRMDRP85B15L219Y',
            'partita_iva' => '98765432109',
            'telefono' => '+39 011 987654',
            'email_aziendale' => 'amministrazione@termoidraulica.it',
            'indirizzo' => 'Corso Francia, 456',
            'cap' => '10138',
            'citta' => 'Torino',
            'cognome_referente' => 'Bianchi',
            'nome_referente' => 'Laura',
            'telefono_referente' => '+39 348 9876543',
            'email_referente' => 'laura.bianchi@termoidraulica.it',
            'attivo' => true,
            'note' => 'Società specializzata in installazione e manutenzione impianti termici per condomini.',
            'created_at' => Carbon::now()->subMonths(3),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        // Crea 2 amministratori per Termoidraulica Piemonte
        $this->createAmministratori($azienda2, [
            [
                'nome' => 'Roberto',
                'cognome' => 'Ferrero',
                'email' => 'r.ferrero@amministrazioniferrero.it',
                'ragione_sociale' => 'Ferrero Amministrazioni',
                'codice_fiscale' => 'FRRRRT68C12L219P',
                'partita_iva' => '33445566778',
                'telefono_ufficio' => '+39 011 456789',
                'indirizzo_ufficio' => 'Via Nizza, 234',
                'cap_ufficio' => '10126',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Ferrero',
                'nome_referente' => 'Roberto',
                'telefono_referente' => '+39 339 4455667',
                'email_referente' => 'roberto.ferrero@libero.it',
                'note' => 'Amministratore specializzato in condomini di grandi dimensioni zona sud Torino.'
            ],
            [
                'nome' => 'Elena',
                'cognome' => 'Gallo',
                'email' => 'e.gallo@studiogallo.net',
                'ragione_sociale' => 'Studio Tecnico Gallo',
                'codice_fiscale' => 'GLLELR79D25L219R',
                'partita_iva' => '44556677889',
                'telefono_ufficio' => '+39 011 567890',
                'indirizzo_ufficio' => 'Via Po, 156',
                'cap_ufficio' => '10124',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Gallo',
                'nome_referente' => 'Elena',
                'telefono_referente' => '+39 342 5566778',
                'email_referente' => 'elena.gallo@yahoo.it',
                'note' => 'Geometra con abilitazione amministrativa, gestisce prevalentemente palazzine residenziali.'
            ]
        ]);

        // Crea una terza azienda non attiva
        $user3 = User::create([
            'nome' => 'Giuseppe',
            'cognome' => 'Verdi',
            'email' => 'info@caldaieverdi.it',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telefono' => '+39 011 555777',
            'ruolo' => RuoliOperatoreEnum::azienda_di_servizio->value,
            'attivo' => false,
            'notifiche_email' => false,
            'notifiche_whatsapp' => false,
            'ultimo_accesso' => Carbon::now()->subMonths(6),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user3->assignRole(RuoliOperatoreEnum::azienda_di_servizio->value);

        $azienda3 = AziendaServizio::create([
            'user_id' => $user3->id,
            'ragione_sociale' => 'Caldaie Verdi S.r.l.',
            'codice_fiscale' => 'CLDVRD75C20L219Z',
            'partita_iva' => '55566677788',
            'telefono' => '+39 011 555777',
            'email_aziendale' => 'contatti@caldaieverdi.it',
            'indirizzo' => 'Via Po, 789',
            'cap' => '10124',
            'citta' => 'Torino',
            'cognome_referente' => 'Verdi',
            'nome_referente' => 'Giuseppe',
            'telefono_referente' => '+39 333 5557777',
            'email_referente' => 'giuseppe.verdi@caldaieverdi.it',
            'attivo' => false,
            'note' => 'Azienda temporaneamente sospesa per ristrutturazione aziendale.',
            'created_at' => Carbon::now()->subYear(),
            'updated_at' => Carbon::now()->subMonths(6),
        ]);

        // Crea 2 amministratori per Caldaie Verdi (ma non attivi)
        $this->createAmministratori($azienda3, [
            [
                'nome' => 'Marco',
                'cognome' => 'Pellegrini',
                'email' => 'm.pellegrini@pellegriniadmin.it',
                'ragione_sociale' => 'Pellegrini Amministrazioni S.r.l.',
                'codice_fiscale' => 'PLLMRC72E18L219S',
                'partita_iva' => '66677788990',
                'telefono_ufficio' => '+39 011 678901',
                'indirizzo_ufficio' => 'Via Lagrange, 67',
                'cap_ufficio' => '10123',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Pellegrini',
                'nome_referente' => 'Marco',
                'telefono_referente' => '+39 345 6677889',
                'email_referente' => 'marco.pellegrini@tin.it',
                'attivo' => false,
                'note' => 'Sospeso temporaneamente insieme all\'azienda di servizio.'
            ],
            [
                'nome' => 'Carla',
                'cognome' => 'Ricci',
                'email' => 'c.ricci@ricciadmin.com',
                'ragione_sociale' => 'Ricci & Partners',
                'codice_fiscale' => 'RCCCRL77F22L219T',
                'partita_iva' => '77788899001',
                'telefono_ufficio' => '+39 011 789012',
                'indirizzo_ufficio' => 'Corso Dante, 89',
                'cap_ufficio' => '10134',
                'citta_ufficio' => 'Torino',
                'cognome_referente' => 'Ricci',
                'nome_referente' => 'Carla',
                'telefono_referente' => '+39 349 7788990',
                'email_referente' => 'carla.ricci@alice.it',
                'attivo' => false,
                'note' => 'In attesa di riattivazione insieme all\'azienda madre.'
            ]
        ], false);

        $this->command->info('Seeder AziendaServizio completato:');
        $this->command->info('- Creati 3 utenti con aziende di servizio');
        $this->command->info('- Creati 6 amministratori di condominio (2 per azienda)');
        $this->command->info('- Email: admin@energyservice.it | Password: password');
        $this->command->info('- Email: admin@termoidraulica.it | Password: password');
        $this->command->info('- Email: info@caldaieverdi.it | Password: password (Non attiva)');
        $this->command->info('- Amministratori: f.romano@condominiocentro.it, s.martinelli@admincond.it');
        $this->command->info('- Amministratori: r.ferrero@amministrazioniferrero.it, e.gallo@studiogallo.net');
        $this->command->info('- Amministratori: m.pellegrini@pellegriniadmin.it, c.ricci@ricciadmin.com (Non attivi)');
    }

    /**
     * Crea gli amministratori per un'azienda di servizio
     */
    private function createAmministratori(AziendaServizio $azienda, array $amministratoriData, bool $attivi = true): void
    {
        foreach ($amministratoriData as $adminData) {
            // Crea l'utente per l'amministratore
            $userAdmin = User::create([
                'nome' => $adminData['nome'],
                'cognome' => $adminData['cognome'],
                'email' => $adminData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'telefono' => $adminData['telefono_ufficio'],
                'ruolo' => RuoliOperatoreEnum::amministratore_condominio->value,
                'attivo' => $attivi && ($adminData['attivo'] ?? true),
                'notifiche_email' => true,
                'notifiche_whatsapp' => false,
                'ultimo_accesso' => $attivi ? Carbon::now()->subDays(rand(1, 7)) : Carbon::now()->subMonths(rand(3, 8)),
                'created_at' => $azienda->created_at->addDays(rand(1, 30)),
                'updated_at' => now(),
            ]);

            // Assegna il ruolo all'utente
            $userAdmin->assignRole(RuoliOperatoreEnum::amministratore_condominio->value);

            // Crea l'amministratore
            Amministratore::create([
                'azienda_servizio_id' => $azienda->id,
                'user_id' => $userAdmin->id,
                'ragione_sociale' => $adminData['ragione_sociale'],
                'codice_fiscale' => $adminData['codice_fiscale'],
                'partita_iva' => $adminData['partita_iva'],
                'telefono_ufficio' => $adminData['telefono_ufficio'],
                'indirizzo_ufficio' => $adminData['indirizzo_ufficio'],
                'cap_ufficio' => $adminData['cap_ufficio'],
                'citta_ufficio' => $adminData['citta_ufficio'],
                'cognome_referente' => $adminData['cognome_referente'],
                'nome_referente' => $adminData['nome_referente'],
                'telefono_referente' => $adminData['telefono_referente'],
                'email_referente' => $adminData['email_referente'],
                'attivo' => $attivi && ($adminData['attivo'] ?? true),
                'note' => $adminData['note'],
                'created_at' => $userAdmin->created_at,
                'updated_at' => now(),
            ]);
        }
    }
}
