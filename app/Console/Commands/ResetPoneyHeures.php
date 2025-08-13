<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poney; 

class ResetPoneyHeures extends Command
{
    protected $signature = 'reset:poney-heures';
    protected $description = 'Reset heures field for all poneys daily';

    public function handle()
    {
        Poney::query()->update(['heures' => 0]);
        
        $this->info('Successfully reset all poney.heures to 0.');
    }
}
