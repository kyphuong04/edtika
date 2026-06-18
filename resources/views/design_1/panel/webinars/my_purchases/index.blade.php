@extends('design_1.panel.layouts.panel')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <style>
        /* ── Two-column layout ──────────────────────────────────── */
        .mcp-layout { display: flex; flex-direction: row; direction: ltr; gap: 24px; align-items: flex-start; }
        .mcp-layout__aside { order: 1; width: 300px; flex-shrink: 0; display: flex; flex-direction: column; gap: 14px; overflow: visible; }
        .mcp-layout__main { order: 0; flex: 1; min-width: 0; direction: ltr; }
        @media (max-width: 1200px) { .mcp-layout__aside { width: 260px; } }
        @media (max-width: 991px) { .mcp-layout { flex-direction: column; } .mcp-layout__aside { width: 100%; order: 1; } }

        /* ── Course card image – compact size when aside is present ── */
        .mcp-layout__main .panel-course-card-1__image { width: 160px; min-width: 160px; height: 160px; }
        .mcp-layout__main .panel-course-card-1__content { width: calc(100% - 176px); }
        @media (max-width: 991px) {
            .mcp-layout__main .panel-course-card-1__image { width: 100%; min-width: unset; height: 180px; }
            .mcp-layout__main .panel-course-card-1__content { width: 100%; }
        }

        /* ── Dashboard-style welcome bar ───────────────────────── */
        .cd-welcome-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .cd-welcome-bar h1 {
            flex-grow: 1;
            min-width: 0;
            margin: 0;
        }
        .cd-welcome-bar__progress {
            margin-top: 8px;
        }
        .cd-welcome-bar__track {
            height: 6px;
            background: #f1f5f9;
        }
        .dark-mode .cd-welcome-bar__track {
            background: #334155;
        }
        .cd-welcome-bar__fill {
            height: 100%;
            background: var(--primary);
            transition: width 0.6s ease;
        }
        .cd-welcome-bar__bell {
            flex-shrink: 0;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .cd-welcome-bar__bell:hover {
            background-color: #f3f4f6 !important;
            text-decoration: none;
        }
        .dark-mode .cd-welcome-bar__bell:hover {
            background-color: #334155 !important;
        }

        .cd-bell-btn {
            border: none;
        }

        /* ── Aside card shell ───────────────────────────────────── */
        .cd-aside-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px 18px; }

        /* ── User card ──────────────────────────────────────────── */
        .cd-user-card { display: flex; align-items: center; gap: 14px; }
        .cd-user-card__avatar { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #eee; flex-shrink: 0; }
        .cd-user-card__name { font-size: 15px; font-weight: 700; }
        .cd-user-card__band { font-size: 12px; color: #666; margin-top: 3px; }
        .cd-user-nav { overflow: visible !important; cursor: pointer; flex-wrap: nowrap; padding: 14px 18px; z-index: 20; }
        .cd-user-nav:hover { z-index: 40; }
        .cd-user-nav .cd-user-nav__dropdown {
            display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0;
            min-width: 240px; z-index: 9999; border-radius: 12px; background: #fff;
            box-shadow: 0 8px 28px rgba(0,0,0,.13);
            max-height: 80vh; overflow-y: auto;
        }
        .cd-user-nav:hover .cd-user-nav__dropdown { display: block; }

        /* ── Word of day ────────────────────────────────────────── */
        .cd-word-card { text-align: center; padding: 4px 0; }
        .cd-word-card__word { font-size: 16px; font-weight: 700; color: #1a1a1a; }
        .cd-word-card__pronunciation { font-size: 13px; color: #888; margin-top: 6px; }
        .cd-word-card__translation { font-size: 13px; color: #555; margin-top: 10px; }
        .cd-word-card__example { font-size: 13px; color: #333; margin-top: 10px; line-height: 1.6; }

        /* ── Mentor chat ────────────────────────────────────────── */
        .cd-chat-card { padding: 0 !important; overflow: hidden; position: relative; z-index: 1; }
        .cd-chat__header { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .cd-chat__header-title { font-size: 13px; font-weight: 700; color: #333; display:flex; align-items:center; gap:5px; }
        .cd-chat__status { font-size: 11px; }
        .cd-chat__body { padding: 12px 14px; max-height: 260px; min-height: 80px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        .cd-chat__empty-state { text-align: center; color: #aaa; padding: 24px 10px; font-size: 12px; line-height: 1.6; }
        .cd-bubble-row { display: flex; align-items: flex-end; gap: 7px; }
        .cd-bubble-row--me { flex-direction: row-reverse; }
        .cd-bubble-row__avatar { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .cd-bubble { max-width: 78%; padding: 7px 11px; border-radius: 14px; font-size: 12px; line-height: 1.5; word-break: break-word; }
        .cd-bubble--other { background: #f0f0f0; color: #333; border-bottom-left-radius: 3px; }
        .cd-bubble--me { background: var(--primary); color: #fff; border-bottom-right-radius: 3px; }
        .cd-bubble__time { font-size: 10px; opacity: .55; display: block; margin-top: 3px; }
        .cd-chat__new-subject { padding: 8px 14px 0; }
        .cd-chat__new-subject label { font-size: 11px; color: #999; display: block; margin-bottom: 3px; }
        .cd-chat__new-subject input { width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 6px 10px; font-size: 12px; outline: none; transition: border-color .2s; }
        .cd-chat__new-subject input:focus { border-color: var(--primary); }
        .cd-chat__footer { padding: 8px 12px 10px; border-top: 1px solid #f0f0f0; display: flex; align-items: flex-end; gap: 8px; }
        .cd-chat__input { flex: 1; border: 1px solid #ddd; border-radius: 18px; padding: 7px 13px; font-size: 12px; resize: none; outline: none; max-height: 78px; overflow-y: auto; line-height: 1.45; transition: border-color .2s; }
        .cd-chat__input:focus { border-color: var(--primary); }
        .cd-chat__send-btn { width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--primary); display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: opacity .2s; }
        .cd-chat__send-btn:hover { opacity: .8; }
        .cd-chat__send-btn svg { fill: #fff; }
        /* Match student dashboard primary color for welcome bar actions */
        .cd-welcome-bar { --primary: #511D99; }
        .cd-welcome-bar .btn-primary,
        .cd-welcome-bar .btn-primary:hover,
        .cd-welcome-bar .btn-primary:focus,
        .cd-welcome-bar .btn-primary:active {
            background-color: #511D99 !important;
            border-color: #511D99 !important;
            color: #fff !important;
        }
        .cd-welcome-bar .btn-outline-secondary,
        .cd-welcome-bar .btn-outline-secondary:focus {
            border-color: #511D99 !important;
            color: #511D99 !important;
        }
        .cd-welcome-bar .btn-outline-secondary:hover {
            background-color: rgba(81,29,153,0.08) !important;
        }
        .cd-welcome-bar__bell { background-color: #F3F4F6; }
    </style>
@endpush

@section('content')

    {{-- Data --}}
    @php
        $authUser = auth()->user();
        $continueLearningUrl = '#';
        if (!empty($sales) && $sales->isNotEmpty()) {
            foreach ($sales as $s) {
                if (!empty($s->webinar)) {
                    $continueLearningUrl = $s->webinar->getLearningPageUrl();
                    break;
                }
            }
        }

        /* Word of the day – pick any random word (active list preferred, fallback any) */
        $mcpWordOfDay = null;
        try {
            $mcpWordOfDay = \App\Models\AcademicWordListWord::whereHas('academicWordList', function($q){ $q->where('is_active', true); })
                ->inRandomOrder()->first();
            if (!$mcpWordOfDay) {
                $mcpWordOfDay = \App\Models\AcademicWordListWord::inRandomOrder()->first();
            }
        } catch (\Exception $e) {}

        /* General mentor support (not tied to a specific course) */
        $mcpMentorSupport = null;
        $mcpMentorConversations = collect();
        try {
            $mcpMentorSupport = \App\Models\Support::where('user_id', $authUser->id)
                ->whereNull('webinar_id')
                ->orderBy('id', 'desc')
                ->first();
            if ($mcpMentorSupport) {
                $mcpMentorConversations = $mcpMentorSupport->conversations()
                    ->with(['sender', 'supporter'])
                    ->orderBy('id', 'asc')
                    ->get();
            }
        } catch (\Exception $e) {}
    @endphp

    <div class="mcp-layout">

    {{-- ── LEFT ASIDE ─────────────────────────────────────────────── --}}
    <div class="mcp-layout__aside">

        {{-- 1. User card with nav dropdown --}}
        <div class="cd-aside-card cd-user-card cd-user-nav position-relative">
            <img src="{{ $authUser->getAvatar(52) }}" class="cd-user-card__avatar" alt="{{ $authUser->full_name }}">
            <div style="flex:1;min-width:0;">
                <div class="cd-user-card__name">{{ mb_strtoupper($authUser->full_name) }}</div>
                <div class="cd-user-card__band">BAND ESTIMATE: {{ number_format($authUser->band_estimate ?? 5.0, 1) }}</div>
            </div>
            <x-iconsax-lin-arrow-down class="icons text-gray-400" width="16px" height="16px" style="flex-shrink:0;margin-left:auto;"/>

            <div class="cd-user-nav__dropdown">
                <ul style="list-style:none;padding:0;margin:8px 0;">
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl('/') : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                            <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="/panel/notifications" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                            <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('panel.notifications') }}</span>
                            @if(!empty($unReadNotifications) && count($unReadNotifications))
                                <span class="count-badge d-inline-flex align-items-center justify-content-center text-white rounded-circle ml-auto font-12 bg-danger">{{ count($unReadNotifications) }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="/panel/courses/purchases" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                            <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                            <span class="ml-8">My Courses</span>
                        </a>
                    </li>
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="/panel/support/new" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                            <x-iconsax-lin-message-question class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('panel.support') }}</span>
                        </a>
                    </li>
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="/panel/setting" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                            <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('public.profile') }}</span>
                        </a>
                    </li>
                </ul>
                <div style="border-top:1px solid #f0f0f0;margin:4px 0;"></div>
                <ul style="list-style:none;padding:0;margin:4px 0 8px;">
                    <li class="navbar-auth-user__dropdown-item">
                        <a href="/logout" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-danger text-decoration-none">
                            <x-iconsax-lin-logout class="icons text-danger" width="24px" height="24px"/>
                            <span class="ml-8 text-danger">{{ trans('panel.log_out') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- 2. Word of the Day --}}
        @if($mcpWordOfDay)
        <div class="cd-aside-card">
            <div class="cd-word-card">
                <div class="cd-word-card__word">{{ $mcpWordOfDay->word }}</div>
                @if($mcpWordOfDay->pronunciation ?? null)
                    <div class="cd-word-card__pronunciation">{{ $mcpWordOfDay->pronunciation }}</div>
                @endif
                @if(($mcpWordOfDay->translation ?? null) || ($mcpWordOfDay->definition ?? null))
                    <div class="cd-word-card__translation">{{ $mcpWordOfDay->translation ?? $mcpWordOfDay->definition }}</div>
                @endif
                @if($mcpWordOfDay->example ?? null)
                    <div class="cd-word-card__example">{{ $mcpWordOfDay->example }}</div>
                @endif
            </div>
        </div>
        @endif

        {{-- 3. Mentor chat --}}
        <div class="cd-aside-card cd-chat-card" id="mcp-chat-wrap">
            <div class="cd-chat__header">
                <span class="cd-chat__header-title">
                    <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                    Tin nhắn với Mentor
                </span>
                @if($mcpMentorSupport)
                    <span class="cd-chat__status" style="color: {{ $mcpMentorSupport->status == 'close' ? '#e74c3c' : '#27ae60' }}">
                        ● {{ $mcpMentorSupport->status == 'close' ? 'Đã đóng' : 'Đang mở' }}
                    </span>
                @endif
            </div>
            <div class="cd-chat__body" id="mcp-chat-body">
                @if($mcpMentorSupport && $mcpMentorConversations->count())
                    @foreach($mcpMentorConversations as $conv)
                        @php
                            $isMe     = $conv->sender_id == $authUser->id;
                            $convUser = $conv->sender ?? null;
                            $convTime = $conv->created_at ? date('H:i', $conv->created_at) : '';
                        @endphp
                        <div class="cd-bubble-row {{ $isMe ? 'cd-bubble-row--me' : '' }}">
                            @if($convUser)
                                <img src="{{ $convUser->getAvatar(26) }}" class="cd-bubble-row__avatar" alt="{{ $convUser->full_name }}">
                            @endif
                            <div class="cd-bubble {{ $isMe ? 'cd-bubble--me' : 'cd-bubble--other' }}">
                                {{ $conv->message }}
                                <span class="cd-bubble__time">{{ $convTime }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="cd-chat__empty-state">
                        <x-iconsax-lin-message-question class="icons" width="28px" height="28px" style="color:#ccc;"/>
                        <div style="margin-top:8px;">Chưa có tin nhắn.<br>Hãy đặt câu hỏi cho giáo viên!</div>
                    </div>
                @endif
            </div>
            @if(!$mcpMentorSupport)
                <div class="cd-chat__new-subject">
                    <label>Tiêu đề</label>
                    <input type="text" id="mcp-chat-title" placeholder="Ví dụ: Câu hỏi chung..." value="Câu hỏi về khóa học">
                </div>
            @endif
            <div class="cd-chat__footer">
                <textarea class="cd-chat__input" id="mcp-chat-input" rows="1" placeholder="Nhập tin nhắn..."></textarea>
                <button class="cd-chat__send-btn" id="mcp-chat-send" title="Gửi" style="background-color: #511D99">
                    <svg width="14" height="14" viewBox="0 0 24 24"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
                </button>
            </div>
        </div>

    </div>{{-- /mcp-layout__aside --}}

    {{-- ── RIGHT MAIN CONTENT ──────────────────────────────────────── --}}
    <div class="mcp-layout__main">

    <div class="cd-welcome-bar bg-white rounded-24 p-16">
        {{-- greeting --}}
        <div class="flex-grow-1 min-w-0">
            <h1 class="font-18 font-weight-bold text-dark text-ellipsis mb-0">
                WELCOME, {{ mb_strtoupper($authUser->name ?? $authUser->full_name) }}! 👋
            </h1>
            <div class="cd-welcome-bar__progress mt-8">
                <div class="cd-welcome-bar__track rounded-pill" style="height:6px;">
                    <div class="cd-welcome-bar__fill rounded-pill" style="width:100%;height:6px;transition:width .6s ease;"></div>
                </div>
            </div>
        </div>

        {{-- actions --}}
        <div class="d-flex align-items-center flex-wrap gap-8">
            <a href="{{ $continueLearningUrl }}" class="btn btn-primary btn-sm rounded-pill px-16">
                {{ trans('update.continue_learning') }} &rarr;
            </a>

            {{-- notification bell dropdown --}}
            <div class="language-select position-relative">
                <a href="/panel/notifications" class="cd-welcome-bar__bell d-flex-center size-40 rounded-circle bg-gray-100 position-relative text-dark">
                    <x-iconsax-bul-notification class="icons" width="20px" height="20px"/>
                    @if(!empty($unReadNotifications) && count($unReadNotifications))
                        <span class="position-absolute top-0 end-0 size-16 rounded-circle bg-danger d-flex-center font-10 text-white"
                              style="font-size:9px;top:2px;right:2px;min-width:16px;height:16px;">{{ count($unReadNotifications) > 9 ? '9+' : count($unReadNotifications) }}</span>
                    @endif
                </a>

                <div class="language-dropdown language-dropdown__notifications py-12" style="right:0;left:auto;">
                    @if(!empty($unReadNotifications) && count($unReadNotifications))
                        <div class="px-12">
                            <div class="d-flex align-items-center p-12 rounded-12 bg-gray-100">
                                <div class="d-flex-center size-48 bg-white rounded-circle">
                                    <div class="d-flex-center size-40 bg-primary rounded-circle">
                                        <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                                    </div>
                                </div>
                                <div class="ml-8">
                                    <h5 class="font-14">{{ count($unReadNotifications) }} {{ trans('panel.notifications') }}</h5>
                                    <a href="/panel/notifications/mark-all-as-read"
                                       class="delete-action d-block mt-4 font-12 cursor-pointer text-gray-500"
                                       data-msg="{{ trans('update.convert_unread_messages_to_read') }}"
                                       data-confirm="{{ trans('update.yes_convert') }}">
                                        {{ trans('update.mark_as_read') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @foreach($unReadNotifications->take(3) as $unReadNotification)
                            <a href="/panel/notifications?notification={{ $unReadNotification->id }}"
                               class="language-dropdown__item d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                                <x-iconsax-bul-notification class="icons text-gray-500" width="24px" height="24px"/>
                                <div class="ml-8">
                                    <h4 class="font-12">{{ $unReadNotification->title }}</h4>
                                    <span class="d-block text-gray-500 font-12 mt-8">{{ dateTimeFormat($unReadNotification->created_at, 'j M Y | H:i') }}</span>
                                </div>
                            </a>
                        @endforeach

                        <div class="px-12">
                            <a href="/panel/notifications" class="btn btn-lg btn-primary btn-block mt-12">
                                {{ trans('notification.all_notifications') }}
                            </a>
                        </div>
                    @else
                        <div class="d-flex-center flex-column text-center px-16 py-54">
                            <div class="d-flex-center size-40 bg-primary rounded-circle">
                                <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                            </div>
                            <span class="mt-12 text-gray-500">{{ trans('notification.empty_notifications') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- full-width progress bar --}}
        <div class="cd-welcome-card__progress">
            <div class="cd-welcome-card__progress-bar" style="width:100%;"></div>
        </div>
    </div>

    {{-- Top Stats --}}
    @include('design_1.panel.webinars.my_purchases.top_stats')

    {{-- Upcoming Live Sessions --}}
    @include('design_1.panel.webinars.my_purchases.upcoming_live_sessions')

    {{-- List Table --}}
    @if(!empty($sales) and $sales->isNotEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/courses">
            <div class="js-page-sales-lists row mt-20">
                @foreach($sales as $saleRow)
                    <div class="col-12 col-lg-6 mb-32">
                        @include("design_1.panel.webinars.my_purchases.item_card.index", ['sale' => $saleRow])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-sales-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'purchased_courses.svg',
            'title' => trans('panel.no_result_purchases') ,
            'hint' => trans('panel.no_result_purchases_hint') ,
            'btn' => ['url' => '/classes?sort=newest','text' => trans('panel.start_learning')]
        ])
    @endif

    </div>{{-- /mcp-layout__main --}}
    </div>{{-- /mcp-layout --}}

@endsection

@push('scripts_bottom')
    <script>
        var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var selectChapterLang = '{{ trans('update.select_chapter') }}';
        var liveSessionInfoLang = '{{ trans('update.live_session_info') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
    </script>

    <script>
        /* ── Mentor chat (My Courses page) ── */
        const mcpCsrf   = '{{ csrf_token() }}';
        let   mcpSupportId = {{ $mcpMentorSupport ? $mcpMentorSupport->id : 'null' }};
        const mcpMyAvatar  = '{{ $authUser->getAvatar(26) }}';
        const mcpMyName    = '{{ addslashes(e($authUser->full_name)) }}';

        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('mcp-chat-body');
            if (body) body.scrollTop = body.scrollHeight;

            const inp = document.getElementById('mcp-chat-input');
            if (inp) {
                inp.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 78) + 'px';
                });
                inp.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); mcpChatSend(); }
                });
            }
            const btn = document.getElementById('mcp-chat-send');
            if (btn) btn.addEventListener('click', mcpChatSend);
        });

        function mcpChatSend() {
            const inp = document.getElementById('mcp-chat-input');
            const msg = inp ? inp.value.trim() : '';
            if (!msg) return;
            if (!mcpSupportId) {
                const titleEl = document.getElementById('mcp-chat-title');
                const title   = titleEl ? (titleEl.value.trim() || 'Câu hỏi về khóa học') : 'Câu hỏi về khóa học';
                mcpFetch('/panel/support/ajax-create', { title: title, message: msg })
                    .then(function (data) {
                        mcpSupportId = data.support_id;
                        var subj = document.querySelector('.cd-chat__new-subject');
                        if (subj) subj.remove();
                        var empty = document.querySelector('#mcp-chat-body .cd-chat__empty-state');
                        if (empty) empty.remove();
                        mcpAppendBubble(data.message);
                        inp.value = ''; inp.style.height = 'auto';
                        mcpUpdateStatus('open');
                    }).catch(function () {});
            } else {
                mcpFetch('/panel/support/' + mcpSupportId + '/ajax-reply', { message: msg })
                    .then(function (data) {
                        mcpAppendBubble(data.message);
                        inp.value = ''; inp.style.height = 'auto';
                    }).catch(function () {});
            }
        }

        function mcpFetch(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': mcpCsrf, 'Accept': 'application/json' },
                body: JSON.stringify(body),
            }).then(function (res) {
                return res.json().then(function (data) {
                    if (!res.ok) { alert(data.message || 'Có lỗi xảy ra.'); return Promise.reject(); }
                    return data;
                });
            });
        }

        function mcpAppendBubble(msg) {
            var body = document.getElementById('mcp-chat-body');
            if (!body) return;
            var row = document.createElement('div');
            row.className = 'cd-bubble-row cd-bubble-row--me';
            row.innerHTML = '<img src="' + mcpMyAvatar + '" class="cd-bubble-row__avatar" alt="' + mcpMyName + '">'
                + '<div class="cd-bubble cd-bubble--me">' + mcpEscape(msg.message)
                + '<span class="cd-bubble__time">' + msg.created_at + '</span></div>';
            body.appendChild(row);
            body.scrollTop = body.scrollHeight;
        }

        function mcpUpdateStatus(status) {
            var el = document.querySelector('#mcp-chat-wrap .cd-chat__status');
            if (!el) {
                var hdr = document.querySelector('#mcp-chat-wrap .cd-chat__header');
                if (hdr) { el = document.createElement('span'); el.className = 'cd-chat__status'; hdr.appendChild(el); }
            }
            if (el) { el.style.color = '#27ae60'; el.textContent = '● Đang mở'; }
        }

        function mcpEscape(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }
    </script>

    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="/assets/design_1/js/panel/my_course_lists.min.js"></script>
    <script src="/assets/design_1/js/panel/make_next_session.min.js"></script>

@endpush
