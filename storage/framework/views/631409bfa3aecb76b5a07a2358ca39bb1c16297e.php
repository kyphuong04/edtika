<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form action="/panel/blog/<?php echo e((!empty($post) ? $post->id.'/update' : 'store')); ?>" method="post" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>


        <div class="bg-white pt-16 rounded-24">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('update.blog_article')); ?> <?php echo e(trans('update.information')); ?></h3>
                </div>
            </div>

            <div class="row p-16">

                <div class="col-12 col-lg-6">
                    <div class="">

                        <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                            'itemRow' => !empty($post) ? $post : null,
                            'withoutReloadLocale' => false,
                            'extraClass' => '',
                            'extraData' => null
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('admin/main.title')); ?></label>
                            <input type="text" name="title"
                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e((!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->title : old('title')); ?>"
                                   placeholder="<?php echo e(trans('admin/main.choose_title')); ?>"/>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('admin/main.subtitle')); ?></label>
                            <input type="text" name="subtitle"
                                   class="form-control <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e((!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->subtitle : old('subtitle')); ?>"
                                   placeholder="<?php echo e(trans('admin/main.choose_subtitle')); ?>"/>
                            <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label"><?php echo e(trans('/admin/main.category')); ?></label>
                            <select name="category_id" class="form-control select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option selected disabled><?php echo e(trans('admin/main.choose_category')); ?></option>

                                <?php $__currentLoopData = $blogCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blogCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($blogCategory->id); ?>" <?php echo e((((!empty($post) and $post->category_id == $blogCategory->id) or (old('category_id') == $blogCategory->id)) ? 'selected="selected"' : '')); ?>><?php echo e($blogCategory->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('public.study_time')); ?> (<?php echo e(trans('update.min')); ?>)</label>
                            <input type="text" name="study_time" class="form-control" value="<?php echo e(!empty($post) ? $post->study_time : old('study_time')); ?>"/>
                        </div>

                        <?php echo $__env->make('design_1.panel.webinars.create.includes.media',[
                                'media' => !empty($post) ? $post->image : null,
                                'mediaName' => 'image',
                                'mediaTitle' => trans('update.article_cover'),
                                'mediaCardClass' => 'col-12 mt-16'
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                        <div class="form-group bg-white-editor mt-28">
                            <label class="form-group-label"><?php echo e(trans('public.summary')); ?></label>
                            <textarea name="description" rows="4" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('update.create_blog_summary_placeholder')); ?>"><?php echo (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->description : old('description'); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group bg-white-editor mt-24">
                            <label class="form-group-label"><?php echo e(trans('public.content')); ?></label>
                            <textarea name="content" data-height="400" class="main-summernote form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('update.create_blog_content_placeholder')); ?>"><?php echo (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->content : old('content'); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-20 mt-lg-0">


                    
                    <?php echo $__env->make("design_1.panel.blog.posts.create.includes.related_posts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>
            </div>


            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 p-16 border-top-gray-100">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14"><?php echo e(trans('product.information')); ?></h5>
                        <p class="mt-2 font-12 text-gray-500"><?php echo e(!empty(getGeneralOptionsSettings('direct_publication_of_blog')) ? trans('update.your_articles_will_be_published_after_save') : trans('update.your_articles_will_be_published_after_admin_approval')); ?></p>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-primary mt-20 mt-lg-0"><?php echo e(trans('public.save')); ?></button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/create_blog.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/blog/posts/create/index.blade.php ENDPATH**/ ?>