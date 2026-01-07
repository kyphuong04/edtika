<tr>
    <td class="text-left">
        <?php if(!empty($sale->buyer)): ?>
            <div class="user-inline-avatar d-flex align-items-center">
                <div class="size-48 bg-gray-200 rounded-circle">
                    <img src="<?php echo e($sale->buyer->getAvatar()); ?>" class="js-avatar-img img-cover rounded-circle" alt="">
                </div>

                <div class=" ml-8">
                    <span class="d-block"><?php echo e($sale->buyer->full_name); ?></span>
                    <span class="mt-4 font-12 text-gray-500 d-block"><?php echo e($sale->buyer->email); ?></span>
                </div>
            </div>
        <?php else: ?>
            <span class="text-danger"><?php echo e(trans('update.deleted_user')); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-left">
        <div class="text-left">
            <?php
                $content = trans('update.deleted_item');
                $contentId = null;

                if(!empty($sale->webinar)) {
                    $content = $sale->webinar->title;
                    $contentId =$sale->webinar->id;
                } elseif(!empty($sale->bundle)) {
                    $content = $sale->bundle->title;
                    $contentId =$sale->bundle->id;
                } elseif(!empty($sale->productOrder) and !empty($sale->productOrder->product)) {
                    $content = $sale->productOrder->product->title;
                    $contentId =$sale->productOrder->product->id;
                } elseif(!empty($sale->registrationPackage)) {
                    $content = $sale->registrationPackage->title;
                    $contentId =$sale->registrationPackage->id;
                } elseif(!empty($sale->subscribe)) {
                    $content = $sale->subscribe->title;
                    $contentId =$sale->subscribe->id;
                } elseif(!empty($sale->promotion)) {
                    $content = $sale->promotion->title;
                    $contentId =$sale->promotion->id;
                } elseif (!empty($sale->meeting_id)) {
                    $content = trans('meeting.reservation_appointment');
                }
            ?>

            <span class="d-block"><?php echo e($content); ?></span>

            <?php if(!empty($contentId)): ?>
                <span class="d-block font-12 text-gray-500">Id: <?php echo e($contentId); ?></span>
            <?php endif; ?>
        </div>
    </td>

    <td class="text-center">
        <?php if($sale->payment_method == \App\Models\Sale::$subscribe): ?>
            <span class=""><?php echo e(trans('financial.subscribe')); ?></span>
        <?php else: ?>
            <span><?php echo e(!empty($sale->amount) ? handlePrice($sale->amount) : '-'); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-center"><?php echo e(!empty($sale->discount) ? handlePrice($sale->discount) : '-'); ?></td>

    <td class="text-center">
        <?php if($sale->payment_method == \App\Models\Sale::$subscribe): ?>
            <span class=""><?php echo e(trans('financial.subscribe')); ?></span>
        <?php else: ?>
            <span><?php echo e(!empty($sale->total_amount) ? handlePrice($sale->total_amount) : '-'); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-center">
        <span><?php echo e(!empty($sale->getIncomeItem()) ? handlePrice($sale->getIncomeItem()) : '-'); ?></span>
    </td>

    <td class="text-center">
        <?php switch($sale->type):
            case (\App\Models\Sale::$webinar): ?>
                <?php if(!empty($sale->webinar)): ?>
                    <span><?php echo e(trans('webinars.'.$sale->webinar->type)); ?></span>
                <?php else: ?>
                    <span><?php echo e(trans('update.class')); ?></span>
                <?php endif; ?>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$meeting): ?>
                <span><?php echo e(trans('meeting.appointment')); ?></span>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$subscribe): ?>
                <span><?php echo e(trans('financial.subscribe')); ?></span>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$promotion): ?>
                <span><?php echo e(trans('panel.promotion')); ?></span>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$registrationPackage): ?>
                <span><?php echo e(trans('update.registration_package')); ?></span>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$bundle): ?>
                <span><?php echo e(trans('update.bundle')); ?></span>
                <?php break; ?>;
            <?php case (\App\Models\Sale::$product): ?>
                <span><?php echo e(trans('update.product')); ?></span>
                <?php break; ?>;
        <?php endswitch; ?>
    </td>

    <td class="text-center">
        <span><?php echo e(dateTimeFormat($sale->created_at, 'j M Y H:i')); ?></span>
    </td>

</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/sales/table_items.blade.php ENDPATH**/ ?>