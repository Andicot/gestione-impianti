<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportazioneCsv extends Model
{
    protected $table = "importazioni_csv";

    public const NOME_SINGOLARE = "importazione CSV";
    public const NOME_PLURALE = "importazioni CSV";

    protected $casts = [
        'log_errori' => 'array',
        'metadata_csv' => 'array',
        'ip_mittente' => 'string'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function concentratore(): BelongsTo
    {
        return $this->belongsTo(Concentratore::class, 'concentratore_id');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(PeriodoContabilizzazione::class, 'periodo_id');
    }

    public function caricatoDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caricato_da_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeCompletate($query)
    {
        return $query->where('stato', 'completato');
    }

    public function scopeConErrori($query)
    {
        return $query->where('stato', 'con_errori'); // Era: completato_con_errori
    }

    public function scopeInElaborazione($query)
    {
        return $query->where('stato', 'in_elaborazione');
    }

    public function scopeConErroreGrave($query)
    {
        return $query->where('stato', 'errore');
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function badgeStato()
    {
        return match ($this->stato) {
            'completato' => '<span class="badge badge-success">Completato</span>',
            'con_errori' => '<span class="badge badge-warning">Con Errori</span>', // Era: completato_con_errori
            'in_elaborazione' => '<span class="badge badge-primary">In Elaborazione</span>',
            'errore' => '<span class="badge badge-danger">Errore</span>',
            default => '<span class="badge badge-secondary">' . ucfirst($this->stato) . '</span>'
        };
    }

    public function iconaTipoFile()
    {
        if (str_contains($this->nome_file, '.csv')) {
            return '<i class="fas fa-file-csv text-success"></i>';
        }

        return '<i class="fas fa-file-excel text-primary"></i>';
    }

    public function riepilogoRighe()
    {
        $html = '<div class="text-dark fw-bolder">' . number_format($this->righe_elaborate) . '</div>';

        if ($this->righe_errore > 0) {
            $html .= '<div class="text-danger fs-7">' . number_format($this->righe_errore) . ' errori</div>';
        }

        if ($this->dispositivi_nuovi > 0) {
            $html .= '<div class="text-success fs-7">' . number_format($this->dispositivi_nuovi) . ' nuovi</div>';
        }

        return $html;
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

    public function percentualeSuccesso()
    {
        if ($this->righe_totali == 0) {
            return 0;
        }

        return round(($this->righe_elaborate / $this->righe_totali) * 100, 2);
    }

    public function percentualeErrori()
    {
        if ($this->righe_totali == 0) {
            return 0;
        }

        return round(($this->righe_errore / $this->righe_totali) * 100, 2);
    }

    public function haErrori()
    {
        return $this->righe_errore > 0 || $this->stato === 'errore';
    }

    public function isCompletata()
    {
        return in_array($this->stato, ['completato', 'con_errori']); // Era: completato_con_errori
    }

    public function tempoElaborazione()
    {
        if (!$this->isCompletata()) {
            return null;
        }

        return $this->created_at->diffInSeconds($this->updated_at);
    }
}
