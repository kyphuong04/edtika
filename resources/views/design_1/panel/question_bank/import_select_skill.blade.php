@extends('design_1.panel.layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-file-import"></i>
                        Import Questions from Excel
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>How it works:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Choose which skill you want to import questions for</li>
                                    <li>Download the Excel template for that skill</li>
                                    <li>Fill in your questions following the template format</li>
                                    <li>Create a ZIP file containing the Excel file and any media files (audio,images)</li>
                                    <li>Upload the ZIP file and preview before importing</li>
                                </ul>
                            </div>
                            
                            <h4 class="mb-4 mt-4">Select Skill to Import:</h4>
                            
                            <div class="row">
                                <!-- Listening -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-headphones fa-4x text-primary"></i>
                                            </div>
                                            <h5 class="card-title font-weight-bold">Listening</h5>
                                            <p class="card-text text-muted">Import listening questions with audio files</p>
                                            <a href="{{ route('panel.question_bank.import', ['skill' => 'listening']) }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-upload"></i> Import Listening
                                            </a>
                                            <a href="{{ route('panel.question_bank.import.template', ['skill' => 'listening']) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="fas fa-download"></i> Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reading -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-success h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-book-open fa-4x text-success"></i>
                                            </div>
                                            <h5 class="card-title font-weight-bold">Reading</h5>
                                            <p class="card-text text-muted">Import reading questions with passages</p>
                                            <a href="{{ route('panel.question_bank.import', ['skill' => 'reading']) }}" class="btn btn-success btn-lg">
                                                <i class="fas fa-upload"></i> Import Reading
                                            </a>
                                            <a href="{{ route('panel.question_bank.import.template', ['skill' => 'reading']) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="fas fa-download"></i> Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Writing -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-warning h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-pen-fancy fa-4x text-warning"></i>
                                            </div>
                                            <h5 class="card-title font-weight-bold">Writing</h5>
                                            <p class="card-text text-muted">Import writing tasks with prompts/images</p>
                                            <a href="{{ route('panel.question_bank.import', ['skill' => 'writing']) }}" class="btn btn-warning btn-lg">
                                                <i class="fas fa-upload"></i> Import Writing
                                            </a>
                                            <a href="{{ route('panel.question_bank.import.template', ['skill' => 'writing']) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="fas fa-download"></i> Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Speaking -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-danger h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-microphone-alt fa-4x text-danger"></i>
                                            </div>
                                            <h5 class="card-title font-weight-bold">Speaking</h5>
                                            <p class="card-text text-muted">Import speaking questions with cue cards</p>
                                            <a href="{{ route('panel.question_bank.import', ['skill' => 'speaking']) }}" class="btn btn-danger btn-lg">
                                                <i class="fas fa-upload"></i> Import Speaking
                                            </a>
                                            <a href="{{ route('panel.question_bank.import.template', ['skill' => 'speaking']) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="fas fa-download"></i> Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="text-center">
                                <a href="{{ route('panel.question_bank') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Question Bank
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
