<?php

namespace Donatella\Console\Commands;

use Donatella\Http\Controllers\Mail\ServerStatusMail;
use Illuminate\Console\Command;

class StatusMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Status:Mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar Mail';

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
        $serverStatusMail = new ServerStatusMail;
        $serverStatusMail->serverStatusMail();
    }
}
