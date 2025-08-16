<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    protected $table = "tickets";

    public const NOME_SINGOLARE = "ticket";
    public const NOME_PLURALE = "tickets";

    protected $casts = [
        'data_chiusura' => 'datetime',
        'dispositivi_coinvolti' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function creadoDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creato_da_id');
    }

    public function assegnatoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assegnato_a_id');
    }

    public function chiusoDa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chiuso_da_id');
    }

    public function unitaImmobiliare(): BelongsTo
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id');
    }

    public function impianto(): BelongsTo
    {
        return $this->belongsTo(Impianto::class, 'impianto_id');
    }

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(DispositivoMisura::class, 'dispositivo_id');
    }

    public function anomalia(): BelongsTo
    {
        return $this->belongsTo(AnomaliaRilevata::class, 'anomalia_id');
    }

    public function risposte(): HasMany
    {
        return $this->hasMany(TicketRisposta::class, 'ticket_id')->orderBy('created_at');
    }

    public function risposteVisibili(): HasMany
    {
        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::tryFrom($user->ruolo ?? '');

        if ($ruolo === RuoliOperatoreEnum::condomino) {
            return $this->hasMany(TicketRisposta::class, 'ticket_id')
                ->where('visibile_condomino', true)
                ->orderBy('created_at');
        }

        return $this->risposte();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeAperti($query)
    {
        return $query->where('stato', 'aperto');
    }

    public function scopeInLavorazione($query)
    {
        return $query->where('stato', 'in_lavorazione');
    }

    public function scopeUrgenti($query)
    {
        return $query->where('priorita', 'urgente');
    }

    public function scopeDelCondomino($query, $userId)
    {
        return $query->where('creato_da_id', $userId);
    }

    public function scopeAssegnatiA($query, $userId)
    {
        return $query->where('assegnato_a_id', $userId);
    }

    public function scopeTecnici($query)
    {
        return $query->whereIn('categoria', [
            'errore_dispositivo',
            'comunicazione_concentratore',
            'manutenzione',
            'tecnico'
        ]);
    }

    public function scopeAmministrativi($query)
    {
        return $query->whereIn('categoria', [
            'bollette',
            'pagamenti',
            'altro'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public function badgeStato()
    {
        $colori = [
            'aperto' => 'warning',
            'in_lavorazione' => 'info',
            'risolto' => 'success',
            'chiuso' => 'secondary'
        ];

        $colore = $colori[$this->stato] ?? 'secondary';
        $testo = ucfirst(str_replace('_', ' ', $this->stato));

        return '<span class="badge badge-' . $colore . ' fw-bolder">' . $testo . '</span>';
    }

    public function badgePriorita()
    {
        $colori = [
            'bassa' => 'success',
            'media' => 'warning',
            'alta' => 'danger',
            'urgente' => 'dark'
        ];

        $colore = $colori[$this->priorita] ?? 'secondary';
        $testo = ucfirst($this->priorita);

        return '<span class="badge badge-' . $colore . ' fw-bolder">' . $testo . '</span>';
    }

    public function badgeCategoria()
    {
        $categorie = [
            'errore_dispositivo' => 'Errore Dispositivo',
            'letture_anomale' => 'Letture Anomale',
            'bollette' => 'Bollette',
            'pagamenti' => 'Pagamenti',
            'comunicazione_concentratore' => 'Comunicazione Concentratore',
            'manutenzione' => 'Manutenzione',
            'tecnico' => 'Tecnico',
            'altro' => 'Altro'
        ];

        $testo = $categorie[$this->categoria] ?? ucfirst($this->categoria);

        return '<span class="badge badge-light-primary">' . $testo . '</span>';
    }

    public function testoOrigine()
    {
        $origini = [
            'condomino' => 'Condomino',
            'amministratore' => 'Amministratore',
            'sistema_automatico' => 'Sistema Automatico'
        ];

        return $origini[$this->origine] ?? ucfirst($this->origine);
    }

    public function numeroTicket()
    {
        return '#' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function tempoApertura()
    {
        if ($this->stato === 'chiuso' && $this->data_chiusura) {
            $diff = $this->created_at->diff($this->data_chiusura);

            if ($diff->days > 0) {
                return $diff->days . ' giorni';
            } elseif ($diff->h > 0) {
                return $diff->h . ' ore';
            } else {
                return $diff->i . ' minuti';
            }
        }

        return $this->created_at->diffForHumans();
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

    public function puo_essere_modificato()
    {
        $user = Auth::user();

        // Admin può modificare tutto
        if ($user->ruolo === 'admin') {
            return true;
        }

        // Il creatore può modificare solo se non ha risposte
        if ($this->creato_da_id === $user->id) {
            return $this->risposte()->count() === 0;
        }

        // Assegnatario può modificare solo stato e priorità
        if ($this->assegnato_a_id === $user->id) {
            return true;
        }

        return false;
    }

    public function puo_essere_eliminato()
    {
        $user = Auth::user();

        // Solo admin o creatore senza risposte
        if ($user->ruolo === 'admin') {
            return true;
        }

        if ($this->creato_da_id === $user->id && $this->risposte()->count() === 0) {
            return true;
        }

        return false;
    }

    public function puo_essere_chiuso()
    {
        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::tryFrom($user->ruolo ?? '');

        // Solo certi ruoli possono chiudere
        if (!in_array($ruolo, [
            RuoliOperatoreEnum::admin,
            RuoliOperatoreEnum::azienda_di_servizio,
            RuoliOperatoreEnum::amministratore_condominio,
            RuoliOperatoreEnum::responsabile_impianto
        ])) {
            return false;
        }

        // Non può essere già chiuso
        if ($this->stato === 'chiuso') {
            return false;
        }

        return true;
    }

    public function puo_aggiungere_risposta()
    {
        $user = Auth::user();

        // Non si può rispondere a ticket chiusi
        if ($this->stato === 'chiuso') {
            return false;
        }

        // Il creatore può sempre rispondere
        if ($this->creato_da_id === $user->id) {
            return true;
        }

        // L'assegnatario può sempre rispondere
        if ($this->assegnato_a_id === $user->id) {
            return true;
        }

        // Admin può sempre rispondere
        if ($user->ruolo === 'admin') {
            return true;
        }

        return false;
    }

    public function getDispositiviCoinvoltiDettaglio()
    {
        if (!$this->dispositivi_coinvolti) {
            return collect();
        }

        return DispositivoMisura::whereIn('id', $this->dispositivi_coinvolti)
            ->with(['unitaImmobiliare', 'impianto'])
            ->get();
    }

    public function isTecnico()
    {
        return in_array($this->categoria, [
            'errore_dispositivo',
            'comunicazione_concentratore',
            'manutenzione',
            'tecnico'
        ]);
    }

    public function isAmministrativo()
    {
        return in_array($this->categoria, [
            'bollette',
            'pagamenti',
            'altro'
        ]);
    }

    public function hasAllegati()
    {
        return $this->risposte()->whereNotNull('allegati')->count() > 0;
    }
}
