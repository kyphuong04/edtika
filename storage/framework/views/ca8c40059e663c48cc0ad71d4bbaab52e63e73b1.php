<?php if(!empty($forumTopics) and !$forumTopics->isEmpty()): ?>
    <div id="profileForumTopicsRow" class="">
        <?php echo $__env->make('design_1.web.forums.components.cards.topic.index',['forumTopics' => $forumTopics, 'cardClassName' => "mt-24 profile-forum-topic"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <?php if(!empty($hasMoreForumTopics)): ?>
        <div class="d-flex-center mt-16">
            <div class="js-profile-tab-load-more-btn btn border-dashed border-gray-300 rounded-12 bg-white bg-hover-gray-100 cursor-pointer" data-path="/users/<?php echo e($user->getUsername()); ?>/get-topics" data-el="profileForumTopicsRow">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-rotate-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 text-gray-500"><?php echo e(trans('update.load_more')); ?></span>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
    <?php echo $__env->make('design_1.panel.includes.no-result',[
        'file_name' => 'profile_forum.svg',
        'title' => trans('update.user_profile_not_have_topics'),
        'hint' => trans('update.user_profile_not_have_topics_hint'),
        'extraClass' => 'mt-0',
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/users/profile/tabs/forum.blade.php ENDPATH**/ ?>