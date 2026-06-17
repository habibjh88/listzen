<?php

namespace Listzen\Helpers;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Constants {

	const LISTZEN_VERSION = '1.0.2';

	const EDD_STORE_URL    = 'https://www.radiustheme.com';
	const EDD_ITEM_ID      = 298869;
	const EDD_ITEM_NAME    = 'Listzen';
	const EDD_AUTHOR       = 'RadiusTheme';
	const LICENSE_OPTION   = 'listzen_license_data';

	public static function get_version() {
		return WP_DEBUG ? time() : self::LISTZEN_VERSION;
	}

	/**
	 * EDD item id for the theme. Override via the
	 * `listzen_edd_item_id` filter or by defining the
	 * LISTZEN_EDD_ITEM_ID constant in wp-config.php.
	 *
	 * @return int
	 */
	public static function edd_item_id() {
		$item_id = defined( 'LISTZEN_EDD_ITEM_ID' ) ? (int) LISTZEN_EDD_ITEM_ID : self::EDD_ITEM_ID;

		return (int) apply_filters( 'listzen_edd_item_id', $item_id );
	}
}