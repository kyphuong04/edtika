@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.workflow-container {
    max-width: 900px;
    margin: 0 auto;
}

.workflow-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
    margin-bottom: 24px;
}

.workflow-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
}

.workflow-header {
    padding: 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 16px;
}

.workflow-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.workflow-icon.legacy {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.workflow-icon.new {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.workflow-title {
    flex: 1;
}

.workflow-title h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.workflow-tag {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.workflow-tag.legacy {
    background: #fee2e2;
    color: #991b1b;
}

.workflow-tag.new {
    background: #dcfce7;
    color: #166534;
}

.workflow-body {
    padding: 24px;
}

.workflow-description {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 16px;
}

.workflow-steps {
    background: #f9fafb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.workflow-steps h4 {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 12px 0;
}

.workflow-step {
    display: flex;
    gap: 12px;
    margin-bottom: 8px;
    align-items: flex-start;
}

.workflow-step:last-child {
    margin-bottom: 0;
}

.step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    font-size: 12px;
    font-weight: 600;
    flex-shrink: 0;
}

.step-text {
    flex: 1;
    font-size: 14px;
    color: #4b5563;
    padding-top: 2px;
}

.workflow-pros {
    background: #dcfce7;
    border-left: 4px solid #10b981;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 13px;
    color: #166534;
    margin-bottom: 12px;
}

.workflow-cons {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 13px;
    color: #991b1b;
    margin-bottom: 12px;
}

.workflow-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

.workflow-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    border: none;
    font-size: 14px;
}

.workflow-action.btn-primary {
    background: #3b82f6;
    color: white;
}

.workflow-action.btn-primary:hover {
    background: #2563eb;
}

.workflow-action.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.workflow-action.btn-secondary:hover {
    background: #e5e7eb;
}

.comparison-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

.comparison-table th,
.comparison-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}

.comparison-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.comparison-table td {
    color: #6b7280;
}

.comparison-table .feature {
    font-weight: 500;
    color: #374151;
}

.icon-check {
    color: #10b981;
    font-weight: bold;
}

.icon-x {
    color: #ef4444;
    font-weight: bold;
}

