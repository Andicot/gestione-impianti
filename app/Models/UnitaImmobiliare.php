<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UnitaImmobiliare extends Model
{

    protected $table = "unita_immobiliari";

    public const NOME_SINGOLARE = "unita immobiliare";
    public const NOME_PLURALE = "unita immobiliari";


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
                    return $builder->whereHas('impianto', function(Builder $builder) use ($user) {
                        $builder->senzaFiltroOperatore()->where('azienda_servizio_id',$user->aziendaServizio->id);
                    });

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


    public function impianto(): HasOne
    {
        return $this->hasOne(Impianto::class, 'id', 'impianto_id');
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

    /*
    |--------------------------------------------------------------------------
    | PER BLADE
    |--------------------------------------------------------------------------
    */

    public static function selected($id)
    {
        $record = self::find($id);
        if ($record) {
            $testo = $record->getDescrizioneCompleta();
            return "<option value='$id' selected>{$testo}</option>";
        }
    }

    public function getDescrizioneCompleta(): string
    {
        $arr = [];

        if ($this->scala) {
            $arr[] = 'Scala: ' . $this->scala;
        }

        if ($this->piano) {
            $arr[] = 'Piano: ' . $this->piano;
        }

        if ($this->interno) {
            $arr[] = 'Interno: ' . $this->interno;
        }

        return implode(', ', $arr);
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
