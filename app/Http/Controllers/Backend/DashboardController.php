<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RuoliOperatoreEnum;
use App\Http\Controllers\Controller;
use App\Http\MieClassiCache\CacheUnaVoltaAlGiorno;
use App\Models\Amministratore;
use App\Models\AziendaServizio;
use App\Models\DispositivoMisura;
use App\Models\Impianto;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        dispatch(function () {
            CacheUnaVoltaAlGiorno::get();
        })->afterResponse();

        $user = Auth::user();
        $ruolo = RuoliOperatoreEnum::from($user->ruolo);

        // Direzioniamo in base al ruolo
        switch ($ruolo) {
            case RuoliOperatoreEnum::admin:
                return $this->showAdmin();
            case RuoliOperatoreEnum::azienda_di_servizio:
                return $this->showAziendaServizio();
            case RuoliOperatoreEnum::amministratore_condominio:
                return $this->showAmministratore();
            case RuoliOperatoreEnum::responsabile_impianto:
                return $this->showResponsabileImpianto();
            case RuoliOperatoreEnum::condomino:
                return $this->showCondomino();
            default:
                return $this->showUtente();
        }
    }

    protected function showAdmin()
    {
        // Statistiche generali per admin
        $statistiche = [
            'impianti' => $this->getStatisticheImpianti(),
            'aziende_servizio' => $this->getStatisticheAziendeServizio(),
            'amministratori' => $this->getStatisticheAmministratori(),
            'dispositivi' => $this->getStatisticheDispositivi(),
        ];

        // Ultimi impianti creati
        $ultimi_impianti = Impianto::latest()
            ->take(5)
            ->get();

        // Ultimi ticket se disponibili
        $ultimi_tickets = null;
        if (class_exists(Ticket::class)) {
            $ultimi_tickets = Ticket::with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('Backend.Dashboard.showAdmin', [
            'titoloPagina' => 'Dashboard Admin',
            'mainMenu' => 'dashboard',
            'statistiche' => $statistiche,
            'ultimi_impianti' => $ultimi_impianti,
            'ultimi_tickets' => $ultimi_tickets,
        ]);
    }

    protected function showAziendaServizio()
    {
        $user = Auth::user();
        $azienda = $user->aziendaServizio;

        if (!$azienda) {
            return redirect()->action([DashboardController::class, 'show'])->with('error', 'Profilo azienda non configurato');
        }

        // Statistiche specifiche per l'azienda
        $statistiche = [
            'impianti' => $this->getStatisticheImpiantiAzienda($azienda->id),
            'amministratori' => $this->getStatisticheAmministratoriAzienda($azienda->id),
            'dispositivi' => $this->getStatisticheDispositiviAzienda($azienda->id),
        ];

        $impianti_recenti = Impianto::where('azienda_servizio_id', $azienda->id)
            ->withCount('unitaImmobiliari')
            ->with('amministratore.user')
            ->latest()
            ->take(5)
            ->get();

        return view('Backend.Dashboard.showAziendaServizio', [
            'titoloPagina' => 'Dashboard Azienda',
            'mainMenu' => 'dashboard',
            'statistiche' => $statistiche,
            'impianti_recenti' => $impianti_recenti,
            'azienda' => $azienda,
        ]);
    }

    protected function showAmministratore()
    {
        $user = Auth::user();
        $amministratoreId = $user->amministratore?->id;

        if (!$amministratoreId) {
            return redirect()->action([DashboardController::class, 'show'])->with('error', 'Profilo amministratore non configurato');
        }

        // Recupera l'amministratore completo
        $amministratore = Amministratore::find($amministratoreId);

        // Statistiche specifiche per l'amministratore
        $statistiche = [
            'impianti' => $this->getStatisticheImpiantiAmministratore($amministratoreId),
            'dispositivi' => $this->getStatisticheDispositiviAmministratore($amministratoreId),
        ];

        $impianti_gestiti = Impianto::where('amministratore_id', $amministratoreId)
            ->withCount('unitaImmobiliari')
            ->latest()
            ->take(5)
            ->get();

        return view('Backend.Dashboard.showAmministratore', [
            'titoloPagina' => 'Dashboard Amministratore',
            'mainMenu' => 'dashboard',
            'statistiche' => $statistiche,
            'impianti_gestiti' => $impianti_gestiti,
            'amministratore' => $amministratore,
        ]);
    }

    protected function showResponsabileImpianto()
    {
        $user = Auth::user();
        $responsabile = $user->responsabileImpianto;

        if (!$responsabile) {
            return redirect()->route('backend.impostazioni')->with('error', 'Profilo responsabile non configurato');
        }

        $impianti_gestiti = Impianto::where('responsabile_impianto_id', $responsabile->id)->get();

        return view('Backend.Dashboard.showResponsabileImpianto', [
            'titoloPagina' => 'Dashboard Responsabile',
            'mainMenu' => 'dashboard',
            'impianti_gestiti' => $impianti_gestiti,
            'responsabile' => $responsabile,
        ]);
    }

    protected function showCondomino()
    {
        // Dashboard per condomini - da implementare
        return view('Backend.Dashboard.showCondomino', [
            'titoloPagina' => 'Dashboard Condomino',
            'mainMenu' => 'dashboard',
        ]);
    }

    protected function showUtente()
    {
        return view('Backend.Dashboard.showUtente', [
            'titoloPagina' => 'Dashboard',
            'mainMenu' => 'dashboard',
        ]);
    }

    /**
     * Statistiche generali degli impianti
     */
    private function getStatisticheImpianti()
    {
        $query = Impianto::query();

        $totale = $query->count();
        $attivi = (clone $query)->where('stato_impianto', 'attivo')->count();
        $dismessi = (clone $query)->where('stato_impianto', 'dismesso')->count();

        // Statistiche per tipologia
        $per_tipologia = (clone $query)
            ->selectRaw('tipologia, COUNT(*) as count')
            ->groupBy('tipologia')
            ->pluck('count', 'tipologia')
            ->toArray();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'dismessi' => $dismessi,
            'per_tipologia' => $per_tipologia,
        ];
    }

    /**
     * Statistiche delle aziende di servizio
     */
    private function getStatisticheAziendeServizio()
    {
        $query = AziendaServizio::query();

        $totale = $query->count();
        $attive = (clone $query)->where('attivo', true)->count();

        return [
            'totale' => $totale,
            'attive' => $attive,
            'non_attive' => $totale - $attive,
        ];
    }

    /**
     * Statistiche degli amministratori
     */
    private function getStatisticheAmministratori()
    {
        $query = Amministratore::query();

        $totale = $query->count();
        $attivi = (clone $query)->where('attivo', true)->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'non_attivi' => $totale - $attivi,
        ];
    }

    /**
     * Statistiche dei dispositivi
     */
    private function getStatisticheDispositivi()
    {
        $query = DispositivoMisura::query();

        $totale = $query->count();
        $attivi = (clone $query)->where('stato_dispositivo', 'attivo')->count();

        // Statistiche per tipo
        $per_tipo = (clone $query)
            ->selectRaw('tipo, COUNT(*) as count')
            ->groupBy('tipo')
            ->pluck('count', 'tipo')
            ->toArray();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'per_tipo' => $per_tipo,
        ];
    }

    /**
     * Statistiche impianti per azienda specifica
     */
    private function getStatisticheImpiantiAzienda($aziendaId)
    {
        $query = Impianto::where('azienda_servizio_id', $aziendaId);

        $totale = $query->count();
        $attivi = (clone $query)->where('stato_impianto', 'attivo')->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'dismessi' => $totale - $attivi,
        ];
    }

    /**
     * Statistiche amministratori per azienda specifica
     */
    private function getStatisticheAmministratoriAzienda($aziendaId)
    {
        $query = Amministratore::where('azienda_servizio_id', $aziendaId);

        $totale = $query->count();
        $attivi = (clone $query)->where('attivo', true)->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
        ];
    }

    /**
     * Statistiche dispositivi per azienda specifica
     */
    private function getStatisticheDispositiviAzienda($aziendaId)
    {
        $query = DispositivoMisura::where('azienda_servizio_id', $aziendaId);

        $totale = $query->count();
        $attivi = (clone $query)->where('stato', 'attivo')->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
        ];
    }

    /**
     * Statistiche impianti per amministratore specifico
     */
    private function getStatisticheImpiantiAmministratore($amministratoreId)
    {
        $query = Impianto::where('amministratore_id', $amministratoreId);

        $totale = $query->count();
        $attivi = (clone $query)->where('stato_impianto', 'attivo')->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
        ];
    }

    /**
     * Statistiche dispositivi per amministratore specifico
     */
    private function getStatisticheDispositiviAmministratore($amministratoreId)
    {
        $query = DispositivoMisura::whereHas('impianto', function ($q) use ($amministratoreId) {
            $q->where('amministratore_id', $amministratoreId);
        });

        $totale = $query->count();
        $attivi = (clone $query)->where('stato', 'attivo')->count();

        // Statistiche per tipo dispositivo
        $per_tipo = (clone $query)
            ->selectRaw('tipo, COUNT(*) as count')
            ->groupBy('tipo')
            ->pluck('count', 'tipo')
            ->toArray();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'per_tipo' => $per_tipo,
        ];
    }
}
