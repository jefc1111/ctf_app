<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Utility\Simulation\SimulationStep;

class RunSimulationStep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ctf:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs randomly generated activity for Events which are configured for simulation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Event::where('simulate_activity', true)
            ->get()
            ->each(fn($e) => (new SimulationStep($e, $this))->run());
    }
}
