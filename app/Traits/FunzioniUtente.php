<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait FunzioniUtente
{

    protected function salvaDatiUtente(User $record, Request $request, string $suffisso, string $ruolo): User
    {
        $nuovo = !$record->id;

        if ($nuovo) {

        }

        //Ciclo su campi
        $campi = [
            'nome' => 'app\getInputUcWords',
            'email' => 'strtolower',
            'cognome' => 'app\getInputUcWords',
            'telefono' => 'app\getInputTelefono',
        ];
        foreach ($campi as $campo => $funzione) {
            $campoRequest = $campo . '_' . $suffisso;
            $valore = $request->input($campoRequest);
            if ($funzione != '') {
                $valore = $funzione($valore);
            }
            $record->$campo = $valore;
        }
        $record->ruolo = $ruolo;
        $record->password = \Hash::make(config('configurazione.sviluppo') ? 'password' : Str::ulid());
        $record->save();

        $record->assignRole($ruolo);
        return $record;
    }

}
