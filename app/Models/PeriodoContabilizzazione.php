<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoContabilizzazione extends Model
{
    protected $table = "periodi_contabilizzazione";

    public const NOME_SINGOLARE = "periodo contabilizzazione";
    public const NOME_PLURALE = "periodi contabilizzazione";

    protected $casts = [
        'data_inizio' => 'date',
        'data_fine' => 'date'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function impianto()
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function importazioniCsv(): HasMany
    {
        return $this->hasMany(ImportazioneCsv::class, 'periodo_id');
    }

    public function lettureConsumi(): HasMany
    {
        return $this->hasMany(LetturaConsumo::class, 'periodo_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeAttivi($query)
    {
        return $query->where('stato', 'in_corso');
    }

    public function scopeCompletati($query)
    {
        return $query->where('stato', 'completato');
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function nomeCompleto()
    {
        return $this->data_inizio->format('d/m/Y') . ' - ' . $this->data_fine->format('d/m/Y');
    }

    public function badgeStato()
    {
        return match ($this->stato) {
            'in_corso' => '<span class="badge badge-primary">In Corso</span>',
            'completato' => '<span class="badge badge-success">Completato</span>',
            'archiviato' => '<span class="badge badge-secondary">Archiviato</span>',
            default => '<span class="badge badge-light">' . ucfirst($this->stato) . '</span>'
        };
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

    public function durataGiorni()
    {
        return $this->data_inizio->diffInDays($this->data_fine) + 1;
    }

    public function isAttivo()
    {
        return $this->stato === 'in_corso';
    }

    public function isCompletato()
    {
        return $this->stato === 'completato';
    }
}
