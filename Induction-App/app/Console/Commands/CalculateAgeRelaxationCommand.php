<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AgeRelaxationService;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\Cache;

class CalculateAgeRelaxationCommand extends Command
{
    protected $signature = 'candidates:calculate-age-relaxation {--batch-size=1000}';
    protected $description = 'Pre-calculate age relaxation for all candidates';

    public function handle()
    {
        $batchSize = $this->option('batch-size');
        
        User::whereHas('candidateProfile')
            ->with(['candidateProfile', 'ageRelaxation'])
            ->chunk($batchSize, function ($users) {
                foreach ($users as $user) {
                    $relaxationDays = AgeRelaxationService::calculateRelaxationDays(
                        $user->candidateProfile, 
                        $user->ageRelaxation
                    );
                    
                    // Store in cache or database
                    Cache::put("age_relaxation_{$user->id}", $relaxationDays, now()->addDay());
                }
            });

        $this->info('Age relaxation calculated for all candidates');
    }
}
