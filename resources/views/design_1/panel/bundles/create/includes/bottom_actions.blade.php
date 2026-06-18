<style>
    .bundle-bottom-actions {
        position: fixed;
        bottom: 16px;
        z-index: 1200;
        width: auto;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(58, 65, 111, 0.08);
        background: #fff;
    }

    .bundle-bottom-actions__inner {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    @media (min-width: 992px) {
        .bundle-bottom-actions__inner {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .btn-1 {
        background-color: #fff;
        border-color: #511D99;
        color: #511D99;
    }

    .btn-1:hover {
        background-color: #511D99;
        border-color: #511D99;
        color: #fff;
    }
</style>

<div class="mt-32 bg-white rounded-16 p-16 soft-shadow-2 bundle-bottom-actions">
    <div class="bundle-bottom-actions__inner">

        <div class="d-flex align-items-center">
            {{-- Previous --}}
            <a href="{{ (!empty($bundle) and $currentStep > 1) ? ("/panel/bundles/{$bundle->id}/step/" . ($currentStep - 1)) : '#!' }}" class="d-flex-center size-48 rounded-circle bg-gray-100">
                @svg("iconsax-lin-arrow-left", ['height' => 16, 'width' => 16, 'class' => (!empty($bundle) and $currentStep > 1) ? '' : 'text-gray-500', 'style' => (!empty($bundle) and $currentStep > 1) ? 'color: #511D99;' : ''])
            </a>

            {{-- Next --}}
            <div id="getNextStep" class="d-flex-center size-48 rounded-circle bg-gray-100 ml-16 cursor-pointer">
                @svg("iconsax-lin-arrow-right", ['height' => 16, 'width' => 16, 'class' => ($currentStep < $stepCount) ? '' : 'text-gray-500', 'style' => ($currentStep < $stepCount) ? 'color: #511D99;' : ''])
            </div>

        </div>

        <div class="d-flex align-items-center gap-8">
            {{-- Save as Draft --}}
            <button type="button" id="saveAsDraft" class="btn-1 btn-lg">{{ trans('public.save_as_draft') }}</button>

            @if(!empty($bundle) and $bundle->creator_id == $authUser->id)
                @include('design_1.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete?redirect_to=/panel/bundles",
                    'deleteContentClassName' => 'webinar-actions text-danger ml-16',
                    'deleteContentItem' => $bundle,
                    'deleteContentItemType' => "bundle",
                ])
            @endif

            {{-- Send for Review --}}
            <button type="button" id="sendForReview" class="btn btn-lg btn-primary ml-16" style="background-color: #511D99; border-color: #511D99; color: #fff;">{{ trans('public.send_for_review') }}</button>
        </div>
    </div>

    @php
        $stepProgressPercent = (($currentStep * 100) / $stepCount);
    @endphp

    <div class="create-course-bottom-progress mt-16">
        <div class="create-course-bottom-progress__process" style="background-color: #511D99; border-color: #511D99; width: {{ $stepProgressPercent }}%"></div>
    </div>
</div>

<script>
    (function () {
        function alignBundleBottomActions() {
            var bar = document.querySelector('.bundle-bottom-actions');
            var container = document.querySelector('#panelContentScrollable .container');

            if (!bar || !container) {
                return;
            }

            var rect = container.getBoundingClientRect();
            bar.style.left = rect.left + 'px';
            bar.style.width = rect.width + 'px';
        }

        window.addEventListener('load', alignBundleBottomActions);
        window.addEventListener('resize', function () {
            window.requestAnimationFrame(alignBundleBottomActions);
        });

        document.addEventListener('scroll', function () {
            window.requestAnimationFrame(alignBundleBottomActions);
        }, true);
    })();
</script>
