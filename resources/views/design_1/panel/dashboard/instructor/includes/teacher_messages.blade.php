{{-- Teacher Messages (Support Tickets) --}}
<div class="bg-white p-16 rounded-24 mt-24">
    <div class="d-flex align-items-center justify-content-between mb-16 position-relative">
        <h4 class="font-14 font-weight-bold text-dark flex-1 text-center">Tin nhắn</h4>
        <a href="/panel/support"
           class="d-flex-center size-32 rounded-8 bg-gray-100 bg-hover-primary-40 text-gray-500">
            <x-iconsax-lin-arrow-right class="icons" width="16px" height="16px"/>
        </a>
    </div>

    @if(!empty($supportMessages['totalTickets']) && $supportMessages['totalTickets'] > 0)
        {{-- Stats row --}}
        <div class="d-grid grid-columns-2 gap-12 mb-16">
            <div class="d-flex align-items-start justify-content-between bg-gray-200 rounded-16 p-12">
                <div>
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $supportMessages['openTickets'] }}</span>
                    <span class="d-block font-11 text-gray-600 mt-4">{{ trans('update.open_tickets') }}</span>
                </div>
                <x-iconsax-bul-message-notif class="icons text-warning" width="20px" height="20px"/>
            </div>
            <div class="d-flex align-items-start justify-content-between bg-gray-200 rounded-16 p-12">
                <div>
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $supportMessages['totalTickets'] }}</span>
                    <span class="d-block font-11 text-gray-600 mt-4">{{ trans('update.total_tickets') }}</span>
                </div>
                <x-iconsax-bul-messages class="icons text-primary" width="20px" height="20px"/>
            </div>
        </div>

        {{-- Message list --}}
        @if(!empty($supportMessages['supports']) && count($supportMessages['supports']))
            <div class="d-flex flex-column gap-10">
                @foreach($supportMessages['supports']->take(3) as $support)
                    @php
                        $supportUser        = $support->user;
                        $lastConversation   = $support->conversations->first();
                    @endphp
                    <a href="/panel/support/{{ $support->id }}/conversations"
                       class="d-flex align-items-center bg-gray-100 rounded-16 p-12 gap-10 text-decoration-none text-dark border-2 border-gray-300">
                        <div class="size-40 rounded-circle flex-shrink-0">
                            <img src="{{ $supportUser->getAvatar(40) }}" alt="{{ $supportUser->full_name }}"
                                 class="img-cover rounded-circle">
                        </div>
                        <div class="flex-1" style="min-width:0;">
                            <span class="d-block font-13 font-weight-bold text-dark text-ellipsis">
                                {{ truncate($supportUser->full_name, 20) }}
                            </span>
                            @if($lastConversation)
                                <span class="d-block font-11 text-gray-500 mt-2 text-ellipsis">
                                    {{ truncate($lastConversation->message, 38) }}
                                </span>
                            @endif
                        </div>
                        @php
                            $badgeClass = $support->status === 'close' ? 'badge-gray' : ($support->status === 'open' ? 'badge-success-light' : 'badge-warning-light');
                        @endphp
                        <span class="badge font-10 flex-shrink-0 {{ $badgeClass }}">
                            {{ $support->status === 'close' ? 'Đóng' : ($support->status === 'open' ? 'Mở' : 'Đang xử lý') }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    @else
        <div class="d-flex-center flex-column text-center p-24 bg-gray-100 border-dashed border-gray-200 rounded-16">
            <div class="d-flex-center size-40 rounded-12 bg-primary-40">
                <x-iconsax-bul-messages class="icons text-primary" width="20px" height="20px"/>
            </div>
            <p class="font-13 text-gray-500 mt-10">{{ trans('update.no_support_ticket!') }}</p>
        </div>
    @endif
</div>
