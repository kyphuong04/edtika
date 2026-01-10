<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IeltsTestSection;
use App\Models\IeltsQuestionGroup;

class LinkSectionsToGroups extends Command
{
    protected $signature = 'ielts:link-sections';
    protected $description = 'Link existing sections to their source question groups for audio access';

    public function handle()
    {
        $sections = IeltsTestSection::whereNull('question_group_id')
                                    ->orWhereNull('audio_file')
                                    ->get();
        
        $this->info("Found {$sections->count()} sections to process");
        
        $updated = 0;
        foreach ($sections as $section) {
            // Try to find matching group by title
            $group = IeltsQuestionGroup::where('title', $section->title)
                                       ->where('skill', $section->skill)
                                       ->first();
            
            if ($group) {
                $updateData = [];
                
                if (!$section->question_group_id) {
                    $updateData['question_group_id'] = $group->id;
                }
                
                if (!$section->audio_file && ($group->audio_path || $group->audio_file)) {
                    $updateData['audio_file'] = $group->audio_path ?? $group->audio_file;
                }
                
                if (!empty($updateData)) {
                    $section->update($updateData);
                    $this->line("Updated section #{$section->id} ({$section->title}) -> group #{$group->id}");
                    $updated++;
                }
            } else {
                $this->line("<comment>No matching group found for section #{$section->id} ({$section->title})</comment>");
            }
        }
        
        $this->info("Done! Updated {$updated} sections.");
        
        return 0;
    }
}
