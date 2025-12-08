<div class="js-instructor-location instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">
    <h5 class="instructor-finder__filters-title font-14 font-weight-bold"><?php echo e(trans('update.location')); ?></h5>

    <div class="form-group  mt-24">
        <label class="form-group-label"><?php echo e(trans('update.country')); ?></label>

        <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-instructor-location" data-map-zoom="5">
            <option value=""><?php echo e(trans('update.choose_a_country')); ?></option>

            <?php if(!empty($countries)): ?>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($country->id); ?>" <?php echo e((request()->get('country_id') == $country->id) ? 'selected' : ''); ?>><?php echo e($country->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label"><?php echo e(trans('update.state')); ?></label>

        <select
            name="province_id"
            class="js-ajax-province_id js-state-selection form-control select2"
            data-regions-parent="js-instructor-location"
            data-map-zoom="8"
            <?php echo e(empty($provinces) ? 'disabled' : ''); ?>

        >
            <option value=""><?php echo e(trans('update.choose_a_state')); ?></option>

            <?php if(!empty($provinces)): ?>
                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($province->id); ?>" <?php echo e((request()->get('province_id') == $province->id) ? 'selected' : ''); ?>><?php echo e($province->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label"><?php echo e(trans('update.city')); ?></label>

        <select name="city_id"
                class="js-ajax-city_id js-city-selection form-control select2"
                data-regions-parent="js-instructor-location"
                data-map-zoom="12"
                <?php echo e(empty($cities) ? 'disabled' : ''); ?>

        >
            <option value=""><?php echo e(trans('update.choose_a_city')); ?></option>

            <?php if(!empty($cities)): ?>
                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($city->id); ?>" <?php echo e((request()->get('city_id') == $city->id) ? 'selected' : ''); ?>><?php echo e($city->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label"><?php echo e(trans('update.district')); ?></label>

        <select name="district_id"
                class="js-ajax-district_id js-district-selection form-control select2"
                data-regions-parent="js-instructor-location"
                data-map-zoom="15"
                <?php echo e(empty($districts) ? 'disabled' : ''); ?>

        >
            <option value=""><?php echo e(trans('update.all_districts')); ?></option>

            <?php if(!empty($districts)): ?>
                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($district->id); ?>" <?php echo e((request()->get('district_id') == $district->id) ? 'selected' : ''); ?>><?php echo e($district->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>

        <div class="invalid-feedback"></div>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/left_side/location.blade.php ENDPATH**/ ?>