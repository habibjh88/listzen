<?php

namespace Listzen\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Helpers\Constants;
use Listzen\Helpers\Fns;
use Listzen\Traits\SingletonTraits;

/**
 * Sidebar Class
 */
class Sidebar {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	/**
	 * widgets init
	 *
	 * @return void
	 */
	public function widgets_init() {
		foreach ( Fns::default_sidebar() as $sidebar ) {
			$classes = $sidebar['class'] ?? '';

			register_sidebar( [
				'id'            => $sidebar['id'],
				'name'          => $sidebar['name'],
				'description'   => $sidebar['description'] ?? '',
				'before_widget' => '<section id="%1$s" class="widget ' . $classes . ' %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			] );
		}
	}
}
