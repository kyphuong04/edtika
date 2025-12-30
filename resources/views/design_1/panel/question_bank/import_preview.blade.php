@extends('design_1.panel.layouts.panel')

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Preview {{ ucfirst($skill) }} Questions</h1>
            <p class="text-gray-500 font-14 mt-4">Review data before importing (showing {{ count($previewData) }} of {{ $totalRows }} questions)</p>
        </div>
    </div>

    {{-- Preview Table --}}
    <div class="bg-white p-20 rounded-24 mb-24">
        <div class="d-flex align-items-center justify-content-between mb-16">
            <h4 class="font-14 font-weight-bold text-dark mb-0">
                Preview Data - {{ $totalRows }} Questions Found
            </h4>
            <div class="badge badge-primary">{{ ucfirst($bankType) }} Bank</div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach($headers as $header)
                            <th class="font-12 text-gray-700">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewData as $row)
                        <tr>
                            @foreach($headers as $header)
                                <td class="font-12">
                                    @php
                                        $value = $row[$header] ?? '';
                                        $displayValue = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                                    @endphp
                                    {{ $displayValue }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($totalRows > 10)
            <div class="alert alert-info mt-16 rounded-12">
                <x-iconsax-bul-info-circle class="icons text-info mr-8" width="16px" height="16px"/>
                Showing first 10 questions. {{ $totalRows - 10 }} more will be imported.
            </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="bg-white p-20 rounded-24">
        <div class="row">
            <div class="col-md-6">
                <form method="POST" action="{{ route('panel.question_bank.import.cancel') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <x-iconsax-bul-close-circle class="icons mr-8" width="16px" height="16px"/>
                        Cancel Import
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('panel.question_bank.import.confirm') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>
                        Confirm & Import {{ $totalRows }} Questions
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="font-12 text-gray-500 mb-0">
                <x-iconsax-bul-info-circle class="icons text-warning mr-4" width="14px" height="14px"/>
                All {{ $totalRows }} questions will be imported to {{ ucfirst($bankType) }} Bank ({{ ucfirst($skill) }} skill)
            </p>
        </div>
    </div>
</section>
@endsection
