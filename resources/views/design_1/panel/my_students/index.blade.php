@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats - Always show --}}
    @include('design_1.panel.my_students.top_stats')

    {{-- Filters - Always show --}}
    <div class="bg-white pt-16 pb-16 rounded-24 mt-20">
        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
            <div class="">
                <h3 class="font-16">{{ trans('update.my_students') }}</h3>
            </div>
        </div>

        @include('design_1.panel.my_students.filters')
    </div>

    {{-- Table or No Result --}}
    @if(!empty($students) and !$students->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="table-responsive-lg">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('auth.name') }}</th>
                        <th class="text-left">{{ trans('auth.email') }}</th>
                        <th class="text-center">{{ trans('public.phone') }}</th>
                        <th class="text-center">{{ trans('webinars.webinars') }}</th>
                        <th class="text-center">{{ trans('quiz.quizzes') }}</th>
                        <th class="text-center">{{ trans('panel.certificates') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-center">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        @include('design_1.panel.my_students.table_item', ['user' => $student])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="card-footer text-center">
                    {{ $students->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="mt-20">
            @include('design_1.panel.includes.no-result',[
                'file_name' => 'students.svg',
                'title' => trans('panel.students_no_result'),
                'hint' => trans('panel.no_students_enrolled_hint'),
            ])
        </div>
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
