<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Impianto>
 */
class ImpiantoFactory extends Factory
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
            'amministratore_id' => '',
            'nome_impianto' => $this->faker->firstName,
            'indirizzo' => $this->faker->streetName(),
            'cap' => rand(11111, 55555),
            'citta' => Comune::inRandomOrder()->first()->id,
            'stato' => '',
            'tipologia' => '',
            'criterio_ripartizione_numerica' => '',
            'percentuale_quota_fissa' => '',
            'servizio' => '',
            'note' => '',

        ];
    }
}
