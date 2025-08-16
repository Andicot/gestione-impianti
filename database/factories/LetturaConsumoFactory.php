<?php

namespace Database\Factories;

use App\Models\Comune;
use Database\Seeders\CfPiRandom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LetturaConsumo>
 */
class LetturaConsumoFactory extends Factory
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
     'dispositivo_id'=>'',
     'tipo_consumo'=>'',
     'categoria'=>'',
     'udr_attuale'=>'',
     'udr_precedente'=>'',
     'differenza_consumi'=>'',
     'unita_misura'=>'',
     'costo_unitario'=>'',
     'costo_totale'=>'',
     'errore'=>rand(0,1),
     'descrizione_errore'=>'',
     'anomalia'=>rand(0,1),
     'importazione_csv_id'=>'',
     'data_lettura'=>'',
     'ora_lettura'=>'',
     'comfort_termico_attuale'=>'',
     'temp_massima_sonde'=>'',
     'data_registrazione_temp_max'=>'',
     'temp_tecnica_tt16'=>'',
     'comfort_termico_periodo_prec'=>'',
     'temp_media_calorifero_prec'=>'',
     'udr_storico_1'=>'',
     'udr_totali'=>'',
     'data_ora_dispositivo'=>'',

        ];
    }
}
