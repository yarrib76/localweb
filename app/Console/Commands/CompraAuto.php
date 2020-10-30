<?php

namespace Donatella\Console\Commands;

use Illuminate\Console\Command;

class CompraAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompraAuto:Mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia mail para la compra automática de artículos';

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
        $compraAuto = new \Donatella\Http\Controllers\Articulo\CompraAuto();
        $compraAuto->inicio();
    }
}
