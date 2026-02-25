<?php

namespace Listzen\Custom;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

class Ajax {
	use SingletonTraits;

	/**
	 * Class Construct
	 */
	public function __construct() {
		//add_action( 'wp_ajax_toggle_bookmark', [ $this, 'handle_toggle_bookmark' ] );

	}


}