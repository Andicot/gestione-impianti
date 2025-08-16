<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bollettino>
 */
class BollettinoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                 'unita_immobiliare_id'=>'',
     'periodo_id'=>'',
     'importo'=>'',
     'metodo_pagamento'=>'',
     'note'=>'',
     'pdf_allegato'=>'',
     'nome_file_originale'=>$this->faker->firstName,
     'mime_type'=>'',
     'dimensione_file'=>'',
     'caricato_da_id'=>'',
     'data_caricamento'=>'',
     'visualizzato'=>rand(0,1),
     'data_visualizzazione'=>'',
     'stato_pagamento'=>'',
     'importo_pagato'=>'',
     'data_scadenza'=>'',

        ];
    }
}
