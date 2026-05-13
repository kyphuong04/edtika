<div class="bg-white rounded-24 p-16 h-100 d-flex flex-column">
    <h4 class="font-14 font-weight-bold text-dark mb-16">Mentor Feedback &amp; Messages</h4>

    @if(!empty($supportMessages['supports']) && count($supportMessages['supports']))

        <div class="d-flex flex-column gap-12 flex-grow-1" style="overflow-y:auto;max-height:280px;" data-simplebar @if(!empty($isRtl)) data-simplebar-direction="rtl" @endif>
            @foreach($supportMessages['supports']->take(3) as $support)
                @php
                    $supportUser      = $support->user;
                    $lastConversation = $support->conversations->first();
                @endphp
                <a href="/panel/support/{{ $support->id }}/conversations" class="text-decoration-none">
                    <div class="rounded-16 bg-gray-100 p-12">
                        <div class="d-flex align-items-center gap-8">
                            <div class="size-36 rounded-circle flex-shrink-0">
                                <img src="{{ $supportUser->getAvatar(36) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="min-w-0">
                                <p class="font-12 font-weight-bold text-dark mb-0 text-ellipsis">{{ truncate($support->title, 30) }}</p>
                                <span class="font-11 text-gray-400">{{ dateTimeFormat($support->created_at, 'j M Y') }}</span>
                            </div>
                            @if($support->status === 'open')
                                <span class="badge badge-warning-light font-10 ml-auto flex-shrink-0">Open</span>
                            @else
                                <span class="badge badge-success-light font-10 ml-auto flex-shrink-0">Closed</span>
                            @endif
                        </div>
                        @if($lastConversation)
                            <p class="font-11 text-gray-500 mt-8 mb-0 white-space-pre-wrap">{{ truncate($lastConversation->message, 80) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <a href="/panel/support" class="btn btn-outline-primary btn-sm rounded-pill mt-12 w-100">
            View all messages
        </a>

    @else
        <div class="d-flex-center flex-column text-center p-20 rounded-16 bg-gray-100 flex-grow-1">
            <x-iconsax-bul-message-text class="icons text-primary" width="32px" height="32px"/>
            <p class="font-12 text-gray-500 mt-8 mb-0">No mentor messages yet.</p>
            <a href="/panel/support/new" class="btn btn-primary btn-sm rounded-pill mt-12">
                Send a message
            </a>
        </div>
    @endif
</div>
