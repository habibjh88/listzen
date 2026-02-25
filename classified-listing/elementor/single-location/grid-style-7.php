<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * @author   RadiusTheme
 *
 * Locationbox style.
 *
 * @package  Classifid-listing
 * @since    2.0.10
 * @version  1.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
/* translators: %s: number of ads */
$count_html = sprintf( _nx( '%s Ad', '%s Ads', $count, 'Number of Ads', 'listzen' ), number_format_i18n( $count ) );

$link_start   = $settings['enable_link'] ? '<a href="' . $permalink . '">' : '';
$link_end     = $settings['enable_link'] ? '</a>' : '';
$location_box = $settings['rtcl_location_style'] ? $settings['rtcl_location_style'] : ' style-1';
$class        = $settings['display_count'] ? ' rtin-has-count ' : '';
$class        .= ' location-box-style-3 location-box-style-4 location-box-style-5 location-box-' . $location_box;

?>
<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>">
    <div class="rtcl-image-wrapper">
		<?php echo wp_kses_post( $link_start ); ?>
        <div class="rtin-img"></div>
		<?php echo wp_kses_post( $link_end ); ?>
    </div>
</div>
