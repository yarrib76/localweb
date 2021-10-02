<?php

namespace Donatella\Console\Commands;

use Donatella\Http\Controllers\TiendaNube\CarritosAbandonados;
use Illuminate\Console\Command;

class CarritoAbandonado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carrito:abandonado {options*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $option = $this->argument('options');

        switch ($option[0]) {
            case 'Samira' : $store_id = 938857;
                break;
            case 'Donatella' : $store_id = 963000;
                break;
            case 'Viamore' : $store_id = 1043936;
                break;
            case 'Dona' : $store_id = 972788;
                break;
        }
        $run = new CarritosAbandonados();
        $run->main($store_id);
    }
}
