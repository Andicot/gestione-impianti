<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsabileImpianto extends Model
{
    use HasFactory;

    protected $table = "responsabili_impianto";

    public const NOME_SINGOLARE = "responsabile impianto";
    public const NOME_PLURALE = "responsabili impianto";

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

    public function nominativo()
    {
        return $this->cognome.' '.$this->nome;
    }

    public static function selected($id)
    {
        $record = self::find($id);
        if ($record) {
            return "<option value='$id' selected>{$record->cognome} {$record->nome}</option>";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
