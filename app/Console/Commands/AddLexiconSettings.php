<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class AddLexiconSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-lexicon-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds common english lexical classes/parts of speech to the lexicon settings table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info('************************');
        $this->info('* ADD LEXICON SETTINGS *');
        $this->info('************************'."\n");

        $this->line("Adding lexicon settings...existing entries will be skipped.\n");

        $this->addLexiconSetting('Noun', 'n', 9);
        $this->addLexiconSetting('Verb', 'v', 8);
        $this->addLexiconSetting('Adjective', 'adj', 7);
        $this->addLexiconSetting('Adverb', 'adv', 6);
        $this->addLexiconSetting('Pronoun', 'pro', 5);
        $this->addLexiconSetting('Preposition', 'prep', 4);
        $this->addLexiconSetting('Conjunction', 'conj', 3);
        $this->addLexiconSetting('Interjection', 'intj', 2);
        $this->addLexiconSetting('Numeral', 'num', 1);
        $this->addLexiconSetting('Article', 'art', 0);
    }

    /**
     * Add a site page.
     *
     *
     * @param string $key
     * @param string $title
     * @param string $text
     */
    private function addLexiconSetting($name, $abbreviation, $sort)
    {
        if (!DB::table('lexicon_settings')->where('name', $name)->exists()) {
            DB::table('lexicon_settings')->insert([
                [
                    'name'         => $name,
                    'abbreviation' => $abbreviation ? $abbreviation : null,
                    'sort'         => $sort,
                ],

            ]);
            $this->info('Added:   '.$name);
        } else {
            $this->line('Skipped: '.$name);
        }
    }
}
