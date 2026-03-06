<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsQuestionGroup;
use Illuminate\Http\Request;


class QuestionGroupController extends Controller
{
   
    public function index(Request $request)
    {
        $type = $request->get('type', 'mock'); // default là mock
        
        $query = IeltsQuestionGroup::where('bank_type', $type)
            ->withCount('questions')
            ->orderBy('created_at', 'desc');
        
        // filter theo skill
        if ($request->has('skill') && $request->skill) {
            $query->where('skill', $request->skill);
        }
        
        // filter theo target_band  
        if ($request->has('band') && $request->band) {
            $query->where('target_band', $request->band);
        }
        
        // search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $groups = $query->paginate(15);
        
        return view('design_1.panel.question_groups.index', compact('groups', 'type'));
    }
    
  
    public function create(Request $request)
    {
        $type = $request->get('type', 'mock');
        $skill = $request->get('skill'); // for mock test specific skill
        
        // If skill is specified (from mock test page), show skill-specific form
        if ($skill && $type === 'mock') {
            return view('design_1.panel.question_groups.create_skill', compact('type', 'skill'));
        }
        
        return view('design_1.panel.question_groups.create', compact('type'));
    }
    
  
    public function store(Request $request)
    {
        // Debug log BEFORE validation
        \Log::info('QuestionGroup Store - RAW Request', [
            'question_type' => $request->question_type,
            'section_type' => $request->section_type,
            'skill' => $request->skill,
            'all_request' => $request->except(['_token', 'audio_file', 'task_image'])
        ]);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bank_type' => 'required|in:mock,practice',
            'skill' => 'required|in:reading,listening,writing,speaking',
            'question_type' => 'nullable|string|in:task1_graph,task1_map,task1_process,task1_letter,task2_essay,part1,part2,part3',
            'section_type' => 'nullable|string|in:task1_graph,task1_map,task1_process,task1_letter,task2_essay,part1,part2,part3',
            'target_band' => 'nullable|numeric|min:1|max:9',
            'instructions' => 'nullable|string',
            'passage' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:51200',
            'task_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,avi|max:204800',
        ]);
        
        // Debug log AFTER validation
        \Log::info('QuestionGroup Store - AFTER Validation', [
            'validated_question_type' => $validated['question_type'] ?? 'NOT_SET',
            'validated_section_type' => $validated['section_type'] ?? 'NOT_SET',
        ]);
        
        // Map section_type to question_type if section_type is provided (from create_skill.blade.php form)
        if (!empty($validated['section_type'])) {
            $validated['question_type'] = $validated['section_type'];
            \Log::info('QuestionGroup Store - Mapped section_type to question_type', [
                'section_type' => $validated['section_type'],
                'question_type' => $validated['question_type']
            ]);
        }
        unset($validated['section_type']); // Remove section_type as it's not in the database
        
        // Debug log BEFORE create
        \Log::info('QuestionGroup Store - Data to be saved', [
            'question_type' => $validated['question_type'] ?? 'NOT_SET',
            'final_data' => $validated
        ]);
        
        // audio file upload
        if ($request->hasFile('audio_file')) {
            $validated['audio_path'] = $request->file('audio_file')->store('question_bank/audio', 'public');
        }
        
        // task image upload (for writing tasks)
        if ($request->hasFile('task_image')) {
            $validated['task_image'] = $request->file('task_image')->store('question_bank/images', 'public');
        }
        
        // video file upload (for speaking)
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('question_bank/videos', 'public');
        }
        
        $validated['creator_id'] = auth()->id();
        $validated['status'] = 'draft';
        
        $group = IeltsQuestionGroup::create($validated);
        
        return redirect()->route('panel.question-groups.show', $group->id)
            ->with('success', 'Group created! Now add questions.');
    }
    

    public function show($id)
    {
        $group = IeltsQuestionGroup::findOrFail($id);
        
        // Load questions based on bank_type
        if ($group->bank_type === 'mock') {
            $group->load('mockQuestions');
        } else {
            $group->load('practiceQuestions');
        }
        
        return view('design_1.panel.question_groups.show', compact('group'));
    }
    
    // from edit group
    public function edit($id)
    {
        $group = IeltsQuestionGroup::findOrFail($id);
        
        return view('design_1.panel.question_groups.edit', compact('group'));
    }
    
    // update group
    public function update(Request $request, $id)
    {
        $group = IeltsQuestionGroup::findOrFail($id);
        
        try {
            // Debug log - remove after testing
            \Log::info('Question Group Update Request', [
                'group_id' => $id,
                'old_question_type' => $group->question_type,
                'new_question_type' => $request->question_type,
                'all_input' => $request->except(['_token', '_method', 'audio_file', 'task_image'])
            ]);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'skill' => 'required|in:reading,listening,writing,speaking',
                'question_type' => 'nullable|string|in:task1_graph,task1_map,task1_process,task1_letter,task2_essay,part1,part2,part3',
                'target_band' => 'nullable|numeric|min:1|max:9',
                'instructions' => 'nullable|string',
                'passage' => 'nullable|string',
                'status' => 'nullable|in:draft,pending,approved',
                'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:51200',
                'task_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
                'video_file' => 'nullable|file|mimes:mp4,webm,mov,avi|max:204800',
            ]);
            
            // IMPORTANT: If question_type is empty/null, don't update it (keep old value)
            // This prevents losing the task type when editing other fields
            if (empty($validated['question_type']) && !empty($group->question_type)) {
                unset($validated['question_type']);
                \Log::info('Question Group Update - Keeping old question_type', [
                    'kept_value' => $group->question_type
                ]);
            }
            
            if ($request->hasFile('audio_file')) {
                $validated['audio_path'] = $request->file('audio_file')->store('question_bank/audio', 'public');
            }
            
            if ($request->hasFile('task_image')) {
                $validated['task_image'] = $request->file('task_image')->store('question_bank/images', 'public');
            }
            
            if ($request->hasFile('video_file')) {
                $validated['video_file'] = $request->file('video_file')->store('question_bank/videos', 'public');
            }
            
            // Debug log - remove after testing
            \Log::info('Question Group Update Validated Data', [
                'validated' => $validated
            ]);
            
            $group->update($validated);
            
            // Debug log - remove after testing
            \Log::info('Question Group After Update', [
                'id' => $group->id,
                'question_type' => $group->fresh()->question_type
            ]);
            
            return redirect()->route('panel.question-groups.show', $group->id)
                ->with('success', 'Group updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Question Group Update Validation Failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Question Group Update Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update group: ' . $e->getMessage())->withInput();
        }
    }
    
        // delete group
    public function destroy($id)
    {
        $group = IeltsQuestionGroup::findOrFail($id);
        $type = $group->bank_type;
        
        // delete all questions in the group first
        $group->questions()->delete();
        $group->delete();
        
        return redirect()->route('panel.question-groups.index', ['type' => $type])
            ->with('success', 'Group deleted!');
    }
    
    //submit form for approval
    public function submitApproval($id)
    {
        $group = IeltsQuestionGroup::findOrFail($id);
        
        // validate status
        if ($group->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft groups can be submitted');
        }
        
        // validate has questions
        if ($group->questions()->count() === 0) {
            return redirect()->back()->with('error', 'Cannot submit empty group. Add questions first.');
        }
        
        $group->update(['status' => 'pending']);
        
        // Notify managers and CEOs
        $approvers = \App\User::whereIn('role_name', ['manager', 'ceo'])
            ->where('status', 'active')
            ->get();
        
        foreach ($approvers as $approver) {
            \App\Models\Notification::create([
                'user_id' => $approver->id,
                'sender_id' => auth()->id(),
                'title' => 'Question Group Pending Approval',
                'message' => auth()->user()->full_name . ' submitted "' . $group->title . '" (' . ucfirst($group->bank_type) . ' - ' . ucfirst($group->skill) . ') for approval.',
                'sender' => \App\Models\Notification::$SystemSender,
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return redirect()->route('panel.question-groups.index', ['type' => $group->bank_type])
            ->with('success', 'Question group submitted for approval!');
    }
}
