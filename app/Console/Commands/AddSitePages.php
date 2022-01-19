<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class AddSitePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-site-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds pages for basic site information (info, ToS, etc.).';

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
        //
        $this->info('******************');
        $this->info('* ADD SITE PAGES *');
        $this->info('******************'."\n");

        $this->line("Adding site pages...existing entries will be skipped.\n");

        $this->addSitePage('terms', 'Terms of Service', 'Your site\'s terms of service go here. This can be edited from the site\'s admin panel!');

        $this->addSitePage('privacy', 'Privacy Policy', 'Your site\'s privacy policy goes here. This can be edited from the site\'s admin panel!');

        $this->addSitePage('about', 'About', 'Info about your site goes here. This can be edited from the site\'s admin panel!');
    }

    /**
     * Add a site page.
     *
     * @param string $key
     * @param string $title
     * @param string $text
     */
    private function addSitePage($key, $title, $text)
    {
        if (!DB::table('site_pages')->where('key', $key)->exists()) {
            DB::table('site_pages')->insert([
                [
                    'key'        => $key,
                    'title'      => $title,
                    'text'       => $text,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],

            ]);
            $this->info('Added:   '.$title);
        } else {
            $this->line('Skipped: '.$title);
        }
    }
}
