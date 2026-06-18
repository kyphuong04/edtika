@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    /* ── Support Conversations: full-height layout ─────────────── */
    .support-conversation-card {
        height: calc(100vh - 168px) !important;
    }

    /* Row stretches both columns to equal height */
    .support-conversations-row {
        align-items: stretch;
    }
    .support-conversations-row > [class*="col-"] {
        display: flex;
        flex-direction: column;
    }

    /* Chat panel fills its column entirely */
    .chat-panel {
        flex: 1 1 auto;
        overflow: hidden;
    }

    /* Scrollable messages area fills the flex space */
    .chat-panel .support-conversation-messages {
        height: auto !important;
        flex: 1 1 0;
        min-height: 0;
        overflow-y: auto;
    }

    /* ── Chat header ──────────────────────────────────────────── */
    .chat-panel__header {
        border-bottom: 1px solid var(--gray-100);
        flex-shrink: 0;
    }

    /* ── Message rows ─────────────────────────────────────────── */
    .chat-message-row {
        margin-bottom: 20px;
    }
    .chat-message-row:first-child { margin-top: 8px; }

    .chat-avatar {
        align-self: flex-end;
    }

    /* ── Chat bubbles ─────────────────────────────────────────── */
    .chat-bubble {
        max-width: 68%;
        padding: 12px 16px;
        border-radius: 18px;
        word-break: break-word;
    }

    /* Received: light, with border */
    .chat-bubble--received {
        background-color: #ffffff;
        border: 1px solid #c3c5c7;
        border-bottom-left-radius: 4px;
        color: #1f2937;
    }
    .chat-bubble--received .chat-bubble__time {
        color: #9ca3af;
        text-align: left;
    }
    .chat-bubble--received .chat-bubble__attach {
        color: #6b7280;
    }

    /* Sent: darker gray */
    .chat-bubble--sent {
        background-color: #511D99;
        border-bottom-right-radius: 4px;
        color: #ffffff;
    }
    .chat-bubble--sent .chat-bubble__time {
        color: rgba(255,255,255,0.65);
        text-align: right;
    }
    .chat-bubble--sent .chat-bubble__attach {
        color: rgba(255,255,255,0.8);
    }

    /* Dark mode overrides */
    .dark-mode .chat-bubble--received {
        background-color: #2a2b32;
        border-color: #3d3e47;
        color: #e5e7eb;
    }
    .dark-mode .chat-bubble--sent {
        background-color: #4b5563;
    }

    /* ── Input area ───────────────────────────────────────────── */
    .chat-panel__input-area {
        flex-shrink: 0;
        border-top: 1px solid var(--gray-100);
    }

    .chat-input-wrapper {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        padding: 6px 6px 6px 18px;
    }

    .dark-mode .chat-input-wrapper {
        background-color: #2a2b32;
        border-color: #3d3e47;
    }

    .chat-input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: inherit;
    }
    .chat-input::placeholder {
        color: #9ca3af;
    }

    .chat-send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background-color: var(--primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0;
        transition: opacity 0.2s;
    }
    .chat-send-btn:hover { opacity: 0.85; }
    .chat-send-btn .icons { color: #fff; }

    /* ── Mobile ───────────────────────────────────────────────── */
    @media (max-width: 991px) {
        .support-conversation-card {
            height: 450px !important;
        }
        .chat-panel {
            height: 550px;
            flex: none;
        }
    }
</style>
@endpush

@section('content')

    @if(!empty($supports) and !$supports->isEmpty())
        <div class="row support-conversations-row">
            <div class="col-12 col-lg-4">
                @include('design_1.panel.support.conversations.lists')
            </div>

            <div class="col-12 col-lg-8">
                @include('design_1.panel.support.conversations.messages')
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'support_tickets.svg',
            'title' => trans('panel.support_no_result'),
            'hint' => nl2br(trans('panel.support_no_result_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/panel/conversations.min.js"></script>
@endpush
