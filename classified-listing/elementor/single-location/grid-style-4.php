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
$class        .= ' location-box-style-3 location-box-' . $location_box;

?>
<div class="rtcl-el-listing-location-box location-box-pro <?php echo esc_attr( $class ); ?>">
    <div class="rtcl-image-wrapper">
		<?php echo wp_kses_post( $link_start ); ?>
        <div class="rtin-img"></div>
		<?php echo wp_kses_post( $link_end ); ?>
    </div>

    <div class="rtin-content">

        <h3 class="rtin-title">
			<?php
			if ( $settings['enable_link'] ) {
				?>
                <a href="<?php echo esc_url( $permalink ); ?>">
					<?php echo esc_html( $title ); ?>
                </a>
				<?php
			} else {
				echo esc_html( $title );
			}
			?>
        </h3>

	    <?php if ( $settings['display_count'] ) :
		    $ads_text = null;
		    if ( ! empty( $settings['display_text_after_count'] ) ) {
			    $ads_text = $settings['display_text_after_count'];
		    }
		    printf( "<div class='rtin-counter'>%d <span class='ads-count'>%s</span></div>", absint( $count ), esc_html( $ads_text ) );
	    endif; ?>

		<?php if ( $settings['enable_link'] ) { ?>
            <a href="<?php echo esc_url( $permalink ); ?>">
                <svg width="40" height="23" viewBox="0 0 40 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M27.4553 0.813466C27.9261 0.342689 28.671 0.313541 29.176 0.725576L29.2737 0.813466L39.2737 10.8135C39.5148 11.0546 39.6506 11.3817 39.6506 11.7226C39.6506 12.0636 39.5148 12.3907 39.2737 12.6318L29.2737 22.6318C28.7716 23.1339 27.9574 23.1339 27.4553 22.6318C26.9532 22.1297 26.9532 21.3156 27.4553 20.8135L35.26 13.0088L1.3645 13.0088C0.654422 13.0088 0.0783691 12.4327 0.0783691 11.7226C0.0783691 11.0126 0.654422 10.4365 1.3645 10.4365L35.26 10.4365L27.4553 2.63183L27.3674 2.53417C26.9554 2.02917 26.9845 1.28424 27.4553 0.813466Z" fill="currentColor"/>
                </svg>
            </a>
		<?php } ?>
    </div>
</div>
