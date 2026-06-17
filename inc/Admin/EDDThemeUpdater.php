<?php
/**
 * EDD Theme Updater Class.
 *
 * @package Listzen
 */

namespace Listzen\Admin;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//phpcs:disable WordPress.Security.NonceVerification.Recommended

/**
 * EDD Theme Updater.
 *
 * Talks to the EDD Software Licensing API on radiustheme.com and
 * injects update information into WordPress' theme update transient
 * so the standard Themes page picks up new releases.
 *
 * Adapted from EDD Software Licensing's reference theme updater so the
 * licensing flow stays compatible with The Post Grid Pro.
 */
class EDDThemeUpdater {

	/**
	 * Remote EDD endpoint.
	 *
	 * @var string
	 */
	private $api_url = '';

	/**
	 * API data sent on every request.
	 *
	 * @var array
	 */
	private $api_data = [];

	/**
	 * Current theme slug (template directory name).
	 *
	 * @var string
	 */
	private $theme_slug = '';

	/**
	 * Current installed version.
	 *
	 * @var string
	 */
	private $version = '';

	/**
	 * Cache key for stored remote version info.
	 *
	 * @var string
	 */
	private $cache_key = '';

	/**
	 * Constructor.
	 *
	 * @param string $api_url  EDD store URL.
	 * @param array  $api_data Per-theme data: theme_slug, version, license, item_id, item_name, author, url, beta.
	 */
	public function __construct( $api_url, $api_data = [] ) {
		$this->api_url    = trailingslashit( $api_url );
		$this->api_data   = (array) $api_data;
		$this->theme_slug = ! empty( $api_data['theme_slug'] ) ? sanitize_key( $api_data['theme_slug'] ) : get_template();
		$this->version    = ! empty( $api_data['version'] ) ? (string) $api_data['version'] : '';
		$beta             = ! empty( $api_data['beta'] );
		$license          = ! empty( $api_data['license'] ) ? (string) $api_data['license'] : '';
		$this->cache_key  = 'listzen_edd_theme_' . md5( $this->theme_slug . $license . ( $beta ? '1' : '0' ) );

		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_update' ] );
		add_filter( 'themes_api', [ $this, 'themes_api_filter' ], 10, 3 );
		add_filter( 'http_request_args', [ $this, 'http_request_args' ], 5, 2 );
	}

	/**
	 * Inject our update info into the update_themes transient.
	 *
	 * @param object $transient Transient value.
	 *
	 * @return object
	 */
	public function check_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			$transient = new stdClass();
		}

		if ( ! empty( $transient->response ) && ! empty( $transient->response[ $this->theme_slug ] ) ) {
			return $transient;
		}

		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {
			$version_info = $this->api_request(
				'get_version',
				[
					'slug' => $this->theme_slug,
					'beta' => ! empty( $this->api_data['beta'] ),
				]
			);
			$this->set_cached_version_info( $version_info );
		}

		if ( ! is_object( $version_info ) || empty( $version_info->new_version ) ) {
			return $transient;
		}

		if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {
			$transient->response[ $this->theme_slug ] = [
				'theme'       => $this->theme_slug,
				'new_version' => $version_info->new_version,
				'url'         => isset( $version_info->homepage ) ? $version_info->homepage : '',
				'package'     => isset( $version_info->package ) ? $version_info->package : ( isset( $version_info->download_link ) ? $version_info->download_link : '' ),
			];
		} else {
			$transient->checked[ $this->theme_slug ] = $this->version;
		}

		return $transient;
	}

	/**
	 * Provide theme details when the user clicks "View version x details".
	 *
	 * @param false|object|array $result Result.
	 * @param string             $action API action.
	 * @param object             $args   Args.
	 *
	 * @return mixed
	 */
	public function themes_api_filter( $result, $action, $args ) {
		if ( 'theme_information' !== $action ) {
			return $result;
		}

		if ( empty( $args->slug ) || $args->slug !== $this->theme_slug ) {
			return $result;
		}

		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {
			$version_info = $this->api_request(
				'get_version',
				[
					'slug' => $this->theme_slug,
					'beta' => ! empty( $this->api_data['beta'] ),
				]
			);
			$this->set_cached_version_info( $version_info );
		}

		if ( ! is_object( $version_info ) ) {
			return $result;
		}

		if ( isset( $version_info->sections ) && ! is_array( $version_info->sections ) ) {
			$sections = [];
			foreach ( (array) $version_info->sections as $key => $value ) {
				$sections[ $key ] = $value;
			}
			$version_info->sections = $sections;
		}

		return $version_info;
	}

	/**
	 * Allow self-signed SSL for the EDD package download.
	 *
	 * @param array  $args HTTP request args.
	 * @param string $url  Request URL.
	 *
	 * @return array
	 */
	public function http_request_args( $args, $url ) {
		if ( false !== strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = $this->verify_ssl();
		}
		return $args;
	}

	/**
	 * Call the EDD API.
	 *
	 * @param string $action API action.
	 * @param array  $data   Additional data.
	 *
	 * @return false|object
	 */
	private function api_request( $action, $data = [] ) {
		if ( trailingslashit( home_url() ) === $this->api_url ) {
			return false;
		}

		$body = array_merge(
			[
				'edd_action' => $action,
				'license'    => ! empty( $this->api_data['license'] ) ? $this->api_data['license'] : '',
				'item_name'  => ! empty( $this->api_data['item_name'] ) ? $this->api_data['item_name'] : '',
				'item_id'    => ! empty( $this->api_data['item_id'] ) ? $this->api_data['item_id'] : '',
				'version'    => $this->version,
				'slug'       => $this->theme_slug,
				'author'     => ! empty( $this->api_data['author'] ) ? $this->api_data['author'] : '',
				'url'        => home_url(),
				'beta'       => ! empty( $this->api_data['beta'] ),
			],
			$data
		);

		$request = wp_remote_post(
			$this->api_url,
			[
				'timeout'   => 15,
				'sslverify' => $this->verify_ssl(),
				'body'      => $body,
			]
		);

		if ( is_wp_error( $request ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $request ) );

		if ( ! is_object( $response ) ) {
			return false;
		}

		if ( isset( $response->sections ) ) {
			$response->sections = maybe_unserialize( $response->sections );
		}

		return $response;
	}

	/**
	 * Get cached version info.
	 *
	 * @return false|object
	 */
	private function get_cached_version_info() {
		$cache = get_option( $this->cache_key );

		if ( empty( $cache['timeout'] ) || time() > (int) $cache['timeout'] ) {
			return false;
		}

		$value = json_decode( $cache['value'] );

		return is_object( $value ) ? $value : false;
	}

	/**
	 * Persist remote version info for 3 hours.
	 *
	 * @param mixed $value Value to cache.
	 *
	 * @return void
	 */
	private function set_cached_version_info( $value ) {
		update_option(
			$this->cache_key,
			[
				'timeout' => time() + ( 3 * HOUR_IN_SECONDS ),
				'value'   => wp_json_encode( $value ),
			],
			false
		);
	}

	/**
	 * Allow filtering whether to verify SSL.
	 *
	 * @return bool
	 */
	private function verify_ssl() {
		return (bool) apply_filters( 'listzen_edd_sl_verify_ssl', true, $this );
	}
}
