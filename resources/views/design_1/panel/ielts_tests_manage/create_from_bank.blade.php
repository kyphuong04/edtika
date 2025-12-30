@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<section class="ielts-test-creator">
    {{-- Header Section --}}
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-text">
                <div class="breadcrumb-modern">
                    <a href="{{ route('panel.my_ielts_tests.index') }}" class="breadcrumb-link">
                        <i class="fas fa-clipboard-list"></i> My Tests
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">Create New Test</span>
                </div>
                <h1 class="page-title-modern">
                    <span class="title-icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    Create IELTS Test
                </h1>
                <p class="page-subtitle">Build a complete IELTS test by selecting question groups from your Question Bank</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn-modern btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Luxury Progress Indicator --}}
    <div class="luxury-progress-wrapper">
        <div class="luxury-progress">
            {{-- Step 1: Active --}}
            <div class="progress-item active">
                <div class="progress-circle">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="progress-info">
                    <p class="progress-step-label">Step 1 of 3</p>
                    <h6 class="progress-step-name">Test Information</h6>
                </div>
            </div>
            
            {{-- Step 2: Inactive --}}
            <div class="progress-item">
                <div class="progress-circle">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            
            {{-- Step 3: Inactive --}}
            <div class="progress-item">
                <div class="progress-circle">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('panel.my_ielts_tests.store_from_bank') }}" method="POST" id="createTestForm">
        @csrf
        
        {{-- Test Information Card --}}
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="card-icon card-icon-purple">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="card-title-section">
                    <h3 class="card-title-modern">Test Information</h3>
                    <p class="card-subtitle">Configure the basic details of your IELTS test</p>
                </div>
            </div>
            
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-lg-8 mb-20">
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-heading label-icon"></i>
                                Test Title
                                <span class="required-star">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                class="form-control-modern" 
                                placeholder="e.g., IELTS Academic Mock Test - December 2024" 
                                required
                            >
                            <small class="form-hint">Choose a descriptive title that helps identify this test</small>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-20">
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-layer-group label-icon"></i>
                                Test Type
                                <span class="required-star">*</span>
                            </label>
                            <div class="select-wrapper-modern">
                                <select name="type" id="testType" class="form-control-modern" required>
                                    <option value="mock">Mock Test</option>
                                    <option value="practice">Practice Test</option>
                                    <option value="diagnostic">Diagnostic Test</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Test Requirements Info --}}
                    <div class="col-lg-12 mb-20">
                        <div class="test-requirements-box" id="testRequirements">
                            <div class="requirements-mock" style="display: block;">
                                <div class="req-header">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <strong>Mock Test Requirements:</strong>
                                </div>
                                <ul class="req-list">
                                    <li><strong>Listening:</strong> 10 questions • 30 minutes • Band score 0-9</li>
                                    <li><strong>Reading:</strong> 10 questions • 60 minutes • Band score 0-9</li>
                                    <li><strong>Writing:</strong> 2 tasks • 60 minutes • Band score 0-9</li>
                                    <li><strong>Speaking:</strong> 3 parts • 11-14 minutes • Band score 0-9</li>
                                    <li><strong>Total Duration:</strong> 2 hours 45 minutes</li>
                                </ul>
                            </div>
                            <div class="requirements-practice" style="display: none;">
                                <div class="req-header">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Practice Test Guidelines:</strong>
                                </div>
                                <ul class="req-list">
                                    <li>Flexible number of questions per skill</li>
                                    <li>Can focus on specific skills or sections</li>
                                    <li>Customizable duration and scoring</li>
                                    <li>Recommended: Select groups that match your training goals</li>
                                </ul>
                            </div>
                            <div class="requirements-diagnostic" style="display: none;">
                                <div class="req-header">
                                    <i class="fas fa-clipboard-check"></i>
                                    <strong>Diagnostic Test Guidelines:</strong>
                                </div>
                                <ul class="req-list">
                                    <li>Designed to assess student's current level</li>
                                    <li>Flexible structure based on assessment needs</li>
                                    <li>Include variety of question types</li>
                                    <li>Provides baseline for improvement tracking</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-20">
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-book label-icon"></i>
                                Format
                                <span class="required-star">*</span>
                            </label>
                            <div class="select-wrapper-modern">
                                <select name="format" class="form-control-modern" required>
                                    <option value="full">Full Test (All Skills)</option>
                                    <option value="academic">Academic</option>
                                    <option value="general">General Training</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 mb-0">
                        <div class="form-group-modern mb-0">
                            <label class="form-label-modern">
                                <i class="fas fa-align-left label-icon"></i>
                                Description
                                <span class="optional-badge">Optional</span>
                            </label>
                            <textarea 
                                name="description" 
                                class="form-control-modern" 
                                rows="3" 
                                placeholder="Add any additional information about this test..."></textarea>
                            <small class="form-hint">Provide context or special instructions for test takers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Question Groups Selection Card --}}
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="card-icon card-icon-blue">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="card-title-section">
                    <h3 class="card-title-modern">Select Question Groups</h3>
                    <p class="card-subtitle">Choose question groups for each skill section</p>
                </div>
            </div>
            
            <div class="card-body-modern">
                {{-- Modern Skill Tabs --}}
                <div class="skills-tabs-container">
                    <div class="skills-tabs">
                        <button type="button" class="skill-tab active" data-skill="listening">
                            <div class="skill-tab-icon">
                                <i class="fas fa-headphones"></i>
                            </div>
                            <div class="skill-tab-content">
                                <div class="skill-tab-title">Listening</div>
                                <div class="skill-tab-count" data-count="listening">0 selected</div>
                            </div>
                        </button>
                        
                        <button type="button" class="skill-tab" data-skill="reading">
                            <div class="skill-tab-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="skill-tab-content">
                                <div class="skill-tab-title">Reading</div>
                                <div class="skill-tab-count" data-count="reading">0 selected</div>
                            </div>
                        </button>
                        
                        <button type="button" class="skill-tab" data-skill="writing">
                            <div class="skill-tab-icon">
                                <i class="fas fa-pen"></i>
                            </div>
                            <div class="skill-tab-content">
                                <div class="skill-tab-title">Writing</div>
                                <div class="skill-tab-count" data-count="writing">0 selected</div>
                            </div>
                        </button>
                        
                        <button type="button" class="skill-tab" data-skill="speaking">
                            <div class="skill-tab-icon">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <div class="skill-tab-content">
                                <div class="skill-tab-title">Speaking</div>
                                <div class="skill-tab-count" data-count="speaking">0 selected</div>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Tab Content --}}
                <div class="skills-content">
                    {{-- Listening Tab --}}
                    <div class="skill-content active" data-skill-content="listening">
                        @php
                            $listeningMock = $mockGroups['listening'] ?? collect();
                            $listeningPractice = $practiceGroups['listening'] ?? collect();
                            $allListening = $listeningMock->merge($listeningPractice);
                        @endphp
                        
                        @forelse($allListening as $group)
                            <label class="question-group-card" data-skill-type="listening">
                                <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox">
                                <div class="group-card-content">
                                    <div class="group-card-header">
                                        <div class="group-title">{{ $group->title }}</div>
                                        <div class="group-check-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="group-meta">
                                        <span class="group-badge badge-{{ $group->bank_type === 'mock' ? 'primary' : 'success' }}">
                                            {{ $group->bank_type_label }}
                                        </span>
                                        <span class="group-info">
                                            <i class="fas fa-question-circle"></i>
                                            {{ $group->question_count }} questions
                                        </span>
                                        @if($group->target_band)
                                            <span class="group-info">
                                                <i class="fas fa-signal"></i>
                                                Band {{ $group->target_band }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h4 class="empty-title">No Listening Groups Available</h4>
                                <p class="empty-text">You need to have Listening question groups in your Question Bank before creating a test.</p>
                                <p class="empty-hint">Go to <strong>Question Bank → Listening</strong> to manage your question groups.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Reading Tab --}}
                    <div class="skill-content" data-skill-content="reading">
                        @php
                            $readingMock = $mockGroups['reading'] ?? collect();
                            $readingPractice = $practiceGroups['reading'] ?? collect();
                            $allReading = $readingMock->merge($readingPractice);
                        @endphp
                        
                        @forelse($allReading as $group)
                            <label class="question-group-card" data-skill-type="reading">
                                <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox">
                                <div class="group-card-content">
                                    <div class="group-card-header">
                                        <div class="group-title">{{ $group->title }}</div>
                                        <div class="group-check-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="group-meta">
                                        <span class="group-badge badge-{{ $group->bank_type === 'mock' ? 'primary' : 'success' }}">
                                            {{ $group->bank_type_label }}
                                        </span>
                                        <span class="group-info">
                                            <i class="fas fa-question-circle"></i>
                                            {{ $group->question_count }} questions
                                        </span>
                                        @if($group->target_band)
                                            <span class="group-info">
                                                <i class="fas fa-signal"></i>
                                                Band {{ $group->target_band }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h4 class="empty-title">No Reading Groups Available</h4>
                                <p class="empty-text">You need to have Reading question groups in your Question Bank before creating a test.</p>
                                <p class="empty-hint">Go to <strong>Question Bank → Reading</strong> to manage your question groups.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Writing Tab --}}
                    <div class="skill-content" data-skill-content="writing">
                        @php
                            $writingMock = $mockGroups['writing'] ?? collect();
                            $writingPractice = $practiceGroups['writing'] ?? collect();
                            $allWriting = $writingMock->merge($writingPractice);
                        @endphp
                        
                        @forelse($allWriting as $group)
                            <label class="question-group-card" data-skill-type="writing">
                                <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox">
                                <div class="group-card-content">
                                    <div class="group-card-header">
                                        <div class="group-title">{{ $group->title }}</div>
                                        <div class="group-check-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="group-meta">
                                        <span class="group-badge badge-{{ $group->bank_type === 'mock' ? 'primary' : 'success' }}">
                                            {{ $group->bank_type_label }}
                                        </span>
                                        <span class="group-info">
                                            <i class="fas fa-question-circle"></i>
                                            {{ $group->question_count }} questions
                                        </span>
                                        @if($group->target_band)
                                            <span class="group-info">
                                                <i class="fas fa-signal"></i>
                                                Band {{ $group->target_band }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h4 class="empty-title">No Writing Groups Available</h4>
                                <p class="empty-text">You need to have Writing question groups in your Question Bank before creating a test.</p>
                                <p class="empty-hint">Go to <strong>Question Bank → Writing</strong> to manage your question groups.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Speaking Tab --}}
                    <div class="skill-content" data-skill-content="speaking">
                        @php
                            $speakingMock = $mockGroups['speaking'] ?? collect();
                            $speakingPractice = $practiceGroups['speaking'] ?? collect();
                            $allSpeaking = $speakingMock->merge($speakingPractice);
                        @endphp
                        
                        @forelse($allSpeaking as $group)
                            <label class="question-group-card" data-skill-type="speaking">
                                <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox">
                                <div class="group-card-content">
                                    <div class="group-card-header">
                                        <div class="group-title">{{ $group->title }}</div>
                                        <div class="group-check-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="group-meta">
                                        <span class="group-badge badge-{{ $group->bank_type === 'mock' ? 'primary' : 'success' }}">
                                            {{ $group->bank_type_label }}
                                        </span>
                                        <span class="group-info">
                                            <i class="fas fa-question-circle"></i>
                                            {{ $group->question_count }} questions
                                        </span>
                                        @if($group->target_band)
                                            <span class="group-info">
                                                <i class="fas fa-signal"></i>
                                                Band {{ $group->target_band }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h4 class="empty-title">No Speaking Groups Available</h4>
                                <p class="empty-text">You need to have Speaking question groups in your Question Bank before creating a test.</p>
                                <p class="empty-hint">Go to <strong>Question Bank → Speaking</strong> to manage your question groups.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Selection Summary --}}
                <div class="selection-summary" id="selectionSummary">
                    <div class="summary-header">
                        <div class="summary-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="summary-title">
                            <h4>Selection Summary</h4>
                            <p id="summaryCount">0 question groups selected</p>
                        </div>
                    </div>
                    <div class="summary-content" id="summaryContent">
                        <p class="text-muted">No question groups selected yet. Select groups from the tabs above.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="form-actions">
            <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn-modern btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>
            <button type="submit" class="btn-modern btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-check-circle"></i>
                <span>Create Test</span>
            </button>
        </div>
    </form>
