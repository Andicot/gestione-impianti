<?php

namespace Database\Seeders;

use App\Models\ResponsabileImpianto;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ElencoNazioniTableSeeder::class);
        $this->call(ElencoProvinceTableSeeder::class);
        $this->call(ElencoComuniTableSeeder::class);

        $this->call(RuoliSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(AziendaServizioSeeder::class);

        // Creazione responsabili impianto con relativi user
        $responsabili = ResponsabileImpianto::factory(2)->create();

        foreach ($responsabili as $responsabile) {
            $user = new User();
            $user->nome = $responsabile->nome;
            $user->cognome = $responsabile->cognome;
            $user->email = $responsabile->email;
            $user->password = Hash::make('password'); // Password di default
            $user->ruolo = 'responsabile_impianto'; // Assumendo che esista questo ruolo
            $user->attivo = true;
            $user->email_verified_at = now();
            $user->save();

            // Aggiorna il responsabile con l'ID del user creato
            $responsabile->user_id = $user->id;
            $responsabile->save();
        }

        $this->call(ImpiantiSeeder::class);
    }
}
