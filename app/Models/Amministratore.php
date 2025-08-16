<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Amministratore extends Model
{
    //
    protected $table = "amministratori";

    public const NOME_SINGOLARE = "amministratore";
    public const NOME_PLURALE = "amministratori";

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

    public function impianti(): HasMany
    {
        return $this->hasMany(Impianto::class, 'amministratore_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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
        $record = self::find($id);
        if ($record) {
            return "<option value='$id' selected>{$record->ragione_sociale}</option>";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
