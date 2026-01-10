@extends('admin.layouts.app')

@push('styles_top')
<style>
    .settings-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }
    .settings-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .settings-card-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }
    .settings-card-header p {
        margin: 4px 0 0;
        font-size: 14px;
        color: #6b7280;
    }
    .settings-card-body {
        padding: 24px;
    }
    
    .setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .setting-row:last-child {
        border-bottom: none;
    }
    .setting-info h6 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 600;
        color: #374151;
    }
    .setting-info p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }
    .setting-control {
        flex-shrink: 0;
        margin-left: 24px;
    }
    
    .number-input {
        width: 80px;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }
    .number-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Custom Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 28px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
        border-radius: 28px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #10b981;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .info-box {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 24px;
    }
    .info-box i {
        color: #0284c7;
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .info-box-content h6 {
        margin: 0 0 4px;
        font-size: 14px;
        font-weight: 600;
        color: #0369a1;
    }
    .info-box-content p {
        margin: 0;
        font-size: 13px;
        color: #0c4a6e;
    }
</style>
@endpush

@section('content')
<section class="section">
    {{-- Modern Header --}}
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24" style="border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-cog text-white" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h1 class="font-20 font-weight-bold text-dark mb-4">
                        IELTS Test Settings
                    </h1>
                    <p class="text-gray-500 font-13 mb-0">
                        <i class="fas fa-info-circle mr-4" style="font-size: 14px;"></i>
                        Configure global test limits and requirements
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-8">
                <!-- Info Box -->
                <div class="info-box" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; padding: 16px 20px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px;">
                    <i class="fas fa-lightbulb" style="color: #0284c7; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></i>
                    <div class="info-box-content">
                        <h6 style="margin: 0 0 4px; font-size: 14px; font-weight: 600; color: #0369a1;">Quick Tip</h6>
                        <p style="margin: 0; font-size: 13px; color: #0c4a6e;">Changes apply immediately to all students. Mock test limits help maintain test integrity.</p>
                    </div>
                </div>
                
                <form action="{{ route('admin.ielts_tests.settings.store') }}" method="POST">
                    @csrf
                    
                    <!-- Mock Test Settings -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h5>🎯 Mock Test Settings</h5>
                            <p>Configure mock test limits and requirements</p>
                        </div>
                        <div class="settings-card-body">
                            <!-- Daily Limit -->
                            <div class="setting-row">
                                <div class="setting-info">
                                    <h6>Daily Mock Test Limit</h6>
                                    <p>Maximum number of mock tests a student can take per day</p>
                                </div>
                                <div class="setting-control">
                                    <input type="number" 
                                           name="mock_tests_per_day" 
                                           class="number-input" 
                                           value="{{ $settings['mock_tests_per_day'] ?? 2 }}" 
                                           min="1" 
                                           max="10">
                                </div>
                            </div>
                            
                            <!-- 4 Skills Required -->
                            <div class="setting-row">
                                <div class="setting-info">
                                    <h6>Require All 4 Skills</h6>
                                    <p>Mock tests must include Listening, Reading, Writing, and Speaking</p>
                                </div>
                                <div class="setting-control">
                                    <label class="toggle-switch">
                                        <input type="checkbox" 
                                               name="mock_requires_4_skills" 
                                               value="1"
                                               {{ ($settings['mock_requires_4_skills'] ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Practice Test Settings -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h5>📚 Practice Test Settings</h5>
                            <p>Configure practice test behavior</p>
                        </div>
                        <div class="settings-card-body">
                            <!-- Unlimited Practice -->
                            <div class="setting-row">
                                <div class="setting-info">
                                    <h6>Unlimited Practice</h6>
                                    <p>Allow students to take unlimited practice tests per day</p>
                                </div>
                                <div class="setting-control">
                                    <label class="toggle-switch">
                                        <input type="checkbox" 
                                               name="practice_unlimited" 
                                               value="1"
                                               {{ ($settings['practice_unlimited'] ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-save mr-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Right Sidebar - Quick Stats -->
            <div class="col-lg-4">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h5>📊 Current Status</h5>
                    </div>
                    <div class="settings-card-body">
                        @php
                            $totalMockTests = \App\Models\IeltsTest::where('type', 'mock')->where('status', 'published')->count();
                            $totalPracticeTests = \App\Models\IeltsTest::where('type', 'practice')->where('status', 'published')->count();
                            $totalStudents = \App\User::where('role_name', 'user')->count();
                        @endphp
                        
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="text-gray-600">Published Mock Tests</span>
                            <strong class="text-primary">{{ $totalMockTests }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="text-gray-600">Published Practice Tests</span>
                            <strong class="text-success">{{ $totalPracticeTests }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-3">
                            <span class="text-gray-600">Total Students</span>
                            <strong>{{ $totalStudents }}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h5>💡 Tips</h5>
                    </div>
                    <div class="settings-card-body">
                        <ul class="list-unstyled mb-0" style="font-size: 13px; color: #6b7280;">
                            <li class="mb-3">
                                <i class="fas fa-lightbulb text-warning mr-2"></i>
                                Setting a daily limit of 2-3 mock tests encourages focused practice
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-lightbulb text-warning mr-2"></i>
                                Students should complete mock tests in one sitting for realistic experience
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-lightbulb text-warning mr-2"></i>
                                Practice tests help students prepare for specific skills
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
