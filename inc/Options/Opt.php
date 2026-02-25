<?php

namespace Listzen\Options;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Api\Customizer;
use Listzen\Traits\SingletonTraits;

/**
 * Options class
 * Store all data though of this class
 */
class Opt {

	use SingletonTraits;

	// Sitewide static variables
	public static $options = null;
	public static $layout = null;
	public static $sidebar = null;
	public static $header_style = null;

	public static $nav_drawer_style = null;
	public static $topbar_style = null;
	public static $footer_style = null;
	public static $footer_schema = null;
	public static $banner_style = null;
	public static $has_banner = null;
	public static $has_breadcrumb = null;
	public static $breadcrumb_title = null;
	public static $banner_image = '';
	public static $banner_color;
	public static $banner_height = '';
	public static $header_width = null;
	public static $menu_alignment = null;
	public static $padding_top = null;
	public static $padding_bottom = null;
	public static $has_tr_header;
	public static $header_tr_color;
	public static $has_top_bar;
	public static $single_style;
	public static $pagebgimg;
	public static $pagebgcolor;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'set_options' ], 99999 );
		add_action( 'customize_preview_init', [ $this, 'set_options' ] );
	}

	/**
	 * Set Options Data
	 *
	 * @return void
	 */
	public function set_options() {
		$newData  = [];
		$defaults = Customizer::$default_value;
		$this->reset_option( $defaults );
		foreach ( $defaults as $key => $dValue ) {
			//$value = $_GET[ $key ] ?? get_theme_mod( $key, $dValue );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_GET[ $key ] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$value = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
			} else {
				$value = get_theme_mod( $key, $dValue );
			}
			$newData[ $key ] = $value;
		}
		self::$options = $newData;
	}

	/**
	 * Reset Options Data
	 *
	 * @param $defaults
	 *
	 * @return void
	 */
	public function reset_option( $defaults ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$reset_theme_mod = ! empty( $_GET['reset_theme_mod'] ) ? sanitize_text_field( wp_unslash( $_GET['reset_theme_mod'] ) ) : false;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$reset_key = ! empty( $_GET['reset_key'] ) ? sanitize_text_field( wp_unslash( $_GET['reset_key'] ) ) : '';

		//Single options remove
		if ( $reset_key ) {
			remove_theme_mod( $reset_key );
			wp_safe_redirect( 'customize.php' );
		}

		//All options remove
		if ( $reset_theme_mod == 1 ) {
			foreach ( $defaults as $key => $dValue ) {
				remove_theme_mod( $key );
				wp_safe_redirect( 'customize.php' );
			}
		}
	}

}

