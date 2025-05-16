<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMundialis extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-mundialis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs general update commands.';

    /**
     * Execute the console command.
     */
    public function handle() {
        //
        $this->info('********************');
        $this->info('* UPDATE MUNDIALIS *');
        $this->info('********************'."\n");

        // Check if the user has run composer and run migrations
        $this->info('This command should be run after installing packages using composer.');

        if ($this->confirm('Have you run the composer install command or equivalent?')) {
            // Run migrations
            $this->line("\n".'Clearing caches...');
            $this->call('config:cache');

            // Run migrations
            $this->line("\n".'Running migrations...');
            $this->call('migrate');

            // Run setup commands
            $this->line("\n".'Updating site pages and settings...');
            $this->call('app:add-site-settings');
            $this->call('app:add-site-pages');
        } else {
            $this->line('Aborting! Please run composer install and then run this command again.');
        }
    }
}
