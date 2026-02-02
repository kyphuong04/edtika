    @extends('design_1.panel.layouts.panel')

    @push('styles_top')
    <style>
    /* Essential styles for IELTS Test Creator */
    .gap-12 { gap: 12px; }
    .rounded-12 { border-radius: 12px; }
    .rounded-16 { border-radius: 16px; }

    .card-modern { background: #fff; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); margin-bottom: 24px; overflow: hidden; }
    .card-header-modern { display: flex; align-items: center; gap: 16px; padding: 24px 32px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #e5e7eb; }
    .card-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; }
    .card-icon-purple, .card-icon-blue { background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); }
    .card-title-section { flex: 1; }
    .card-title-modern { font-size: 20px; font-weight: 700; color: #1f2937; margin: 0 0 4px 0; }
    .card-subtitle { font-size: 14px; color: #6b7280; margin: 0; }
    .card-body-modern { padding: 32px; }

    .form-group-modern { margin-bottom: 24px; }
    .form-label-modern { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 10px; }
    .label-icon { color: #9ca3af; }
    .required-star { color: #ef4444; }
    .form-control-modern { width: 100%; padding: 12px 16px; font-size: 15px; border: 2px solid #e5e7eb; border-radius: 12px; }
    .form-control-modern:focus { outline: none; border-color: #1a3a5c; box-shadow: 0 0 0 4px rgba(26,58,92,0.1); }
    .select-wrapper-modern { position: relative; }
    .select-wrapper-modern .form-control-modern { appearance: none; padding-right: 40px; cursor: pointer; }
    .select-arrow { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }


    /* Professional Progress Steps */
    .progress-steps-container {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .step-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #f3f4f6;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .progress-step.active .step-circle {
        background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%);
        border-color: #1a3a5c;
        color: #fff;
        box-shadow: 0 4px 12px rgba(26, 58, 92, 0.3);
    }

    .step-connector {
        position: absolute;
        top: 28px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: #e5e7eb;
        z-index: 1;
    }

    .progress-step.active .step-connector {
        background: linear-gradient(90deg, #1a3a5c 0%, #e5e7eb 100%);
    }

    .progress-step:last-child .step-connector {
        display: none;
    }

    .step-label {
        margin-top: 12px;
        text-align: center;
    }

    .step-number {
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .progress-step.active .step-number {
        color: #1a3a5c;
    }

    .step-title {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }

    .progress-step.active .step-title {
        color: #1f2937;
    }


    .skills-tabs { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
    .skill-tab { background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .skill-tab.active { background: #1a3a5c; border-color: #1a3a5c; color: #fff; }
    .skill-tab-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; }
    .skill-tab.active .skill-tab-icon { background: rgba(255,255,255,0.2); color: #fff; }
    .skill-tab-title { font-size: 15px; font-weight: 600; color: #374151; }
    .skill-tab.active .skill-tab-title { color: #fff; }
    .skill-tab-count { font-size: 12px; color: #6b7280; }
    .skill-tab.active .skill-tab-count { color: rgba(255,255,255,0.9); }

    .skill-content { display: none; }
    .skill-content.active { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }

    .question-group-card { position: relative; display: block; cursor: pointer; margin: 0; }
    .question-group-card .group-checkbox { position: absolute; opacity: 0; pointer-events: none; }
    .group-card-content { background: #fff; border: 2px solid #e5e7eb; border-radius: 12px; padding: 16px; transition: all 0.3s; }
    .question-group-card:hover .group-card-content { border-color: #1a3a5c; }
    .question-group-card .group-checkbox:checked ~ .group-card-content { border-color: #1a3a5c; background: rgba(26,58,92,0.05); }
    .group-card-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
    .group-title { font-size: 15px; font-weight: 600; color: #1f2937; flex: 1; }
    .group-check-icon { width: 24px; height: 24px; border-radius: 50%; border: 2px solid #d1d5db; display: flex; align-items: center; justify-content: center; color: transparent; }
    .question-group-card .group-checkbox:checked ~ .group-card-content .group-check-icon { background: #1a3a5c; border-color: #1a3a5c; color: #fff; }
    .group-meta { display: flex; gap: 8px; flex-wrap: wrap; }
    .group-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
    .group-badge.badge-primary { background: #dbeafe; color: #1e40af; }
    .group-badge.badge-success { background: #d1fae5; color: #065f46; }
    .group-info { display: flex; align-items: center; gap: 4px; font-size: 13px; color: #6b7280; }

    .test-requirements-box { padding: 16px 20px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #bfdbfe; border-radius: 12px; }
    .req-header { display: flex; align-items: center; gap: 8px; color: #1e40af; margin-bottom: 12px; }
    .req-list { list-style: none; padding: 0; margin-bottom: 12px; }
    .req-list li { margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
    .req-note { padding: 10px 14px; background: rgba(59,130,246,0.1); border-radius: 8px; border-left: 3px solid #3b82f6; }

    .time-settings-card { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bae6fd; border-radius: 12px; padding: 16px 20px; }
    .time-settings-header { display: flex; align-items: center; gap: 10px; color: #0369a1; margin-bottom: 8px; }

    .selection-summary { margin-top: 24px; padding: 16px 20px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #bbf7d0; border-radius: 12px; }
    .summary-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .summary-icon { width: 40px; height: 40px; border-radius: 10px; background: #22c55e; display: flex; align-items: center; justify-content: center; color: #fff; }
    .summary-title h4 { font-size: 16px; font-weight: 700; color: #166534; margin: 0; }
    .summary-title p { font-size: 13px; color: #16a34a; margin: 0; }

    .empty-state { text-align: center; padding: 40px 20px; }
    .empty-icon { width: 60px; height: 60px; margin: 0 auto 16px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 24px; }
    .empty-title { font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 8px; }
    .empty-text { font-size: 14px; color: #6b7280; }

    .btn-modern { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
    .btn-modern.btn-primary { background: #1a3a5c; color: #fff; border: none; }
    .btn-modern.btn-primary:hover { background: #2e5a8a; }
    .btn-modern.btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    .btn-modern.btn-lg { padding: 14px 28px; font-size: 16px; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding: 20px; background: #f9fafb; border-radius: 12px; }

    @media (max-width: 768px) {
    .skills-tabs { grid-template-columns: repeat(2, 1fr); }
    .skill-content.active { grid-template-columns: 1fr; }
    }

    /* CSS CUSTOM CHO FILTER SECTION */
    .filter-card-modern {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-main-icon {
        width: 40px;
        height: 40px;
        background: #f0f7ff;
        color: #1a3a5c;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clear-link {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-clear-link:hover {
        color: #ef4444;
        background: #fef2f2;
    }

    .filter-group {
        position: relative;
    }

    .filter-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        padding-left: 2px;
    }

    .filter-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-inner {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        pointer-events: none;
    }

    .filter-input-wrapper .form-control-modern {
        padding-left: 40px;
        height: 44px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 10px;
        font-size: 14px;
        color: #334155;
        font-weight: 500;
    }

    .filter-input-wrapper .form-control-modern:focus {
        background: #fff;
        border-color: #1a3a5c;
        box-shadow: 0 0 0 4px rgba(26, 58, 92, 0.08);
    }

    /* Custom Select Dropdown Arrow */
    .select-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 14px;
        padding-right: 40px !important;
    }

    .text-gray-400 { color: #94a3b8; }
    .text-gray-500 { color: #64748b; }
    </style>
    @endpush

    @section('content')
    <section>
        {{-- Header Section --}}
        <div class="bg-white rounded-16 shadow-sm p-24 mb-24">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-8">
                        <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%);">
                            <x-iconsax-bul-add-circle class="icons text-white" width="24px" height="24px"/>
                        </div>
                        <div>
                            <h1 class="font-20 font-weight-bold text-dark mb-4">
                                {{ trans('update.create_ielts_test') }}
                            </h1>
                            <p class="text-gray-500 font-13 mb-0">
                                <x-iconsax-lin-info-circle class="icons mr-4" width="14px" height="14px"/>
                                {{ trans('update.create_ielts_test_hint') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-12">
                    <a href="{{ route('panel.my_ielts_tests.index') }}" 
                    class="btn btn-outline-secondary"
                    style="background: #f3f4f6; border: 1px solid #e5e7eb;">
                        <x-iconsax-lin-arrow-left class="icons mr-8" width="16px" height="16px"/>{{ trans('update.back') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Professional Progress Steps --}}
        <div class="bg-white rounded-16 shadow-sm p-24 mb-24">
            <div class="progress-steps-container">
                {{-- Step 1: Active --}}
                <div class="progress-step active">
                    <div class="step-circle">
                        <x-iconsax-bul-document-text class="icons" width="20px" height="20px"/>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-label">
                        <div class="step-number">{{ trans('update.step') }} 1</div>
                        <div class="step-title">{{ trans('update.test_information') }}</div>
                    </div>
                </div>
                
                {{-- Step 2: Pending --}}
                <div class="progress-step">
                    <div class="step-circle">
                        <x-iconsax-bul-task-square class="icons" width="20px" height="20px"/>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-label">
                        <div class="step-number">{{ trans('update.step') }} 2</div>
                        <div class="step-title">{{ trans('update.select_groups') }}</div>
                    </div>
                </div>
                
                {{-- Step 3: Pending --}}
                <div class="progress-step">
                    <div class="step-circle">
                        <x-iconsax-bul-tick-circle class="icons" width="20px" height="20px"/>
                    </div>
                    <div class="step-label">
                        <div class="step-number">{{ trans('update.step') }} 3</div>
                        <div class="step-title">{{ trans('update.review_submit') }}</div>
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
                        <x-iconsax-bul-info-circle class="icons text-white" width="28px" height="28px"/>
                    </div>
                    <div class="card-title-section">
                        <h3 class="card-title-modern">{{ trans('update.test_information') }}</h3>
                        <p class="card-subtitle">{{ trans('update.test_information_hint') }}</p>
                    </div>
                </div>
                
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-lg-8 mb-20">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <x-iconsax-lin-text class="icons label-icon" width="16px" height="16px"/>
                                    {{ trans('update.test_title') }}
                                    <span class="required-star">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="title" 
                                    class="form-control-modern" 
                                    placeholder="e.g., IELTS Academic Mock Test - December 2024" 
                                    required
                                >
                                <small class="form-hint">{{ trans('update.test_title_hint') }}</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-20">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <x-iconsax-lin-category class="icons label-icon" width="16px" height="16px"/>
                                    {{ trans('update.test_type') }}
                                    <span class="required-star">*</span>
                                </label>
                                <div class="select-wrapper-modern">
                                    <select name="type" id="testType" class="form-control-modern" required>
                                        <option value="mock">{{ trans('update.mock_test') }}</option>
                                        <option value="practice">{{ trans('update.practice_test') }}</option>
                                        <option value="diagnostic">{{ trans('update.diagnostic_test') }}</option>
                                    </select>
                                    <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Test Requirements Info --}}
                        <div class="col-lg-12 mb-20">
                            <div class="test-requirements-box" id="testRequirements">
                                <div class="requirements-mock" style="display: block;">
                                    <div class="req-header">
                                        <x-iconsax-bul-info-circle class="icons" width="18px" height="18px"/>
                                        <strong>{{ trans('update.mock_test_requirements_full') }}</strong>
                                    </div>
                                    <ul class="req-list">
                                        <li><x-iconsax-bul-headphone class="icons" width="18px" height="18px" style="color: #1a3a5c;"/> <strong>{{ trans('update.listening') }}:</strong> 4 sections (Part 1-4) • 40 {{ trans('update.questions_count') }} • 30 {{ trans('update.min') }}</li>
                                        <li><x-iconsax-bul-book class="icons" width="18px" height="18px" style="color: #10b981;"/> <strong>{{ trans('update.reading') }}:</strong> 3 passages • 40 {{ trans('update.questions_count') }} • 60 {{ trans('update.min') }}</li>
                                        <li><x-iconsax-bul-edit-2 class="icons" width="18px" height="18px" style="color: #f59e0b;"/> <strong>{{ trans('update.writing') }}:</strong> Task 1 + Task 2 • 60 {{ trans('update.min') }}</li>
                                        <li><x-iconsax-bul-microphone-2 class="icons" width="18px" height="18px" style="color: #8b5cf6;"/> <strong>{{ trans('update.speaking') }}:</strong> Part 1 + Part 2 + Part 3 • 11-14 {{ trans('update.min') }}</li>
                                    </ul>
                                    <div class="req-note">
                                        <x-iconsax-lin-info-circle class="icons" width="16px" height="16px"/>
                                        <span>{!! trans('update.mock_bank_only_hint') !!}</span>
                                    </div>
                                </div>
                                <div class="requirements-practice" style="display: none;">
                                    <div class="req-header">
                                        <x-iconsax-lin-info-circle class="icons" width="18px" height="18px"/>
                                        <strong>{{ trans('update.practice_test_guidelines') }}</strong>
                                    </div>
                                    <ul class="req-list">
                                        <li><x-iconsax-bul-tick-circle class="icons" width="18px" height="18px" style="color: #10b981;"/> Focus on specific skills or question types</li>
                                        <li><x-iconsax-bul-tick-circle class="icons" width="18px" height="18px" style="color: #10b981;"/> Flexible number of questions and duration</li>
                                        <li><x-iconsax-bul-tick-circle class="icons" width="18px" height="18px" style="color: #10b981;"/> Immediate feedback option available</li>
                                        <li><x-iconsax-bul-tick-circle class="icons" width="18px" height="18px" style="color: #10b981;"/> Can include 1 or more skills</li>
                                    </ul>
                                    <div class="req-note">
                                        <x-iconsax-lin-info-circle class="icons" width="16px" height="16px"/>
                                        <span>{!! trans('update.practice_bank_only_hint') !!}</span>
                                    </div>
                                </div>
                                <div class="requirements-diagnostic" style="display: none;">
                                    <div class="req-header">
                                        <x-iconsax-bul-clipboard-tick class="icons" width="18px" height="18px"/>
                                        <strong>{{ trans('update.diagnostic_test_guidelines') }}</strong>
                                    </div>
                                    <ul class="req-list">
                                        <li><x-iconsax-bul-chart class="icons" width="18px" height="18px" style="color: #1a3a5c;"/> Designed to assess student's current level</li>
                                        <li><x-iconsax-bul-chart class="icons" width="18px" height="18px" style="color: #1a3a5c;"/> Mix of question types and difficulties</li>
                                        <li><x-iconsax-bul-chart class="icons" width="18px" height="18px" style="color: #1a3a5c;"/> Results help identify strengths and weaknesses</li>
                                        <li><x-iconsax-bul-chart class="icons" width="18px" height="18px" style="color: #1a3a5c;"/> {{ trans('update.diagnostic_bank_hint') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-20">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <x-iconsax-lin-book class="icons label-icon" width="16px" height="16px"/>
                                    {{ trans('update.format') }}
                                    <span class="required-star">*</span>
                                </label>
                                <div class="select-wrapper-modern">
                                    <select name="format" class="form-control-modern" required>
                                        <option value="academic">{{ trans('update.academic') }}</option>
                                        <option value="general">{{ trans('update.general_training') }}</option>
                                    </select>
                                    <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Time Settings for Practice Test --}}
                        <div class="col-lg-12 mb-20 practice-time-settings" style="display: none;">
                            <div class="time-settings-card">
                                <div class="time-settings-header">
                                    <x-iconsax-lin-clock class="icons" width="18px" height="18px"/>
                                    <strong>{{ trans('update.practice_time_settings') }}</strong>
                                </div>
                                <div class="row mt-12">
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <x-iconsax-lin-timer class="icons label-icon" width="16px" height="16px"/>
                                                {{ trans('update.practice_mode') }}
                                            </label>
                                            <div class="select-wrapper-modern">
                                                <select name="practice_mode" class="form-control-modern">
                                                    <option value="untimed">{{ trans('update.untimed') }}</option>
                                                    <option value="timed">{{ trans('update.timed') }}</option>
                                                    <option value="exam_mode">{{ trans('update.exam_mode') }}</option>
                                                </select>
                                                <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 custom-duration-field" style="display: none;">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <x-iconsax-lin-clock class="icons label-icon" width="16px" height="16px"/>
                                                Duration (minutes)
                                            </label>
                                            <input type="number" name="total_duration" class="form-control-modern" 
                                                min="5" max="180" placeholder="e.g., 60">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <x-iconsax-lin-eye class="icons label-icon" width="16px" height="16px"/>
                                                {{ trans('update.show_answers') }}
                                            </label>
                                            <div class="select-wrapper-modern">
                                                <select name="show_answers_immediately" class="form-control-modern">
                                                    <option value="0">{{ trans('update.after_submission') }}</option>
                                                    <option value="1">{{ trans('update.immediately') }}</option>
                                                </select>
                                                <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-8 mb-0">
                            <div class="form-group-modern mb-0">
                                <label class="form-label-modern">
                                    <x-iconsax-lin-document-text class="icons label-icon" width="16px" height="16px"/>
                                    {{ trans('update.description') }}
                                    <span class="optional-badge">{{ trans('update.optional') }}</span>
                                </label>
                                <textarea 
                                    name="description" 
                                    class="form-control-modern" 
                                    rows="3" 
                                    placeholder="{{ trans('update.description_placeholder') }}"></textarea>
                                <small class="form-hint">{{ trans('update.description_hint') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Question Groups Selection Card --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-icon card-icon-blue">
                        <x-iconsax-bul-task-square class="icons text-white" width="28px" height="28px"/>
                    </div>
                    <div class="card-title-section">
                        <h3 class="card-title-modern">{{ trans('update.select_groups') }}</h3>
                        <p class="card-subtitle">{{ trans('update.select_groups_hint') }}</p>
                    </div>
                </div>
                
                <div class="card-body-modern">
                    {{-- Modern Filter Section --}}
                    <div class="filter-card-modern mb-24">
                        <div class="filter-header d-flex align-items-center justify-content-between mb-16">
                            <div class="d-flex align-items-center">
                                <div class="filter-main-icon mr-12">
                                    <x-iconsax-lin-filter width="20px" height="20px"/>
                                </div>
                                <div>
                                    <h6 class="font-15 font-weight-bold text-dark mb-0">{{ trans('update.smart_filters') }}</h6>
                                    <p class="text-gray-500 font-12 mb-0" id="filterResultCount">{{ trans('update.showing_all_groups') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-clear-link" id="clearFiltersBtn">
                                <x-iconsax-lin-close-circle class="mr-4" width="16px" height="16px"/>
                                {{ trans('update.clear_all_filters') }}
                            </button>
                        </div>

                        <div class="row gx-3">
                            {{-- Search Input --}}
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label class="filter-label">{{ trans('update.search_title') }}</label>
                                    <div class="filter-input-wrapper">
                                        <x-iconsax-lin-search-normal class="input-icon-inner" width="16px" height="16px"/>
                                        <input type="text" class="form-control-modern" id="filterSearch" placeholder="{{ trans('update.topic_placeholder') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Band Select --}}
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label class="filter-label">{{ trans('update.target_band') }}</label>
                                    <div class="filter-input-wrapper">
                                        <x-iconsax-lin-medal-star class="input-icon-inner" width="16px" height="16px"/>
                                        <select class="form-control-modern select-modern" id="filterBand">
                                            <option value="">{{ trans('update.all_bands') }}</option>
                                            @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                                                <option value="{{ $band }}">{{ trans('update.band_score', ['score' => $band]) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Task/Part Select --}}
                            <div class="col-md-5">
                                <div class="filter-group">
                                    <label class="filter-label">{{ trans('update.category_section') }}</label>
                                    <div class="filter-input-wrapper">
                                        <x-iconsax-lin-category class="input-icon-inner" width="16px" height="16px"/>
                                        <select class="form-control-modern select-modern" id="filterTaskPart">
                                            <option value="">{{ trans('update.all_tasks') }}</option>
                                            <optgroup label="{{ trans('update.writing_skills') }}">
                                                <option value="task1">Writing Task 1 (Academic/General)</option>
                                                <option value="task2">Writing Task 2 (Essay)</option>
                                            </optgroup>
                                            <optgroup label="{{ trans('update.speaking_skills') }}">
                                                <option value="part1">Speaking Part 1 (Interview)</option>
                                                <option value="part2">Speaking Part 2 (Cue Card)</option>
                                                <option value="part3">Speaking Part 3 (Discussion)</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Modern Skill Tabs --}}
                    <div class="skills-tabs-container">
                        <div class="skills-tabs">
                            <button type="button" class="skill-tab active" data-skill="listening">
                                <div class="skill-tab-icon">
                                    <x-iconsax-bul-headphone class="icons" width="20px" height="20px"/>
                                </div>
                                <div class="skill-tab-content">
                                    <div class="skill-tab-title">{{ trans('update.listening') }}</div>
                                    <div class="skill-tab-count" data-count="listening">0 {{ trans('update.selected') }}</div>
                                </div>
                            </button>
                            
                            <button type="button" class="skill-tab" data-skill="reading">
                                <div class="skill-tab-icon">
                                    <x-iconsax-bul-book class="icons" width="20px" height="20px"/>
                                </div>
                                <div class="skill-tab-content">
                                    <div class="skill-tab-title">{{ trans('update.reading') }}</div>
                                    <div class="skill-tab-count" data-count="reading">0 {{ trans('update.selected') }}</div>
                                </div>
                            </button>
                            
                            <button type="button" class="skill-tab" data-skill="writing">
                                <div class="skill-tab-icon">
                                    <x-iconsax-bul-edit-2 class="icons" width="20px" height="20px"/>
                                </div>
                                <div class="skill-tab-content">
                                    <div class="skill-tab-title">{{ trans('update.writing') }}</div>
                                    <div class="skill-tab-count" data-count="writing">0 {{ trans('update.selected') }}</div>
                                </div>
                            </button>
                            
                            <button type="button" class="skill-tab" data-skill="speaking">
                                <div class="skill-tab-icon">
                                    <x-iconsax-bul-microphone-2 class="icons" width="20px" height="20px"/>
                                </div>
                                <div class="skill-tab-content">
                                    <div class="skill-tab-title">{{ trans('update.speaking') }}</div>
                                    <div class="skill-tab-count" data-count="speaking">0 {{ trans('update.selected') }}</div>
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
                            @endphp
                            
                            @if($listeningMock->isEmpty() && $listeningPractice->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <x-iconsax-bul-box class="icons" width="36px" height="36px"/>
                                    </div>
                                    <h4 class="empty-title">{{ trans('update.no_groups_available', ['skill' => trans('update.listening')]) }}</h4>
                                    <p class="empty-text">{{ trans('update.no_groups_hint', ['skill' => trans('update.listening')]) }}</p>
                                    <p class="empty-hint">{!! trans('update.manage_bank_hint', ['skill' => trans('update.listening')]) !!}</p>
                                </div>
                            @else
                                {{-- Mock Bank Groups --}}
                                @foreach($listeningMock as $group)
                                    <label class="question-group-card" data-skill-type="listening" data-bank-type="mock" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="mock">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-primary">
                                                    <x-iconsax-lin-teacher class="icons mr-4" width="14px" height="14px"/>{{ trans('update.mock_bank') }}
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                                                    {{ $group->question_count }} {{ trans('update.questions_count') }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        {{ trans('update.band_score', ['score' => $group->target_band]) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                
                                {{-- Practice Bank Groups --}}
                                @foreach($listeningPractice as $group)
                                    <label class="question-group-card" data-skill-type="listening" data-bank-type="practice" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="practice">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-success">
                                                    <x-iconsax-lin-weight class="icons mr-4" width="14px" height="14px"/>{{ trans('update.practice_bank') }}
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                                                    {{ $group->question_count }} {{ trans('update.questions_count') }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        {{ trans('update.band_score', ['score' => $group->target_band]) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>

                        {{-- Reading Tab --}}
                        <div class="skill-content" data-skill-content="reading">
                            @php
                                $readingMock = $mockGroups['reading'] ?? collect();
                                $readingPractice = $practiceGroups['reading'] ?? collect();
                            @endphp
                            
                            @if($readingMock->isEmpty() && $readingPractice->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <x-iconsax-bul-box class="icons" width="36px" height="36px"/>
                                    </div>
                                    <h4 class="empty-title">{{ trans('update.no_groups_available', ['skill' => trans('update.reading')]) }}</h4>
                                    <p class="empty-text">{{ trans('update.no_groups_hint', ['skill' => trans('update.reading')]) }}</p>
                                    <p class="empty-hint">{!! trans('update.manage_bank_hint', ['skill' => trans('update.reading')]) !!}</p>
                                </div>
                            @else
                                {{-- Mock Bank Groups --}}
                                @foreach($readingMock as $group)
                                    <label class="question-group-card" data-skill-type="reading" data-bank-type="mock" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="mock">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-primary">
                                                    <x-iconsax-lin-teacher class="icons mr-4" width="14px" height="14px"/>Mock Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                                                    {{ $group->question_count }} questions
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                
                                {{-- Practice Bank Groups --}}
                                @foreach($readingPractice as $group)
                                    <label class="question-group-card" data-skill-type="reading" data-bank-type="practice" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="practice">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-success">
                                                    <x-iconsax-lin-weight class="icons mr-4" width="14px" height="14px"/>Practice Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                                                    {{ $group->question_count }} questions
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>

                        {{-- Writing Tab --}}
                        <div class="skill-content" data-skill-content="writing">
                            @php
                                $writingMock = $mockGroups['writing'] ?? collect();
                                $writingPractice = $practiceGroups['writing'] ?? collect();
                            @endphp
                            
                            @if($writingMock->isEmpty() && $writingPractice->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <x-iconsax-bul-box class="icons" width="36px" height="36px"/>
                                    </div>
                                    <h4 class="empty-title">{{ trans('update.no_groups_available', ['skill' => trans('update.writing')]) }}</h4>
                                    <p class="empty-text">{{ trans('update.no_groups_hint', ['skill' => trans('update.writing')]) }}</p>
                                    <p class="empty-hint">{!! trans('update.manage_bank_hint', ['skill' => trans('update.writing')]) !!}</p>
                                </div>
                            @else
                                {{-- Mock Bank Groups --}}
                                @foreach($writingMock as $group)
                                    @php
                                        $taskType = 'task2';
                                        if ($group->question_type && str_starts_with($group->question_type, 'task1_')) {
                                            $taskType = 'task1';
                                        } elseif (strpos($group->title, 'Task 1') !== false) {
                                            $taskType = 'task1';
                                        }
                                    @endphp
                                    <label class="question-group-card" data-skill-type="writing" data-bank-type="mock" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}" data-task-type="{{ $taskType }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="mock" data-task="{{ $taskType }}">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-primary">
                                                    <x-iconsax-lin-teacher class="icons mr-4" width="14px" height="14px"/>Mock Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-document-text class="icons" width="14px" height="14px"/>
                                                    {{ $taskType === 'task1' ? trans('update.task').' 1' : trans('update.task').' 2' }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                
                                {{-- Practice Bank Groups --}}
                                @foreach($writingPractice as $group)
                                    @php
                                        $taskType = 'task2';
                                        if ($group->question_type && str_starts_with($group->question_type, 'task1_')) {
                                            $taskType = 'task1';
                                        } elseif (strpos($group->title, 'Task 1') !== false) {
                                            $taskType = 'task1';
                                        }
                                    @endphp
                                    <label class="question-group-card" data-skill-type="writing" data-bank-type="practice" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}" data-task-type="{{ $taskType }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="practice" data-task="{{ $taskType }}">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-success">
                                                    <x-iconsax-lin-weight class="icons mr-4" width="14px" height="14px"/>Practice Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-document-text class="icons" width="14px" height="14px"/>
                                                    {{ $taskType === 'task1' ? 'Task 1' : 'Task 2' }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>

                        {{-- Speaking Tab --}}
                        <div class="skill-content" data-skill-content="speaking">
                            @php
                                $speakingMock = $mockGroups['speaking'] ?? collect();
                                $speakingPractice = $practiceGroups['speaking'] ?? collect();
                            @endphp
                            
                            @if($speakingMock->isEmpty() && $speakingPractice->isEmpty())
                                <div class="empty-state">
                                                    <div class="empty-icon">
                                                        <x-iconsax-bul-box class="icons" width="36px" height="36px"/>
                                                    </div>
                                                    <h4 class="empty-title">{{ trans('update.no_groups_available', ['skill' => trans('update.speaking')]) }}</h4>
                                                    <p class="empty-text">{{ trans('update.no_groups_hint', ['skill' => trans('update.speaking')]) }}</p>
                                                    <p class="empty-hint">{!! trans('update.manage_bank_hint', ['skill' => trans('update.speaking')]) !!}</p>
                                                </div>
                            @else
                                {{-- Mock Bank Groups --}}
                                @foreach($speakingMock as $group)
                                    @php
                                        $partType = 'part1';
                                        if ($group->question_type === 'part2') {
                                            $partType = 'part2';
                                        } elseif ($group->question_type === 'part3') {
                                            $partType = 'part3';
                                        } elseif (strpos($group->title, 'Part 2') !== false) {
                                            $partType = 'part2';
                                        } elseif (strpos($group->title, 'Part 3') !== false) {
                                            $partType = 'part3';
                                        }
                                    @endphp
                                    <label class="question-group-card" data-skill-type="speaking" data-bank-type="mock" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}" data-part-type="{{ $partType }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="mock" data-part="{{ $partType }}">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-primary">
                                                    <x-iconsax-lin-teacher class="icons mr-4" width="14px" height="14px"/>Mock Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-microphone-2 class="icons" width="14px" height="14px"/>
                                                    {{ $partType === 'part1' ? trans('update.part').' 1' : ($partType === 'part2' ? trans('update.part').' 2' : trans('update.part').' 3') }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                
                                {{-- Practice Bank Groups --}}
                                @foreach($speakingPractice as $group)
                                    @php
                                        $partType = 'part1';
                                        if ($group->question_type === 'part2') {
                                            $partType = 'part2';
                                        } elseif ($group->question_type === 'part3') {
                                            $partType = 'part3';
                                        } elseif (strpos($group->title, 'Part 2') !== false) {
                                            $partType = 'part2';
                                        } elseif (strpos($group->title, 'Part 3') !== false) {
                                            $partType = 'part3';
                                        }
                                    @endphp
                                    <label class="question-group-card" data-skill-type="speaking" data-bank-type="practice" data-target-band="{{ $group->target_band }}" data-group-id="{{ $group->id }}" data-usage-count="{{ $group->usage_count ?? 0 }}" data-part-type="{{ $partType }}">
                                        <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" class="group-checkbox" data-bank="practice" data-part="{{ $partType }}">
                                        <div class="group-card-content">
                                            <div class="group-card-header">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-check-icon">
                                                    <x-iconsax-bul-tick-circle class="icons" width="14px" height="14px"/>
                                                </div>
                                            </div>
                                            <div class="group-meta">
                                                <span class="group-badge badge-success">
                                                    <x-iconsax-lin-weight class="icons mr-4" width="14px" height="14px"/>Practice Bank
                                                </span>
                                                <span class="group-info">
                                                    <x-iconsax-lin-microphone-2 class="icons" width="14px" height="14px"/>
                                                    {{ $partType === 'part1' ? 'Part 1' : ($partType === 'part2' ? 'Part 2' : 'Part 3') }}
                                                </span>
                                                @if($group->target_band)
                                                    <span class="group-info">
                                                        <x-iconsax-lin-chart class="icons" width="14px" height="14px"/>
                                                        Band {{ $group->target_band }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Selection Summary --}}
                    <div class="selection-summary" id="selectionSummary">
                        <div class="summary-header">
                            <div class="summary-icon">
                                <x-iconsax-bul-clipboard-tick class="icons" width="20px" height="20px"/>
                            </div>
                            <div class="summary-title">
                                 <h4>{{ trans('update.selection_summary') }}</h4>
                                 <p id="summaryCount">{{ trans('update.selected_groups_count', ['count' => 0]) }}</p>
                             </div>
                        </div>
                        <div class="summary-content" id="summaryContent">
                             <p class="text-muted">{{ trans('update.no_groups_selected_yet') }}</p>
                         </div>
                        
                        {{-- Validation Status --}}
                        <div class="validation-status mt-16 pt-16 border-top" id="validationStatus" style="display: none;">
                            <div class="validation-header d-flex align-items-center mb-12">
                                <x-iconsax-bul-shield-tick class="icons text-success mr-8" width="18px" height="18px"/>
                                 <strong class="font-14">{{ trans('update.validation_status') }}</strong>
                            </div>
                            <div class="validation-items" id="validationItems">
                                {{-- Filled by JS --}}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Auto Generate Test Section --}}
                    <div class="auto-generate-section mt-24">
                        <div class="card-auto-generate" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px solid #0ea5e9; border-radius: 16px; padding: 24px;">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="d-flex align-items-center gap-12">
                                    <div class="auto-icon" style="width: 56px; height: 56px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                        <x-iconsax-bul-magic-star class="icons text-white" width="28px" height="28px"/>
                                    </div>
                                    <div>
                                         <h4 class="font-16 font-weight-bold text-dark mb-4">
                                             <x-iconsax-lin-cpu class="icons mr-8" width="18px" height="18px"/>
                                             {{ trans('update.auto_generate_test') }}
                                         </h4>
                                         <p class="text-gray-600 font-13 mb-0">
                                             {{ trans('update.auto_generate_hint') }}
                                         </p>
                                     </div>
                                </div>
                                <button type="button" class="btn btn-info" id="btnAutoGenerate" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none; padding: 12px 24px; border-radius: 10px;">
                                     <x-iconsax-lin-flash class="icons mr-8" width="18px" height="18px"/>
                                     {{ trans('update.auto_generate') }}
                                 </button>
                            </div>
                            
                            {{-- Auto Generate Options --}}
                            <div class="auto-generate-options mt-20 pt-20 border-top" id="autoGenerateOptions" style="display: none; border-color: #bae6fd !important;">
                                <div class="row">
                                    <div class="col-md-4 mb-16">
                                        <label class="form-label-modern">
                                             <x-iconsax-lin-chart class="icons label-icon" width="16px" height="16px"/>
                                             {{ trans('update.target_band_score') }}
                                         </label>
                                        <div class="select-wrapper-modern">
                                            <select id="autoTargetBand" class="form-control-modern">
                                                 <option value="">{{ trans('update.any_band_score') }}</option>
                                                 @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                                                     <option value="{{ $band }}">{{ trans('update.band_score', ['score' => $band]) }}</option>
                                                 @endforeach
                                             </select>
                                            <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-16">
                                        <label class="form-label-modern">
                                             <x-iconsax-lin-setting-3 class="icons label-icon" width="16px" height="16px"/>
                                             {{ trans('update.selection_strategy') }}
                                         </label>
                                        <div class="select-wrapper-modern">
                                            <select id="autoStrategy" class="form-control-modern">
                                                 <option value="hybrid" selected>🚀 {{ trans('update.hybrid') }} ({{ trans('update.recommended') }})</option>
                                                 <option value="optimal">{{ trans('update.optimal') }}</option>
                                                 <option value="diverse">{{ trans('update.diverse') }}</option>
                                                 <option value="random">{{ trans('update.random') }}</option>
                                             </select>
                                            <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-16">
                                        <label class="form-label-modern">
                                             <x-iconsax-lin-refresh-2 class="icons label-icon" width="16px" height="16px"/>
                                             {{ trans('update.avoid_recently_used') }}
                                         </label>
                                        <div class="select-wrapper-modern">
                                            <select id="autoAvoidRecent" class="form-control-modern">
                                                 <option value="0">{{ trans('update.no_restriction') }}</option>
                                                 <option value="7">{{ trans('update.last_n_days', ['days' => 7]) }}</option>
                                                 <option value="14">{{ trans('update.last_n_days', ['days' => 14]) }}</option>
                                                 <option value="30" selected>{{ trans('update.last_n_days', ['days' => 30]) }}</option>
                                             </select>
                                            <x-iconsax-lin-arrow-down class="icons select-arrow" width="16px" height="16px"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="algorithm-info p-16 rounded-12" style="background: rgba(14, 165, 233, 0.1);">
                                    <div class="d-flex align-items-start gap-12">
                                        <x-iconsax-lin-info-circle class="icons text-info" width="20px" height="20px"/>
                                        <div>
                                             <strong class="font-13 text-dark">{{ trans('update.algorithm_information') }}:</strong>
                                             <ul class="font-12 text-gray-600 mb-0 mt-8 pl-16">
                                                 <li><strong>{{ trans('update.optimal') }}:</strong> {{ trans('update.optimal_greedy_hint') }}</li>
                                                 <li><strong>{{ trans('update.diverse') }}:</strong> {{ trans('update.diverse_backtracking_hint') }}</li>
                                                 <li><strong>{{ trans('update.random') }}:</strong> {{ trans('update.random_weighted_hint') }}</li>
                                                 <li><strong>{{ trans('update.hybrid') }}:</strong> {{ trans('update.hybrid_hint') }}</li>
                                             </ul>
                                         </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-12 mt-16">
                                    <button type="button" class="btn btn-light" id="btnCancelAutoGenerate">
                                         <x-iconsax-lin-close-circle class="icons mr-4" width="16px" height="16px"/>
                                         {{ trans('admin/main.cancel') }}
                                     </button>
                                    <button type="button" class="btn btn-info" id="btnConfirmAutoGenerate" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none;">
                                         <x-iconsax-lin-tick-circle class="icons mr-8" width="16px" height="16px"/>
                                         {{ trans('update.generate_selection') }}
                                     </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="form-actions">
                <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn-modern btn-secondary btn-lg">
                    <x-iconsax-lin-close-circle class="icons" width="18px" height="18px"/>
                    <span>{{ trans('admin/main.cancel') }}</span>
                </a>
                <button type="submit" class="btn-modern btn-primary btn-lg" id="submitBtn">
                    <x-iconsax-lin-tick-circle class="icons" width="18px" height="18px"/>
                    <span>{{ trans('update.create_test') }}</span>
                </button>
            </div>
        </form>
    </section>



    <script>
    const ieltsTranslations = {
        showing_all: "{{ trans('update.showing_all_groups') }}",
        showing_n_of_m: "{{ trans('update.showing_n_of_m', ['n' => ':n', 'm' => ':m']) }}",
        groups_selected: "{{ trans('update.selected_groups_count', ['count' => ':count']) }}",
        selected: "{{ trans('update.selected') }}",
        no_groups_selected_yet: "{{ trans('update.no_groups_selected_yet') }}"
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Test Type Requirements Toggle
        const testTypeSelect = document.getElementById('testType');
        const practiceTimeSettings = document.querySelector('.practice-time-settings');
        const practiceModeSelect = document.querySelector('select[name="practice_mode"]');
        const customDurationField = document.querySelector('.custom-duration-field');
        const allGroupCards = document.querySelectorAll('.question-group-card');
        const checkboxes = document.querySelectorAll('.group-checkbox');
        
        // Auto Generate Elements
        const btnAutoGenerate = document.getElementById('btnAutoGenerate');
        const autoGenerateOptions = document.getElementById('autoGenerateOptions');
        const btnCancelAutoGenerate = document.getElementById('btnCancelAutoGenerate');
        const btnConfirmAutoGenerate = document.getElementById('btnConfirmAutoGenerate');
        
        // Filter Elements
        const filterSearch = document.getElementById('filterSearch');
        const filterBand = document.getElementById('filterBand');
        const filterTaskPart = document.getElementById('filterTaskPart');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const filterResultCount = document.getElementById('filterResultCount');
        
        // ==========================================
        // FILTER FUNCTIONALITY
        // ==========================================
        
        function applyFilters() {
            const searchTerm = filterSearch ? filterSearch.value.toLowerCase().trim() : '';
            const bandFilter = filterBand ? filterBand.value : '';
            const taskPartFilter = filterTaskPart ? filterTaskPart.value : '';
            
            let visibleCount = 0;
            let totalCount = 0;
            
            allGroupCards.forEach(card => {
                totalCount++;
                let shouldShow = true;
                
                // Search filter
                if (searchTerm) {
                    const title = card.querySelector('.group-title')?.textContent?.toLowerCase() || '';
                    const description = card.querySelector('.group-details')?.textContent?.toLowerCase() || '';
                    if (!title.includes(searchTerm) && !description.includes(searchTerm)) {
                        shouldShow = false;
                    }
                }
                
                // Band filter
                if (bandFilter && shouldShow) {
                    const cardBand = card.dataset.targetBand;
                    if (cardBand !== bandFilter) {
                        shouldShow = false;
                    }
                }
                
                // Task/Part filter
                if (taskPartFilter && shouldShow) {
                    const taskType = card.dataset.taskType || '';
                    const partType = card.dataset.partType || '';
                    
                    if (taskPartFilter.startsWith('task')) {
                        // Writing task filter
                        if (!taskType.toLowerCase().includes(taskPartFilter)) {
                            shouldShow = false;
                        }
                    } else if (taskPartFilter.startsWith('part')) {
                        // Speaking part filter
                        if (!partType.toLowerCase().includes(taskPartFilter)) {
                            shouldShow = false;
                        }
                    }
                }
                
                // Apply visibility
                if (shouldShow) {
                    card.classList.remove('filter-hidden');
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.classList.add('filter-hidden');
                    card.style.display = 'none';
                }
            });
            
            // Update count display
            if (filterResultCount) {
                if (!searchTerm && !bandFilter && !taskPartFilter) {
                    filterResultCount.textContent = ieltsTranslations.showing_all;
                } else {
                    filterResultCount.textContent = ieltsTranslations.showing_n_of_m
                        .replace(':n', visibleCount)
                        .replace(':m', totalCount);
                }
            }
        }
        
        // Filter event listeners
        if (filterSearch) {
            filterSearch.addEventListener('input', applyFilters);
        }
        
        if (filterBand) {
            filterBand.addEventListener('change', applyFilters);
        }
        
        if (filterTaskPart) {
            filterTaskPart.addEventListener('change', applyFilters);
        }
        
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                if (filterSearch) filterSearch.value = '';
                if (filterBand) filterBand.value = '';
                if (filterTaskPart) filterTaskPart.value = '';
                applyFilters();
            });
        }
        
       // auto generation algorithms
        
        class TestAutoGenerator {
            constructor(groups, testType, targetBand = null) {
                this.groups = groups;
                this.testType = testType;
                this.targetBand = targetBand;
                this.selectedGroups = [];
                this.usedQuestionTypes = new Set();
            }
            
            // Greedy Algorithm - Select best matching groups first
            greedySelect(skill, requirements = {}) {
                const candidates = this.groups.filter(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    if (!card || card.classList.contains('hidden-group') || card.classList.contains('filter-hidden')) return false;
                    if (card.dataset.skillType !== skill) return false;
                    if (this.targetBand && card.dataset.targetBand !== this.targetBand) return false;
                    return true;
                });
                
                // Score each candidate
                const scored = candidates.map(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    let score = 100;
                    
                    // Band match bonus
                    if (this.targetBand && card.dataset.targetBand === this.targetBand) {
                        score += 50;
                    }
                    
                    // Lower usage count = higher priority
                    const usageCount = parseInt(card.dataset.usageCount) || 0;
                    score -= usageCount * 5;
                    
                    // Question count bonus
                    score += (g.questionCount || 10);
                    
                    return { ...g, score, card };
                });
                
                // Sort by score (highest first)
                scored.sort((a, b) => b.score - a.score);
                
                return scored.slice(0, requirements.count || 1);
            }
            
            // Backtracking Algorithm - Ensure diversity and no duplicates
            backtrackingSelect(skill, requirements = {}) {
                const candidates = this.groups.filter(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    if (!card || card.classList.contains('hidden-group') || card.classList.contains('filter-hidden')) return false;
                    if (card.dataset.skillType !== skill) return false;
                    return true;
                });
                
                const selected = [];
                const usedTypes = new Set();
                
                const backtrack = (index) => {
                    if (selected.length >= (requirements.count || 1)) {
                        return true;
                    }
                    
                    if (index >= candidates.length) {
                        return selected.length > 0;
                    }
                    
                    const candidate = candidates[index];
                    const card = document.querySelector(`[data-group-id="${candidate.id}"]`);
                    const type = card.dataset.taskType || card.dataset.partType || 'default';
                    
                    // Check if this type is already used (for diversity)
                    if (!usedTypes.has(type) || requirements.allowDuplicateTypes) {
                        selected.push(candidate);
                        usedTypes.add(type);
                        
                        if (backtrack(index + 1)) {
                            return true;
                        }
                        
                        // Backtrack
                        selected.pop();
                        usedTypes.delete(type);
                    }
                    
                    return backtrack(index + 1);
                };
                
                backtrack(0);
                return selected;
            }
            
            // Weighted Random Selection - Ensure coverage
            weightedRandomSelect(skill, requirements = {}) {
                const candidates = this.groups.filter(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    if (!card || card.classList.contains('hidden-group') || card.classList.contains('filter-hidden')) return false;
                    if (card.dataset.skillType !== skill) return false;
                    return true;
                });
                
                if (candidates.length === 0) return [];
                
                // Calculate weights (inverse of usage count + band match bonus)
                const weighted = candidates.map(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    const usageCount = parseInt(card.dataset.usageCount) || 0;
                    let weight = Math.max(1, 100 - usageCount * 10);
                    
                    if (this.targetBand && card.dataset.targetBand === this.targetBand) {
                        weight *= 2;
                    }
                    
                    return { ...g, weight, card };
                });
                
                const totalWeight = weighted.reduce((sum, g) => sum + g.weight, 0);
                const selected = [];
                const usedIds = new Set();
                
                const count = requirements.count || 1;
                while (selected.length < count && selected.length < weighted.length) {
                    let random = Math.random() * totalWeight;
                    
                    for (const g of weighted) {
                        if (usedIds.has(g.id)) continue;
                        
                        random -= g.weight;
                        if (random <= 0) {
                            selected.push(g);
                            usedIds.add(g.id);
                            break;
                        }
                    }
                }
                
                return selected;
            }
            
            // Hybrid Algorithm - Combines ALL approaches for best results
            hybridSelect(skill, requirements = {}) {
                const count = requirements.count || 1;
                const candidates = this.groups.filter(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    if (!card || card.classList.contains('hidden-group') || card.classList.contains('filter-hidden')) return false;
                    if (card.dataset.skillType !== skill) return false;
                    if (this.targetBand && card.dataset.targetBand !== this.targetBand) return false;
                    return true;
                });
                
                if (candidates.length === 0) return [];
                
                // Step 1: Score using Greedy algorithm (quality-based)
                const greedyScored = candidates.map(g => {
                    const card = document.querySelector(`[data-group-id="${g.id}"]`);
                    let score = 0;
                    
                    // Band match bonus
                    if (this.targetBand && card.dataset.targetBand === this.targetBand) score += 50;
                    
                    // Lower usage count is better
                    const usageCount = parseInt(card.dataset.usageCount) || 0;
                    score += Math.max(0, 30 - usageCount * 3);
                    
                    // Has questions bonus
                    if (card.dataset.questionCount > 0) score += 20;
                    
                    return { group: g, greedyScore: score };
                });
                
                // Step 2: Apply Backtracking filter for diversity
                const usedTypes = new Set();
                const diverseFiltered = greedyScored.filter(item => {
                    const card = document.querySelector(`[data-group-id="${item.group.id}"]`);
                    const taskType = card?.dataset?.taskType || '';
                    const partType = card?.dataset?.partType || '';
                    const typeKey = taskType || partType || 'general';
                    
                    if (!requirements.allowDuplicateTypes && usedTypes.has(typeKey)) {
                        return false;
                    }
                    usedTypes.add(typeKey);
                    return true;
                });
                
                // Step 3: Apply Weighted Random for final selection variety
                const weightedList = diverseFiltered.map(item => {
                    const card = document.querySelector(`[data-group-id="${item.group.id}"]`);
                    const usageCount = parseInt(card?.dataset?.usageCount) || 0;
                    
                    // Combine greedy score with inverse usage weight
                    const randomFactor = Math.random() * 0.2; // 20% randomness
                    const finalScore = item.greedyScore * (1 + randomFactor) * (1 / (usageCount + 1));
                    
                    return { ...item, finalScore };
                });
                
                // Step 4: Sort by final hybrid score and select top items
                weightedList.sort((a, b) => b.finalScore - a.finalScore);
                
                return weightedList.slice(0, count).map(item => item.group);
            }
            
            // Generate complete test based on requirements
            generateTest(strategy = 'hybrid') {
                const result = {
                    listening: [],
                    reading: [],
                    writing: [],
                    speaking: []
                };
                
                const requirements = this.testType === 'mock' ? {
                    listening: { count: 1 },
                    reading: { count: 1 },
                    writing: { count: 2, types: ['task1', 'task2'] },
                    speaking: { count: 3, types: ['part1', 'part2', 'part3'] }
                } : {
                    listening: { count: 1 },
                    reading: { count: 1 },
                    writing: { count: 1 },
                    speaking: { count: 1 }
                };
                
                const selectFn = {
                    'optimal': (skill, req) => this.greedySelect(skill, req),
                    'diverse': (skill, req) => this.backtrackingSelect(skill, req),
                    'random': (skill, req) => this.weightedRandomSelect(skill, req),
                    'hybrid': (skill, req) => this.hybridSelect(skill, req)
                }[strategy] || this.hybridSelect.bind(this);
                
                // For mock test writing, ensure both Task 1 and Task 2
                if (this.testType === 'mock') {
                    const task1Groups = this.groups.filter(g => {
                        const card = document.querySelector(`[data-group-id="${g.id}"]`);
                        return card && !card.classList.contains('hidden-group') && !card.classList.contains('filter-hidden') && 
                            card.dataset.skillType === 'writing' && 
                            card.dataset.taskType === 'task1';
                    });
                    const task2Groups = this.groups.filter(g => {
                        const card = document.querySelector(`[data-group-id="${g.id}"]`);
                        return card && !card.classList.contains('hidden-group') && !card.classList.contains('filter-hidden') && 
                            card.dataset.skillType === 'writing' && 
                            card.dataset.taskType === 'task2';
                    });
                    
                    if (task1Groups.length > 0) result.writing.push(selectFn('writing', { count: 1 })[0] || task1Groups[0]);
                    if (task2Groups.length > 0) result.writing.push(task2Groups[0]);
                    
                    // Speaking Parts 1, 2, 3
                    ['part1', 'part2', 'part3'].forEach(part => {
                        const partGroups = this.groups.filter(g => {
                            const card = document.querySelector(`[data-group-id="${g.id}"]`);
                            return card && !card.classList.contains('hidden-group') && !card.classList.contains('filter-hidden') && 
                                card.dataset.skillType === 'speaking' && 
                                card.dataset.partType === part;
                        });
                        if (partGroups.length > 0) result.speaking.push(partGroups[0]);
                    });
                } else {
                    result.writing = selectFn('writing', requirements.writing);
                    result.speaking = selectFn('speaking', requirements.speaking);
                }
                
                result.listening = selectFn('listening', requirements.listening);
                result.reading = selectFn('reading', requirements.reading);
                
                return result;
            }
        }
        
        // AUTO GENERATE UI HANDLERS
        if (btnAutoGenerate) {
            btnAutoGenerate.addEventListener('click', function() {
                autoGenerateOptions.style.display = autoGenerateOptions.style.display === 'none' ? 'block' : 'none';
            });
        }
        
        if (btnCancelAutoGenerate) {
            btnCancelAutoGenerate.addEventListener('click', function() {
                autoGenerateOptions.style.display = 'none';
            });
        }
        
        if (btnConfirmAutoGenerate) {
            btnConfirmAutoGenerate.addEventListener('click', function() {
                const strategy = document.getElementById('autoStrategy').value;
                const targetBand = document.getElementById('autoTargetBand').value;
                const testType = testTypeSelect.value;
                
                // Collect all available groups from the DOM
                const groups = Array.from(allGroupCards).map(card => ({
                    id: card.dataset.groupId,
                    skill: card.dataset.skillType,
                    bankType: card.dataset.bankType,
                    targetBand: card.dataset.targetBand,
                    usageCount: parseInt(card.dataset.usageCount) || 0
                }));
                
                const generator = new TestAutoGenerator(groups, testType, targetBand || null);
                const selection = generator.generateTest(strategy);
                
                // Clear all current selections
                checkboxes.forEach(cb => cb.checked = false);
                
                // Apply new selections
                let selectedCount = 0;
                Object.values(selection).forEach(skillGroups => {
                    skillGroups.forEach(group => {
                        if (group && group.id) {
                            const checkbox = document.querySelector(`input[value="${group.id}"]`);
                            if (checkbox && !checkbox.closest('.hidden-group')) {
                                checkbox.checked = true;
                                selectedCount++;
                            }
                        }
                    });
                });
                
                autoGenerateOptions.style.display = 'none';
                updateProgress();
                
                const strategyNames = {
                    'hybrid': 'Hybrid (Greedy + Backtracking + Weighted Random)',
                    'optimal': 'Optimal (Greedy)',
                    'diverse': 'Diverse (Backtracking)',
                    'random': 'Random (Weighted)'
                };
                
                Swal.fire({
                    title: 'Auto Generation Complete',
                    html: `<p>Selected <strong>${selectedCount}</strong> question groups.</p>
                        <p class="font-13"><strong>Strategy:</strong> ${strategyNames[strategy] || strategy}</p>
                        <p class="text-muted font-12 mt-8">Review the selection below and adjust if needed.</p>`,
                    icon: 'success',
                    confirmButtonText: 'Great!',
                    confirmButtonColor: '#0ea5e9'
                });
            });
        }
        
    
        // Filter groups based on test type
        function filterGroupsByTestType(testType) {
            allGroupCards.forEach(card => {
                const bankType = card.dataset.bankType;
                const checkbox = card.querySelector('.group-checkbox');
                
                if (testType === 'mock') {
                    // Mock test: only show Mock Bank groups
                    if (bankType === 'mock') {
                        card.style.display = '';
                        card.classList.remove('hidden-group');
                    } else {
                        card.style.display = 'none';
                        card.classList.add('hidden-group');
                        if (checkbox) checkbox.checked = false;
                    }
                } else if (testType === 'practice') {
                    // Practice test: only show Practice Bank groups
                    if (bankType === 'practice') {
                        card.style.display = '';
                        card.classList.remove('hidden-group');
                    } else {
                        card.style.display = 'none';
                        card.classList.add('hidden-group');
                        if (checkbox) checkbox.checked = false;
                    }
                } else {
                    // Diagnostic test: show all groups
                    card.style.display = '';
                    card.classList.remove('hidden-group');
                }
            });
            
            // Update empty state visibility for each skill
            ['listening', 'reading', 'writing', 'speaking'].forEach(skill => {
                const container = document.querySelector(`[data-skill-content="${skill}"]`);
                if (!container) return;
                
                const visibleCards = container.querySelectorAll('.question-group-card:not(.hidden-group)');
                const emptyState = container.querySelector('.empty-state');
                
                // Check if we need to show a "no groups for this type" message
                if (visibleCards.length === 0 && !emptyState) {
                    // Create temporary empty message
                    let tempEmpty = container.querySelector('.temp-empty-state');
                    if (!tempEmpty) {
                        tempEmpty = document.createElement('div');
                        tempEmpty.className = 'empty-state temp-empty-state';
                        tempEmpty.innerHTML = `
                            <div class="empty-icon" style="width: 60px; height: 60px; margin: 0 auto 16px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center;"><span style="font-size: 24px; color: #9ca3af;">⚠️</span></div>
                            <h4 class="empty-title">No ${skill.charAt(0).toUpperCase() + skill.slice(1)} Groups for ${testType.charAt(0).toUpperCase() + testType.slice(1)} Test</h4>
                            <p class="empty-text">There are no approved ${skill} question groups in the ${testType === 'mock' ? 'Mock' : 'Practice'} Bank.</p>
                        `;
                        container.appendChild(tempEmpty);
                    }
                } else {
                    // Remove temporary empty message
                    const tempEmpty = container.querySelector('.temp-empty-state');
                    if (tempEmpty) tempEmpty.remove();
                }
            });
            
            updateProgress();
        }
        
        if (testTypeSelect) {
            testTypeSelect.addEventListener('change', function() {
                const type = this.value;
                document.querySelectorAll('.requirements-mock, .requirements-practice, .requirements-diagnostic').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelector('.requirements-' + type).style.display = 'block';
                
                // Show/hide practice time settings
                if (practiceTimeSettings) {
                    if (type === 'practice' || type === 'diagnostic') {
                        practiceTimeSettings.style.display = 'block';
                    } else {
                        practiceTimeSettings.style.display = 'none';
                    }
                }
                
                // Filter groups based on test type
                filterGroupsByTestType(type);
            });
            
            // Initialize filter on page load
            filterGroupsByTestType(testTypeSelect.value);
        }
        
        // Practice Mode Toggle
        if (practiceModeSelect) {
            practiceModeSelect.addEventListener('change', function() {
                if (customDurationField) {
                    if (this.value === 'timed') {
                        customDurationField.style.display = 'block';
                    } else {
                        customDurationField.style.display = 'none';
                    }
                }
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
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateProgress);
        });
        
    
        function validateSelection(testType, checkedGroups) {
            const validation = {
                valid: true,
                errors: [],
                warnings: [],
                skillStatus: {
                    listening: { selected: false, count: 0, bandScores: [] },
                    reading: { selected: false, count: 0, bandScores: [] },
                    writing: { selected: false, count: 0, bandScores: [], hasTask1: false, hasTask2: false },
                    speaking: { selected: false, count: 0, bandScores: [], hasPart1: false, hasPart2: false, hasPart3: false }
                }
            };
            
            // Collect information about selected groups
            checkedGroups.forEach(cb => {
                const card = cb.closest('[data-skill-type]');
                if (!card) return;
                
                const skill = card.dataset.skillType;
                const bandScore = card.dataset.targetBand;
                const taskType = card.dataset.taskType;
                const partType = card.dataset.partType;
                
                validation.skillStatus[skill].selected = true;
                validation.skillStatus[skill].count++;
                if (bandScore) {
                    validation.skillStatus[skill].bandScores.push(parseFloat(bandScore));
                }
                
                if (skill === 'writing') {
                    if (taskType === 'task1') validation.skillStatus.writing.hasTask1 = true;
                    if (taskType === 'task2') validation.skillStatus.writing.hasTask2 = true;
                }
                
                if (skill === 'speaking') {
                    if (partType === 'part1') validation.skillStatus.speaking.hasPart1 = true;
                    if (partType === 'part2') validation.skillStatus.speaking.hasPart2 = true;
                    if (partType === 'part3') validation.skillStatus.speaking.hasPart3 = true;
                }
            });
            
            // Validate based on test type
            if (testType === 'mock') {
                // Mock Test: All 4 skills required
                ['listening', 'reading', 'writing', 'speaking'].forEach(skill => {
                    if (!validation.skillStatus[skill].selected) {
                        validation.valid = false;
                        validation.errors.push(`Missing ${skill.charAt(0).toUpperCase() + skill.slice(1)} section`);
                    }
                });
                
                // Writing must have both Task 1 and Task 2
                if (!validation.skillStatus.writing.hasTask1) {
                    validation.valid = false;
                    validation.errors.push('Writing Task 1 is required for Mock Test');
                }
                if (!validation.skillStatus.writing.hasTask2) {
                    validation.valid = false;
                    validation.errors.push('Writing Task 2 is required for Mock Test');
                }
                
                // Speaking must have all 3 parts
                if (!validation.skillStatus.speaking.hasPart1) {
                    validation.valid = false;
                    validation.errors.push('Speaking Part 1 is required for Mock Test');
                }
                if (!validation.skillStatus.speaking.hasPart2) {
                    validation.valid = false;
                    validation.errors.push('Speaking Part 2 is required for Mock Test');
                }
                if (!validation.skillStatus.speaking.hasPart3) {
                    validation.valid = false;
                    validation.errors.push('Speaking Part 3 is required for Mock Test');
                }
                
                // Check band score consistency
                const allBandScores = Object.values(validation.skillStatus)
                    .flatMap(s => s.bandScores)
                    .filter(b => b);
                
                if (allBandScores.length > 0) {
                    const uniqueBands = [...new Set(allBandScores)];
                    if (uniqueBands.length > 1) {
                        validation.valid = false;
                        validation.errors.push(`Band score mismatch: Groups have different target bands (${uniqueBands.join(', ')}). Mock Test requires consistent band score.`);
                    }
                }
                
            } else if (testType === 'practice') {
                // Practice Test: At least one skill required
                const selectedSkills = Object.values(validation.skillStatus).filter(s => s.selected);
                if (selectedSkills.length === 0) {
                    validation.valid = false;
                    validation.errors.push('Select at least one question group');
                }
                
                // Check band score consistency - REQUIRED for Practice Test too
                const allBandScores = Object.values(validation.skillStatus)
                    .flatMap(s => s.bandScores)
                    .filter(b => b);
                
                if (allBandScores.length > 0) {
                    const uniqueBands = [...new Set(allBandScores)];
                    if (uniqueBands.length > 1) {
                        validation.valid = false;
                        validation.errors.push(`Band score mismatch: Groups have different target bands (${uniqueBands.join(', ')}). All selected groups must have the same target band.`);
                    }
                }
            }
            
            return validation;
        }
        
        function updateProgress() {
            const allChecked = Array.from(checkboxes).filter(cb => cb.checked && !cb.closest('.hidden-group'));
            const totalCount = allChecked.length;
            const testType = testTypeSelect.value;
            
            // Track skills with selections
            const skillsWithSelection = {};
            
            // Update skill counts
            ['listening', 'reading', 'writing', 'speaking'].forEach(skill => {
                const skillChecked = Array.from(checkboxes).filter(cb => {
                    return cb.checked && !cb.closest('.hidden-group') && cb.closest(`[data-skill-type="${skill}"]`);
                });
                const countEl = document.querySelector(`[data-count="${skill}"]`);
                if (countEl) {
                    countEl.textContent = `${skillChecked.length} ${ieltsTranslations.selected}`;
                }
                skillsWithSelection[skill] = skillChecked.length > 0;
            });
            
            // Update summary
            const summaryCount = document.getElementById('summaryCount');
            const summaryContent = document.getElementById('summaryContent');
            
            summaryCount.textContent = ieltsTranslations.groups_selected.replace(':count', totalCount);
            
            if (totalCount === 0) {
                summaryContent.innerHTML = `<p class="text-muted">${ieltsTranslations.no_groups_selected_yet}</p>`;
            } else {
                const groupNames = allChecked.map(cb => {
                    const card = cb.closest('.question-group-card');
                    const title = card.querySelector('.group-title').textContent;
                    const skill = card.dataset.skillType;
                    const skillColors = {
                        listening: '#3b82f6',
                        reading: '#10b981',
                        writing: '#f59e0b',
                        speaking: '#8b5cf6'
                    };
                    return `<span style="display: inline-block; margin: 4px; padding: 6px 12px; background: #fff; border-radius: 8px; font-size: 13px; border-left: 3px solid ${skillColors[skill] || '#6b7280'};">${title}</span>`;
                });
                summaryContent.innerHTML = groupNames.join('');
            }
            
            // Update validation status
            const validation = validateSelection(testType, allChecked);
            const validationStatus = document.getElementById('validationStatus');
            const validationItems = document.getElementById('validationItems');
            
            if (totalCount > 0) {
                validationStatus.style.display = 'block';
                
                let itemsHtml = '';
                
                // Show skill status
                ['listening', 'reading', 'writing', 'speaking'].forEach(skill => {
                    const status = validation.skillStatus[skill];
                    const isRequired = testType === 'mock';
                    const icon = status.selected ? '✓' : '✗';
                    const color = status.selected ? '#10b981' : (isRequired ? '#ef4444' : '#9ca3af');
                    
                    let details = '';
                    if (skill === 'writing' && testType === 'mock') {
                        details = ` (Task 1: ${status.hasTask1 ? '✓' : '✗'}, Task 2: ${status.hasTask2 ? '✓' : '✗'})`;
                    } else if (skill === 'speaking' && testType === 'mock') {
                        details = ` (P1: ${status.hasPart1 ? '✓' : '✗'}, P2: ${status.hasPart2 ? '✓' : '✗'}, P3: ${status.hasPart3 ? '✓' : '✗'})`;
                    }
                    
                    itemsHtml += `<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <span style="color: ${color}; font-weight: bold;">${icon}</span>
                        <span style="color: ${status.selected ? '#374151' : '#9ca3af'};">
                            ${skill.charAt(0).toUpperCase() + skill.slice(1)}${details}
                        </span>
                    </div>`;
                });
                
                // Show errors
                if (validation.errors.length > 0) {
                    itemsHtml += '<div class="mt-12 pt-12 border-top">';
                    validation.errors.forEach(err => {
                        itemsHtml += `<div style="color: #ef4444; font-size: 13px; margin-bottom: 4px;">⚠️ ${err}</div>`;
                    });
                    itemsHtml += '</div>';
                }
                
                // Show warnings
                if (validation.warnings.length > 0) {
                    itemsHtml += '<div class="mt-12 pt-12 border-top">';
                    validation.warnings.forEach(warn => {
                        itemsHtml += `<div style="color: #f59e0b; font-size: 13px; margin-bottom: 4px;">⚠️ ${warn}</div>`;
                    });
                    itemsHtml += '</div>';
                }
                
                validationItems.innerHTML = itemsHtml;
            } else {
                validationStatus.style.display = 'none';
            }
        }
        
        // Form validation with strict Mock Test requirements
        const form = document.getElementById('createTestForm');
        
        form.addEventListener('submit', function(e) {
            const testType = testTypeSelect.value;
            const checkedGroups = Array.from(checkboxes).filter(cb => cb.checked && !cb.closest('.hidden-group'));
            
            const validation = validateSelection(testType, checkedGroups);
            
            if (!validation.valid) {
                e.preventDefault();
                
                let errorHtml = '<div style="text-align: left;">';
                
                if (testType === 'mock') {
                    errorHtml += '<p><strong>Mock Test Requirements:</strong></p>';
                    errorHtml += '<ul style="margin-top: 10px;">';
                    
                    ['Listening', 'Reading', 'Writing', 'Speaking'].forEach(skill => {
                        const status = validation.skillStatus[skill.toLowerCase()];
                        const isValid = status.selected;
                        
                        let details = '';
                        if (skill === 'Writing') {
                            const task1Valid = status.hasTask1;
                            const task2Valid = status.hasTask2;
                            details = ` <small style="color: ${task1Valid && task2Valid ? '#10b981' : '#ef4444'}">(Task 1: ${task1Valid ? '✓' : '✗'}, Task 2: ${task2Valid ? '✓' : '✗'})</small>`;
                        } else if (skill === 'Speaking') {
                            const p1 = status.hasPart1;
                            const p2 = status.hasPart2;
                            const p3 = status.hasPart3;
                            details = ` <small style="color: ${p1 && p2 && p3 ? '#10b981' : '#ef4444'}">(Part 1: ${p1 ? '✓' : '✗'}, Part 2: ${p2 ? '✓' : '✗'}, Part 3: ${p3 ? '✓' : '✗'})</small>`;
                        }
                        
                        errorHtml += `<li style="color: ${isValid ? '#10b981' : '#ef4444'}">
                            ${isValid ? '✓' : '✗'} ${skill}${details}
                        </li>`;
                    });
                    
                    errorHtml += '</ul>';
                }
                
                if (validation.errors.length > 0) {
                    errorHtml += '<div style="margin-top: 15px; padding: 10px; background: #fee2e2; border-radius: 8px;">';
                    errorHtml += '<strong style="color: #991b1b;">Issues:</strong><ul style="margin: 5px 0 0 0; padding-left: 20px; color: #991b1b;">';
                    validation.errors.forEach(err => {
                        errorHtml += `<li style="font-size: 13px;">${err}</li>`;
                    });
                    errorHtml += '</ul></div>';
                }
                
                errorHtml += '</div>';
                
                Swal.fire({
                    title: testType === 'mock' ? 'Mock Test Requirements Not Met' : 'Validation Error',
                    html: errorHtml,
                    icon: 'error',
                    confirmButtonText: 'I Understand',
                    confirmButtonColor: '#1a3a5c'
                });
                
                return false;
            }
            
            // Show warnings if any
            if (validation.warnings.length > 0) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Warning',
                    html: validation.warnings.map(w => `<p>${w}</p>`).join(''),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Continue Anyway',
                    cancelButtonText: 'Go Back',
                    confirmButtonColor: '#1a3a5c'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                
                return false;
            }
            
            return true;
        });
        
        // Initialize
        updateProgress();
    });
    </script>
    @endsection
