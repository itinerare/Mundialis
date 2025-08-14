<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyAssetsToS3 extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy-assets-to-s3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies all the assets from the "public" disk over to the "s3" disk';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $from = 'public';
        $to = 's3';

        $directories = ['files', 'images'];

        foreach ($directories as $directory) {
            if (!Storage::disk($from)->exists($directory)) {
                continue;
            }

            $files = Storage::disk($from)->allFiles($directory);
            $this->line('Copying '.count($files).' files from /'.$directory.'...');

            foreach ($files as $file) {
                Storage::disk($to)->put($file, Storage::disk($from)->get($file));
            }
        }

        if (Storage::disk($from)->exists('/css/custom.css')) {
            $this->line('Copying /css/custom.css...');
            Storage::disk($to)->put('/css/custom.css', Storage::disk($from)->get('/css/custom.css'));
        }
    }
}
