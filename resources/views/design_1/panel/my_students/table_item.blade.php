<tr>
    {{-- Name Column with Avatar --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $user->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $user->full_name }}">
            </div>
            <div class="ml-8">
                <span class="d-block font-weight-bold">{{ $user->full_name }}</span>
            </div>
        </div>
    </td>

    {{-- Email Column --}}
    <td class="text-left">
        @if($user->email)
            <span class="text-gray-700 dark:text-gray-200">{{ $user->email }}</span>
        @else
            <span class="text-gray-700 dark:text-gray-200">-</span>
        @endif
    </td>

    {{-- Phone Column --}}
    <td class="text-center">
        @if($user->mobile)
            <span>{{ $user->mobile }}</span>
        @else
            -
        @endif
    </td>

    {{-- Webinars/Live Classes Column --}}
    <td class="text-center">
        @php
            // Count webinars this student is enrolled in
            $webinarCount = \App\Models\Sale::where('buyer_id', $user->id)
                ->whereNull('refund_at')
                ->whereNotNull('webinar_id')
                ->distinct('webinar_id')
                ->count('webinar_id');
        @endphp
        <span>{{ $webinarCount }}</span>
    </td>

    {{-- Quizzes Column --}}
    <td class="text-center">
        @php
            // Count quiz results for this student
            $quizCount = \App\Models\QuizzesResult::where('user_id', $user->id)->count();
        @endphp
        <span>{{ $quizCount }}</span>
    </td>

    {{-- Certificates Column --}}
    <td class="text-center">
        @php
            // Count certificates earned by this student
            $certificateCount = \App\Models\Certificate::where('student_id', $user->id)->count();
        @endphp
        <span>{{ $certificateCount }}</span>
    </td>

    {{-- Date Column --}}
    <td class="text-center">
        <span>{{ dateTimeFormat($user->purchase_date ?? $user->created_at, 'j M Y') }}</span>
    </td>

    {{-- Actions Column --}}
    <td class="text-center">
        @if(!empty($user->id))
        <div class="actions-dropdown position-relative d-flex justify-content-center align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32 bg-white dark:bg-dark-blue-deep">
                <ul class="my-8">
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $user->getProfileUrl() }}" target="_blank" class="text-gray-700 dark:text-gray-100">
                            <x-iconsax-lin-user class="icons mr-2" width="18"/>
                            {{ trans('public.profile') }}
                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/students-tracking/{{ $user->id }}/quizResults" class="text-gray-700 dark:text-gray-100">
                            <x-iconsax-lin-document-text class="icons mr-2" width="18"/>
                            {{ trans('panel.quiz_results') }}
                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/students-tracking/{{ $user->id }}/assignments" class="text-gray-700 dark:text-gray-100">
                            <x-iconsax-lin-task-square class="icons mr-2" width="18"/>
                            {{ trans('panel.assignments') }}
                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/support/new" target="_blank" class="text-primary dark:text-primary-light">
                            <x-iconsax-lin-message class="icons mr-2" width="18"/>
                            Support Ticket
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </td>
</tr>
