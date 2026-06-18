@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.test-type-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.test-type-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.test-type-card:hover {
    border-color: #3b82f6;
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(59, 130, 246, 0.15);
}

.test-type-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s;
}

.test-type-card:hover::before {
    opacity: 1;
}

.test-icon {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
}

.test-type-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.test-type-desc {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 24px;
    line-height: 1.6;
}

.test-features {
    list-style: none;
    padding: 0;
    margin: 24px 0;
    text-align: left;
}

.test-features li {
    padding: 8px 0;
    font-size: 14px;
    color: #374151;
    display: flex;
    align-items: center;
}

.test-features li::before {
    content: '✓';
    color: #10b981;
    font-weight: bold;
    margin-right: 12px;
    font-size: 16px;
}

.select-btn {
    margin-top: auto;
    padding: 12px 32px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    background: #3b82f6;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.select-btn:hover {
    background: #2563eb;
    transform: scale(1.02);
}

.select-btn i {
    font-size: 18px;
}

@media (max-width: 768px) {
    .test-type-card {
        margin-bottom: 20px;
    }
}
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="test-type-container">
        {{-- Header --}}
        <div class="text-center mb-50">
            <h1 class="font-30 font-weight-bold text-dark-blue">Create New IELTS Test</h1>
            <p class="font-14 text-gray mt-10">Choose the type that fits your needs</p>
        </div>

        {{-- Test Type Cards --}}
        <div class="row">
            {{-- Mock Test Card --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="test-type-card">
                    <span class="test-icon">📋</span>
                    <h3 class="test-type-title">Mock Test</h3>
                    <p class="test-type-desc">Full 4-skill IELTS simulation with official test format</p>
                    
                    <ul class="test-features">
                        <li>All 4 skills required</li>
                        <li>Fixed duration: 165 minutes</li>
                        <li>Exam mode testing</li>
                        <li>Official IELTS structure</li>
                        <li>Listening: 30 min (40Q)</li>
                        <li>Reading: 60 min (40Q)</li>
                        <li>Writing: 60 min (2 Tasks)</li>
                        <li>Speaking: 11-14 min (3 Parts)</li>
                    </ul>

                    <a href="{{ route('panel.my_ielts_tests.create.mock') }}" class="select-btn">
                        Select Mock Test
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Practice Test Card --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="test-type-card">
                    <span class="test-icon">💪</span>
                    <h3 class="test-type-title">Practice Test</h3>
                    <p class="test-type-desc">Flexible practice with customizable skills and duration</p>
                    
                    <ul class="test-features">
                        <li>1+ skills (your choice)</li>
                        <li>Custom duration</li>
                        <li>Flexible question count</li>
                        <li>Custom point values</li>
                        <li>Timed or untimed</li>
                        <li>Focus on weak areas</li>
                        <li>Immediate feedback</li>
                        <li>Retake allowed</li>
                    </ul>

                    <a href="{{ route('panel.my_ielts_tests.create.practice') }}" class="select-btn">
                        Select Practice Test
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="text-center mt-40">
            <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn btn-sm btn-gray">
                <i class="fas fa-arrow-left mr-5"></i>
                Back to My Tests
            </a>
        </div>
    </div>
</section>
@endsection
