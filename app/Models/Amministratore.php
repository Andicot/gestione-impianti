<?php

namespace App\Models;

use App\Models\Scopes\FiltroOperatoreScope;
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
        static::addGlobalScope(new FiltroOperatoreScope());
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
