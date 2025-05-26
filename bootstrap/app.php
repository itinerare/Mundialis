<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckRead;
use App\Http\Middleware\CheckWrite;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'read'  => CheckRead::class,
            'write' => CheckWrite::class,
            'admin' => CheckAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        if (config('mundialis.settings.enable_backups')) {
            $schedule->command('backup:clean')
                ->daily()->at('01:30');
            $schedule->command('backup:run')
                ->daily()->at('01:00');
            $schedule->command('backup:monitor')
                ->daily()->at('01:40');
        }
    })
    ->create();
