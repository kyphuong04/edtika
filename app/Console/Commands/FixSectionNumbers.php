<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSectionNumbers extends Command
{
    protected $signature = 'ielts:fix-section-numbers';
    protected $description = 'Fix section_number to be part numbers within each skill (e.g., Reading Part 1, 2, 3)';

    public function handle()
    {
        $this->info('Fixing section numbers...');
        
        // Standard IELTS skill order
        $skillOrder = ['listening', 'reading', 'writing', 'speaking'];
        
        // Get all tests
        $tests = DB::table('ielts_tests')->get();
        
        $testsUpdated = 0;
        $sectionsUpdated = 0;
        
        foreach ($tests as $test) {
            $globalOrder = 0;
            
            foreach ($skillOrder as $skill) {
                // Get sections for this test and skill, ordered by current sort_order
                $sections = DB::table('ielts_test_sections')
                    ->where('test_id', $test->id)
                    ->where('skill', $skill)
                    ->orderBy('sort_order')
                    ->get();
                
                $partNumber = 0;
                
                foreach ($sections as $section) {
                    $partNumber++;
                    $globalOrder++;
                    
                    DB::table('ielts_test_sections')
                        ->where('id', $section->id)
                        ->update([
                            'section_number' => $partNumber,
                            'sort_order' => $globalOrder
                        ]);
                    
                    $sectionsUpdated++;
                }
            }
            
            if ($globalOrder > 0) {
                $testsUpdated++;
            }
        }
        
        $this->info("Done! Updated {$sectionsUpdated} sections across {$testsUpdated} tests.");
        
        return 0;
    }
}
