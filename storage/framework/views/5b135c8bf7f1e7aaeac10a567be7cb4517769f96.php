<?php if((!empty($mapCenter) and is_array($mapCenter))): ?>
    <div class="position-relative instructor-finder__map-container bg-gray-200" id="instructorFinderPageMap"
         data-latitude="<?php echo e($mapCenter[0]); ?>"
         data-longitude="<?php echo e($mapCenter[1]); ?>"
         data-zoom="<?php echo e($mapZoom); ?>"
         data-dragging="true"
         data-zoomControl="false"
         data-scrollWheelZoom="true"
         data-zoomControlPosition="bottomleft"
    >
        
    </div>
<?php endif; ?>


<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/map.blade.php ENDPATH**/ ?>