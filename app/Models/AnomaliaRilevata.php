<?php

namespace App\Models;

use App\Enums\TipoAnomaliaEnum;
use App\Enums\SeveritaAnomaliaEnum;
use App\Enums\StatoAnomaliaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnomaliaRilevata extends Model
{
    protected $table = "anomalie_rilevate";

    public const NOME_SINGOLARE = "anomalia rilevata";
    public const NOME_PLURALE = "anomalie rilevate";

    protected $casts = [
        'data_rilevazione' => 'datetime',
        'data_conferma' => 'datetime',
        'data_risoluzione' => 'datetime',
        'dati_tecnici' => 'array',
        'confermata' => 'boolean',
        'valore_rilevato' => 'decimal:3',
        'valore_atteso' => 'decimal:3',
        'tipo_anomalia' => TipoAnomaliaEnum::class,
        'severita' => SeveritaAnomaliaEnum::class,
        'stato' => StatoAnomaliaEnum::class
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(DispositivoMisura::class, 'dispositivo_id');
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function unitaImmobiliare(): BelongsTo
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id');
    }

    public function confermataDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confermata_da_id');
    }

    public function risoltaDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'risolta_da_id');
    }

    public function importazioneCsv(): BelongsTo
    {
        return $this->belongsTo(ImportazioneCsv::class, 'importazione_csv_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'anomalia_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeAperte($query)
    {
        return $query->where('stato', 'aperta');
    }

    public function scopeConfermate($query)
    {
        return $query->where('confermata', true);
    }

    public function scopePerSeverita($query, SeveritaAnomaliaEnum $severita)
    {
        return $query->where('severita', $severita);
    }

    public function scopePerTipo($query, TipoAnomaliaEnum $tipo)
    {
        return $query->where('tipo_anomalia', $tipo);
    }

    public function scopePerStato($query, StatoAnomaliaEnum $stato)
    {
        return $query->where('stato', $stato);
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function badgeSeverita()
    {
        return "<span class='badge badge-light-{$this->severita->colore()}'>{$this->severita->testo()}</span>";
    }

    public function badgeStato()
    {
        return "<span class='badge badge-light-{$this->stato->colore()}'>{$this->stato->testo()}</span>";
    }

    public function badgeTipoAnomalia()
    {
        return "<span class='badge badge-light-{$this->tipo_anomalia->colore()}'>{$this->tipo_anomalia->testo()}</span>";
    }

    /*
    |--------------------------------------------------------------------------
    | METODI DI BUSINESS
    |--------------------------------------------------------------------------
    */

    public function puo_essere_confermata(): bool
    {
        return $this->stato->puo_essere_confermata() && !$this->confermata;
    }

    public function puo_essere_risolta(): bool
    {
        return $this->stato->puo_essere_risolta() && $this->confermata;
    }

    public function puo_essere_modificata(): bool
    {
        return $this->stato->puo_essere_modificata();
    }

    public function conferma($user_id, $note = null): bool
    {
        if (!$this->puo_essere_confermata()) {
            return false;
        }

        $this->confermata = true;
        $this->confermata_da_id = $user_id;
        $this->data_conferma = now();
        $this->stato = StatoAnomaliaEnum::confermata;

        if ($note) {
            $this->note_risoluzione = $note;
        }

        return $this->save();
    }

    public function risolvi($user_id, $note): bool
    {
        if (!$this->puo_essere_risolta()) {
            return false;
        }

        $this->stato = StatoAnomaliaEnum::risolta;
        $this->risolta_da_id = $user_id;
        $this->data_risoluzione = now();
        $this->note_risoluzione = $note;

        return $this->save();
    }

    public function marcaComeFalsoPositivo($user_id, $note): bool
    {
        if ($this->stato === StatoAnomaliaEnum::risolta) {
            return false;
        }

        $this->stato = StatoAnomaliaEnum::falso_positivo;
        $this->risolta_da_id = $user_id;
        $this->data_risoluzione = now();
        $this->note_risoluzione = $note;

        return $this->save();
    }

    public function prendiInVerifica($user_id): bool
    {
        if ($this->stato !== StatoAnomaliaEnum::aperta) {
            return false;
        }

        $this->stato = StatoAnomaliaEnum::in_verifica;
        $this->confermata_da_id = $user_id;

        return $this->save();
    }

    public function tempoApertura(): string
    {
        return $this->data_rilevazione->diffForHumans();
    }

    public function ha_ticket_associato(): bool
    {
        return $this->tickets()->exists();
    }

    public function richiede_intervento_immediato(): bool
    {
        return $this->severita === SeveritaAnomaliaEnum::critica ||
            $this->tipo_anomalia->richiede_intervento_tecnico();
    }

    public function tempo_risposta_massimo(): int
    {
        return $this->severita->tempo_risposta_ore();
    }

    public function in_ritardo(): bool
    {
        $ore_trascorse = $this->data_rilevazione->diffInHours(now());
        return $ore_trascorse > $this->tempo_risposta_massimo();
    }
}

