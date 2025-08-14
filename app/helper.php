<?php

namespace App;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Ritorna Carbon altrimenti null
 *
 * @param mixed $value
 * @return string
 */
function getInputData($value)
{
    return $value ? Carbon::createFromFormat('d/m/Y', $value)->toDateString() : null;
}

function getInputDataOra($value)
{
    return $value ? Carbon::createFromFormat('d/m/Y H:i', $value)->toDateTimeString() : null;
}

function getInputOra($value)
{
    return $value ? Carbon::createFromFormat('H:i', $value)->toDateTimeString() : null;
}

function getInputUcwords($value)
{
    return $value ? ucwords(strtolower($value), " '-") : null;

}

function getInputToUpper($value)
{
    return $value ? mb_strtoupper($value) : null;
}

function getInputToLower($value)
{
    return $value ? mb_strtolower($value) : null;
}

function getInputUcfirst($value)
{
    return ucfirst(strtolower($value));
}

function getInputTelefono($value)
{
    if ($value) {
        $value = str_replace(' ', '', $value);
        if (!Str::of($value)->startsWith(['+', '00'])) {
            $value = '+39' . $value;
        }
        return $value;
    } else {
        return null;
    }
}

function getInputNumero($value)
{
    if ($value) {
        $value = str_replace('.', '', $value);
        return (float)str_replace(',', '.', $value);
    } else {
        return null;
    }
}

function getInputNumeroZero($value)
{
    if ($value) {
        $value = str_replace('.', '', $value);
        return (float)str_replace(',', '.', $value);
    } else {
        return 0;
    }
}


function getInputCheckbox($value = 0)
{
    return $value == '' ? 0 : 1;
}

function getInputNullSeVuoto($value)
{
    return $value === '' ? null : $value;
}

function getInputAggiungi39($value)
{
    if (!Str::of($value)->startsWith('+39')) {
        $value = '+39' . $value;
    }
    return $value;
}


//Numeri e iva
function importo($valore, $conEuro = false, $zeroSeNull = false)
{
    if ($zeroSeNull) {
        if ($valore === null) {
            $valore = 0;
        }
    }

    if ($valore !== null) {
        return ($conEuro ? '€ ' : '') . number_format($valore, 2, ',', '.');
    }
}

function intero($valore, $zeroSeNull = false)
{
    if ($zeroSeNull) {
        if ($valore === null) {
            $valore = 0;
        }
    }

    if ($valore !== null) {
        return number_format($valore, 0, ',', '.');
    }
}

function rimuoviZeriInutili($numero)
{
    // Rimuovi eventuali zeri inutili a destra usando rtrim
    $numero_senza_zeri = rtrim($numero, '0');

    // Controlla se il numero senza zeri è un numero intero
    if (strpos($numero_senza_zeri, ',') !== false) {
        // Se il numero contiene un punto decimale, rimuovi eventuali zeri inutili anche dopo il punto
        $numero_senza_zeri = rtrim($numero_senza_zeri, ',');
    }

    return $numero_senza_zeri;
}

function applicaIva($valore)
{
    return $valore * (1 + config('configurazione.aliquota_iva') / 100);
}

function calcolaImposta($importoConIVA, $percentualeIVA)
{
    $imponibile = $importoConIVA / (1 + ($percentualeIVA / 100));
    $imposta = $importoConIVA - $imponibile;
    return round($imposta, 2);
}

function calcolaImponibile($importoConIVA, $percentualeIVA)
{
    $imponibile = $importoConIVA / (1 + ($percentualeIVA / 100));
    return round($imponibile, 2);
}

function applicaSconto($prezzo, $sconto)
{
    return round($prezzo * (1 - $sconto / 100));
}

function percentuale($valore, $totale)
{
    if ($totale == 0) {
        return 0;
    } else {
        return intval($valore / $totale * 100);
    }
}


function isNumeroCellulare($numero)
{
    if (str_starts_with($numero, '3')) {
        return true;
    }
    if (str_starts_with($numero, '+393')) {
        return true;
    }
    return false;
}


function telefonoWhatsapp($numero)
{

    if ($numero) {
        $html = "";
        if (str_starts_with($numero, '+393')) {
            $html .= '<a href="https://api.whatsapp.com/send?phone=' . str_replace('+', '', $numero) . '" class="text-gray-900 text-hover-primary"><i class="fab fa-whatsapp" style="color: #25D366;"></i>' . str_replace('+39', '', $numero) . '</a>';
        } else {
            $html .= '<a href = "tel:' . $numero . '" class="text-gray-900 text-hover-primary" >' . $numero . '</a >';
        }
        return $html;
    }

}


//ore
function time_to_decimal($ore)
{

    $ex = explode(':', $ore);

    $hours = $ex[0];
    $minutes = $ex[1];

    return $hours + round($minutes / 60, 2);
}

