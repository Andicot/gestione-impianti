<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitaImmobiliare>
 */
class UnitaImmobiliareFactory extends Factory
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
            'impianto_id' => '',
            'scala' => '',
            'piano' => '',
            'interno' => '',
            'nominativo_unita' => '',
            'tipologia' => '',
            'millesimi_riscaldamento' => '',
            'millesimi_acs' => '',
            'metri_quadri' => '',
            'corpo_scaldante' => '',
            'contatore_acs_numero' => '',
            'note' => '',

        ];
    }
}
