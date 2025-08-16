<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Backend\DashboardController::class, 'show']);

//Modal
//Route::get('modal/{cosa}/{id?}', [\App\Http\Controllers\Backend\ModalController::class, 'show']);

//Impostazioni
Route::get('/settings/{sezione}', [\App\Http\Controllers\Backend\SettingController::class, 'edit']);
Route::get('/settings', [\App\Http\Controllers\Backend\SettingController::class, 'index'])->name('settings');
Route::post('/settings/{sezione}', [\App\Http\Controllers\Backend\SettingController::class, 'store'])->name('settings.store');

//Registri
Route::get('registro/{cosa}', [\App\Http\Controllers\Backend\RegistriController::class, 'index']);

//Dati utente
Route::get('/dati-utente', [\App\Http\Controllers\Backend\DatiUtenteController::class, 'show']);
Route::patch('/dati-utente/{cosa}', [\App\Http\Controllers\Backend\DatiUtenteController::class, 'update']);

//Tabelle

//Operatore
Route::get('/operatore-tab/{id}/tab/{tab}', [\App\Http\Controllers\Backend\OperatoreController::class, 'tab']);
Route::resource('/operatore', \App\Http\Controllers\Backend\OperatoreController::class);
Route::post('/operatore/{id}/azione/{azione}', [\App\Http\Controllers\Backend\OperatoreController::class, 'azioni']);

/*
|--------------------------------------------------------------------------
| ADMIN - Solo admin di sistema
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin'], function () {
    Route::resource('azienda_servizio', \App\Http\Controllers\Backend\AziendaServizioController::class);
    Route::get('azienda_servizio/{id}/tab/{tab}', [\App\Http\Controllers\Backend\AziendaServizioController::class, 'tab']);
});

/*
|--------------------------------------------------------------------------
| AZIENDA SERVIZIO - Admin e Aziende di servizio
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin|azienda_di_servizio'], function () {
    // Amministratori (solo admin e aziende di servizio possono gestirli)
    Route::resource('amministratore', \App\Http\Controllers\Backend\AmministratoreController::class);
    Route::get('amministratore/{id}/tab/{tab}', [\App\Http\Controllers\Backend\AmministratoreController::class, 'tab']);

    // Responsabili Impianto (solo admin e aziende di servizio possono gestirli)
    Route::resource('responsabile-impianto', \App\Http\Controllers\Backend\ResponsabileImpiantoController::class)->except(['show']);

    // Importazioni (solo admin e aziende di servizio)
    Route::get('importazione', [\App\Http\Controllers\Backend\ImportazioneController::class, 'index'])->name('importazione.index');
    Route::post('importazione/carica-file', [\App\Http\Controllers\Backend\ImportazioneController::class, 'caricaFile'])->name('importazione.carica');
    Route::get('importazione/storico', [\App\Http\Controllers\Backend\ImportazioneController::class, 'storico'])->name('importazione.storico');
    Route::get('importazione/{id}/dettaglio', [\App\Http\Controllers\Backend\ImportazioneController::class, 'dettaglioImportazione'])->name('importazione.dettaglio');
});

/*
|--------------------------------------------------------------------------
| GESTIONE IMPIANTI - Admin, Aziende di servizio e Amministratori condominio
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin|azienda_di_servizio|amministratore_condominio'], function () {

    // Impianti (tutti e tre i ruoli possono vedere, ma con scope diversi)
    Route::resource('impianto', \App\Http\Controllers\Backend\ImpiantoController::class);
    Route::get('impianto/{id}/tab/{tab}', [\App\Http\Controllers\Backend\ImpiantoController::class, 'tab']);

    // Concentratori
    Route::resource('concentratore', \App\Http\Controllers\Backend\ConcentratoreController::class);

    // Unità immobiliari (legate agli impianti)
    Route::resource('unita_immobiliari', \App\Http\Controllers\Backend\UnitaImmobiliareController::class);

    // Dispositivi misura (legati agli impianti)
    Route::resource('dispositivo-misura', \App\Http\Controllers\Backend\DispositivoMisuraController::class);

    //Documenti
    Route::resource('documento',\App\Http\Controllers\Backend\DocumentoController::class);
    Route::get('documento/{id}/download', [\App\Http\Controllers\Backend\DocumentoController::class, 'download'])->name('documento.download');
});


/*
|--------------------------------------------------------------------------
| GESTIONE IMPIANTI - Admin, Aziende di servizio e Amministratori condominio
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin|azienda_di_servizio|amministratore_condominio|responsabile_impianto'], function () {

    // Impianti (tutti e tre i ruoli possono vedere, ma con scope diversi)
    Route::resource('lettura-consumo', \App\Http\Controllers\Backend\LetturaConsumoController::class);

});

/*
|--------------------------------------------------------------------------
| CONDOMINI - Solo per condomini
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:condomino'], function () {
    // Qui andranno le rotte per i condomini (visualizzazione bollette, consumi, etc.)
    // Route::get('mie-bollette', ...);
    // Route::get('miei-consumi', ...);
});



/*
|--------------------------------------------------------------------------
| TICKETS / COMUNICAZIONI - ROTTE ESSENZIALI
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    // ✅ ROTTE CRUD BASE (usate nel controller e viste esistenti)
    Route::resource('tickets', \App\Http\Controllers\TicketController::class);

    // ✅ AZIONI SPECIFICHE (usate nella vista index)
    Route::post('tickets/{id}/prendi-in-carico', [\App\Http\Controllers\TicketController::class, 'prendiInCarico'])
        ->name('tickets.prendiInCarico');

    Route::post('tickets/{id}/chiudi', [\App\Http\Controllers\TicketController::class, 'chiudi'])
        ->name('tickets.chiudi');

    // ✅ GESTIONE RISPOSTE (usate nella vista show)
    Route::post('tickets/{id}/risposte', [\App\Http\Controllers\TicketController::class, 'aggiungiRisposta'])
        ->name('tickets.aggiungiRisposta');
});

