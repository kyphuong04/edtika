@extends('design_1.panel.layouts.panel')

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Import {{ ucfirst($skill) }} Questions</h1>
            <p class="text-gray-500 font-14 mt-4">Batch upload with Excel + {{ $skill === 'listening' ? 'Audio' : 'Image' }} files via ZIP</p>
        </div>
        <a href="{{ route('panel.question_bank') }}" class="btn btn-outline-secondary">
            <x-iconsax-lin-arrow-left class="icons mr-8" width="16px" height="16px"/>Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Upload Form --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">Upload ZIP File</h4>

                <form method="POST" action="{{ route('panel.question_bank.import.preview') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <input type="hidden" name="skill" value="{{ $skill }}">

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Bank Type *</label>
                        <select name="bank_type" class="form-control" required>
                            <option value="">Select Skill</option>
                            <option value="listening">Listening</option>
                            <option value="reading">Reading</option>
                            <option value="writing">Writing</option>
                            <option value="speaking">Speaking</option>
                        </select>
                    </div>

                    <div class="mb-20">
                        <label class="font-12 text-gray-500 mb-8">ZIP File * (Excel + {{ $skill === 'listening' ? 'Audio' : 'Images' }})</label>
                        <div class="border-dashed border-gray-200 rounded-16 p-32 text-center bg-gray-100">
                            <input type="file" name="zip_file" id="zip_file" class="d-none" accept=".zip" required>
                            <label for="zip_file" class="cursor-pointer">
                                <div class="d-flex-center size-64 rounded-16 bg-primary-40 mx-auto mb-12">
                                    <x-iconsax-bul-archive class="icons text-primary" width="32px" height="32px"/>
                                </div>
                                <h5 class="font-14 text-dark mb-4">Click to upload ZIP file</h5>
                                <p class="font-12 text-gray-500 mb-0">Max size: 100MB</p>
                            </label>
                        </div>
                        <div id="file-name" class="mt-8 font-12 text-gray-500"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <x-iconsax-lin-eye class="icons mr-8" width="16px" height="16px"/>
                        Preview Questions
                    </button>
                </form>
            </div>

            {{-- Validation Results --}}
            @if(session('import_results'))
                <div class="bg-white p-20 rounded-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Import Results</h4>

                    @php $results = session('import_results'); @endphp

                    {{-- Success Summary --}}
                    @if($results['success_count'] > 0)
                        <div class="alert alert-success rounded-12 mb-16">
                            <div class="d-flex align-items-center">
                                <x-iconsax-bul-tick-circle class="icons text-success mr-12" width="20px" height="20px"/>
                                <div>
                                    <strong class="font-14">{{ $results['success_count'] }} questions imported successfully</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Errors --}}
                    @if(isset($results['errors']) && count($results['errors']) > 0)
                        <div class="alert alert-danger rounded-12">
                            <div class="d-flex align-items-start mb-12">
                                <x-iconsax-bul-danger class="icons text-danger mr-12" width="20px" height="20px"/>
                                <div>
                                    <strong class="font-14">{{ count($results['errors']) }} errors found</strong>
                                </div>
                            </div>
                            <div class="mt-12">
                                @foreach($results['errors'] as $error)
                                    <div class="font-12 text-dark mb-4">
                                        <strong>Row {{ $error['row'] }}:</strong> {{ $error['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- ZIP Structure --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">📦 ZIP File Structure</h4>
                <div class="bg-gray-100 p-12 rounded-12 font-12 font-mono">
<pre style="margin: 0;">{{ $skill }}-questions.zip
├── questions.xlsx
@if($skill === 'listening')
└── audio/
    ├── Part1.mp3
    └── Part2.mp3
@else
└── images/
    ├── diagram1.png
    └── chart1.jpg
@endif</pre>
                </div>
            </div>
            
            {{-- Templates --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">Download Template</h4>
                <p class="font-12 text-gray-500 mb-16">Template for {{ ucfirst($skill) }} questions</p>

                <a href="{{ route('panel.question_bank.import.template', $skill) }}" class="d-flex align-items-center p-12 rounded-12 bg-primary-40 bg-hover-primary-60">
                    <div class="d-flex-center size-32 rounded-8 bg-primary mr-12">
                        @if($skill === 'listening')
                            <x-iconsax-bul-headphone class="icons text-white" width="16px" height="16px"/>
                        @elseif($skill === 'reading')
                            <x-iconsax-bul-book class="icons text-white" width="16px" height="16px"/>
                        @elseif($skill === 'writing')
                            <x-iconsax-bul-edit class="icons text-white" width="16px" height="16px"/>
                        @else
                            <x-iconsax-bul-microphone class="icons text-white" width="16px" height="16px"/>
                        @endif
                    </div>
                    <span class="font-14 text-dark">{{ ucfirst($skill) }} Template</span>
                </a>
            </div>

            {{-- Instructions --}}
            <div class="bg-white p-20 rounded-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">Important Notes</h4>

                <div class="font-13 text-gray-500 mb-12">
                    <x-iconsax-bul-info-circle class="icons text-primary mr-8" width="16px" height="16px"/>
                    ZIP must contain `questions.xlsx`
                </div>

                <div class="font-13 text-gray-500 mb-12">
                    <x-iconsax-bul-info-circle class="icons text-primary mr-8" width="16px" height="16px"/>
                    @if($skill === 'listening')
                        Audio files go in `audio/` folder
                    @else
                        Image files go in `images/` folder
                    @endif
                </div>

                <div class="font-13 text-gray-500 mb-12">
                    <x-iconsax-bul-info-circle class="icons text-primary mr-8" width="16px" height="16px"/>
                    Reference files by name in Excel
                </div>

                <div class="font-13 text-gray-500">
                    <x-iconsax-bul-info-circle class="icons text-primary mr-8" width="16px" height="16px"/>
                    Max 100 questions per upload
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('zip_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        document.getElementById('file-name').textContent = 'Selected: ' + fileName;
    }
});
</script>
@endsection
