<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace Listzen\Shortcode;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * ThemeJetpack Class
 */
class Shortcode {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_shortcode( 'listzen_listing_header', [$this, 'listzen_listing_header_shortcode'] );
	}

	public function listzen_listing_header_shortcode() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>
			<div class="rtcl-listing-gallery-top-wrapper">
				<?php
				do_action( 'rtcl_single_listing_title' );
				do_action( 'rtcl_single_listing_gallery' );
				?>
			</div>
			<?php
			return ob_get_clean();
		}

		return '';
	}
}
