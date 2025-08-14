<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use function App\humanFileSize;

class Documento extends Model
{

    const NOME_SINGOLARE = 'documento';
    const NOME_PLURALE = 'documenti';

    protected $table = 'documenti';

    protected $casts = [
        'utenti_autorizzati' => 'array',
        'data_scadenza' => 'date',
        'ultima_visualizzazione' => 'datetime',
        'pubblico' => 'boolean',
        'riservato_amministratori' => 'boolean',
        'notifica_scadenza' => 'boolean',
    ];

    protected $dates = [
        'data_scadenza',
        'ultima_visualizzazione'
    ];

    // Relazioni
    public function caricatoDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caricato_da_id');
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class);
    }

    public function unitaImmobiliare(): BelongsTo
    {
        return $this->belongsTo(UnitaImmobiliare::class);
    }

    public function bollettino(): BelongsTo
    {
        return $this->belongsTo(Bollettino::class);
    }

    public function visualizzazioni(): HasMany
    {
        return $this->hasMany(VisualizzazioneDocumento::class);
    }

    // Scopes
    public function scopeAttivi($query)
    {
        return $query->where('stato', 'attivo');
    }

    public function scopePubblici($query)
    {
        return $query->where('pubblico', true);
    }

    public function scopeRiservatiAmministratori($query)
    {
        return $query->where('riservato_amministratori', true);
    }

    public function scopePerImpianto($query, $impiantoId)
    {
        return $query->where('impianto_id', $impiantoId);
    }

    public function scopePerTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    // Metodi helper
    public function puo_visualizzare_utente($userId): bool
    {
        // Se è pubblico, può vedere tutti dell'impianto
        if ($this->pubblico) {
            return true;
        }

        // Se è caricato dall'utente stesso
        if ($this->caricato_da_id == $userId) {
            return true;
        }

        // Se è negli utenti autorizzati
        if ($this->utenti_autorizzati && in_array($userId, $this->utenti_autorizzati)) {
            return true;
        }

        return false;
    }

    public function incrementa_visualizzazioni($userId = null)
    {
        $this->increment('numero_visualizzazioni');
        $this->ultima_visualizzazione = now();
        $this->save();

        if ($userId) {
            // Cerca visualizzazione esistente per oggi
            $visualizzazione = VisualizzazioneDocumento::where('documento_id', $this->id)
                ->where('user_id', $userId)
                ->whereDate('data_visualizzazione', now())
                ->first();

            if (!$visualizzazione) {
                $visualizzazione = new VisualizzazioneDocumento();
                $visualizzazione->documento_id = $this->id;
                $visualizzazione->user_id = $userId;
                $visualizzazione->data_visualizzazione = now();
                $visualizzazione->ip_address = request()->ip();
                $visualizzazione->save();
            }
        }
    }

    public function segna_come_scaricato($userId)
    {
        $visualizzazione = VisualizzazioneDocumento::where('documento_id', $this->id)
            ->where('user_id', $userId)
            ->whereDate('data_visualizzazione', now())
            ->first();

        if ($visualizzazione) {
            $visualizzazione->scaricato = true;
            $visualizzazione->data_download = now();
            $visualizzazione->save();
        }
    }

    public function dimensione_leggibile(): string
    {


        return humanFileSize($this->dimensione_file);
    }

    public function is_pdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function is_immagine(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function url_download(): string
    {
        return route('documento.download', $this->id);
    }

    public function in_scadenza(): bool
    {
        if (!$this->data_scadenza) {
            return false;
        }

        return $this->data_scadenza->diffInDays(now()) <= 30;
    }

    public function scaduto(): bool
    {
        if (!$this->data_scadenza) {
            return false;
        }

        return $this->data_scadenza->isPast();
    }
}
