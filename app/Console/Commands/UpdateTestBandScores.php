<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IeltsTest;

class UpdateTestBandScores extends Command
{
    protected $signature = 'ielts:update-band-scores';
    protected $description = 'Update target_band_min and target_band_max for existing tests';

    public function handle()
    {
        $tests = IeltsTest::whereNull('target_band_min')
                          ->orWhereNull('target_band_max')
                          ->get();
        
        $this->info("Found {$tests->count()} tests without band scores");
        
        $updated = 0;
        foreach ($tests as $test) {
            // Default band range based on test type
            // Practice tests typically range 5.0 - 7.0
            // Mock tests typically range 5.5 - 8.0
            if ($test->type === 'mock') {
                $defaultMin = 5.5;
                $defaultMax = 8.0;
            } else {
                $defaultMin = 5.0;
                $defaultMax = 7.0;
            }
            
            $test->update([
                'target_band_min' => $defaultMin,
                'target_band_max' => $defaultMax
            ]);
            $this->line("Set band for test #{$test->id} ({$test->title}): {$defaultMin} - {$defaultMax}");
            $updated++;
        }
        
        $this->info("Done! Updated {$updated} tests.");
        
        return 0;
    }
}
