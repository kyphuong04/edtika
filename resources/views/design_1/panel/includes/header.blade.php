<div class="panel-header d-flex align-items-center bg-white px-24 px-lg-0">

    <div class="panel-header__contents d-flex align-items-center justify-content-between h-100 border-bottom-gray-200" style="width:100%;">

        <div class="d-flex align-items-center">
        </div>

        <div class="d-flex align-items-center">

            @if($authUser->checkAccessToAIContentFeature())
                <div class="js-show-ai-content-drawer d-none d-lg-flex align-items-center justify-content-center size-32 rounded-8 bg-gray-100 mr-16 cursor-pointer">
                    <x-iconsax-lin-cpu-charge class="icons text-gray-500" width="20px" height="20px"/>
                </div>
            @endif

            @include('design_1.panel.includes.header.auth_user_info')
        </div>
    </div>
</div>
