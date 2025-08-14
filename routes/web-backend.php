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
    Route::resource('azienda_servizio', \App\Http\Controllers\Admin\AziendaServizioController::class);
    Route::get('azienda_servizio/{id}/tab/{tab}', [\App\Http\Controllers\Admin\AziendaServizioController::class, 'tab']);
});

/*
|--------------------------------------------------------------------------
| AZIENDA SERVIZIO - Admin e Aziende di servizio
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin|azienda_di_servizio'], function () {
    // Amministratori (solo admin e aziende di servizio possono gestirli)
    Route::resource('amministratore', \App\Http\Controllers\Aziendadiservizio\AmministratoreController::class);
    Route::get('amministratore/{id}/tab/{tab}', [\App\Http\Controllers\Aziendadiservizio\AmministratoreController::class, 'tab']);

    // Responsabili Impianto (solo admin e aziende di servizio possono gestirli)
    Route::resource('responsabile-impianto', \App\Http\Controllers\Aziendadiservizio\ResponsabileImpiantoController::class)->except(['show']);

    // Importazioni (solo admin e aziende di servizio)
    Route::get('importazione', [\App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'index'])->name('importazione.index');
    Route::post('importazione/carica-file', [\App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'caricaFile'])->name('importazione.carica');
    Route::get('importazione/storico', [\App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'storico'])->name('importazione.storico');
    Route::get('importazione/{id}/dettaglio', [\App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'dettaglioImportazione'])->name('importazione.dettaglio');
});

/*
|--------------------------------------------------------------------------
| GESTIONE IMPIANTI - Admin, Aziende di servizio e Amministratori condominio
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin|azienda_di_servizio|amministratore_condominio'], function () {

    // Impianti (tutti e tre i ruoli possono vedere, ma con scope diversi)
    Route::resource('impianto', \App\Http\Controllers\Aziendadiservizio\ImpiantoController::class);
    Route::get('impianto/{id}/tab/{tab}', [\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class, 'tab']);

    // Concentratori
    Route::resource('concentratore', \App\Http\Controllers\Aziendadiservizio\ConcentratoreController::class);

    // Unità immobiliari (legate agli impianti)
    Route::resource('unita_immobiliari', \App\Http\Controllers\Aziendadiservizio\UnitaImmobiliareController::class);

    // Dispositivi misura (legati agli impianti)
    Route::resource('dispositivo-misura', \App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class);

    //Documenti
    Route::resource('documento',\App\Http\Controllers\Backend\DocumentoController::class);
    Route::get('documento/{id}/download', [\App\Http\Controllers\Backend\DocumentoController::class, 'download'])->name('documento.download');
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
