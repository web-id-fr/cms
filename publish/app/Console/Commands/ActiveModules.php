<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActiveModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Active modules';

    /** @var array */
    protected $whitelist_module = [
        //
    ];

    /**
     * @return void
     */
    public function handle()
    {
        if (empty($this->whitelist_module)) {
            $this->warn("/!\ Il n'y a aucun module à activer !");
            return;
        }

        $this->info('[Activation des modules requis]');
        foreach ($this->whitelist_module as $module) {
            $this->call("module:enable", ['module' => $module]);
        }

        $this->info(PHP_EOL . '[Exécution des migrations des modules]');
        $this->call('migrate');

        $this->info(PHP_EOL . '[Publication des fichiers des modules]');
        foreach ($this->whitelist_module as $module) {
            $provider = "Webid\\Cms\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            $this->line(" - $provider : ");
            $this->call('vendor:publish', ['--provider' => $provider]);
        }
    }
}