function decimal_to_time($decimal)
{
    $hours = intval($decimal);

    $minutes = $decimal - $hours;
    $minutesTime = round($minutes * 60, 0);
    if ($minutesTime < 10) {
        $minutesTime = '0' . $minutesTime;
    }
    if ($hours < 10) {
        $hours = '0' . $hours;
    }
    return $hours . ":" . $minutesTime;
}


function mese($m)
{
    switch ($m) {
        case 1:
            return 'Gen';
        case 2:
            return 'Feb';
        case 3:
            return 'Mar';
        case 4:
            return 'Apr';
        case 5:
            return 'Mag';
        case 6:
            return 'Giu';
        case 7:
            return 'Lug';
        case 8:
            return 'Ago';
        case 9:
            return 'Set';
        case 10:
            return 'Ott';
        case 11:
            return 'Nov';
        case 12:
            return 'Dic';
    }
}

function giorno($m)
{
    switch ($m) {
        case 1:
            return 'Lunedì';

        case 2:
            return 'Martedì';
        case 3:
            return 'Mercoledì';
        case 4:
            return 'Giovedì';
        case 5:
            return 'Venerdì';
        case 6:
            return 'Sabato';
        case 7:
            return 'Domenica';
    }
}


function debug_string_backtrace()
{
    ob_start();
    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();

    // Remove first item from backtrace as it's this function which
    // is redundant.
    $trace = preg_replace('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

    // Renumber backtrace items.
    $trace = preg_replace('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);

    return $trace;
}

if (!function_exists('setting')) {

    function setting($key, $default = null)
    {
        if (is_null($key)) {
            return new Setting();
        }

        if (is_array($key)) {
            return Setting::set($key[0], $key[1]);
        }

        $value = Setting::get($key);

        return is_null($value) ? value($default) : $value;
    }
}

function singolareOplurale($conteggio, $singolare, $plurale)
{
    return $conteggio . ' ' . ($conteggio == 1 ? $singolare : $plurale);
}

function maschileFemminile($genere, $maschile, $femminile)
{
    if (strtolower($genere) == 'm') {
        return $maschile;
    } else {
        return $femminile;
    }
}

function siNo($valore)
{
    return $valore ? 'Si' : 'No';
}

//File
function humanFileSize($size)
{
    $precision = 2;
    $units = array('byte', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }
    return number_format(round($size, $precision), 1, ',', '.') .' '. $units[$i];
}

function concatenaPercorsoFile(...$paths): string
{
    // Rimuove spazi vuoti e filtra elementi vuoti
    $paths = array_filter($paths, function ($path) {
        return !empty(trim($path));
    });

    // Pulisce ogni parte del percorso
    $cleanPaths = array_map(function ($path) {
        // Rimuove gli slash iniziali e finali
        $path = trim($path, '/\\');
        // Normalizza gli slash al forward slash
        $path = str_replace('\\', '/', $path);
        return $path;
    }, $paths);

    // Unisce le parti con uno slash
    return implode('/', $cleanPaths);
}

//Estrai da Codice fiscale
function estraiDataNascitaDaCodiceFiscale($codiceFiscale)
{
    // Estrai i caratteri relativi alla data di nascita
    $anno = substr($codiceFiscale, 6, 2);
    $mese = substr($codiceFiscale, 8, 1);
    $giorno = substr($codiceFiscale, 9, 2);

    // Converti l'anno in formato completo (es. 85 -> 1985)
    $annoCompleto = (intval($anno) < 20) ? "20" . $anno : "19" . $anno;

    // Mappa dei mesi (A = Gennaio, B = Febbraio, ..., T = Dicembre)
    $mesi = [
        'A' => '01', 'B' => '02', 'C' => '03', 'D' => '04',
        'E' => '05', 'H' => '06', 'L' => '07', 'M' => '08',
        'P' => '09', 'R' => '10', 'S' => '11', 'T' => '12'
    ];

    $meseNumero = $mesi[$mese];

    // Gestione del giorno per le donne (aggiungi 40)
    $giornoNumero = intval($giorno);
    if ($giornoNumero > 40) {
        $giornoNumero -= 40;
    }

    // Formatta la data di nascita
    $dataNascita = sprintf("%04d-%02d-%02d", $annoCompleto, $meseNumero, $giornoNumero);

    return $dataNascita;
}

function estraiSessoDaCodiceFiscale($codiceFiscale)
{
    if ($codiceFiscale) {
        $giornoNascita = intval(substr($codiceFiscale, 9, 2));
        if ($giornoNascita > 31) {
            return 'F'; // Femmina
        }
    }
    // Altrimenti, il sesso è maschile
    return "M";
}

function storeUpdateHelper($controller, $id)
{
    if ($id) {
        return action([$controller, 'update'], $id);
    } else {
        return action([$controller, 'store']);
    }
}



