<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
use Illuminate\Database\Eloquent\Model;

class Concentratore extends Model
{
    //
    protected $table = "concentratori";

    public const NOME_SINGOLARE = "concentratore";
    public const NOME_PLURALE = "concentratori";

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
            $testo = $record->marca . ' - ' . $record->modello . ' - ' . $record->matricola;
            return "<option value='$id' selected>{$testo}</option>";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
