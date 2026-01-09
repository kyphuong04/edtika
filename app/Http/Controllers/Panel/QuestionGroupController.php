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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bank_type' => 'required|in:mock,practice',
            'skill' => 'required|in:reading,listening,writing,speaking',
            'question_type' => 'nullable|string|in:task1_graph,task1_map,task1_process,task1_letter,task2_essay,part1,part2,part3',
            'target_band' => 'nullable|numeric|min:1|max:9',
            'instructions' => 'nullable|string',
            'passage' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:51200',
        ]);
        
        // aduio file upload
        if ($request->hasFile('audio_file')) {
            $validated['audio_path'] = $request->file('audio_file')->store('question_bank/audio', 'public');
        }
        
        $validated['creator_id'] = auth()->id();
        $validated['status'] = 'draft';
        
        $group = IeltsQuestionGroup::create($validated);
        
        return redirect()->route('panel.question-groups.show', $group->id)
            ->with('success', 'Group created! Now add questions.');
    }
    

    public function show($id)
    {
        $group = IeltsQuestionGroup::with('questions')->findOrFail($id);
        
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
            ]);
            
            if ($request->hasFile('audio_file')) {
                $validated['audio_path'] = $request->file('audio_file')->store('question_bank/audio', 'public');
            }
            
            if ($request->hasFile('task_image')) {
                $validated['task_image'] = $request->file('task_image')->store('question_bank/images', 'public');
            }
            
            $group->update($validated);
            
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
