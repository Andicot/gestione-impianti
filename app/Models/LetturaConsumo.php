<?php

namespace App\Models;

use App\Enums\CategoriaConsumoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetturaConsumo extends Model
{
    use HasFactory;

    protected $table = "letture_consumi";

    public const NOME_SINGOLARE = "lettura consumo";
    public const NOME_PLURALE = "letture consumi";

    protected $casts = [
        'udr_attuale' => 'decimal:3',
        'udr_precedente' => 'decimal:3',
        'differenza_consumi' => 'decimal:3',
        'costo_unitario' => 'decimal:4',
        'costo_totale' => 'decimal:2',
        'errore' => 'boolean',
        'anomalia' => 'boolean',
        'data_lettura' => 'date',
        'ora_lettura' => 'datetime',
        'comfort_termico_attuale' => 'decimal:2',
        'temp_massima_sonde' => 'decimal:2',
        'data_registrazione_temp_max' => 'date',
        'temp_tecnica_tt16' => 'decimal:2',
        'comfort_termico_periodo_prec' => 'decimal:2',
        'temp_media_calorifero_prec' => 'decimal:2',
        'udr_storico_1' => 'decimal:3',
        'udr_totali' => 'decimal:3',
        'data_ora_dispositivo' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function unitaImmobiliare(): BelongsTo
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(PeriodoContabilizzazione::class, 'periodo_id');
    }

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(DispositivoMisura::class, 'dispositivo_id');
    }

    public function importazioneCsv(): BelongsTo
    {
        return $this->belongsTo(ImportazioneCsv::class, 'importazione_csv_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */


    public function consumo_calcolato(): float
    {
        return $this->udr_attuale - $this->udr_precedente;
    }

    public function badgeCategoria()
    {
        $stato = CategoriaConsumoEnum::tryFrom($this->stato);
        return '<span class="badge badge-light-' . $stato->colore() . ' fw-bolder">' . $stato->testo() . '</span>';
    }


    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */
}
