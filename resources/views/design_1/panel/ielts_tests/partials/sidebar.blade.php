{{--
  Shared sidebar for Mock Test & Practice Test listing pages.
  Required variables: $authUser, $bandEstimate, $streak, $randomWord, $enrolledCourses
--}}

<style>
/* ======================================================
   WIREFRAME SIDEBAR WIDGETS
   ====================================================== */

/* ── Profile Card ────────────────────────────────────── */
.wf-sidebar-profile {
    background: rgba(212, 211, 254, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.62);
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    position: relative;
    cursor: pointer;
    user-select: none;
    margin-bottom: 16px;
    overflow: hidden;
}
.dark-mode .wf-sidebar-profile {
    background: #1e293b;
}
.wf-profile-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 18px 20px;
    position: relative;
}
.wf-profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid #ddd7f8;
}
.wf-profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.wf-profile-text {
    min-width: 0;
}
.wf-profile-name {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dark-mode .wf-profile-name { color: #f1f5f9; }
.wf-profile-band {
    font-size: 13px;
    color: #64748b;
    margin-top: 4px;
}
.dark-mode .wf-profile-band { color: #94a3b8; }
.wf-profile-arrow {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    transition: transform 0.2s;
}
.wf-sidebar-profile.open .wf-profile-arrow {
    transform: translateY(-50%) rotate(180deg);
}

/* Dropdown */
.wf-profile-dropdown {
    display: none;
    margin-top: 16px;
    border-top: 1px solid rgba(220, 214, 250, 0.8);
    padding-top: 12px;
}
.dark-mode .wf-profile-dropdown { border-top-color: #334155; }
/* JS-driven: always works on click */
.wf-sidebar-profile.open .wf-profile-dropdown {
    display: block;
}
/* CSS-driven hover: only on real pointer devices (not touchscreens) */
@media (hover: hover) {
    .wf-sidebar-profile:hover .wf-profile-dropdown {
        display: block;
    }
}
.wf-profile-dropdown a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    text-decoration: none;
    transition: background 0.15s;
}
.dark-mode .wf-profile-dropdown a { color: #cbd5e1; }
.wf-profile-dropdown a:hover {
    background: rgba(212, 211, 254, 0.48);
    text-decoration: none;
}
.dark-mode .wf-profile-dropdown a:hover { background: #0f172a; }
.wf-profile-dropdown a i {
    width: 18px;
    text-align: center;
    color: #3b82f6;
}
.wf-profile-dropdown a.wf-logout { color: #ef4444; }
.wf-profile-dropdown a.wf-logout i { color: #ef4444; }
.wf-profile-dropdown a.wf-logout:hover { background: #fee2e2; }

/* ── Vocabulary Word Card ─────────────────────────────── */
.wf-vocab-card {
    background: rgba(212, 211, 254, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.62);
    border-radius: 16px;
    padding: 20px 20px 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    margin-bottom: 16px;
    text-align: center;
}
.dark-mode .wf-vocab-card { background: #1e293b; }
.wf-vocab-word {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
}
.dark-mode .wf-vocab-word { color: #f1f5f9; }
.wf-vocab-type {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 4px;
    font-style: italic;
}
.dark-mode .wf-vocab-type { color: #94a3b8; }
.wf-vocab-translation {
    font-size: 13px;
    color: #475569;
    margin-bottom: 10px;
}
.dark-mode .wf-vocab-translation { color: #94a3b8; }
.wf-vocab-divider {
    border: none;
    border-top: 1px solid rgba(220, 214, 250, 0.8);
    margin: 10px 0;
}
.dark-mode .wf-vocab-divider { border-top-color: #334155; }
.wf-vocab-example {
    font-size: 12px;
    color: #64748b;
    font-style: italic;
    line-height: 1.6;
}
.dark-mode .wf-vocab-example { color: #94a3b8; }
.wf-vocab-empty {
    font-size: 13px;
    color: #94a3b8;
}

/* ── Stats Widgets (Streak / Ranking) ─────────────────── */
.wf-stats-widget {
    background: rgba(212, 211, 254, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.62);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    margin-bottom: 16px;
    text-align: center;
}
.dark-mode .wf-stats-widget { background: #1e293b; }
.wf-widget-title {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #374151;
    margin-bottom: 14px;
}
.dark-mode .wf-widget-title { color: #cbd5e1; }

/* Streak circles */
.wf-streak-circles {
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
}
.wf-streak-day {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #94a3b8;
    transition: all 0.2s;
}
.wf-streak-day.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
}
.wf-streak-count {
    margin-top: 10px;
    font-size: 12px;
    color: #64748b;
}
.dark-mode .wf-streak-count { color: #94a3b8; }

/* Ranking */
.wf-ranking-placeholder {
    padding: 16px 0 8px;
    color: #94a3b8;
    font-size: 13px;
}

/* Sidebar layout */
.wf-sidebar-wrap {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.wf-ranking-widget {
    min-height: 120px;
}
</style>

<div class="wf-sidebar-wrap">
{{-- ── 1. Profile Card ─────────────────────────────── --}}
<div class="wf-sidebar-profile" id="wfProfileCard">
    <div class="wf-profile-inner">
        <div class="wf-profile-avatar">
            <img src="{{ $authUser->getAvatar(180) }}" alt="{{ $authUser->full_name }}">
        </div>
        <div class="wf-profile-text">
            <div class="wf-profile-name">{{ $authUser->full_name }}</div>
            <div class="wf-profile-band">BAND ESTIMATE: {{ number_format($bandEstimate, 1) }}</div>
        </div>
        <i class="fas fa-chevron-down wf-profile-arrow"></i>
    </div>

    {{-- Dropdown --}}
    <div class="wf-profile-dropdown">
        <a href="{{ url('/panel/setting') }}">
            <i class="fas fa-user-circle"></i> View Profile
        </a>
        <a href="{{ url('/panel/setting') }}">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="{{ url('/panel/setting/step/3') }}">
            <i class="fas fa-lock"></i> Change Password
        </a>
        <a href="{{ url('/logout') }}" class="wf-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

{{-- ── 2. Word of the Day ───────────────────────────── --}}
<div class="wf-vocab-card">
    @if($randomWord)
        <div class="wf-vocab-word">{{ $randomWord->word }}</div>
        <div class="wf-vocab-type">
            @if($randomWord->word_type)({{ $randomWord->word_type }}) @endif
            @if($randomWord->pronunciation)/{{ $randomWord->pronunciation }}/@endif
        </div>
        @if($randomWord->translation)
            <div class="wf-vocab-translation">{{ $randomWord->translation }}</div>
        @endif
        <hr class="wf-vocab-divider">
        @if($randomWord->example)
            <div class="wf-vocab-example">{{ $randomWord->example }}</div>
        @endif
    @else
        <div class="wf-vocab-word">food additives</div>
        <div class="wf-vocab-type">(n) /fuːd ə'dɪktɪv/</div>
        <div class="wf-vocab-translation">chất phụ gia thực phẩm</div>
        <hr class="wf-vocab-divider">
        <div class="wf-vocab-example">Food additives improve the taste of food.</div>
    @endif
</div>

{{-- ── 3. Streak ────────────────────────────────────── --}}
<div class="wf-stats-widget">
    <div class="wf-widget-title">STREAK</div>
    <div class="wf-streak-circles">
        @for($i = 1; $i <= 7; $i++)
            <div class="wf-streak-day {{ $i <= $streak ? 'active' : '' }}">{{ $i }}</div>
        @endfor
    </div>
    <div class="wf-streak-count">
        @if($streak > 0)
            {{ $streak }} day{{ $streak !== 1 ? 's' : '' }} in a row 🔥
        @else
            Start your streak today!
        @endif
    </div>
</div>

{{-- ── 4. Ranking ───────────────────────────────────── --}}
<div class="wf-stats-widget wf-ranking-widget">
    <div class="wf-widget-title">RANKING</div>
    <div class="wf-ranking-placeholder">
        Your ranking will appear here
    </div>
</div>
</div>{{-- end wf-sidebar-wrap --}}

@push('scripts_bottom')
<script>
(function () {
    // Toggle profile dropdown on click (for touch devices)
    var card = document.getElementById('wfProfileCard');
    if (card) {
        card.addEventListener('click', function (e) {
            // Don't toggle when clicking a link inside the dropdown
            if (e.target.closest('a')) return;
            var opening = !card.classList.contains('open');
            card.classList.toggle('open');
            // Close the switch-courses dropdown if we are opening the profile
            if (opening) {
                var switchDrop = document.getElementById('wfSwitchDropdown');
                if (switchDrop) switchDrop.classList.remove('open');
            }
        });
        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!card.contains(e.target)) {
                card.classList.remove('open');
            }
        });
    }
})();
</script>
@endpush
