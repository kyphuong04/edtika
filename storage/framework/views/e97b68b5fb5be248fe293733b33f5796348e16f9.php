<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
<?php $__env->stopPush(); ?>

<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-4">

            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.personal_information')); ?></h3>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('update.birthday')); ?></label>
                    <span class="has-translation"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
                    <input type="text" name="birthday" class="form-control datepicker js-default-init-date-picker <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(!empty($user->birthday) ? dateTimeFormat($user->birthday, 'Y-m-d H:i', false) : old('birthday')); ?>" data-format="YYYY/MM/DD" data-show-drops="true"/>

                    <?php $__errorArgs = ['birthday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"> <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group mb-0">
                    <label class="font-14 font-weight-bold bg-white"><?php echo e(trans('update.gender')); ?>:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="gender" value="man" <?php echo e((!empty($user->gender) and $user->gender == 'man') ? 'checked="checked"' : ''); ?> id="man" class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="man"><?php echo e(trans('update.man')); ?></label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="gender" value="woman" id="woman" <?php echo e((!empty($user->gender) and $user->gender == 'woman') ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="woman"><?php echo e(trans('update.woman')); ?></label>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.meeting_settings')); ?></h3>

                

                <div class="form-group">
                    <label class="font-14 font-weight-bold bg-white"><?php echo e(trans('update.meeting_type')); ?>:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="meeting_type" value="in_person" id="in_person" <?php echo e((!empty($user->meeting_type) and $user->meeting_type == 'in_person') ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="in_person"><?php echo e(trans('update.in_person')); ?></label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="meeting_type" value="online" id="online" <?php echo e((!empty($user->meeting_type) and $user->meeting_type == 'online') ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="online"><?php echo e(trans('update.online')); ?></label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="meeting_type" value="all" id="all" <?php echo e((!empty($user->meeting_type) and $user->meeting_type == 'all') ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="all"><?php echo e(trans('public.all')); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="font-14 font-weight-bold bg-white"><?php echo e(trans('update.level_of_training')); ?>:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="level_of_training[]" value="beginner" id="beginner" <?php echo e((!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('beginner',$user->level_of_training)) ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="beginner"><?php echo e(trans('update.beginner')); ?></label>
                        </div>

                        <div class="custom-control custom-checkbox mt-12">
                            <input type="checkbox" name="level_of_training[]" value="middle" id="middle" <?php echo e((!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('middle',$user->level_of_training)) ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="middle"><?php echo e(trans('update.middle')); ?></label>
                        </div>

                        <div class="custom-control custom-checkbox mt-12">
                            <input type="checkbox" name="level_of_training[]" value="expert" id="expert" <?php echo e((!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('expert',$user->level_of_training)) ? 'checked="checked"' : ''); ?> class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="expert"><?php echo e(trans('update.expert')); ?></label>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if(!empty($formFieldsHtml)): ?>
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.forms')); ?></h3>

                    <div class="">
                        <?php echo $formFieldsHtml; ?>

                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="js-user-location col-12 col-lg-8 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.region')); ?></h3>

                        <div class="form-group mt-20 ">
                            <label class="form-group-label"><?php echo e(trans('update.country')); ?></label>

                            <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-user-location" data-map-zoom="5">
                                <option value=""><?php echo e(trans('update.choose_a_country')); ?></option>

                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->id); ?>" <?php echo e((!empty($user) and $user->country_id == $country->id) ? 'selected' : ''); ?> data-center="<?php echo e(implode(',', $country->geo_center)); ?>"><?php echo e($country->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label"><?php echo e(trans('update.state')); ?></label>

                            <select
                                name="province_id"
                                <?php echo e((empty($user) or empty($user->country_id)) ? 'disabled' : ''); ?>

                                class="js-ajax-province_id js-state-selection form-control select2"
                                data-regions-parent="js-user-location"
                                data-map-zoom="8"
                            >
                                <option value=""><?php echo e(trans('update.choose_a_state')); ?></option>

                                <?php if(!empty($user) and !empty($user->country_id)): ?>
                                    <?php $__currentLoopData = \App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$province, 'country_id', $user->country_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($province->id); ?>" <?php echo e((!empty($user) and $user->province_id == $province->id) ? 'selected' : ''); ?> data-center="<?php echo e(implode(',', $province->geo_center)); ?>"><?php echo e($province->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label"><?php echo e(trans('update.city')); ?></label>

                            <select name="city_id"
                                    class="js-ajax-city_id js-city-selection form-control select2"
                                    <?php echo e((empty($user) or empty($user->province_id)) ? 'disabled' : ''); ?>

                                    data-regions-parent="js-user-location"
                                    data-map-zoom="12"
                            >
                                <option value=""><?php echo e(trans('update.choose_a_city')); ?></option>

                                <?php if(!empty($user) and !empty($user->province_id)): ?>
                                    <?php $__currentLoopData = \App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$city, 'province_id', $user->province_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city->id); ?>" <?php echo e((!empty($user) and $user->city_id == $city->id) ? 'selected' : ''); ?> data-center="<?php echo e(implode(',', $city->geo_center)); ?>"><?php echo e($city->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="form-group ">
                            <label class="form-group-label"><?php echo e(trans('update.district')); ?></label>

                            <select name="district_id"
                                    class="js-ajax-district_id js-district-selection form-control select2"
                                    <?php echo e((empty($user) or empty($user->city_id)) ? 'disabled' : ''); ?>

                                    data-regions-parent="js-user-location"
                                    data-map-zoom="15"
                            >
                                <option value=""><?php echo e(trans('update.all_districts')); ?></option>

                                <?php if(!empty($user) and !empty($user->city_id)): ?>
                                    <?php $__currentLoopData = \App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$district, 'city_id', $user->city_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($district->id); ?>" <?php echo e((!empty($user) and $user->district_id == $district->id) ? 'selected' : ''); ?> data-center="<?php echo e(implode(',', $district->geo_center)); ?>"><?php echo e($district->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-30">
                            <label class="form-group-label"><?php echo e(trans('update.address')); ?>:</label>
                            <input type="text" name="address" value="<?php echo e(!empty($user->address) ? $user->address : ''); ?>" class="form-control">
                        </div>

                    </div>

                    <?php
                        $latitude = getDefaultMapsLocation()['lat'];
                        $longitude = getDefaultMapsLocation()['lon'];

                        if(!empty($user->location)) {
                            $latitude = $user->location[0];
                            $longitude = $user->location[1];
                        }
                    ?>

                    <div class="col-12 col-lg-8">
                        <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.location_on_map')); ?></h3>

                        <input type="hidden" id="LocationLatitude" name="latitude" value="<?php echo e($latitude); ?>">
                        <input type="hidden" id="LocationLongitude" name="longitude" value="<?php echo e($longitude); ?>">

                        <div class="region-map with-default-initial-drag w-100 rounded-8 mt-16 bg-gray-100" id="mapBox"
                             data-latitude="<?php echo e($latitude); ?>"
                             data-longitude="<?php echo e($longitude); ?>"
                             data-zoom="12"
                             data-dragging="true"
                             data-zoomControl="true"
                             data-scrollWheelZoom="true"
                        >
                            <div class="map-dont-click-alert d-none align-items-center justify-content-center">
                                <div class="d-flex-center flex-column">
                                    <img src="/assets/design_1/img/spread-icon.png" height="96px">
                                    <span class="mt-12 font-20 text-white"><?php echo e(trans('update.instead_of_clicking_please_move_the_map')); ?></span>
                                </div>
                            </div>

                            <img src="/assets/default/img/location.png" class="marker" width="40" height="40">
                        </div>


                    </div>
                </div>
            </div>


            <?php
                $socials = getSocials();
                if (!empty($socials) and count($socials)) {
                    $socials = collect($socials)->sortBy('order')->toArray();
                }

                $userSocials = !empty($user->socials) ? json_decode($user->socials, true) : [];
            ?>

            <?php if(count($socials)): ?>
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.social_networks')); ?></h3>

                    <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialKey => $socialValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($socialValue['title'])): ?>
                            <div class="d-flex align-items-center gap-12 mt-24">
                                <div class="d-flex-center size-48 bg-gray-100 border-gray-300 rounded-12">
                                    <?php if(!empty($socialValue['image'])): ?>
                                        <img src="<?php echo e($socialValue['image']); ?>" alt="" class="img-fluid" width="24px" height="24px">
                                    <?php else: ?>
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-mobile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icon text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group mb-0 flex-1">
                                    <label class="form-group-label"><?php echo e($socialValue['title']); ?></label>
                                    <input type="text" name="socials[<?php echo e($socialKey); ?>]" value="<?php echo e((!empty($userSocials) and !empty($userSocials[$socialKey])) ? $userSocials[$socialKey] : ''); ?>" class="form-control">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="d-flex align-items-center mt-16 pt-16 border-top-gray-100">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icon text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <h4 class="font-14"><?php echo e(trans('update.note')); ?></h4>
                            <p class="font-12 text-gray-500"><?php echo e(trans('update.leave_social_media_links_blank_to_hide_them_on_the_front_side')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var leafletApiPath = '<?php echo e(getLeafletApiPath()); ?>';
        var selectRegionDefaultVal = '';
        var selectStateLang = '<?php echo e(trans('update.choose_a_state')); ?>';
        var selectCityLang = '<?php echo e(trans('update.choose_a_city')); ?>';
        var selectDistrictLang = '<?php echo e(trans('update.all_districts')); ?>';
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("leaflet_map")); ?>"></script>
    <script src="/assets/design_1/js/parts/get_regions.min.js"></script>

<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/settings/tabs/extra_information.blade.php ENDPATH**/ ?>