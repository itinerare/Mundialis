<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateChangelog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-changelog {--first-release} {--to-date} {--from-tag} {--to-tag} {--major} {--minor} {--patch} {--rc} {--beta} {--alpha} {--ver} {--history} {--no-verify} {--no-tag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the changelog.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = 'vendor/marcocesarato/php-conventional-changelog/conventional-changelog';
        if (is_file($file)) {
            include $file;
        } else {
            $this->error("file '{$file}' not found!");
        }
    }
}
