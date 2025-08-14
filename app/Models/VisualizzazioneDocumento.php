<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisualizzazioneDocumento extends Model
{
    use HasFactory;

    const NOME_SINGOLARE = 'visualizzazione documento';
    const NOME_PLURALE = 'visualizzazioni documenti';

    protected $table = 'visualizzazioni_documenti';

    protected $casts = [
        'data_visualizzazione' => 'datetime',
        'data_download' => 'datetime',
        'scaricato' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAZIONI
    |--------------------------------------------------------------------------
    */

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function utente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function data_visualizzazione_formattata(): string
    {
        return $this->data_visualizzazione->format('d/m/Y H:i');
    }

    public function data_download_formattata(): ?string
    {
        return $this->data_download?->format('d/m/Y H:i');
    }

    /*
    |--------------------------------------------------------------------------
    | ALTRO
    |--------------------------------------------------------------------------
    */
}
