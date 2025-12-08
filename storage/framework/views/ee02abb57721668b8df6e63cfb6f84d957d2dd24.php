<div class="tab-pane mt-0 fade" id="topics" role="tabpanel" aria-labelledby="topics-tab">
    <div class="row">

        <div class="col-12">
            <h5 class="section-title after-line"><?php echo e(trans('update.forum_topics')); ?></h5>

            <div class="table-responsive mt-1">
                <table class="table custom-table table-md">
                    <tr>
                        <th><?php echo e(trans('public.topic')); ?></th>
                        <th><?php echo e(trans('admin/main.category')); ?></th>
                        <th><?php echo e(trans('site.posts')); ?></th>
                        <th><?php echo e(trans('admin/main.created_at')); ?></th>
                        <th><?php echo e(trans('admin/main.updated_at')); ?></th>
                        <th class="text-right"><?php echo e(trans('admin/main.actions')); ?></th>
                    </tr>

                    <?php if(!empty($topics)): ?>
                        <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td width="25%">
                                    <a href="<?php echo e($topic->getPostsUrl()); ?>" target="_blank" class=""><?php echo e($topic->title); ?></a>
                                </td>

                                <td>
                                    <?php echo e($topic->forum->title); ?>

                                </td>
                                <td><?php echo e($topic->posts_count); ?></td>
                                <td class="text-center"><?php echo e(dateTimeFormat($topic->created_at,'j M Y | H:i')); ?></td>
                                <td class="text-center"><?php echo e((!empty($topic->posts) and count($topic->posts)) ? dateTimeFormat($topic->posts->first()->created_at,'j M Y | H:i') : '-'); ?></td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum_topics_lists')): ?>
                                                <?php if(!$topic->close): ?>
                                                    <?php echo $__env->make('admin.includes.delete_button',[
                                                        'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/close",
                                                        'btnClass' => 'dropdown-item text-warning mb-3 py-3 px-0 font-14',
                                                        'btnText' => trans('public.close'),
                                                        'btnIcon' => 'lock',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-warning mr-2',
                                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                <?php else: ?>
                                                    <?php echo $__env->make('admin.includes.delete_button',[
                                                        'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/open",
                                                        'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                        'btnText' => trans('public.open'),
                                                        'btnIcon' => 'unlock',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-success mr-2',
                                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                                    
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum_topics_posts')): ?>
                                                <a href="<?php echo e(getAdminPanelUrl()); ?>/forums/<?php echo e($topic->forum_id); ?>/topics/<?php echo e($topic->id); ?>/posts"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                    <span class="text-gray-500 font-14"><?php echo e(trans('site.posts')); ?></span>
                                                </a>
                                            <?php endif; ?>
                                                    
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_block_access')): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/delete?no_redirect=true",
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('admin/main.delete'),
                                                    'btnIcon' => 'trash',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </div>
                                </td>


                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/users/editTabs/topics.blade.php ENDPATH**/ ?>