<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillQuestionOptions extends Command
{
    protected $signature = 'ielts:backfill-question-options';
    protected $description = 'Backfill answer_options for existing test questions from question bank data';

    public function handle()
    {
        $this->info('Starting to backfill question options...');
        
        // Get all test questions without answer_options
        $testQuestions = DB::table('ielts_test_questions')
            ->whereNull('answer_options')
            ->orWhere('answer_options', '')
            ->get();
        
        $this->info("Found {$testQuestions->count()} questions without options.");
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($testQuestions as $tq) {
            // Get section to find group_id
            $section = DB::table('ielts_test_sections')
                ->where('id', $tq->section_id)
                ->first();
            
            if (!$section || !$section->question_group_id) {
                $skipped++;
                continue;
            }
            
            // Get group to determine bank type
            $group = DB::table('ielts_question_groups')
                ->where('id', $section->question_group_id)
                ->first();
            
            if (!$group) {
                $skipped++;
                continue;
            }
            
            // Determine which bank table
            $bankTable = $group->bank_type === 'mock' 
                ? 'ielts_mock_question_bank' 
                : 'ielts_practice_question_bank';
            
            // Find matching question in bank by question_text or sort_order
            $bankQuestion = DB::table($bankTable)
                ->where('group_id', $group->id)
                ->where(function($q) use ($tq) {
                    $q->where('question_text', $tq->question_text)
                      ->orWhere('sort_order', $tq->question_number);
                })
                ->first();
            
            if (!$bankQuestion) {
                $skipped++;
                continue;
            }
            
            $answerOptions = null;
            
            // First check if bank question has answer_options
            if (!empty($bankQuestion->answer_options)) {
                $answerOptions = $bankQuestion->answer_options;
            }
            // Otherwise extract from question_data
            elseif (!empty($bankQuestion->question_data)) {
                $questionData = json_decode($bankQuestion->question_data, true);
                if (is_array($questionData)) {
                    $extractedOptions = [];
                    foreach ($questionData as $key => $value) {
                        // Match both 'options[A]' and 'options[A' (legacy data)
                        if (preg_match('/^options\[([A-Z]+)\]?$/', $key, $matches)) {
                            $extractedOptions[$matches[1]] = $value;
                        }
                    }
                    if (!empty($extractedOptions)) {
                        $answerOptions = json_encode($extractedOptions);
                    }
                }
            }
            
            if ($answerOptions) {
                DB::table('ielts_test_questions')
                    ->where('id', $tq->id)
                    ->update(['answer_options' => $answerOptions]);
                $updated++;
                $this->line("Updated question #{$tq->id}: {$tq->question_number}");
            } else {
                $skipped++;
            }
        }
        
        $this->info("Done! Updated {$updated} questions, skipped {$skipped}.");
        
        return 0;
    }
}
