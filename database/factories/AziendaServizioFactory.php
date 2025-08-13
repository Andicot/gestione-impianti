<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AziendaServizio>
 */
class AziendaServizioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ragione_sociale' => $this->faker->company(),
            'codice_fiscale' => CfPiRandom::getCodiceFiscale(),
            'partita_iva' => CfPiRandom::getPartitaIva(),
            'telefono' => $this->faker->phoneNumber(),
            'email_aziendale' => $this->faker->unique()->safeEmail(),
            'indirizzo' => $this->faker->streetName(),
            'cap' => '',
            'citta' => '',
            'cognome_referente' => $this->faker->lastName,
            'nome_referente' => $this->faker->firstName,
            'telefono_referente' => $this->faker->phoneNumber(),
            'email_referente' => $this->faker->unique()->safeEmail(),
            'attivo' => rand(0, 1),
            'note' => '',

        ];
    }
}
