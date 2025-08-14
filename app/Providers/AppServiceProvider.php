<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() {
        // As these are concerned with application correctness,
        // leave them enabled all the time.
        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();

        // While automatic eager loading should prevent this from being relevant,
        // leave this enabled in non-production environments to help catch any errors
        Model::preventLazyLoading(!$this->app->isProduction());
        Model::automaticallyEagerLoadRelationships();

        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        Storage::extend('dropbox', function ($app, $config) {
            $adapter = new DropboxAdapter(new DropboxClient(
                $config['token']
            ));

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

        // Load class aliases
        AliasLoader::getInstance([
            'Settings'      => \App\Facades\Settings::class,
            'Notifications' => \App\Facades\Notifications::class,
            'Image'         => \Intervention\Image\Facades\Image::class,
        ]);

        // Set custom polymorphic types for pages and entries,
        // for use by page links
        Relation::enforceMorphMap([
            'page'  => 'App\Models\Page\Page',
            'entry' => 'App\Models\Lexicon\LexiconEntry',
        ]);

        if (DB::Connection() instanceof SQLiteConnection) {
            // Handle REGEXP for sqlite
            // Adapted from https://bannister.me/blog/using-mysql-and-postgres-functions-in-sqlite
            DB::connection()->getPdo()->sqliteCreateFunction('regexp', function ($pattern, $string) {
                if (preg_match('/'.$pattern.'/', $string)) {
                    return true;
                }

                return false;
            }, 2);
        }

        /*
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path'     => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
