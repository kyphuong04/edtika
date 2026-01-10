@extends('admin.layouts.app')

@push('styles_top')
<style>
    .wizard-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Progress Steps */
    .wizard-progress {
        display: flex;
        justify-content: space-between;
        padding: 30px 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px 8px 0 0;
        position: relative;
    }
    
    .wizard-progress::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 80px;
        right: 80px;
        height: 3px;
        background: rgba(255,255,255,0.3);
        transform: translateY(-50%);
    }
    
    .wizard-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    
    .wizard-step-number {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border: 2px solid rgba(255,255,255,0.3);
    }
    
    .wizard-step.active .wizard-step-number {
        background: #fff;
        color: #667eea;
        border-color: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .wizard-step.completed .wizard-step-number {
        background: #28a745;
        color: #fff;
        border-color: #28a745;
    }
    
    .wizard-step-label {
        color: rgba(255,255,255,0.7);
        margin-top: 10px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
    }
    
    .wizard-step.active .wizard-step-label,
    .wizard-step.completed .wizard-step-label {
        color: #fff;
    }
    
    /* Step Content */
    .wizard-content {
        padding: 40px;
    }
    
    .step-panel {
        display: none;
    }
    
    .step-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Type Cards */
    .type-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
    }
    
    .type-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
    }
    
    .type-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
    }
    
    .type-card-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 28px;
    }
    
    .type-card.mock .type-card-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
    }
    
    .type-card.practice .type-card-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #fff;
    }
    
    .type-card h4 {
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .type-card p {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 0;
    }
    
    /* Skill Cards */
    .skill-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 25px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .skill-card:hover {
        border-color: #667eea;
    }
    
    .skill-card.selected {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    
    .skill-card-icon {
        font-size: 32px;
        margin-bottom: 12px;
    }
    
    .skill-card.listening .skill-card-icon { color: #f39c12; }
    .skill-card.reading .skill-card-icon { color: #3498db; }
    .skill-card.writing .skill-card-icon { color: #9b59b6; }
    .skill-card.speaking .skill-card-icon { color: #e74c3c; }
    
    .skill-card h5 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .skill-card small {
        color: #6c757d;
    }
    
    /* Mock Skills Preview */
    .mock-skills-list {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .mock-skill-item {
        text-align: center;
        flex: 1;
        position: relative;
    }
    
    .mock-skill-item:not(:last-child)::after {
        content: '→';
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 20px;
    }
    
    .mock-skill-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #667eea;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    /* Section Preview Cards */
    .section-preview-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        background: #fff;
    }
    
    .section-preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .section-preview-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Wizard Footer */
    .wizard-footer {
        display: flex;
        justify-content: space-between;
        padding: 20px 40px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create IELTS Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">Create (Wizard)</div>
        </div>
    </div>

    <div class="section-body">
        <div class="wizard-container">
            <!-- Progress Steps -->
            <div class="wizard-progress">
                <div class="wizard-step active" data-step="1">
                    <div class="wizard-step-number">1</div>
                    <div class="wizard-step-label">Test Type</div>
                </div>
                <div class="wizard-step" data-step="2">
                    <div class="wizard-step-number">2</div>
                    <div class="wizard-step-label">Basic Info</div>
                </div>
                <div class="wizard-step" data-step="3">
                    <div class="wizard-step-number">3</div>
                    <div class="wizard-step-label">Sections</div>
                </div>
                <div class="wizard-step" data-step="4">
                    <div class="wizard-step-number">4</div>
                    <div class="wizard-step-label">Review</div>
                </div>
            </div>

            <form id="wizardForm" action="{{ route('admin.ielts_tests.wizard.store') }}" method="POST">
                @csrf
                
                <div class="wizard-content">
                    <!-- Step 1: Choose Test Type -->
                    <div class="step-panel active" data-step="1">
                        <h4 class="mb-4">What type of test do you want to create?</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="type-card mock" data-type="mock">
                                    <input type="radio" name="type" value="mock" class="d-none" id="typeMock">
                                    <div class="type-card-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h4>Mock Test</h4>
                                    <p>Full IELTS exam simulation with all 4 skills in official order</p>
                                    
                                    <div class="mock-skills-list">
                                        <div class="mock-skill-item">
                                            <div class="mock-skill-number">1</div>
                                            <div><strong>Listening</strong></div>
                                            <small>30 min</small>
                                        </div>
                                        <div class="mock-skill-item">
                                            <div class="mock-skill-number">2</div>
                                            <div><strong>Reading</strong></div>
                                            <small>60 min</small>
                                        </div>
                                        <div class="mock-skill-item">
                                            <div class="mock-skill-number">3</div>
                                            <div><strong>Writing</strong></div>
                                            <small>60 min</small>
                                        </div>
                                        <div class="mock-skill-item">
                                            <div class="mock-skill-number">4</div>
                                            <div><strong>Speaking</strong></div>
                                            <small>15 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="type-card practice" data-type="practice">
                                    <input type="radio" name="type" value="practice" class="d-none" id="typePractice">
                                    <div class="type-card-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <h4>Practice Test</h4>
                                    <p>Focus on ONE skill for targeted practice</p>
                                    
                                    <div class="row mt-4" id="practiceSkillsPreview" style="display: none;">
                                        <div class="col-6 col-lg-3 mb-3">
                                            <div class="skill-card listening" data-skill="listening">
                                                <input type="radio" name="practice_skill" value="listening" class="d-none">
                                                <div class="skill-card-icon"><i class="fas fa-headphones"></i></div>
                                                <h5>Listening</h5>
                                                <small>30 mins</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 mb-3">
                                            <div class="skill-card reading" data-skill="reading">
                                                <input type="radio" name="practice_skill" value="reading" class="d-none">
                                                <div class="skill-card-icon"><i class="fas fa-book-open"></i></div>
                                                <h5>Reading</h5>
                                                <small>60 mins</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 mb-3">
                                            <div class="skill-card writing" data-skill="writing">
                                                <input type="radio" name="practice_skill" value="writing" class="d-none">
                                                <div class="skill-card-icon"><i class="fas fa-pencil-alt"></i></div>
                                                <h5>Writing</h5>
                                                <small>60 mins</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 mb-3">
                                            <div class="skill-card speaking" data-skill="speaking">
                                                <input type="radio" name="practice_skill" value="speaking" class="d-none">
                                                <div class="skill-card-icon"><i class="fas fa-microphone"></i></div>
                                                <h5>Speaking</h5>
                                                <small>15 mins</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Basic Info -->
                    <div class="step-panel" data-step="2">
                        <h4 class="mb-4">Test Information</h4>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="input-label">Test Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-control-lg" 
                                           placeholder="e.g., Academic Reading Practice - Passage 1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">Format <span class="text-danger">*</span></label>
                                    <select name="format" class="form-control form-control-lg" required>
                                        <option value="academic">Academic</option>
                                        <option value="general">General Training</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="input-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Brief description of this test..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">Difficulty Level</label>
                                    <select name="difficulty_level" class="form-control">
                                        <option value="beginner">Beginner (Band 4-5)</option>
                                        <option value="intermediate" selected>Intermediate (Band 5.5-6.5)</option>
                                        <option value="advanced">Advanced (Band 7+)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">Target Band Min</label>
                                    <select name="target_band_min" class="form-control">
                                        <option value="">No minimum</option>
                                        @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">Target Band Max</label>
                                    <select name="target_band_max" class="form-control">
                                        <option value="">No maximum</option>
                                        @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Practice-specific options -->
                        <div id="practiceOptionsStep2" style="display: none;">
                            <hr class="my-4">
                            <h5 class="mb-3">Practice Options</h5>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">Duration (minutes)</label>
                                        <input type="number" name="skill_duration" id="skillDuration" class="form-control" 
                                               placeholder="Auto-filled based on skill">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">Practice Mode</label>
                                        <select name="practice_mode" class="form-control">
                                            <option value="untimed">Untimed (No pressure)</option>
                                            <option value="timed">Timed (With countdown)</option>
                                            <option value="exam">Exam Mode (Strict timing)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">Category</label>
                                        <select name="practice_category_id" class="form-control">
                                            <option value="">No category</option>
                                            @if(isset($practiceCategories))
                                                @foreach($practiceCategories as $skill => $categories)
                                                    <optgroup label="{{ ucfirst($skill) }}">
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" data-skill="{{ $skill }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="show_answers_immediately" class="custom-control-input" id="showAnswers" value="1" checked>
                                        <label class="custom-control-label" for="showAnswers">Show answers immediately after submission</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="allow_retake" class="custom-control-input" id="allowRetake" value="1" checked>
                                        <label class="custom-control-label" for="allowRetake">Allow unlimited retakes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Sections Preview -->
                    <div class="step-panel" data-step="3">
                        <h4 class="mb-4">Sections to be Created</h4>
                        <p class="text-muted mb-4">These sections will be automatically created. You can add content after the test is created.</p>
                        
                        <div id="sectionsPreview">
                            <!-- Will be filled by JavaScript -->
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Next Steps:</strong> After creating the test, you'll be redirected to add question groups and questions to each section.
                        </div>
                    </div>

                    <!-- Step 4: Review -->
                    <div class="step-panel" data-step="4">
                        <h4 class="mb-4">Review & Create</h4>
                        
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="mb-3">Test Summary</h5>
                                
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="200">Test Type:</th>
                                        <td id="reviewType" class="font-weight-bold"></td>
                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td id="reviewTitle"></td>
                                    </tr>
                                    <tr>
                                        <th>Format:</th>
                                        <td id="reviewFormat"></td>
                                    </tr>
                                    <tr>
                                        <th>Difficulty:</th>
                                        <td id="reviewDifficulty"></td>
                                    </tr>
                                    <tr>
                                        <th>Duration:</th>
                                        <td id="reviewDuration"></td>
                                    </tr>
                                    <tr>
                                        <th>Sections:</th>
                                        <td id="reviewSections"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="alert alert-success mt-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Ready to create!</strong> Click "Create Test" to proceed. Sections will be automatically generated.
                        </div>
                    </div>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="visibility: hidden;">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display: none;">
                        <i class="fas fa-check mr-2"></i> Create Test & Add Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts_bottom')
<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 4;
    let selectedType = null;
    let selectedSkill = null;

    // Type card selection
    $('.type-card').on('click', function() {
        $('.type-card').removeClass('selected');
        $(this).addClass('selected');
        selectedType = $(this).data('type');
        $(this).find('input[type="radio"]').prop('checked', true);
        
        if (selectedType === 'practice') {
            $('#practiceSkillsPreview').show();
        } else {
            $('#practiceSkillsPreview').hide();
            selectedSkill = null;
            $('.skill-card').removeClass('selected');
        }
    });

    // Skill card selection
    $('.skill-card').on('click', function() {
        $('.skill-card').removeClass('selected');
        $(this).addClass('selected');
        selectedSkill = $(this).data('skill');
        $(this).find('input[type="radio"]').prop('checked', true);
        
        // Set duration
        const durations = { listening: 30, reading: 60, writing: 60, speaking: 15 };
        $('#skillDuration').val(durations[selectedSkill]);
    });

    // Next button
    $('#nextBtn').on('click', function() {
        if (!validateStep(currentStep)) return;
        
        if (currentStep < totalSteps) {
            goToStep(currentStep + 1);
        }
    });

    // Previous button
    $('#prevBtn').on('click', function() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });

    function goToStep(step) {
        // Update progress
        $(`.wizard-step[data-step="${currentStep}"]`).removeClass('active').addClass('completed');
        $(`.wizard-step[data-step="${step}"]`).addClass('active');
        
        // Update panels
        $(`.step-panel[data-step="${currentStep}"]`).removeClass('active');
        $(`.step-panel[data-step="${step}"]`).addClass('active');
        
        currentStep = step;
        
        // Update buttons
        $('#prevBtn').css('visibility', step > 1 ? 'visible' : 'hidden');
        
        if (step === totalSteps) {
            $('#nextBtn').hide();
            $('#submitBtn').show();
            updateReview();
        } else {
            $('#nextBtn').show();
            $('#submitBtn').hide();
        }
        
        // Update specific step content
        if (step === 2) {
            updateStep2();
        } else if (step === 3) {
            updateSectionsPreview();
        }
    }

    function validateStep(step) {
        if (step === 1) {
            if (!selectedType) {
                alert('Please select a test type');
                return false;
            }
            if (selectedType === 'practice' && !selectedSkill) {
                alert('Please select a skill for practice test');
                return false;
            }
        }
        
        if (step === 2) {
            const title = $('input[name="title"]').val();
            if (!title || title.trim() === '') {
                alert('Please enter a test title');
                return false;
            }
        }
        
        return true;
    }

    function updateStep2() {
        if (selectedType === 'practice') {
            $('#practiceOptionsStep2').show();
            // Set duration based on skill
            const durations = { listening: 30, reading: 60, writing: 60, speaking: 15 };
            if (selectedSkill) {
                $('#skillDuration').val(durations[selectedSkill]);
            }
        } else {
            $('#practiceOptionsStep2').hide();
        }
    }

    function updateSectionsPreview() {
        let html = '';
        
        if (selectedType === 'mock') {
            const sections = [
                { skill: 'Listening', icon: 'fa-headphones', color: '#f39c12', duration: '30 min', questions: '40 questions (4 parts)' },
                { skill: 'Reading', icon: 'fa-book-open', color: '#3498db', duration: '60 min', questions: '40 questions (3 passages)' },
                { skill: 'Writing', icon: 'fa-pencil-alt', color: '#9b59b6', duration: '60 min', questions: '2 tasks' },
                { skill: 'Speaking', icon: 'fa-microphone', color: '#e74c3c', duration: '11-14 min', questions: '3 parts' }
            ];
            
            sections.forEach((s, i) => {
                html += `
                    <div class="section-preview-card">
                        <div class="section-preview-header">
                            <div>
                                <span class="badge" style="background: ${s.color}; color: #fff;">${i + 1}</span>
                                <i class="fas ${s.icon} ml-2 mr-2" style="color: ${s.color};"></i>
                                <strong>${s.skill}</strong>
                            </div>
                            <span class="text-muted">${s.duration}</span>
                        </div>
                        <div class="text-muted">${s.questions}</div>
                    </div>
                `;
            });
        } else if (selectedType === 'practice') {
            const skillConfig = {
                listening: { icon: 'fa-headphones', color: '#f39c12', questions: 'Up to 40 questions' },
                reading: { icon: 'fa-book-open', color: '#3498db', questions: 'Up to 40 questions' },
                writing: { icon: 'fa-pencil-alt', color: '#9b59b6', questions: '1-2 tasks' },
                speaking: { icon: 'fa-microphone', color: '#e74c3c', questions: '1-3 parts' }
            };
            
            const s = skillConfig[selectedSkill];
            const duration = $('#skillDuration').val() || 'Custom';
            
            html = `
                <div class="section-preview-card">
                    <div class="section-preview-header">
                        <div>
                            <span class="badge" style="background: ${s.color}; color: #fff;">1</span>
                            <i class="fas ${s.icon} ml-2 mr-2" style="color: ${s.color};"></i>
                            <strong>${selectedSkill.charAt(0).toUpperCase() + selectedSkill.slice(1)}</strong>
                        </div>
                        <span class="text-muted">${duration} min</span>
                    </div>
                    <div class="text-muted">${s.questions}</div>
                </div>
            `;
        }
        
        $('#sectionsPreview').html(html);
    }

    function updateReview() {
        const title = $('input[name="title"]').val();
        const format = $('select[name="format"]').val();
        const difficulty = $('select[name="difficulty_level"] option:selected').text();
        
        let typeText = selectedType === 'mock' ? 'Mock Test (Full 4 Skills)' : `Practice Test (${selectedSkill.charAt(0).toUpperCase() + selectedSkill.slice(1)})`;
        let duration = selectedType === 'mock' ? '165 minutes total' : ($('#skillDuration').val() || 'Custom') + ' minutes';
        let sections = selectedType === 'mock' ? 'Listening → Reading → Writing → Speaking' : selectedSkill.charAt(0).toUpperCase() + selectedSkill.slice(1);
        
        $('#reviewType').html(`<span class="badge badge-${selectedType === 'mock' ? 'danger' : 'primary'}">${typeText}</span>`);
        $('#reviewTitle').text(title);
        $('#reviewFormat').text(format.charAt(0).toUpperCase() + format.slice(1));
        $('#reviewDifficulty').text(difficulty);
        $('#reviewDuration').text(duration);
        $('#reviewSections').text(sections);
    }
});
</script>
@endpush
