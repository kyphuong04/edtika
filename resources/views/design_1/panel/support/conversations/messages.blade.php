<div class="bg-white rounded-24 chat-panel d-flex flex-column">


    @if(!empty($selectSupport))

        {{-- Chat Header --}}
        <div class="chat-panel__header d-flex align-items-center px-20 py-16">
            <div class="size-44 rounded-circle bg-gray-200 flex-shrink-0">
                <img src="{{ $selectSupport->user->getAvatar(44) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-12 flex-1 overflow-hidden">
                <h5 class="font-14 font-weight-bold text-dark mb-0 text-uppercase">{{ $selectSupport->user->full_name }}</h5>
                @if(!empty($selectSupport->webinar))
                    <span class="font-11 text-gray-400 d-block text-truncate">{{ $selectSupport->webinar->title }}</span>
                @else
                    <span class="font-11 text-gray-400">{{ $selectSupport->title }}</span>
                @endif
            </div>
            @if($selectSupport->status != 'close')
                <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center ml-8">
                    <button type="button" class="d-flex-center size-32 btn-transparent">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>
                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/support/{{ $selectSupport->id }}/close" class="text-danger">{{ trans('public.close') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Messages Area --}}
        <div id="conversationsCard" class="support-conversation-messages flex-1 px-16 py-8" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
            @if(!empty($selectSupport->conversations) and !$selectSupport->conversations->isEmpty())
                @foreach($selectSupport->conversations as $conversation)
                    @php
                        $conversationUser = (!empty($conversation->supporter)) ? $conversation->supporter : $conversation->sender;
                        $isSentByMe = ($conversation->sender_id == $authUser->id) ||
                                      (!empty($conversation->supporter) && $conversation->supporter->id == $authUser->id);
                    @endphp

                    <div class="chat-message-row d-flex {{ $isSentByMe ? 'justify-content-end' : 'justify-content-start align-items-end' }}">
                        @if(!$isSentByMe)
                            <div class="chat-avatar size-36 rounded-circle bg-gray-200 flex-shrink-0 mr-10">
                                <img src="{{ $conversationUser->getAvatar(36) }}" class="img-cover rounded-circle" alt="">
                            </div>
                        @endif

                        <div class="chat-bubble {{ $isSentByMe ? 'chat-bubble--sent' : 'chat-bubble--received' }}">
                            <p class="font-14 mb-0 white-space-pre-wrap">{{ $conversation->message }}</p>
                            @if(!empty($conversation->attach))
                                <a href="{{ url($conversation->attach) }}" target="_blank" class="chat-bubble__attach d-inline-flex align-items-center mt-8">
                                    <x-iconsax-lin-document-download class="icons" width="14px" height="14px"/>
                                    <span class="font-12 ml-4">{{ trans('update.attachment') }}</span>
                                </a>
                            @endif
                            <span class="chat-bubble__time d-block font-11 mt-6">{{ dateTimeFormat($conversation->created_at, 'H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Message Input --}}
        <form action="/panel/support/{{ $selectSupport->id }}/conversations" method="post" class="chat-panel__input-area px-16 pb-16 pt-8" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="attach" id="attach" class="d-none"/>

            <div class="chat-input-wrapper d-flex align-items-center">
                <input type="text" name="message"
                    placeholder="{{ trans('update.type_your_message') }}"
                    class="chat-input flex-1 font-14 @error('message') is-invalid @enderror"
                    autocomplete="off">
                <button type="submit" class="chat-send-btn d-flex-center flex-shrink-0">
                    <x-iconsax-lin-send-2 class="icons" width="20px" height="20px"/>
                </button>
            </div>

            @error('message')
                <div class="text-danger font-12 mt-4">{{ $message }}</div>
            @enderror
        </form>

    </div>
    @else

        <div class="row">

          <div class="col-12 col-lg-4">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.total_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                            <x-iconsax-bul-message class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $supportsCount }}</h5>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-16 mt-md-0">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.open_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                            <x-iconsax-bul-message-time class="icons text-warning" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $openSupportsCount }}</h5>
                </div>
            </div>
            <div class="col-12 col-lg-4 mt-16 mt-md-0">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.closed_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                            <x-iconsax-bul-message-remove class="icons text-danger" width="24px" height="24px"/>
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1">{{ $closeSupportsCount }}</h5>
                </div>
            </div>

        </div>


        @include('design_1.panel.includes.no-result',[
            'file_name' => 'support_tickets.svg',
            'title' => trans('panel.select_support'),
            'hint' => nl2br(trans('panel.select_support_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif

</div>
