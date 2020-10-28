<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BundesligaController;

class GenerateCSVForFlourish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bundesliga:generatecsvforflourish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate csv file for flourish line chart.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BundesligaController $bundesligaController)
    {
        parent::__construct();
        $this->bundesliga = $bundesligaController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->bundesliga->generateCSVForFlourish();
        return 'done';
    }
}
