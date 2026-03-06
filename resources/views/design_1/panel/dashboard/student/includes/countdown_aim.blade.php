@php
    $savedDate = $mockTestDate ?? null;
    $daysLeft  = null;
    if ($savedDate) {
        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($savedDate)->startOfDay(), false);
        if ($daysLeft < 0) $daysLeft = 0;
    }
    $savedAimBand = $aimBand ?? null;
@endphp

{{-- Countdown Card --}}
<div class="bg-white rounded-24 p-16">
    <div class="d-flex align-items-center justify-content-between mb-8">
        <h5 class="font-13 font-weight-bold text-dark mb-0">Count down</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill font-10 px-8 py-4"
                data-toggle="collapse" data-target="#mockTestDateForm">
            Set date
        </button>
    </div>

    <div class="collapse" id="mockTestDateForm">
        <form id="mockDateForm" class="d-flex align-items-center gap-8 mt-8">
            @csrf
            <input type="date" name="mock_test_date" id="mockTestDateInput"
                   class="form-control form-control-sm rounded-12 font-12"
                   style="flex:1;min-width:0;"
                   value="{{ $savedDate }}"
                   min="{{ now()->addDay()->format('Y-m-d') }}">
            <button type="submit" class="btn btn-primary btn-sm rounded-12 font-12 px-12 flex-shrink-0" style="white-space:nowrap;">Save</button>
        </form>
    </div>

    <div class="mt-8 text-center">
        @if($savedDate)
            @if((int)$daysLeft > 0)
                <p class="font-28 font-weight-bold text-primary mb-0">{{ $daysLeft }}</p>
                <p class="font-12 text-gray-500 mb-0">days left until mock test</p>
                <p class="font-11 text-gray-400 mb-0">{{ \Carbon\Carbon::parse($savedDate)->format('d.m.Y') }}</p>
            @else
                <p class="font-14 font-weight-bold text-success mb-0">🎉 Mock test day!</p>
                <p class="font-12 text-gray-500 mb-0">{{ \Carbon\Carbon::parse($savedDate)->format('d.m.Y') }}</p>
            @endif
        @else
            <p class="font-12 text-gray-400 mb-0">Set your mock test date to start countdown</p>
        @endif
    </div>
</div>

{{-- Aim Band Card --}}
<div class="bg-white rounded-24 p-16 mt-16">
    <div class="d-flex align-items-center justify-content-between mb-8">
        <h5 class="font-13 font-weight-bold text-dark mb-0">Aim band</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill font-10 px-8 py-4"
                data-toggle="collapse" data-target="#aimBandForm">
            Set goal
        </button>
    </div>

    <div class="collapse" id="aimBandForm">
        <form id="aimBandFormEl" class="mt-8">
            @csrf
            <div class="d-flex align-items-center gap-8">
                <input type="number" name="aim_band" id="aimBandInput"
                       class="form-control form-control-sm rounded-12 font-12"
                       style="flex:1;min-width:0;"
                       value="{{ $savedAimBand }}"
                       min="0" max="9" step="0.5"
                       placeholder="e.g. 7.0">
                <button type="submit" class="btn btn-primary btn-sm rounded-12 font-12 px-12 flex-shrink-0" style="white-space:nowrap;">Save</button>
            </div>
        </form>
    </div>

    <div class="mt-8 text-center">
        @if($savedAimBand)
            <p class="font-28 font-weight-bold text-warning mb-0">{{ $savedAimBand }}</p>
            <p class="font-12 text-gray-500 mb-0">target IELTS band</p>
        @else
            <p class="font-12 text-gray-400 mb-0">Set your target band score (0 – 9)</p>
        @endif
    </div>
</div>

@push('scripts_bottom')
<script>
(function() {
    "use strict";
    var settingsUrl = '/panel/dashboard/save-settings';

    function saveSettings(data, $form) {
        $.post(settingsUrl, $.extend(data, { _token: $('meta[name=csrf-token]').attr('content') }))
            .done(function() {
                toastr.success('Saved!');
                setTimeout(function() { location.reload(); }, 800);
            })
            .fail(function() { toastr.error('Failed to save. Please try again.'); });
    }

    $('#mockDateForm').on('submit', function(e) {
        e.preventDefault();
        saveSettings({ mock_test_date: $('#mockTestDateInput').val() });
    });

    $('#aimBandFormEl').on('submit', function(e) {
        e.preventDefault();
        var v = parseFloat($('#aimBandInput').val());
        if (isNaN(v) || v < 0 || v > 9) {
            toastr.warning('Band must be between 0 and 9');
            return;
        }
        saveSettings({ aim_band: v });
    });
})();
</script>
@endpush
