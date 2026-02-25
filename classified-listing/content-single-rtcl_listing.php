<?php
/**
 * The template for displaying product content in the single-rtcl_listing.php template
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/content-single-rtcl_listing.php.
 *
 * @package ClassifiedListing/Templates
 * @version 1.5.56
 */


use Rtcl\Helpers\Functions;
use Listzen\Helpers\CLFns;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $listing;

if ( post_password_required() ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo get_the_password_form();

	return;
}

$listzen_sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
/**
 * Hook: rtcl_before_single_product.
 *
 * @hooked rtcl_print_notices - 10
 */
do_action( 'rtcl_before_single_listing' );
?>
<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( 'rtcl-single-listing-item', $listing ); ?>>

	<div class="listing-content">
		<div class="mb-4 rtcl-single-listing-details">

			<div class="rtcl-main-content-wrapper">

				<!-- Description -->
				<div class="rtcl-listing-description rtcl-content-section">
					<div class="rtcl-section-heading-simple">
						<h3><?php echo esc_html__( 'Description', 'listzen' ); ?></h3>
					</div>
					<div class="listing-desc-content">
						<?php $listing->the_content(); ?>
					</div>
				</div>

				<!--Custom Fields-->

				<?php do_action( 'rtcl_single_listing_inner_sidebar' ); ?>

				<?php do_action( 'rtcl_single_listing_services', $listing ); ?>

				<?php do_action( 'rtcl_single_listing_amenities', $listing ); ?>

				<?php do_action( 'rtcl_single_listing_food_menu', $listing ); ?>

				<?php do_action( 'rtcl_single_listing_repeater', $listing ); ?>

				<?php do_action( 'rtcl_single_listing_separate_video', $listing ); ?>

				<?php do_action( 'rtcl_single_listing_faqs', $listing ); ?>

				<?php if ( $listzen_sidebar_position === 'bottom' ) : ?>
					<!-- Sidebar -->
					<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
				<?php endif; ?>
			</div>
		</div>

		<!-- MAP -->
		<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>

		<!-- Business Hours  -->
		<?php
		if ( 'content' === CLFns::business_hour_pos() ) {
			do_action( 'rtcl_single_listing_business_hours' );
		}
		?>

		<?php do_action( 'listzen_listing_tags', $listing ); ?>

		<!-- Related Listing -->
		<?php $listing->the_related_listings(); ?>

		<!-- Review  -->
		<?php do_action( 'rtcl_single_listing_review' ); ?>

		<?php if ( ! Functions::is_enable_template_support() && in_array( $listzen_sidebar_position, [ 'left', 'right' ] ) ) : ?>
			<!-- Sidebar -->
			<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'rtcl_after_single_listing' ); ?>
