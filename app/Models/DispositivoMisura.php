<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use App\Enums\StatoDispositivoEnum;
use App\Models\Scopes\FiltroOperatoreScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DispositivoMisura extends Model
{
    //
    protected $table = "dispositivi_misura";

    public const NOME_SINGOLARE = "dispositivo misura";
    public const NOME_PLURALE = "dispositivi misura";

    protected $casts = [
        'data_installazione' => 'date',
        'data_ultima_lettura' => 'datetime'
    ];

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
                    return $builder->whereHas('impianto', function(Builder $builder) use ($user) {
                        $builder->senzaFiltroOperatore()->where('amministratore_id',$user->amministratore->id);
                    });


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

    public function concentratore(): HasOne
    {
        return $this->hasOne(Concentratore::class, 'id', 'concentratore_id');
    }

    public function impianto(): HasOne
    {
        return $this->hasOne(Impianto::class, 'id', 'impianto_id');
    }

    public function unitaImmobiliare(): HasOne
    {
        return $this->hasOne(UnitaImmobiliare::class, 'id', 'unita_immobiliare_id');
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
    public function badgeStato()
    {
        $stato = StatoDispositivoEnum::tryFrom($this->stato_dispositivo);
        return '<span class="badge badge-light-' . $stato->colore() . ' fw-bolder">' . $stato->testo() . '</span>';
    }

    public function badgeOrigine()
    {
        if($this->creato_automaticamente){
            return ' <span class="badge badge-light-info">Da importazione</span>';
        }else{
            return ' <span class="badge badge-light-primary">Inserito manualmente</span>';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
