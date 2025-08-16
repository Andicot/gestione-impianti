<?php

namespace App\Models;

use App\Enums\StatoPagamentoBollettinoEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bollettino extends Model
{
    protected $table = "bollettini";

    public const NOME_SINGOLARE = "bollettino";
    public const NOME_PLURALE = "bollettini";

    protected $casts = [
        'importo' => 'decimal:2',
        'importo_pagato' => 'decimal:2',
        'data_visualizzazione' => 'datetime',
        'data_scadenza' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($bollettino) {
            // Elimina il file PDF se esiste
            if ($bollettino->path_file && \Storage::exists($bollettino->path_file)) {
                \Storage::delete($bollettino->path_file);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function aziendaServizio(): BelongsTo
    {
        return $this->belongsTo(AziendaServizio::class, 'azienda_servizio_id');
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function unitaImmobiliare(): BelongsTo
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(PeriodoContabilizzazione::class, 'periodo_id');
    }

    public function caricatoDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caricato_da_id');
    }

    public function pagamenti(): HasMany
    {
        return $this->hasMany(Pagamento::class, 'bollettino_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeScaduti($query)
    {
        return $query->where('data_scadenza', '<', now())
            ->where('stato_pagamento', '!=', 'pagato');
    }

    public function scopeNonPagati($query)
    {
        return $query->where('stato_pagamento', 'non_pagato');
    }

    public function scopePagati($query)
    {
        return $query->where('stato_pagamento', 'pagato');
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function badgeStatoPagamento()
    {
        $stato = StatoPagamentoBollettinoEnum::tryFrom($this->stato_pagamento);
        if($stato )
        return '<span class="badge badge-light-' . $stato->colore() . ' fw-bolder">' . $stato->testo() . '</span>';
    }


}
