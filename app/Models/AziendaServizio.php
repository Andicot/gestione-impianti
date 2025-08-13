<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AziendaServizio extends Model
{
    //
    protected $table = "aziende_servizio";

    public const NOME_SINGOLARE = "azienda servizio";
    public const NOME_PLURALE = "aziende servizio";

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function amministratori(): HasMany
    {
        return $this->hasMany(Amministratore::class, 'azienda_servizio_id');
    }

    public function comune()
    {
        return $this->hasOne(Comune::class, 'id', 'citta')->select(['id', 'comune', 'targa']);
    }

    public function impianti(): HasMany
    {
        return $this->hasMany(Impianto::class, 'azienda_servizio_id');
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

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */

}
