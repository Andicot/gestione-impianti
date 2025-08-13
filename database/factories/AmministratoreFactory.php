<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Amministratore>
 */
class AmministratoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'azienda_servizio_id' => '',
            'ragione_sociale' => $this->faker->company(),
            'codice_fiscale' => CfPiRandom::getCodiceFiscale(),
            'partita_iva' => CfPiRandom::getPartitaIva(),
            'telefono_ufficio' => $this->faker->phoneNumber(),
            'indirizzo_ufficio' => $this->faker->streetName(),
            'cap_ufficio' => rand(11111, 55555),
            'citta_ufficio' => Comune::inRandomOrder()->first()->id,
            'cognome_referente' => $this->faker->lastName,
            'nome_referente' => $this->faker->firstName,
            'telefono_referente' => $this->faker->phoneNumber(),
            'attivo' => rand(0, 1),
            'note' => '',

        ];
    }
}
