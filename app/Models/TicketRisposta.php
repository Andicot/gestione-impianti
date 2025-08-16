<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TicketRisposta extends Model
{
    protected $table = "ticket_risposte";

    public const NOME_SINGOLARE = "risposta ticket";
    public const NOME_PLURALE = "risposte ticket";

    protected $casts = [
        'allegati' => 'array',
        'visibile_condomino' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function autore(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autore_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeVisibiliAlCondomino($query)
    {
        return $query->where('visibile_condomino', true);
    }

    public function scopeDelTicket($query, $ticketId)
    {
        return $query->where('ticket_id', $ticketId);
    }

    public function scopeRecenti($query, $giorni = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($giorni));
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function badgeAutore()
    {
        $ruolo = RuoliOperatoreEnum::tryFrom($this->autore->ruolo ?? '');
        if (!$ruolo) {
            return '<span class="badge badge-secondary">Utente</span>';
        }

        return '<span class="badge badge-' . $ruolo->colore() . '">' . $ruolo->testo() . '</span>';
    }

    public function iconaVisibilita()
    {
        if ($this->visibile_condomino) {
            return '<i class="fas fa-eye text-success" title="Visibile al condomino"></i>';
        } else {
            return '<i class="fas fa-eye-slash text-warning" title="Non visibile al condomino"></i>';
        }
    }

    public function dataFormattata()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function tempoTrascorso()
    {
        return $this->created_at->diffForHumans();
    }

    public function messaggioFormattato()
    {
        // Converte a capo in <br> per visualizzazione HTML
        return nl2br(e($this->messaggio));
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

    public function puo_essere_modificata()
    {
        $user = Auth::user();

        // Solo l'autore può modificare entro 15 minuti dalla creazione
        if ($this->autore_id === $user->id) {
            return $this->created_at->diffInMinutes(now()) <= 15;
        }

        // Admin può sempre modificare
        if ($user->ruolo === 'admin') {
            return true;
        }

        return false;
    }

    public function puo_essere_eliminata()
    {
        $user = Auth::user();

        // Solo l'autore può eliminare entro 5 minuti dalla creazione
        if ($this->autore_id === $user->id) {
            return $this->created_at->diffInMinutes(now()) <= 5;
        }

        // Admin può sempre eliminare
        if ($user->ruolo === 'admin') {
            return true;
        }

        return false;
    }

    public function hasAllegati()
    {
        return !empty($this->allegati);
    }

    public function getAllegatiDettaglio()
    {
        if (!$this->hasAllegati()) {
            return collect();
        }

        return collect($this->allegati)->map(function($allegato) {
            return [
                'nome' => $allegato['nome'] ?? 'File',
                'path' => $allegato['path'] ?? '',
                'size' => $allegato['size'] ?? 0,
                'mime' => $allegato['mime'] ?? 'application/octet-stream',
                'url' => asset('storage/' . ($allegato['path'] ?? ''))
            ];
        });
    }

    public function èRispostaTecnica()
    {
        $ruolo = RuoliOperatoreEnum::tryFrom($this->autore->ruolo ?? '');

        return in_array($ruolo, [
            RuoliOperatoreEnum::responsabile_impianto,
            RuoliOperatoreEnum::azienda_di_servizio
        ]);
    }

    public function èRispostaAmministrativa()
    {
        $ruolo = RuoliOperatoreEnum::tryFrom($this->autore->ruolo ?? '');

        return $ruolo === RuoliOperatoreEnum::amministratore_condominio;
    }

    public function èRispostaCondomino()
    {
        $ruolo = RuoliOperatoreEnum::tryFrom($this->autore->ruolo ?? '');

        return $ruolo === RuoliOperatoreEnum::condomino;
    }

    public function iconaTipoRisposta()
    {
        if ($this->èRispostaTecnica()) {
            return '<i class="fas fa-tools text-primary" title="Risposta tecnica"></i>';
        }

        if ($this->èRispostaAmministrativa()) {
            return '<i class="fas fa-building text-warning" title="Risposta amministrativa"></i>';
        }

        if ($this->èRispostaCondomino()) {
            return '<i class="fas fa-user text-success" title="Risposta condomino"></i>';
        }

        return '<i class="fas fa-comment text-secondary" title="Risposta"></i>';
    }

    protected static function booted()
    {
        // Quando viene creata una risposta, aggiorna il timestamp del ticket
        static::created(function ($risposta) {
            $risposta->ticket->touch();

            // Se il ticket era "aperto" e riceve una risposta, passa a "in_lavorazione"
            if ($risposta->ticket->stato === \App\Enums\StatoTicketEnum::aperto) {
                $risposta->ticket->stato = \App\Enums\StatoTicketEnum::in_lavorazione;
                $risposta->ticket->save();
            }
        });
    }
}
