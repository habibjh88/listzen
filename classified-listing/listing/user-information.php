<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var Listing $listing
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Rtcl\Models\Listing;

?>
<div class="rtcl-listing-user-button-group rtcl-listing-user-info">
    <div class="rtcl-list-group">
		<?php do_action( 'rtcl_single_listing_button_group', $listing ); ?>
    </div>
</div>
<div class="rtcl-listing-user-info">
    <div class="listzen-section-title">
        <div class="main-title-wrap"><h3 class="main-title"><?php esc_html_e( "Information", 'listzen' ); ?></h3></div>
    </div>
    <div class="rtcl-list-group">
		<?php do_action( 'rtcl_listing_seller_information', $listing ); ?>
    </div>
</div>

