<?php

namespace App\Models;

use App\Enums\RuoliOperatoreEnum;
use App\Enums\StatoImpiantoEnum;
use App\Models\Scopes\FiltroOperatoreScope;
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
        static::addGlobalScope(new FiltroOperatoreScope());
    }


    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */
    public function amministratore(): HasOne
    {
        return $this->hasOne(Amministratore::class, 'id', 'amministratore_id');
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
