<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 * @var Listing $listing
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use \Listzen\Helpers\CLFns;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! $listing ) {
	global $listing;
}

if ( empty( $listing ) ) {
	return;
}

if ( ! $listing->can_show_date() && ! $listing->can_show_user() && ! $listing->can_show_category() && ! $listing->can_show_location() && ! $listing->can_show_views() ) {
	return;
}
?>

<ul class="rtcl-listing-meta-data">
	<?php
	if ( $listing->can_show_ad_type() ) :
		$listing_types = Functions::get_listing_types();
		$types = ! empty( $listing_types ) && isset( $listing_types[ $listing->get_ad_type() ] ) ? $listing_types[ $listing->get_ad_type() ] : '';
		if ( $types ) {
			?>
            <li class="ad-type"><i class="rtcl-icon rt-icon-tags"></i>&nbsp;<?php echo esc_html( $types ); ?></li>
		<?php } ?>
	<?php endif; ?>
	<?php if ( $listing->can_show_date() ) : ?>
        <li class="updated"><i class="rtcl-icon rt-icon-clock"></i>&nbsp;<?php $listing->the_time(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_user() ) : ?>
        <li class="author">
            <i class="rtcl-icon rt-icon-user-3"></i>
			<?php esc_html_e( 'by ', 'listzen' ); ?>
			<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                <a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
			<?php else : ?>
				<?php $listing->the_author(); ?>
			<?php endif; ?>
			<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
        </li>
	<?php endif; ?>
	<?php
	if ( $listing->has_category() && $listing->can_show_category() ) :
		$categories = $listing->get_categories();
		if ( ! empty( $categories ) ) {
			?>
            <li class="rt-categories">
				<?php
				foreach ( $categories as $category ) {
					if ( ! empty( $glue ) ) {
						listzen_html( $glue );
					}
					//$cat_image   = get_term_meta( $category->term_id, '_rtcl_image', true );
					$cat_icon    = get_term_meta( $category->term_id, '_rtcl_icon', true );
					$_icon_class = $cat_icon ?: ' rt-icon-folder-open';
					?>
                    <a class="listzen-term" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
						<?php printf( "<i class='rtcl-icon rtcl-icon-%s'></i>", esc_attr( $_icon_class ) ); ?>
						<?php echo esc_html( $category->name ); ?>
                    </a>
					<?php
					$glue = '<span class="rtcl-delimiter">,</span>';
				}
				?>
            </li>
		<?php } endif; ?>
	<?php
	if ( $listing->has_location() && $listing->can_show_location() ) :
		?>
        <li class="rt-location">
            <i class="rtcl-icon rt-icon-marker"></i>
			<?php $listing->the_locations( true, true ); ?>
        </li>
	<?php endif; ?>
	<?php if ( $listing->can_show_views() ) : ?>
        <li class="rt-views">
            <i class="rtcl-icon rt-icon-eye"> </i>
			<?php echo esc_html( sprintf( /* translators: View count */ _n( '%s view', '%s views', $listing->get_view_counts(), 'listzen' ), esc_html( number_format_i18n( $listing->get_view_counts() ) ) ) ); ?>
        </li>
	<?php endif; ?>

	<?php if ( CLFns::can_show_phone_number() ) :
		$phone = get_post_meta( $listing->get_id(), 'phone', true );
		?>
        <li class="rt-views">
            <i class="rtcl-icon rt-icon-phone"></i>
			<?php printf( '<a href="tel:%s">%s</a>', esc_attr( $phone ), esc_html( $phone ) ); ?>
        </li>
	<?php endif; ?>
</ul>
