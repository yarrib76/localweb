<?php

namespace Donatella\Console\Commands;

use Donatella\Http\Controllers\ClientesFidelizacion\ClientesFidel;
use Illuminate\Console\Command;

class AutoCargaClienteFidel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carga:clientesfidel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga la tabla para gestionar la fidelizacion de clientes';

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
        $cargaFidelizacion = new ClientesFidel();
        dd($cargaFidelizacion->cargoClientesFidel());
    }
}