<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concentratore>
 */
class ConcentratoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'marca' => '',
            'modello' => '',
            'matricola' => '',
            'frequenza_scansione' => '',
            'stato' => '',
            'ip_connessione' => '',
            'ip_invio_csv' => '',
            'endpoint_csv' => '',
            'token_autenticazione' => '',
            'ultima_comunicazione' => '',
            'ultimo_csv_ricevuto' => '',
            'note' => '',

        ];
    }
}
