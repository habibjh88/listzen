<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

</div>
</div>  <!-- End wiper-wrapper -->
<?php if ( $instance['slider_nav'] ) { ?>
    <!-- If we need navigation buttons -->
    <span class="rtcl-slider-btn button-left rt-icon-arrow-small-left"></span>
    <span class="rtcl-slider-btn button-right rt-icon-arrow-small-right"></span>

<?php } ?>
<?php if ( $instance['slider_dots'] ) { ?>
    <!-- If we need pagination -->
    <div class="rtcl-slider-pagination"></div>
<?php } ?>
</div>
</div>
