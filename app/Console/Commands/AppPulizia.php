<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AppPulizia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pulizia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inizio pulizia del progetto...');

        $commands = [
            'cache:clear',
            'route:clear',
            'config:clear',
            'view:clear',
            'event:clear',
            'clear-compiled',
            'optimize:clear',
        ];

        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('Cache svuotate con successo.');


        // Cancella i log (esclude i .gitignore, anche se di solito non ci sono)
        $logFiles = glob(storage_path('logs/*.log'));
        foreach ($logFiles as $file) {
            File::delete($file);
        }
        $this->info('Log cancellati.');

        // Elimina la cartella laravel-excel se presente
        $excelCachePath = storage_path('framework/cache/laravel-excel');
        if (File::exists($excelCachePath)) {
            File::deleteDirectory($excelCachePath);
            $this->info('Cartella laravel-excel eliminata.');
        }


        $this->info('Pulizia eseguita con successo.');
    }
}
