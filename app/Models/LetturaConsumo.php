<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
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
    protected static function booted()
    {
        static::addGlobalScope(new FiltroOperatoreScope());
    }

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

    public function data_lettura_formattata(): string
    {
        return $this->data_lettura->format('d/m/Y');
    }

    public function ora_lettura_formattata(): ?string
    {
        return $this->ora_lettura?->format('H:i');
    }

    public function consumo_calcolato(): float
    {
        return $this->udr_attuale - $this->udr_precedente;
    }

    public function costo_calcolato(): float
    {
        return $this->consumo_calcolato() * $this->costo_unitario;
    }

    public function ha_unita_immobiliare(): bool
    {
        return $this->unita_immobiliare_id !== null;
    }

    public function descrizione_posizione(): string
    {
        if ($this->ha_unita_immobiliare() && $this->unitaImmobiliare) {
            $unita = $this->unitaImmobiliare;
            return ($unita->scala ? "Scala {$unita->scala}, " : "") .
                "Piano {$unita->piano}, Int. {$unita->interno}" .
                ($unita->nominativo_unita ? " - {$unita->nominativo_unita}" : "");
        }

        return "Dispositivo {$this->dispositivo->matricola}" .
            ($this->ambiente ? " ({$this->ambiente})" : "") .
            " - Unità da associare";
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */
}
