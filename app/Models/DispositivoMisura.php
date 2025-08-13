<?php

namespace App\Models;

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