</section>

<style>
/* Modern Page Styling */
.ielts-test-creator {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Section */
.page-header-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
}

.breadcrumb-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s;
}

.breadcrumb-link:hover {
    color: #fff;
}

.breadcrumb-separator {
    color: rgba(255, 255, 255, 0.6);
}

.breadcrumb-current {
    color: #fff;
    font-weight: 500;
}

.page-title-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 8px 0;
}

.title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.page-subtitle {
    font-size: 15px;
    color: rgba(255, 255, 255, 0.95);
    margin: 0;
    line-height: 1.5;
}

/* Luxury Progress Indicator */
.luxury-progress-wrapper {
    margin-bottom: 40px;
}

.luxury-progress {
    position: relative;
    display: flex;
    align-items: center;
    padding: 24px 32px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.luxury-progress::before {
    content: '';
    position: absolute;
    left: 32px;
    right: 32px;
    top: 50%;
    height: 2px;
    background: linear-gradient(90deg, #e5e7eb 0%, #f3f4f6 100%);
    z-index: 0;
    transform: translateY(-50%);
}

.progress-item {
    position: relative;
    display: flex;
    align-items: center;
    z-index: 1;
    flex: 1;
}

.progress-item:not(:last-child) {
    margin-right: 48px;
}

.progress-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #f3f4f6;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #9ca3af;
    flex-shrink: 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.progress-item.active .progress-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
    transform: scale(1.05);
}

.progress-info {
    margin-left: 16px;
    opacity: 1;
}

.progress-item:not(.active) .progress-info {
    display: none;
}

.progress-step-label {
    font-size: 12px;
    color: #9ca3af;
    margin: 0 0 4px 0;
    font-weight: 500;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.progress-step-name {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    letter-spacing: -0.2px;
}

/* Modern Card */
.card-modern {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.3s;
}

.card-modern:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.card-header-modern {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px 32px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e5e7eb;
}

.card-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}

.card-icon-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-icon-blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.card-title-section {
    flex: 1;
}

.card-title-modern {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.card-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.card-body-modern {
    padding: 32px;
}

/* Modern Form Controls */
.form-group-modern {
    margin-bottom: 24px;
}

.form-label-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 10px;
}

.label-icon {
    color: #9ca3af;
    font-size: 13px;
}

.required-star {
    color: #ef4444;
    font-size: 16px;
}

.optional-badge {
    font-size: 11px;
    font-weight: 500;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 4px;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    font-size: 15px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s;
    background: #fff;
    color: #1f2937;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control-modern::placeholder {
    color: #9ca3af;
}

textarea.form-control-modern {
    resize: vertical;
    min-height: 80px;
}

.select-wrapper-modern {
    position: relative;
}

.select-wrapper-modern .form-control-modern {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    font-size: 12px;
}

.form-hint {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: #6b7280;
}

/* Modern Skills Tabs */
.skills-tabs-container {
    margin-bottom: 24px;
}

.skills-tabs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.skill-tab {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.skill-tab:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-2px);
}

