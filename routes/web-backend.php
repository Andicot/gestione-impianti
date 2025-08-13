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
| ADMIN
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'role:admin'], function () {
    Route::resource('azienda_servizio',\App\Http\Controllers\Admin\AziendaServizioController::class);
    Route::get('azienda_servizio/{id}/tab/{tab}', [\App\Http\Controllers\Admin\AziendaServizioController::class, 'tab']);
});

/*
|--------------------------------------------------------------------------
| AZIOENDA SERVIZIO
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'role:admin|azienda_di_servizio'], function () {

//Ammministratore
    Route::resource('amministratore', \App\Http\Controllers\Aziendadiservizio\AmministratoreController::class);
    Route::get('amministratore/{id}/tab/{tab}', [\App\Http\Controllers\Aziendadiservizio\AmministratoreController::class, 'tab']);

//Impianto
    Route::resource('impianto', \App\Http\Controllers\Aziendadiservizio\ImpiantoController::class);
    Route::get('impianto/{id}/tab/{tab}', [\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class, 'tab']);

//Concentratore
    Route::resource('concentratore', \App\Http\Controllers\Aziendadiservizio\ConcentratoreController::class);

//Responsabile Impianto
    Route::resource('responsabile-impianto', \App\Http\Controllers\Aziendadiservizio\ResponsabileImpiantoController::class)->except(['show']);


//Unità immobiliari
    Route::resource('unita_immobiliari', \App\Http\Controllers\Aziendadiservizio\UnitaImmobiliareController::class);

//Dispositivi misura
    Route::resource('dispositivo-misura', \App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class);
});

Route::group(['middleware' => 'role:admin|azienda_di_servizio|'], function () {

});

