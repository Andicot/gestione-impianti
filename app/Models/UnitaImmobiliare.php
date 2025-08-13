<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UnitaImmobiliare extends Model
{
    //
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
        static::addGlobalScope(new FiltroOperatoreScope());
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