.skill-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
}

.skill-tab-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #667eea;
    flex-shrink: 0;
}

.skill-tab.active .skill-tab-icon {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.skill-tab-content {
    flex: 1;
    text-align: left;
}

.skill-tab-title {
    font-size: 15px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 2px;
}

.skill-tab.active .skill-tab-title {
    color: #fff;
}

.skill-tab-count {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.skill-tab.active .skill-tab-count {
    color: rgba(255, 255, 255, 0.9);
}

/* Skills Content */
.skills-content {
    position: relative;
    min-height: 300px;
}

.skill-content {
    display: none;
}

.skill-content.active {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

/* Question Group Card */
.question-group-card {
    position: relative;
    display: block;
    cursor: pointer;
    margin: 0;
}

.question-group-card .group-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.group-card-content {
    background: #fff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    transition: all 0.3s;
}

.question-group-card:hover .group-card-content {
    border-color: #667eea;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.question-group-card .group-checkbox:checked ~ .group-card-content {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
}

.group-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
}

.group-title {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
    line-height: 1.4;
    flex: 1;
    padding-right: 8px;
}

.group-check-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    font-size: 12px;
    flex-shrink: 0;
    transition: all 0.3s;
}

.question-group-card .group-checkbox:checked ~ .group-card-content .group-check-icon {
    background: #667eea;
    border-color: #667eea;
    color: #fff;
}

.group-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.group-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.group-badge.badge-primary {
    background: #dbeafe;
    color: #1e40af;
}

.group-badge.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.group-info {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    color: #6b7280;
}

.group-info i {
    font-size: 11px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: #9ca3af;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.empty-text {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 12px;
}

.empty-hint {
    font-size: 13px;
    color: #9ca3af;
    margin: 0;
}

/* Test Requirements Box */
.test-requirements-box {
    padding: 20px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 2px solid #93c5fd;
    border-radius: 12px;
    margin-top: 8px;
}

.req-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    color: #1e40af;
    margin-bottom: 12px;
}

