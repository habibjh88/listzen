<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace Listzen\Plugins;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;
use Jetpack;

/**
 * ThemeJetpack Class
 */
class ThemeJetpack {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		if ( ! class_exists( 'Jetpack' ) ) {
			return;
		}

		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		add_filter( 'jetpack_photon_pre_args', [ $this, 'photon_compression' ] );

	}

	/**
	 * ThemeJetpack Setup
	 *
	 * @return void
	 */
	public function setup() {

		// Add theme support for Infinite Scroll.
		add_theme_support( 'infinite-scroll', [
			'container' => 'main',
			'render'    => [ $this, 'infinite_scroll_render' ],
			'footer'    => 'page',
		] );

		// Add theme support for Responsive Videos.
		add_theme_support( 'jetpack-responsive-videos' );
	}

	/**
	 * ThemeJetpack Infinite Scroll
	 *
	 * @return void
	 */
	public function infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				get_template_part( 'template-parts/content', 'search' );
			else :
				get_template_part( 'template-parts/content', get_post_format() );
			endif;
		}
	}

	/**
	 * Photon Compression
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function photon_compression( $args ) {
		$args['quality'] = 100;
		$args['strip']   = 'all';

		return $args;
	}
}
