<?php

namespace Database\Factories;

use App\Enums\StatoGenericoEnum;
use App\Models\AziendaServizio;
use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResponsabileImpianto>
 */
class ResponsabileImpiantoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'azienda_servizio_id' => rand(1, 3),
            'cognome' => $this->faker->lastName,
            'nome' => $this->faker->firstName,
            'codice_fiscale' => CfPiRandom::getCodiceFiscale(),
            'telefono' => $this->faker->phoneNumber(),
            'cellulare' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'attivo' => rand(0, 1),
            'note' => '',
        ];
    }
}
