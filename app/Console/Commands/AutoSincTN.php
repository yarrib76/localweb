<?php

namespace Donatella\Console\Commands;

use Donatella\Http\Controllers\Api\Automation\ReplicaTN;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AutoSincTN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sinctn {options*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronización de ART con TN. Primer parametro cantidad de ART, Segundo Nombre del Local';

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
    public function fire()
    {
        /*
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $option = $this->argument('options');

        switch ($option[1]) {
            case 'Samira' : $store_id = 938857;
                break;
            case 'Donatella' : $store_id = 963000;
                break;
            case 'Viamore' : $store_id = 1043936;
                break;
            case 'Dona' : $store_id = 972788;
                break;
        }
        $artiCant = $option[0];

        $sincArticulosTN = new ReplicaTN();
        $sincArticulosTN->sincroArticulos($store_id,$artiCant);
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::OPTIONAL, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];


    }
}
