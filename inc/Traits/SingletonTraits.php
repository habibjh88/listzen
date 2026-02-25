<?php
/**
 *
 */

namespace Listzen\Traits;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * SingletonTraits trait for all singleton class
 */
trait SingletonTraits {
	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	final public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Prevent cloning.
	 */
	final public function __clone() {
	}

	// Prevent serialization of the instance
	public function __sleep() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'listzen' ), '1.0' );
		die();
	}


	/**
	 * Prevent unserializing.
	 */
	final public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'listzen' ), '1.0' );
		die();
	}
}
