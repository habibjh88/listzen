<?php
/**
 * Build Gutenberg Blocks
 *
 * @package listzen
 */

namespace Listzen\Api;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * Gutenberg class
 */
class Gutenberg {
	use SingletonTraits;

	/**
	 * Register default hooks and actions for WordPress
	 *
	 * @return WordPress add_action()
	 */
	public function __construct() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'gutenberg_init' ] );

	}

	/**
	 * Custom Gutenberg settings
	 * @return
	 */
	public function gutenberg_init() {
		add_theme_support( 'gutenberg', [
			// Theme supports responsive video embeds
			'responsive-embeds' => true,
			// Theme supports wide images, galleries and videos.
			'wide-images'       => true,
		] );

		$primary_color     = listzen_option( 'listzen_primary_color', '#FA350C' );
		$secondary_color   = listzen_option( 'listzen_secondary_color', '#111111' );

		add_theme_support( 'editor-color-palette', [
			[
				'name' => esc_html__( 'Primary Color', 'listzen' ),
				'slug' => 'listzen-primary',
				'color' => $primary_color,
			],
			[
				'name' => esc_html__( 'Secondary Color', 'listzen' ),
				'slug' => 'listzen-secondary',
				'color' => $secondary_color,
			],
			[
				'name' => esc_html__( 'Yellow Color', 'listzen' ),
				'slug' => 'listzen-yellow',
				'color' => '#F9BA19',
			],
			[
				'name' => esc_html__( 'Dark gray', 'listzen' ),
				'slug' => 'listzen-dark-gray',
				'color' => '#696969',
			],
			[
				'name' => esc_html__( 'light gray', 'listzen' ),
				'slug' => 'listzen-light-gray',
				'color' => '#f8f8f7',
			],
			[
				'name' => esc_html__( 'white', 'listzen' ),
				'slug' => 'listzen-white',
				'color' => '#ffffff',
			],
		] );

		add_theme_support( 'editor-font-sizes', [
			[
				'name' => esc_html__( 'Small', 'listzen' ),
				'size' => 16,
				'slug' => 'small'
			],
			[
				'name' => esc_html__( 'Normal', 'listzen' ),
				'size' => 24,
				'slug' => 'normal'
			],
			[
				'name' => esc_html__( 'Large', 'listzen' ),
				'size' => 36,
				'slug' => 'large'
			],
			[
				'name' => esc_html__( 'Huge', 'listzen' ),
				'size' => 44,
				'slug' => 'huge'
			]
		] );
	}
}
