<?php

namespace App\Models\Scopes;

use App\Enums\RuoliOperatoreEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FiltroOperatoreScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        // Se l'utente è superadmin, può vedere tutto
        if ($user->ruolo === RuoliOperatoreEnum::admin->value) {
            return;
        }

        $table = $model->getTable();

        // Se è un'azienda di servizio, filtra per azienda_servizio_id
        if ($user->ruolo === RuoliOperatoreEnum::azienda_di_servizio->value) {
            $aziendaServizio = $user->aziendaServizio;
            if ($aziendaServizio && $this->hasColumn($builder, 'azienda_servizio_id')) {
                $builder->where("{$table}.azienda_servizio_id", $aziendaServizio->id);
            } else {
                // Se non ha un'azienda di servizio associata o la tabella non ha la colonna, non vede nulla
                $builder->whereRaw('1 = 0');
            }
            return;
        }

        // Se è un amministratore di condominio
        if ($user->ruolo === RuoliOperatoreEnum::amministratore_condominio->value) {
            $amministratore = $user->amministratore;
            if ($amministratore && $this->hasColumn($builder, 'amministratore_id')) {
                $builder->where("{$table}.amministratore_id", $amministratore->id);
            } else {
                $builder->whereRaw('1 = 0');
            }
            return;
        }

        // Se è un condomino
        if ($user->ruolo === RuoliOperatoreEnum::condomino->value) {
            $condomino = $user->condomino;
            if ($condomino && $this->hasColumn($builder, 'condomino_id')) {
                $builder->where("{$table}.condomino_id", $condomino->id);
            } else {
                $builder->whereRaw('1 = 0');
            }
            return;
        }

        // Se il ruolo non è riconosciuto, non vede nulla
        $builder->whereRaw('1 = 0');
    }

    /**
     * Verifica se una colonna esiste nella tabella del model
     */
    private function hasColumn(Builder $builder, string $column): bool
    {
        $table = $builder->getModel()->getTable();
        return \Schema::hasColumn($table, $column);
    }

    /**
     * Extend the query builder with macros.
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('senzaFiltroOperatore', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('soloAziendaServizio', function (Builder $builder, int $aziendaId) {
            $table = $builder->getModel()->getTable();
            return $builder->withoutGlobalScope($this)
                ->where("{$table}.azienda_servizio_id", $aziendaId);
        });

        $builder->macro('soloAmministratore', function (Builder $builder, int $amministratoreId) {
            $table = $builder->getModel()->getTable();
            return $builder->withoutGlobalScope($this)
                ->where("{$table}.amministratore_id", $amministratoreId);
        });

        $builder->macro('soloCondomino', function (Builder $builder, int $condominoId) {
            $table = $builder->getModel()->getTable();
            return $builder->withoutGlobalScope($this)
                ->where("{$table}.condomino_id", $condominoId);
        });

        $builder->macro('perAzienda', function (Builder $builder, int $aziendaId) {
            $table = $builder->getModel()->getTable();
            return $builder->where("{$table}.azienda_servizio_id", $aziendaId);
        });

        $builder->macro('perAmministratore', function (Builder $builder, int $amministratoreId) {
            $table = $builder->getModel()->getTable();
            return $builder->where("{$table}.amministratore_id", $amministratoreId);
        });

        $builder->macro('perCondomino', function (Builder $builder, int $condominoId) {
            $table = $builder->getModel()->getTable();
            return $builder->where("{$table}.condomino_id", $condominoId);
        });
    }
}
