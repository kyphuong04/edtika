<?php

namespace App\Console\Commands;

use App\Models\IeltsTestAnswer;
use Illuminate\Console\Command;

class NormalizeSpeakingAnswers extends Command
{
    protected $signature = 'ielts:normalize-speaking-answers';
    protected $description = 'Normalize speaking answers - move audio URLs from answer_text to file_url column';

    public function handle()
    {
        $this->info('Starting normalization of speaking answers...');
        
        // Find all speaking answers where answer_text contains audio URL but file_url is empty
        $answers = IeltsTestAnswer::whereHas('question.section', function ($query) {
                $query->where('skill', 'speaking');
            })
            ->whereNull('file_url')
            ->where(function ($query) {
                $query->where('answer_text', 'like', '/storage/speaking_answers/%')
                      ->orWhere('answer_text', 'like', '%.webm%')
                      ->orWhere('answer_text', 'like', '%.mp3%');
            })
            ->get();
        
        $count = $answers->count();
        $this->info("Found {$count} answers to normalize.");
        
        if ($count === 0) {
            $this->info('No answers need normalization.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        $updated = 0;
        foreach ($answers as $answer) {
            // Move audio URL from answer_text to file_url
            $answer->file_url = $answer->answer_text;
            $answer->answer_text = null; // Clear answer_text since it was storing audio URL
            $answer->save();
            
            $updated++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully normalized {$updated} speaking answers.");
        
        return 0;
    }
}
