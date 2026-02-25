<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @author        RadiusTheme
 * @version       1.0.0
 */

use Rtcl\Helpers\Functions;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="rtcl rtcl-categories-elementor rt-el-listing-cat-box-3">
	<?php
	$classes = '';
	$i       = 0;
	foreach ( $terms as $trm ) {
		$count = 0;
		if ( ! empty( $settings['rtcl_hide_empty'] ) || ! empty( $settings['rtcl_show_count'] ) ) {
			$count = Functions::get_listings_count_by_taxonomy(
				$trm->term_id,
				rtcl()->category,
				! empty( $settings['rtcl_pad_counts'] ) ? 1 : 0
			);

			if ( ! empty( $settings['rtcl_hide_empty'] ) && 0 == $count ) {
				continue;
			}
		}


		$view_post = sprintf(
		/* translators: %s: Category term */
			__( 'View all posts in %s', 'listzen' ),
			$trm->name
		);

		printf(
			"<div class='cat-item-wrap'><div class='cat-details'><h3><a class='rtcl-listing-category' href='%s' title='%s'>%s</a></h3></div></div>",
			esc_url( get_term_link( $trm ) ),
			esc_attr( $view_post ),
			esc_html( $trm->name )
		);
	}
	?>

</div>
