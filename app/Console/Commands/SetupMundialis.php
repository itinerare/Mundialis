<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupMundialis extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup-mundialis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs set-up commands, excluding migrations as first-time migration must be done separately..';

    /**
     * Execute the console command.
     */
    public function handle() {
        //
        $this->info('********************');
        $this->info('* SET UP MUNDIALIS *');
        $this->info('********************'."\n");

        // Check if the user has run composer and run migrations
        $this->info('This command should be run after installing packages using composer and running first-time migrations. Once initial set-up has been performed, you will be prompted for details to set up the site\'s admin account.');
        if ($this->confirm('Have you run the composer install command or equivalent and run first-time migrations?')) {
            // Run setup commands
            $this->line("\n".'Adding site pages and settings...');
            $this->call('app:add-site-settings');
            $this->call('app:add-site-pages');
            $this->call('app:add-lexicon-settings');

            $this->line("\n".'Copying default images...');
            $this->call('app:copy-default-images');

            // Run admin user setup
            $this->line("\n".'Setting up admin user...');
            $this->call('app:setup-admin-user');
        } else {
            $this->line('Aborting! Please run composer install and php artisan migrate and then run this command again.');
        }
    }
}
