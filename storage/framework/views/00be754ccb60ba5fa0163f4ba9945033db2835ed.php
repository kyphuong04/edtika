<button class="d-flex align-items-center btn-transparent gap-4 <?php echo e($btnClass ?? ''); ?>"
        data-confirm="<?php echo e($deleteConfirmMsg ?? trans('admin/main.delete_confirm_msg')); ?>"
        data-confirm-href="<?php echo e($url); ?>"
        data-confirm-text-yes="<?php echo e(trans('admin/main.yes')); ?>"
        data-confirm-text-cancel="<?php echo e(trans('admin/main.cancel')); ?>"
        <?php if(empty($btnText)): ?>
            data-toggle="tooltip" data-placement="top" title="<?php echo e(!empty($tooltip) ? $tooltip : trans('admin/main.delete')); ?>"
    <?php endif; ?>
>
    <?php if(!empty($btnIcon)): ?>
        <?php
            $btnIconType = (!empty($iconType) and in_array($iconType, ['lin', 'bol', 'bul'])) ? $iconType : 'lin';
            $btnIconClass = !empty($iconClass) ? $iconClass : 'text-black';
        ?>

        <?php echo e(svg("iconsax-{$btnIconType}-{$btnIcon}", ['width' => '18px', 'height' => '18px', 'class' => "icons {$btnIconClass}"])); ?>
    <?php endif; ?>

    <span class=""><?php echo $btnText ?? ''; ?></span>
</button>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/includes/delete_button.blade.php ENDPATH**/ ?>