.alert-new {
    background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
    border-left: 4px solid #10b981;
    padding: 16px;
    border-radius: 6px;
    margin-bottom: 24px;
}
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">
            <i class="fas fa-cog mr-10"></i>
            Choose Test Creation Method
        </h1>
        <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn btn-sm btn-gray">
            <i class="fas fa-arrow-left mr-5"></i>Back
        </a>
    </div>

    <div class="workflow-container">
        {{-- Alert: New Method Recommended --}}
        <div class="alert-new">
            <strong><i class="fas fa-lightbulb mr-8"></i>Recommended: New Inline Method</strong>
            <p class="mb-0 mt-8">Create complete tests with all questions at once! Faster approval process with no question group approvals needed.</p>
        </div>

        {{-- NEW METHOD: Inline Complete --}}
        <div class="workflow-card">
            <div class="workflow-header">
                <div class="workflow-icon new">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="workflow-title">
                    <h3>Create Complete Test (Inline)</h3>
                    <span class="workflow-tag new">⭐ NEW - RECOMMENDED</span>
                </div>
            </div>

            <div class="workflow-body">
                <p class="workflow-description">
                    Create a complete IELTS test with all sections and questions in one go. No need to use Question Bank. Perfect for quick test creation with immediate approval workflow.
                </p>

                <div class="workflow-pros">
                    <strong>✓ Benefits:</strong>
                    <ul class="mb-0 mt-8">
                        <li>Create all questions directly (Listening 40Q, Reading 40Q, Writing 2Q, Speaking 3Q)</li>
                        <li>Submit complete test for review - CEO/Manager approve once</li>
                        <li>No tedious question group approvals</li>
                        <li>Faster test publication</li>
                        <li>Better organization - test is single entity</li>
                    </ul>
                </div>

                <div class="workflow-steps">
                    <h4><i class="fas fa-list-ol mr-8"></i>How It Works</h4>
                    <div class="workflow-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Enter test title, format, and basic info</span>
                    </div>
                    <div class="workflow-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Add questions for each skill (Listening, Reading, Writing, Speaking)</span>
                    </div>
                    <div class="workflow-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Submit complete test for approval</span>
                    </div>
                    <div class="workflow-step">
                        <span class="step-number">4</span>
                        <span class="step-text">Manager/CEO approves entire test once → Published & Live</span>
                    </div>
                </div>

                <div class="workflow-footer">
                    <small class="text-muted">⏱️ ~10-15 minutes per test</small>
                    <a href="{{ route('panel.my_ielts_tests.create_inline') }}" class="workflow-action btn-primary">
                        <i class="fas fa-arrow-right"></i>Create Inline Test
                    </a>
                </div>
            </div>
        </div>

        {{-- LEGACY METHOD: From Question Bank --}}
        <div class="workflow-card">
            <div class="workflow-header">
                <div class="workflow-icon legacy">
                    <i class="fas fa-database"></i>
                </div>
                <div class="workflow-title">
                    <h3>Create from Question Bank</h3>
                    <span class="workflow-tag legacy">LEGACY</span>
                </div>
            </div>

            <div class="workflow-body">
                <p class="workflow-description">
                    Create tests using pre-existing question groups from the Question Bank. Useful for reusing approved questions across multiple tests.
                </p>

                <div class="workflow-cons">
                    <strong>ℹ️ Note:</strong>
                    <ul class="mb-0 mt-8">
                        <li>Requires pre-created question groups in Question Bank</li>
                        <li>Each question group needs separate approval</li>
                        <li>Longer approval process (multiple steps)</li>
                        <li>Better for question reuse across many tests</li>
                    </ul>
                </div>

                <div class="workflow-steps">
                    <h4><i class="fas fa-list-ol mr-8"></i>How It Works</h4>
                    <div class="workflow-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Ensure question groups exist in Question Bank (already approved)</span>
                    </div>
                    <div class="workflow-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Create test and select question groups for each section</span>
                    </div>
                    <div class="workflow-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Submit test for approval</span>
                    </div>
                </div>

                <div class="workflow-footer">
                    <small class="text-muted">⏱️ ~5-10 minutes per test (if questions exist)</small>
                    <a href="{{ route('panel.my_ielts_tests.create') }}" class="workflow-action btn-secondary">
                        <i class="fas fa-arrow-right"></i>Create from Bank
                    </a>
                </div>
            </div>
        </div>

        {{-- Quick Comparison --}}
        <div style="background: white; border-radius: 12px; padding: 24px; margin-top: 32px; border: 1px solid #e5e7eb;">
            <h3 class="font-16 font-weight-bold mb-15">Quick Comparison</h3>

            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th style="text-align: center;">Inline Complete</th>
                        <th style="text-align: center;">From Question Bank</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="feature">Create questions directly</td>
                        <td style="text-align: center;"><span class="icon-check">✓</span></td>
                        <td style="text-align: center;"><span class="icon-x">✗</span></td>
                    </tr>
                    <tr>
                        <td class="feature">One-time approval</td>
                        <td style="text-align: center;"><span class="icon-check">✓</span></td>
                        <td style="text-align: center;"><span class="icon-x">✗ (Multiple groups)</span></td>
                    </tr>
                    <tr>
                        <td class="feature">Reuse questions</td>
                        <td style="text-align: center;"><span class="icon-x">✗</span></td>
                        <td style="text-align: center;"><span class="icon-check">✓</span></td>
                    </tr>
                    <tr>
                        <td class="feature">Quick setup</td>
                        <td style="text-align: center;"><span class="icon-check">✓ Fast</span></td>
                        <td style="text-align: center;"><span class="icon-x">Requires bank</span></td>
                    </tr>
                    <tr>
                        <td class="feature">Approval speed</td>
                        <td style="text-align: center;"><span class="icon-check">✓ 1 approval</span></td>
                        <td style="text-align: center;"><span class="icon-x">Multiple approvals</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Help Section --}}
        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 6px; margin-top: 24px;">
            <strong><i class="fas fa-question-circle mr-8"></i>Which method should I use?</strong>
            <p class="mb-0 mt-8 text-muted" style="color: #475569;">
                <strong>Choose Inline Complete if:</strong> You want to create tests quickly, don't need question reuse, and prefer fast approval process.<br><br>
                <strong>Choose From Question Bank if:</strong> You have pre-made question groups, plan to reuse questions across multiple tests, or need fine-grained control over question approvals.
            </p>
        </div>
    </div>
</section>
@endsection
