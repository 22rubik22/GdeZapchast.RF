<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Advert;
use App\Models\User;
use App\Models\Tariff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

Artisan::command('logs:clear', function() {
    
    exec('rm -f ' . storage_path('logs/*.log'));

    exec('rm -f ' . base_path('*.log'));
    
    $this->comment('Logs have been cleared!');
    
})->describe('Clear log files');