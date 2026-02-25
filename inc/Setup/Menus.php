<?php

namespace Listzen\Setup;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * Menus Class
 */
class Menus {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'menus' ] );
	}

	/**
	 * Register Menu
	 *
	 * @return void
	 */
	public function menus() {
		/*
			Register all your menus here
		*/
		register_nav_menus( [
			'primary' => esc_html__( 'Primary', 'listzen' ),
			'topbar'  => esc_html__( 'Topbar Menu', 'listzen' ),
		] );
	}
}
