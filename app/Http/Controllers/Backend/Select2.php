<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Amministratore;
use App\Models\Comune;
use App\Models\DispositivoMisura;
use App\Models\Impianto;
use App\Models\NazioneDiNascita;
use App\Models\Provincia;
use App\Models\UnitaImmobiliare;
use App\Rules\CodiceFiscaleRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use robertogallea\LaravelCodiceFiscale\CodiceFiscale;

class Select2 extends Controller
{
    public function response(Request $request)
    {


        $querystring = $request->input();


        //Prende la prima chiave della querystring
        reset($querystring);
        $key = key($querystring);
        //

        $term = trim($request->input('term'));


        // $term=trim($term);
        switch ($key) {


            case 'amministratore_id':
                $queryBuilder = Amministratore::orderBy('ragione_sociale')
                    ->select(['id', DB::raw('ragione_sociale as text')]);

                if ($term) {
                    $arrTerm = explode(' ', $term);
                    foreach ($arrTerm as $t) {
                        $queryBuilder->whereRaw('concat_ws(\' \',ragione_sociale,cognome_referente,nome_referente) like ?', "%$t%");
                    }
                }
                return $queryBuilder->get();

            case 'impianto_id':
                $queryBuilder = Impianto::orderBy('nome_impianto')
                    ->select(['id', DB::raw('nome_impianto as text')]);
                if ($term) {
                    $arrTerm = explode(' ', $term);
                    foreach ($arrTerm as $t) {
                        $queryBuilder->whereRaw('concat_ws(\' \',nome_impianto) like ?', "%$t%");
                    }
                }
                return $queryBuilder->get();

            case 'unita_immobiliare_id':
                $queryBuilder = UnitaImmobiliare::query()
                    ->orderBy('scala')->orderBy('piano')->orderBy('interno');

                if ($request->input('impianto_id')) {
                    $queryBuilder->where('impianto_id', $request->input('impianto_id'));
                }
                $res = $queryBuilder->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'text' => $item->getDescrizioneCompleta() // o $item->descrizione_completa se usi l'accessor
                        ];
                    });

                return $res;

            case 'dispositivo_id':
                $queryBuilder = DispositivoMisura::query()
                    ->orderBy('matricola')
                    ->select(['id', DB::raw('matricola as text')]);;

                if ($request->input('unita_immobiliare_id')) {
                    $queryBuilder->where('unita_immobiliare_id', $request->input('unita_immobiliare_id'));
                }

                if ($request->input('impianto_id')) {
                    $queryBuilder->where('impianto_id', $request->input('impianto_id'));
                }
                if ($term) {
                    $queryBuilder->whereRaw('matricola like ?', "$term%");
                }
                return $queryBuilder->get();


            case 'genera-cf':
                if ($request->input('comune_di_nascita')) {
                    $comuneDiNascita = Comune::find($request->input('comune_di_nascita'))?->codice_catastale;
                } else {
                    $comuneDiNascita = $request->input('nazione_di_nascita');
                }

                try {
                    $cf_string = CodiceFiscale::generate($request->input('nome'), $request->input('cognome'), Carbon::createFromFormat('d/m/Y', $request->input('data_di_nascita')), $comuneDiNascita, $request->input('sesso'));
                    return ['success' => true, 'codice_fiscale' => $cf_string];
                } catch (\Exception $exception) {
                    return ['success' => false, 'message' => 'Verifica i dati inseriti '];
                }

            case 'dati-cf':
                $codiceFiscale = strtoupper($request->input('codice_fiscale'));
                $validator = \Validator::make(['codice_fiscale' => $codiceFiscale], ['codice_fiscale' => new CodiceFiscaleRule()]);
                if ($validator->fails()) {
                    return ['success' => false, 'message' => $validator->messages()->first('codice_fiscale')];
                }
                $datiRitorno = [];
                $parserCodiceFiscale = new CodiceFiscale();
                try {
                    if ($parserCodiceFiscale->parse($codiceFiscale) !== false) {
                        $datiRitorno['genere'] = $parserCodiceFiscale->getGender();
                        $datiRitorno['data_di_nascita'] = $parserCodiceFiscale->getBirthdate()->format('d/m/Y');
                        $luogoNascita = $parserCodiceFiscale->getBirthPlace();
                        if (\Str::of($luogoNascita)->startsWith('Z')) {
                            $nazione = NazioneDiNascita::find($luogoNascita);
                            if ($nazione) {
                                $datiRitorno['nazione_di_nascita'] = ['codice' => $nazione->id, 'nazione' => $nazione->nazione];
                            } else {
                                \Log::warning('Non trovata nazione di nascita ' . $luogoNascita);
                            }
                        } else {
                            $datiRitorno['nazione_di_nascita'] = ['codice' => 'IT', 'nazione' => 'Italia'];
                            $cittaNascita = Comune::where('codice_catastale', $luogoNascita)->select(['id', 'comune'])->first();
                            if ($cittaNascita) {
                                $datiRitorno['luogo_di_nascita'] = $cittaNascita->toArray();
                            } else {
                                \Log::warning('Non trovata città ' . $luogoNascita);
                                $datiRitorno['luogo_di_nascita'] = '';
                            }
                        }

                        return ['success' => true, 'dati_ritorno' => $datiRitorno];
                    }
                } catch (\Exception $exception) {
                    \Log::warning('Errore decodifica codice fiscale ' . $codiceFiscale);
                }
                return ['success' => false, 'message' => 'Codice fiscale errato'];

            case 'citta':
                if (empty($term)) {
                    return [''];
                }
                if (is_array($term)) {
                    $term = $term['term'];
                }


                $queryBuilder = Comune::orderBy('comune')->select(['elenco_comuni.id', DB::raw('CONCAT(comune, " (", targa,")") AS text'), 'cap']);
                return $queryBuilder->where('comune', 'like', $term . '%')->where('soppresso', 0)->get();
                break;


            case 'provincia':
                if (empty($term)) {
                    return [''];
                }
                return Provincia::orderBy('provincia')->select(['id', 'provincia as text'])->where('provincia', 'like', $term . '%')->get();


            case 'regione':
                $queryBuilder = Provincia::orderBy('regione')->select(['id_regione as id', 'regione as text']);
                if ($term != '') {
                    $queryBuilder->where('regione', 'like', $term . '%');
                }
                return $queryBuilder->distinct()->get();

            case 'nazione':
                if (empty($term)) {
                    return [''];
                }
                return DB::table('elenco_nazioni')
                    ->select('alpha2 as id', 'langit as text')
                    ->orderBy('langit')
                    ->where('langit', 'like', $term . '%')
                    ->get();

            case 'nazione_di_nascita':
                if (empty($term)) {
                    return [''];
                }
                return NazioneDiNascita::
                select(['id', 'nazione as text'])
                    ->orderBy('nazione')
                    ->where('nazione', 'like', "%$term%")
                    ->get();


            default:

                return [['id' => 1, 'text' => 'Chiave ' . $key . ' non gestita']];


        }


    }
}
