<?php

namespace Donatella\Console;

use Donatella\Models\Compras;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         Commands\ReporteArticuloProveedor::class,
         Commands\StatusMail::class,
         Commands\AutoSincTN::class,
         Commands\CompraAuto::class,
         Commands\CarritoAbandonado::class,
         Commands\AutoSincroWebFull::class,
         Commands\AutoCargaClienteFidel::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