.req-header i {
    font-size: 18px;
}

.req-list {
    margin: 0;
    padding-left: 20px;
    list-style: none;
}

.req-list li {
    font-size: 14px;
    color: #1e3a8a;
    margin-bottom: 8px;
    position: relative;
    padding-left: 16px;
}

.req-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #3b82f6;
    font-weight: bold;
}

/* Selection Summary */
.selection-summary {
    margin-top: 32px;
    padding: 24px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #86efac;
    border-radius: 14px;
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #22c55e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
}

.summary-title h4 {
    font-size: 16px;
    font-weight: 700;
    color: #166534;
    margin: 0 0 4px 0;
}

.summary-title p {
    font-size: 13px;
    color: #16a34a;
    margin: 0;
}

.summary-content {
    font-size: 14px;
    color: #166534;
    line-height: 1.6;
}

.summary-content .text-muted {
    color: #16a34a !important;
}

/* Modern Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    white-space: nowrap;
}

.btn-modern.btn-lg {
    padding: 14px 32px;
    font-size: 16px;
}

.btn-modern.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-modern.btn-primary:hover {
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    transform: translateY(-2px);
}

.btn-modern.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-modern.btn-secondary:hover {
    background: #e5e7eb;
    border-color: #d1d5db;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    padding: 24px;
    background: #f9fafb;
    border-radius: 16px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .skills-tabs {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .luxury-progress {
        padding: 20px 24px;
        overflow-x: auto;
    }
    
    .luxury-progress::before {
        left: 24px;
        right: 24px;
    }
    
    .progress-item:not(:last-child) {
        margin-right: 32px;
    }
}

@media (max-width: 640px) {
    .luxury-progress {
        padding: 16px;
    }
    
    .luxury-progress::before {
        left: 16px;
        right: 16px;
    }
    
    .progress-circle {
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
    
    .progress-step-name {
        font-size: 14px;
    }
}

@media (max-width: 640px) {
    .page-header-modern {
        padding: 24px;
    }
    
    .page-title-modern {
        font-size: 24px;
    }
    
    .skills-tabs {
        grid-template-columns: 1fr;
    }
    
    .skill-content.active {
        grid-template-columns: 1fr;
    }
    
    .card-body-modern {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test Type Requirements Toggle
    const testTypeSelect = document.getElementById('testType');
    
    if (testTypeSelect) {
        testTypeSelect.addEventListener('change', function() {
            const type = this.value;
            document.querySelectorAll('.requirements-mock, .requirements-practice, .requirements-diagnostic').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelector('.requirements-' + type).style.display = 'block';
        });
    }
    
    // Tab Switching
    const skillTabs = document.querySelectorAll('.skill-tab');
    const skillContents = document.querySelectorAll('.skill-content');
    
    skillTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const skill = this.dataset.skill;
            
            // Update active tabs
            skillTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update active content
            skillContents.forEach(c => c.classList.remove('active'));
            document.querySelector(`[data-skill-content="${skill}"]`).classList.add('active');
            
            // Update progress
            updateProgress();
        });
    });
    
    // Checkbox Management
    const checkboxes = document.querySelectorAll('.group-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateProgress);
    });
    
    function updateProgress() {
        const allChecked = Array.from(checkboxes).filter(cb => cb.checked);
        const totalCount = allChecked.length;
        
        // Update skill counts
        ['listening', 'reading', 'writing', 'speaking'].forEach(skill => {
            const skillChecked = Array.from(checkboxes).filter(cb => {
                return cb.checked && cb.closest(`[data-skill-type="${skill}"]`);
            });
            const countEl = document.querySelector(`[data-count="${skill}"]`);
            if (countEl) {
                countEl.textContent = `${skillChecked.length} selected`;
            }
        });
        
        // Update summary
        const summaryCount = document.getElementById('summaryCount');
        const summaryContent = document.getElementById('summaryContent');
        
        summaryCount.textContent = `${totalCount} question group${totalCount !== 1 ? 's' : ''} selected`;
        
        if (totalCount === 0) {
            summaryContent.innerHTML = '<p class="text-muted">No question groups selected yet. Select groups from the tabs above.</p>';
        } else {
            const groupNames = allChecked.map(cb => {
                const title = cb.closest('.question-group-card').querySelector('.group-title').textContent;
                return `<span style="display: inline-block; margin: 4px; padding: 6px 12px; background: #fff; border-radius: 8px; font-size: 13px; border: 1px solid #86efac;">${title}</span>`;
            });
            summaryContent.innerHTML = groupNames.join('');
        }
        
        // Note: Step progress updates removed - new design uses simple static steps
    }
    
    // Update form title input to trigger validation
    const titleInput = document.querySelector('input[name="title"]');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            // Just for future validation if needed
        });
    }
    
    // Form validation
    const form = document.getElementById('createTestForm');
    
    form.addEventListener('submit', function(e) {
        const checkedGroups = Array.from(checkboxes).filter(cb => cb.checked);
        
        if (checkedGroups.length === 0) {
            e.preventDefault();
            alert('Please select at least one question group.');
            return false;
        }
    });
    
    // Initialize
    updateProgress();
});
</script>
@endsection
