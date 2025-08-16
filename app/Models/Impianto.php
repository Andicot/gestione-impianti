<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use App\Enums\StatoImpiantoEnum;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Impianto extends Model
{
    //
    protected $table = "impianti";

    public const NOME_SINGOLARE = "impianto";
    public const NOME_PLURALE = "impianti";

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::addGlobalScope('filtroOperatore', function (Builder $builder) {
            $user = \Auth::user();

            if (!$user) {
                return $builder->whereRaw('1 = 0');
            }

            switch ($user->ruolo) {
                case RuoliOperatoreEnum::admin->value:
                    return $builder;

                case RuoliOperatoreEnum::azienda_di_servizio->value:
                    if (!$user->aziendaServizio) {
                        return $builder->whereRaw('1 = 0');
                    }
                    return $builder->where('azienda_servizio_id', $user->aziendaServizio->id);

                case RuoliOperatoreEnum::amministratore_condominio->value:
                    if (!$user->amministratore) {
                        return $builder->whereRaw('1 = 0');
                    }
                    return $builder->where('amministratore_id', $user->amministratore->id);

                case RuoliOperatoreEnum::responsabile_impianto->value:
                    if (!$user->responsabileImpianto) {
                        return $builder->whereRaw('1 = 0');
                    }
                    return $builder->where('responsabile_impianto_id', $user->responsabileImpianto->id);


                default:
                    return $builder->whereRaw('1 = 0');
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */
    public function amministratore(): HasOne
    {
        return $this->hasOne(Amministratore::class, 'id', 'amministratore_id')->senzaFiltroOperatore();
    }

    public function aziendaServizio(): HasOne
    {
        return $this->hasOne(AziendaServizio::class, 'id', 'azienda_servizio_id');
    }

    public function comune(): HasOne
    {
        return $this->hasOne(Comune::class, 'id', 'citta')->select(['id', 'comune', 'targa']);
    }

    public function concentratori(): HasOne
    {
        return $this->hasOne(Concentratore::class, 'impianto_id', 'id');
    }

    public function unitaImmobiliari(): HasMany
    {
        return $this->hasMany(UnitaImmobiliare::class, 'impianto_id', 'id');
    }

    public function dispositivi(): HasMany
    {
        return $this->hasMany(DispositivoMisura::class, 'impianto_id', 'id');
    }

    public function responsabileImpianto(): HasOne
    {
        return $this->hasOne(ResponsabileImpianto::class,'id','responsabile_impianto_id')->select(['id', 'cognome', 'nome']);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    #[Scope]
    protected function senzaFiltroOperatore(Builder $query): Builder
    {
        return $query->withoutGlobalScope('filtroOperatore');
    }

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */
    public static function selected($id)
    {
        $nazione = self::find($id);
        if ($nazione) {
            return "<option value='$id' selected>{$nazione->nome_impianto}</option>";
        }
    }

    public function badgeStato()
    {
        $stato = StatoImpiantoEnum::tryFrom($this->stato_impianto);
        return '<span class="badge badge-light-' . $stato->colore() . ' fw-bolder">' . $stato->testo() . '</span>';
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */



}
