<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DispositivoMisura>
 */
class DispositivoMisuraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricola' => '',
            'nome_dispositivo' => $this->faker->firstName,
            'descrizione_1' => '',
            'descrizione_2' => '',
            'marca' => '',
            'modello' => '',
            'tipo' => '',
            'offset' => '',
            'data_installazione' => '',
            'stato' => '',
            'ubicazione' => '',
            'unita_immobiliare_id' => '',
            'impianto_id' => '',
            'concentratore_id' => '',
            'ultimo_valore_rilevato' => '',
            'data_ultima_lettura' => '',
            'creato_automaticamente' => rand(0, 1),
            'note' => '',

        ];
    }
}
