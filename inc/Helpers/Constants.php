<?php

namespace Listzen\Helpers;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Constants {

	const LISTZEN_VERSION = '1.0.3';

	public static function get_version() {
		return WP_DEBUG ? time() : self::LISTZEN_VERSION;
	}
}