<?php

namespace App\Http\Controllers;

use App\Enums\RuoliOperatoreEnum;
use App\Http\MieClassi\DatiRitorno;
use App\Models\Impianto;
use App\Models\Ticket;
use App\Models\TicketRisposta;
use App\Models\UnitaImmobiliare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $conFiltro = false;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = Ticket::with(['creadoDa', 'assegnatoA', 'unitaImmobiliare', 'impianto'])
            ->where(function ($query) {
                $user = Auth::user();
                $ruolo = RuoliOperatoreEnum::from($user->ruolo);

                switch ($ruolo) {
                    case RuoliOperatoreEnum::admin:
                        // Admin vede tutti i tickets
                        break;
                    case RuoliOperatoreEnum::azienda_di_servizio:
                        // Vede tickets degli impianti della sua azienda
                        $query->whereHas('impianto', function ($q) use ($user) {
                            $q->whereHas('aziendaServizio', function ($subq) use ($user) {
                                $subq->where('user_id', $user->id);
                            });
                        });
                        break;
                    case RuoliOperatoreEnum::amministratore_condominio:
                        // Vede tickets degli impianti che amministra
                        $query->whereHas('impianto', function ($q) use ($user) {
                            $q->whereHas('amministratore', function ($subq) use ($user) {
                                $subq->where('user_id', $user->id);
                            });
                        });
                        break;
                    case RuoliOperatoreEnum::responsabile_impianto:
                        // Vede tickets tecnici assegnati a lui
                        $query->where('assegnato_a_id', $user->id)
                            ->whereIn('categoria', ['errore_dispositivo', 'comunicazione_concentratore', 'manutenzione', 'tecnico']);
                        break;
                    case RuoliOperatoreEnum::condomino:
                        // Vede solo i suoi tickets
                        $query->where('creato_da_id', $user->id);
                        break;
                }
            })
            ->when($request->filled('stato'), function ($query) use ($request) {
                $query->where('stato', $request->stato);
            })
            ->when($request->filled('priorita'), function ($query) use ($request) {
                $query->where('priorita', $request->priorita);
            })
            ->when($request->filled('categoria'), function ($query) use ($request) {
                $query->where('categoria', $request->categoria);
            })
            ->when($request->filled('cerca'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('titolo', 'like', '%' . $request->cerca . '%')
                        ->orWhere('descrizione', 'like', '%' . $request->cerca . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate()
            ->withQueryString();

        $statistiche = $this->calcolaStatistiche();

        return view('Ticket.index', [
            'records' => $records,
            'controller' => TicketController::class,
            'titoloPagina' => 'Elenco ' . Ticket::NOME_PLURALE,
            'statistiche' => $statistiche,
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => $this->puoCreareTicket() ? 'Nuovo ticket' : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        return view('Ticket.edit', [
            'record' => new Ticket(),
            'controller' => TicketController::class,
            'titoloPagina' => 'Nuovo ' . Ticket::NOME_SINGOLARE,
            'breadcrumbs' => [action([TicketController::class, 'index']) => 'Torna a elenco ' . Ticket::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));

        $record = new Ticket();
        $this->salvaDati($record, $request);

        // Assegnazione automatica
        $this->assegnaAutomaticamente($record);

        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Ticket::with([
            'creadoDa',
            'assegnatoA',
            'chiusoDa',
            'unitaImmobiliare' => function($q) { $q->senzaFiltroOperatore(); },
            'impianto' => function($q) { $q->senzaFiltroOperatore(); },
            'dispositivo' => function($q) { $q->senzaFiltroOperatore(); },
            'anomalia' => function($q) {  },
            'risposte' => function($q) { },
            'risposte.autore'
        ])->find($id);


        abort_if(!$record, 404, 'Questo ticket non esiste');

        // Verifica autorizzazioni
        $this->verificaAutorizzazioni($record);

        return view('Ticket.show', [
            'record' => $record,
            'controller' => TicketController::class,
            'titoloPagina' => 'Ticket #' . $record->id,
            'breadcrumbs' => [action([TicketController::class, 'index']) => 'Torna a elenco ' . Ticket::NOME_PLURALE]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $this->verificaAutorizzazioni($record);

        $eliminabile = $record->risposte()->count() === 0;

        return view('Ticket.edit', [
            'record' => $record,
            'controller' => TicketController::class,
            'titoloPagina' => 'Modifica ' . Ticket::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([TicketController::class, 'index']) => 'Torna a elenco ' . Ticket::NOME_PLURALE]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $this->verificaAutorizzazioni($record);
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $this->verificaAutorizzazioni($record);

        // Solo se non ha risposte
        if ($record->risposte()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Impossibile eliminare ticket con risposte'
            ];
        }

        $record->delete();

        return [
            'success' => true,
            'redirect' => action([TicketController::class, 'index'])
        ];
    }


    public function azioni($id, $azione)
    {
        switch ($azione) {
            case 'prendi-in-carico':
                return $this->prendiInCarico($id);

            case 'risolvi':
                return $this->risolvi($id);

            case 'chiudi':
                return $this->chiudi($id);

            default:
                abort(404, 'Azione non trovata');
        }
    }

    /**
     * Aggiungi risposta
     */
    public function aggiungiRisposta(Request $request, string $id)
    {
        $request->validate([
            'messaggio' => 'required|string|max:2000',
            'visibile_condomino' => 'nullable|boolean'
        ]);

        $ticket = Ticket::find($id);
        abort_if(!$ticket, 404, 'Questo ticket non esiste');

        $this->verificaAutorizzazioni($ticket);

        $risposta = new TicketRisposta();
        $risposta->ticket_id = $ticket->id;
        $risposta->messaggio = $request->messaggio;
        $risposta->autore_id = Auth::id();
        $risposta->visibile_condomino = $request->boolean('visibile_condomino', true);
        $risposta->save();

        // Aggiorna stato ticket se necessario
        if ($ticket->stato === 'aperto') {
            $ticket->stato = 'in_lavorazione';
            $ticket->save();
        }

      return redirect()->route('tickets.show', [$ticket->id]);
    }

    /**
     * Salvataggio dati
     */
    protected function salvaDati(Ticket $record, Request $request)
    {
        $nuovo = !$record->id;

        $campi = [
            'titolo' => '',
            'descrizione' => '',
            'priorita' => '',
            'categoria' => '',
            'unita_immobiliare_id' => '',
            'impianto_id' => '',
            'dispositivo_id' => '',
            'anomalia_id' => ''
        ];

        foreach ($campi as $campo => $funzione) {
            $valore = $request->$campo;
            if ($funzione != '') {
                $valore = $funzione($valore);
            }
            $record->$campo = $valore;
        }

        if ($nuovo) {
            $record->creato_da_id = Auth::id();
            $record->origine = $this->determinaOrigine();
        }

        // Gestione dispositivi multipli
        if ($request->filled('dispositivi_coinvolti')) {
            $record->dispositivi_coinvolti = json_encode($request->dispositivi_coinvolti);
        }

        $record->save();
        return $record;
    }

    /**
     * Regole di validazione
     */
    protected function rules($id = null)
    {
        return [
            'titolo' => ['required', 'max:255'],
            'descrizione' => ['required'],
            'priorita' => ['required', 'in:bassa,media,alta,urgente'],
            'categoria' => ['required', 'in:errore_dispositivo,letture_anomale,bollette,pagamenti,comunicazione_concentratore,manutenzione,tecnico,altro'],
            'unita_immobiliare_id' => ['nullable', 'exists:unita_immobiliari,id'],
            'impianto_id' => ['nullable', 'exists:impianti,id'],
            'dispositivo_id' => ['nullable', 'exists:dispositivi_misura,id'],
            'anomalia_id' => ['nullable', 'exists:anomalie_rilevate,id'],
            'dispositivi_coinvolti' => ['nullable', 'array'],
            'dispositivi_coinvolti.*' => ['exists:dispositivi_misura,id']
        ];
    }

    /**
     * Assegnazione automatica ticket
     */
    protected function assegnaAutomaticamente(Ticket $ticket)
    {
        // Logica di assegnazione basata su categoria
        switch ($ticket->categoria) {
            case 'errore_dispositivo':
            case 'comunicazione_concentratore':
            case 'manutenzione':
            case 'tecnico':
                // Assegna al responsabile impianto

                return;
                if ($ticket->impianto_id) {
                    $responsabile = User::where('ruolo', 'responsabile_impianto')
                        ->whereHas('responsabileImpianto', function ($q) use ($ticket) {
                            $q->whereHas('impiantiAssegnati', function ($subq) use ($ticket) {
                                $subq->where('impianto_id', $ticket->impianto_id);
                            });
                        })
                        ->first();

                    if ($responsabile) {
                        $ticket->assegnato_a_id = $responsabile->id;
                    }
                }
                break;

            case 'bollette':
            case 'pagamenti':
            case 'altro':
                // Assegna all'amministratore del condominio
                if ($ticket->impianto_id) {
                    $amministratore = User::where('ruolo', 'amministratore_condominio')
                        ->whereHas('amministratore.impianti', function ($q) use ($ticket) {
                            $q->where('id', $ticket->impianto_id);
                        })
                        ->first();

                    if ($amministratore) {
                        $ticket->assegnato_a_id = $amministratore->id;
                    }
                }
                break;
        }

        $ticket->save();
    }

    /**
     * Determina origine ticket
     */
    protected function determinaOrigine()
    {
        $ruolo = RuoliOperatoreEnum::from(Auth::user()->ruolo);

        return match ($ruolo) {
            RuoliOperatoreEnum::condomino => 'condomino',
            RuoliOperatoreEnum::amministratore_condominio => 'amministratore',
            default => 'amministratore'
        };
    }

    /**
     * Verifica autorizzazioni
     */
    protected function verificaAutorizzazioni(Ticket $ticket)
    {
        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::from($user->ruolo);

        switch ($ruolo) {
            case RuoliOperatoreEnum::admin:
                return true;
            case RuoliOperatoreEnum::condomino:
                if ($ticket->creato_da_id !== $user->id) {
                    abort(403, 'Non autorizzato');
                }
                break;
            // Altri controlli di autorizzazione...
        }
    }

    /**
     * Calcola statistiche
     */
    protected function calcolaStatistiche()
    {
        $user = Auth::user();
        $query = Ticket::query();

        // Applica filtri di visibilità
        $this->applicaFiltriVisibilita($query, $user);

        return [
            'totale' => (clone $query)->count(),
            'aperti' => (clone $query)->where('stato', 'aperto')->count(),
            'in_lavorazione' => (clone $query)->where('stato', 'in_lavorazione')->count(),
            'urgenti' => (clone $query)->where('priorita', 'urgente')->count()
        ];
    }

    /**
     * Applica filtri di visibilità
     */
    protected function applicaFiltriVisibilita($query, $user)
    {
        $ruolo = RuoliOperatoreEnum::from($user->ruolo);

        switch ($ruolo) {
            case RuoliOperatoreEnum::condomino:
                $query->where('creato_da_id', $user->id);
                break;
            // Altri filtri...
        }
    }

    /**
     * Ritorna all'index
     */
    protected function backToIndex()
    {
        return redirect()->action([TicketController::class, 'index']);
    }

    private function puoCreareTicket(): bool
    {
        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::from($user->ruolo);

        return match ($ruolo) {
            RuoliOperatoreEnum::admin => true,
            RuoliOperatoreEnum::azienda_di_servizio => false, // Non crea ticket direttamente
            RuoliOperatoreEnum::amministratore_condominio => true,
            RuoliOperatoreEnum::condomino => true,
            RuoliOperatoreEnum::responsabile_impianto => true,
        };
    }

    /**
     * Risolvi il ticket
     */
    private function risolvi(string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $user = Auth::user();

        // Verifica che sia assegnato all'utente corrente
        if ($record->assegnato_a_id !== $user->id) {
            abort(403, 'Puoi risolvere solo i ticket assegnati a te');
        }

        $record->stato = 'risolto';
        $record->chiuso_da_id = $user->id;
        $record->data_chiusura = now();
        $record->save();

        return (new DatiRitorno())
            ->success(true)
            ->message('Ticket preso in carico con successo')
            ->redirect(route('tickets.show',  $id))
            ->toArray();
    }

    /**
     * Prendi in carico il ticket
     */
    private function prendiInCarico(string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::from($user->ruolo);

        // Solo certi ruoli possono prendere in carico
        if (!in_array($ruolo, [
            RuoliOperatoreEnum::amministratore_condominio,
            RuoliOperatoreEnum::responsabile_impianto,
            RuoliOperatoreEnum::azienda_di_servizio
        ])) {
            abort(403, 'Non autorizzato');
        }

        $record->assegnato_a_id = $user->id;
        $record->stato = 'in_lavorazione';
        $record->save();



        return (new DatiRitorno())
            ->success(true)
            ->message('Ticket preso in carico con successo')
            ->redirect(route('tickets.show',  $id))
            ->toArray();
    }

    /**
     * Chiudi il ticket definitivamente
     */
    private function chiudi(string $id)
    {
        $record = Ticket::find($id);
        abort_if(!$record, 404, 'Questo ticket non esiste');

        $user = Auth::user();

        $record->stato = 'chiuso';
        $record->chiuso_da_id = $user->id;
        $record->data_chiusura = now();

        // Se sono state passate note di chiusura
        if (request()->filled('note_chiusura')) {
            $record->note_chiusura = request()->note_chiusura;
        }

        $record->save();

        return (new DatiRitorno())
            ->success(true)
            ->message('Ticket preso in carico con successo')
            ->redirect(route('tickets.show',  $id))
            ->toArray();
    }
}
