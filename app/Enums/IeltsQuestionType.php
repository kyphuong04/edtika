<?php

namespace App\Enums;

class IeltsQuestionType
{
    // reading types (13)
    const MULTIPLE_CHOICE_SINGLE = 'multiple_choice_single';
    const MULTIPLE_CHOICE_MULTIPLE = 'multiple_choice_multiple';
    const TRUE_FALSE_NOT_GIVEN = 'true_false_not_given';
    const YES_NO_NOT_GIVEN = 'yes_no_not_given';
    const MATCHING_HEADINGS = 'matching_headings';
    const MATCHING_INFORMATION = 'matching_information';
    const MATCHING_FEATURES = 'matching_features';
    const MATCHING_SENTENCE_ENDINGS = 'matching_sentence_endings';
    const SENTENCE_COMPLETION = 'sentence_completion';
    const SUMMARY_COMPLETION = 'summary_completion';
    const NOTE_COMPLETION = 'note_completion';
    const TABLE_COMPLETION = 'table_completion';
    const SHORT_ANSWER = 'short_answer';
    
    // listening types (7) - reuse some from reading
    const FORM_COMPLETION = 'form_completion';
    const FLOW_CHART = 'flow_chart';
    const DIAGRAM_LABELING = 'diagram_labeling';
    const MAP_LABELING = 'map_labeling';
    
    // writing types (3)
    const WRITING_TASK1_GRAPH = 'writing_task1_graph';
    const WRITING_TASK1_PROCESS = 'writing_task1_process';
    const WRITING_TASK2_ESSAY = 'writing_task2_essay';
    
    // speaking types (3)
    const SPEAKING_PART1 = 'speaking_part1';
    const SPEAKING_PART2_CUE_CARD = 'speaking_part2_cue_card';
    const SPEAKING_PART3 = 'speaking_part3';
    
    // get all types
    public static function all()
    {
        return [
            // reading
            self::MULTIPLE_CHOICE_SINGLE,
            self::MULTIPLE_CHOICE_MULTIPLE,
            self::TRUE_FALSE_NOT_GIVEN,
            self::YES_NO_NOT_GIVEN,
            self::MATCHING_HEADINGS,
            self::MATCHING_INFORMATION,
            self::MATCHING_FEATURES,
            self::MATCHING_SENTENCE_ENDINGS,
            self::SENTENCE_COMPLETION,
            self::SUMMARY_COMPLETION,
            self::NOTE_COMPLETION,
            self::TABLE_COMPLETION,
            self::SHORT_ANSWER,
            
            // listening
            self::FORM_COMPLETION,
            self::FLOW_CHART,
            self::DIAGRAM_LABELING,
            self::MAP_LABELING,
            
            // writing
            self::WRITING_TASK1_GRAPH,
            self::WRITING_TASK1_PROCESS,
            self::WRITING_TASK2_ESSAY,
            
            // speaking
            self::SPEAKING_PART1,
            self::SPEAKING_PART2_CUE_CARD,
            self::SPEAKING_PART3,
        ];
    }
    
    // get types by skill
    public static function forSkill($skill)
    {
        $types = [
            'reading' => [
                self::MULTIPLE_CHOICE_SINGLE,
                self::MULTIPLE_CHOICE_MULTIPLE,
                self::TRUE_FALSE_NOT_GIVEN,
                self::YES_NO_NOT_GIVEN,
                self::MATCHING_HEADINGS,
                self::MATCHING_INFORMATION,
                self::MATCHING_FEATURES,
                self::MATCHING_SENTENCE_ENDINGS,
                self::SENTENCE_COMPLETION,
                self::SUMMARY_COMPLETION,
                self::NOTE_COMPLETION,
                self::TABLE_COMPLETION,
                self::SHORT_ANSWER,
            ],
            'listening' => [
                self::MULTIPLE_CHOICE_SINGLE, // shared with reading
                self::FORM_COMPLETION,
                self::NOTE_COMPLETION, // shared
                self::TABLE_COMPLETION, // shared
                self::FLOW_CHART,
                self::DIAGRAM_LABELING,
                self::MAP_LABELING,
            ],
            'writing' => [
                self::WRITING_TASK1_GRAPH,
                self::WRITING_TASK1_PROCESS,
                self::WRITING_TASK2_ESSAY,
            ],
            'speaking' => [
                self::SPEAKING_PART1,
                self::SPEAKING_PART2_CUE_CARD,
                self::SPEAKING_PART3,
            ],
        ];
        
        return $types[$skill] ?? [];
    }
    
    // get human-readable label
    public static function label($type)
    {
        $labels = [
            self::MULTIPLE_CHOICE_SINGLE => 'Multiple Choice (Single)',
            self::MULTIPLE_CHOICE_MULTIPLE => 'Multiple Choice (Multiple)',
            self::TRUE_FALSE_NOT_GIVEN => 'True/False/Not Given',
            self::YES_NO_NOT_GIVEN => 'Yes/No/Not Given',
            self::MATCHING_HEADINGS => 'Matching Headings',
            self::MATCHING_INFORMATION => 'Matching Information',
            self::MATCHING_FEATURES => 'Matching Features',
            self::MATCHING_SENTENCE_ENDINGS => 'Matching Sentence Endings',
            self::SENTENCE_COMPLETION => 'Sentence Completion',
            self::SUMMARY_COMPLETION => 'Summary Completion',
            self::NOTE_COMPLETION => 'Note Completion',
            self::TABLE_COMPLETION => 'Table Completion',
            self::SHORT_ANSWER => 'Short Answer Questions',
            self::FORM_COMPLETION => 'Form Completion',
            self::FLOW_CHART => 'Flow Chart Completion',
            self::DIAGRAM_LABELING => 'Diagram Labeling',
            self::MAP_LABELING => 'Map/Plan Labeling',
            self::WRITING_TASK1_GRAPH => 'Task 1 - Graph/Chart/Table',
            self::WRITING_TASK1_PROCESS => 'Task 1 - Process/Diagram',
            self::WRITING_TASK2_ESSAY => 'Task 2 - Essay',
            self::SPEAKING_PART1 => 'Part 1 - Introduction & Interview',
            self::SPEAKING_PART2_CUE_CARD => 'Part 2 - Cue Card',
            self::SPEAKING_PART3 => 'Part 3 - Discussion',
        ];
        
        return $labels[$type] ?? $type;
    }
}
