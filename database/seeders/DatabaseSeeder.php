<?php

namespace Database\Seeders;

use App\Models\ResponsabileImpianto;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ResponsabileImpianto::factory(10)->create();
        $this->call(ImpiantiSeeder::class);
    }
}
