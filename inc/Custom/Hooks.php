<?php

namespace Listzen\Custom;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Modules\IconList;
use Listzen\Traits\SingletonTraits;

/**
 * Hooks Class
 */
class Hooks {

	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'meta_css' ] );
		add_filter( 'wp_kses_allowed_html', [ __CLASS__, 'custom_wpkses_post_tags' ], 10, 2 );
		add_action( 'wp_footer', [ __CLASS__, 'wp_footer_hook' ] );
		add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'elementor_icon_support' ] );
		add_filter('rtcl_verification_front_script', '__return_true' );
	}

	/**
	 * Single post meta visibility
	 *
	 * @param $screen
	 *
	 * @return void
	 */
	public static function meta_css( $screen ) {
		if ( 'post.php' !== $screen ) {
			return;
		}
		global $typenow;
		$display = 'post' === $typenow ? 'table-row' : 'none';
		echo '<style>.single_post_style {display: ' . esc_attr( $display ) . '}</style>';
	}


	/**
	 * Add exceptions in wp_kses_post tags.
	 *
	 * @param array $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 */
	public static function custom_wpkses_post_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = [
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			];

			$tags['svg'] = [
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'stroke'          => true,
				'fill'            => true,
			];

			$tags['g']     = [ 'fill' => true ];
			$tags['title'] = [ 'title' => true ];
			$tags['path']  = [
				'class'           => true,
				'd'               => true,
				'fill'            => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
				'fill-rule'       => true,
				'clip-rule'       => true,
				'stroke'          => true,
			];
		}

		return $tags;
	}

	/**
	 * push style in wp_footer
	 *
	 * @return void
	 */
	public static function wp_footer_hook() {
		echo '<style>.listzen-header-footer .site-header {opacity: 1}</style>';
	}

	/**
	 * Add support for custom Fontello icon set in Elementor icon manager.
	 *
	 * Registers a new icon tab with Raw Icons.
	 *
	 * @param array $tabs Existing icon manager tabs.
	 *
	 * @return array Modified icon manager tabs including Raw Icons.
	 * @since 1.0
	 */
	public function elementor_icon_support( $tabs = [] ): array {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$tabs['rt-icons'] = [
				'name'          => 'rt-icons',
				'label'         => esc_html__( 'RT Icons', 'listzen' ),
				'labelIcon'     => 'fab fa-elementor',
				'prefix'        => '',
				'displayPrefix' => '',
				'url'           => get_template_directory_uri() . '/assets/fonts/rt-icons.css',
				'icons'         => IconList::get_icons(),
				'ver'           => '1.0',
			];
		}

		return $tabs;

	}

}
