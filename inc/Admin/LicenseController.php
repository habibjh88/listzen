<?php
/**
 * License Controller Class.
 *
 * @package Listzen
 */

namespace Listzen\Admin;

use Listzen\Helpers\Constants;
use Listzen\Traits\SingletonTraits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//phpcs:disable WordPress.Security.NonceVerification.Recommended

/**
 * License Controller Class.
 *
 * Handles theme license activation/deactivation via the
 * EDD Software Licensing API. Mirrors the implementation
 * used in The Post Grid Pro so that updates can be served
 * from radiustheme.com using the same workflow.
 */
class LicenseController {

	use SingletonTraits;

	const MENU_SLUG = 'listzen-license';

	const NONCE_ACTION = 'listzen_license_nonce';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'admin_init', [ $this, 'init_updater' ] );
		add_action( 'wp_ajax_listzen_manage_license', [ $this, 'manage_license' ] );
		add_action( 'in_admin_header', [ $this, 'suppress_notices_on_license_page' ], 1000 );
		add_action( 'admin_notices', [ $this, 'dashboard_license_notice' ] );
	}

	/**
	 * Strip every queued admin notice on the License screen so the
	 * panel UI is the only thing the user sees.
	 *
	 * @return void
	 */
	public function suppress_notices_on_license_page() {
		if ( ! $this->is_license_screen() ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'network_admin_notices' );
	}

	/**
	 * Show a dismiss-free notice on the Dashboard when the license
	 * is missing or inactive.
	 *
	 * @return void
	 */
	public function dashboard_license_notice() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'dashboard' !== $screen->id ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->is_license_active() ) {
			return;
		}

		$url = admin_url( 'themes.php?page=' . self::MENU_SLUG );
		?>
		<div class="notice notice-warning listzen-license-dashboard-notice">
			<p>
				<?php
				printf(
					/* translators: 1: opening anchor tag, 2: closing anchor tag */
					esc_html__( 'The Listzen theme license is not activated. %1$sActivate your license%2$s to receive automatic updates and premium support.', 'listzen' ),
					'<a href="' . esc_url( $url ) . '"><strong>',
					'</strong></a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Are we currently on the License admin screen?
	 *
	 * @return bool
	 */
	private function is_license_screen() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}
		$screen = get_current_screen();
		return $screen && 'appearance_page_' . self::MENU_SLUG === $screen->id;
	}

	/**
	 * Register the License page under Appearance.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_theme_page(
			esc_html__( 'Theme License', 'listzen' ),
			esc_html__( 'License', 'listzen' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Render the license admin page.
	 *
	 * @return void
	 */
	public function render_page() {
		$template = get_template_directory() . '/inc/Admin/views/license-panel.php';
		?>
		<div class="wrap listzen-license-wrap">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Theme License', 'listzen' ); ?></h1>
			<?php
			if ( file_exists( $template ) ) {
				include $template;
			}
			?>
		</div>
		<?php
	}

	/**
	 * Enqueue license assets only on the license screen.
	 *
	 * @param string $hook Current admin page hook.
	 *
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( 'appearance_page_' . self::MENU_SLUG !== $hook ) {
			return;
		}

		$ver = Constants::get_version();

		wp_enqueue_style(
			'listzen-license',
			get_template_directory_uri() . '/assets/css/license.css',
			[],
			$ver
		);

		wp_enqueue_script(
			'listzen-license',
			get_template_directory_uri() . '/assets/js/license.js',
			[ 'jquery' ],
			$ver,
			true
		);

		wp_localize_script(
			'listzen-license',
			'listzenLicense',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
				'i18n'    => [
					'activated'         => esc_html__( 'Activated', 'listzen' ),
					'notActivated'      => esc_html__( 'Not Activated', 'listzen' ),
					'activating'        => esc_html__( 'Activating…', 'listzen' ),
					'deactivating'      => esc_html__( 'Deactivating…', 'listzen' ),
					'emptyKey'          => esc_html__( 'Please enter your license key.', 'listzen' ),
					'networkError'      => esc_html__( 'Network error. Please try again.', 'listzen' ),
					'unexpected'        => esc_html__( 'Unexpected response. Please try again.', 'listzen' ),
					'expiresLabel'      => esc_html__( 'Expires:', 'listzen' ),
					'lifetime'          => esc_html__( 'Lifetime license', 'listzen' ),
					'confirmDeactivate' => esc_html__( 'Do you want to deactivate your license from this site?', 'listzen' ),
				],
			]
		);
	}

	/**
	 * Boot the EDD theme updater on every admin request.
	 *
	 * @return void
	 */
	public function init_updater() {
		$license = $this->get_license_key();

		new EDDThemeUpdater(
			Constants::EDD_STORE_URL,
			[
				'theme_slug' => get_template(),
				'item_name'  => Constants::EDD_ITEM_NAME,
				'item_id'    => Constants::edd_item_id(),
				'version'    => Constants::LISTZEN_VERSION,
				'license'    => $license,
				'author'     => Constants::EDD_AUTHOR,
				'url'        => home_url(),
				'beta'       => false,
			]
		);
	}

	/**
	 * Activate or deactivate the license via AJAX.
	 *
	 * @return void
	 */
	public function manage_license() {
		$this->authorize_request();

		$type = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
		if ( ! in_array( $type, [ 'license_activate', 'license_deactivate' ], true ) ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => 'invalid',
					'msg'    => esc_html__( 'Invalid action.', 'listzen' ),
				]
			);
		}

		$data       = $this->get_license_data();
		$stored_key = ! empty( $data['license_key'] ) ? $this->clean_license_key( $data['license_key'] ) : '';
		$posted_key = isset( $_REQUEST['license_key'] ) ? $this->clean_license_key( wp_unslash( $_REQUEST['license_key'] ) ) : '';
		$license    = '' !== $posted_key ? $posted_key : $stored_key;
		$current_st = ! empty( $data['license_status'] ) ? $data['license_status'] : 'invalid';

		if ( ! $license ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => 'invalid',
					'msg'    => esc_html__( 'Please enter your license key.', 'listzen' ),
				]
			);
		}

		if ( 'license_activate' === $type ) {
			$result = $this->edd_request( 'activate_license', $license );
			if ( $result['error'] ) {
				wp_send_json(
					[
						'error'  => true,
						'status' => $current_st,
						'msg'    => $result['error'],
					]
				);
			}

			$license_data = $result['data'];

			if ( ! $this->is_edd_success( $license_data ) ) {
				wp_send_json(
					[
						'error'  => true,
						'status' => 'invalid',
						'msg'    => $this->get_error_message( $license_data ),
					]
				);
			}

			$data['license_key']    = $license;
			$data['license_status'] = isset( $license_data->license ) ? $license_data->license : 'valid';
			$data['license_expires'] = isset( $license_data->expires ) ? $license_data->expires : '';
			$this->update_license_data( $data );

			wp_send_json(
				[
					'error'   => false,
					'status'  => 'valid',
					'msg'     => esc_html__( 'License successfully activated.', 'listzen' ),
					'expires' => $this->format_expires( $license_data ),
				]
			);
		}

		// Deactivate.
		$result = $this->edd_request( 'deactivate_license', $license );
		if ( $result['error'] ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => $current_st,
					'msg'    => $result['error'],
				]
			);
		}

		$license_data = $result['data'];

		if ( isset( $license_data->license ) && 'failed' === $license_data->license ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => $current_st,
					'msg'    => esc_html__( 'License could not be deactivated. Please try again.', 'listzen' ),
				]
			);
		}

		unset( $data['license_status'], $data['license_expires'] );
		$this->update_license_data( $data );

		wp_send_json(
			[
				'error'  => false,
				'status' => 'invalid',
				'msg'    => esc_html__( 'License successfully deactivated.', 'listzen' ),
			]
		);
	}

	/**
	 * Get stored license payload.
	 *
	 * @return array
	 */
	public function get_license_data() {
		$data = get_option( Constants::LICENSE_OPTION, [] );

		return is_array( $data ) ? $data : [];
	}

	/**
	 * Save license payload.
	 *
	 * @param array $data License payload.
	 *
	 * @return void
	 */
	private function update_license_data( $data ) {
		update_option( Constants::LICENSE_OPTION, $data );
	}

	/**
	 * Get the trimmed license key.
	 *
	 * @return string
	 */
	public function get_license_key() {
		$data = $this->get_license_data();

		return ! empty( $data['license_key'] ) ? trim( $data['license_key'] ) : '';
	}

	/**
	 * Is the stored license currently valid.
	 *
	 * @return bool
	 */
	public function is_license_active() {
		$data = $this->get_license_data();

		return ! empty( $data['license_status'] ) && 'valid' === $data['license_status'];
	}

	/**
	 * Aggressively clean a pasted license key.
	 *
	 * @param string $key Raw key string.
	 *
	 * @return string
	 */
	private function clean_license_key( $key ) {
		if ( ! is_string( $key ) ) {
			return '';
		}
		$cleaned = preg_replace(
			'/[\s\x{00A0}\x{200B}-\x{200D}\x{FEFF}\p{Cc}\p{Cf}]+/u',
			'',
			$key
		);
		if ( null === $cleaned ) {
			$cleaned = preg_replace( '/[^\x21-\x7E]/', '', $key );
		}
		return is_string( $cleaned ) ? $cleaned : '';
	}

	/**
	 * Treat the EDD response as a success only when explicitly so.
	 *
	 * @param object $license_data Decoded EDD payload.
	 *
	 * @return bool
	 */
	private function is_edd_success( $license_data ) {
		if ( ! is_object( $license_data ) || ! isset( $license_data->success ) ) {
			return false;
		}
		return (bool) $license_data->success;
	}

	/**
	 * Translate EDD error codes into user-facing messages.
	 *
	 * @param object $license_data Response payload from EDD.
	 *
	 * @return string
	 */
	private function get_error_message( $license_data ) {
		$error = isset( $license_data->error ) ? $license_data->error : '';

		switch ( $error ) {
			case 'expired':
				$expires = isset( $license_data->expires ) ? strtotime( $license_data->expires ) : false;
				return $expires
					? sprintf(
						/* translators: %s: expiration date */
						esc_html__( 'Your license key expired on %s.', 'listzen' ),
						date_i18n( get_option( 'date_format' ), $expires )
					)
					: esc_html__( 'Your license key has expired.', 'listzen' );
			case 'disabled':
			case 'revoked':
				return esc_html__( 'Your license key has been disabled.', 'listzen' );
			case 'missing':
				return esc_html__( 'Invalid license. Please double-check your key.', 'listzen' );
			case 'invalid':
			case 'site_inactive':
				return esc_html__( 'Your license is not active for this URL.', 'listzen' );
			case 'item_name_mismatch':
				return sprintf(
					/* translators: %s: product name */
					esc_html__( 'This appears to be an invalid license key for %s.', 'listzen' ),
					Constants::EDD_ITEM_NAME
				);
			case 'no_activations_left':
				$limit      = isset( $license_data->license_limit ) ? (int) $license_data->license_limit : 0;
				$site_count = isset( $license_data->site_count ) ? (int) $license_data->site_count : 0;
				if ( $limit > 0 ) {
					return sprintf(
						/* translators: 1: number of sites already activated, 2: total activation limit */
						esc_html__( 'Your license key has reached its activation limit (%1$d of %2$d sites used). Please deactivate the license on a site you no longer use, or upgrade your license to add more sites.', 'listzen' ),
						$site_count,
						$limit
					);
				}
				return esc_html__( 'Your license key has reached its activation limit. Please deactivate the license on a site you no longer use, or upgrade your license to add more sites.', 'listzen' );
			default:
				return esc_html__( 'An error occurred, please try again.', 'listzen' );
		}
	}

	/**
	 * Format expiration date for display.
	 *
	 * @param object $license_data Response payload from EDD.
	 *
	 * @return string|null
	 */
	private function format_expires( $license_data ) {
		if ( ! isset( $license_data->expires ) ) {
			return null;
		}
		if ( 'lifetime' === $license_data->expires ) {
			return esc_html__( 'Lifetime', 'listzen' );
		}
		$timestamp = strtotime( $license_data->expires );
		return $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : null;
	}

	/**
	 * Common pre-flight checks for licensing AJAX endpoints.
	 *
	 * @return bool True if the request can proceed.
	 */
	private function authorize_request() {
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => 'invalid',
					'msg'    => esc_html__( 'Session expired. Please refresh the page and try again.', 'listzen' ),
				]
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'error'  => true,
					'status' => 'invalid',
					'msg'    => esc_html__( 'You do not have permission to manage the license.', 'listzen' ),
				]
			);
		}

		return true;
	}

	/**
	 * Make an EDD API request.
	 *
	 * @param string $edd_action activate_license|deactivate_license|check_license.
	 * @param string $license    License key.
	 *
	 * @return array
	 */
	private function edd_request( $edd_action, $license ) {
		$response = wp_remote_post(
			Constants::EDD_STORE_URL,
			[
				'timeout'    => 30,
				'sslverify'  => false,
				'user-agent' => 'Listzen/' . Constants::LISTZEN_VERSION . '; ' . home_url(),
				'body'       => [
					'edd_action' => $edd_action,
					'license'    => $license,
					'item_id'    => Constants::edd_item_id(),
					'item_name'  => Constants::EDD_ITEM_NAME,
					'url'        => home_url(),
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			return [
				'data'  => null,
				'error' => $this->describe_http_error( $response ),
			];
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			return [
				'data'  => null,
				'error' => sprintf(
					/* translators: %d: HTTP status code */
					esc_html__( 'Licensing server returned an unexpected status (HTTP %d). Please try again in a few minutes.', 'listzen' ),
					(int) $code
				),
			];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = $body ? json_decode( $body ) : null;
		if ( ! is_object( $data ) ) {
			return [
				'data'  => null,
				'error' => esc_html__( 'Unexpected response from the licensing server. Please try again.', 'listzen' ),
			];
		}

		return [
			'data'  => $data,
			'error' => null,
		];
	}

	/**
	 * Build an actionable message for common HTTP transport failures.
	 *
	 * @param \WP_Error $err Error object from wp_remote_post.
	 *
	 * @return string
	 */
	private function describe_http_error( $err ) {
		$code     = $err->get_error_code();
		$message  = $err->get_error_message();
		$host     = wp_parse_url( Constants::EDD_STORE_URL, PHP_URL_HOST );
		$host     = $host ? $host : 'www.radiustheme.com';
		$haystack = strtolower( (string) $message );

		$is_blocked = ( 'http_request_not_executed' === $code )
			|| false !== strpos( $haystack, 'blocked' )
			|| false !== strpos( $haystack, 'not allowed' );

		if ( $is_blocked ) {
			return sprintf(
				/* translators: %s: licensing server hostname */
				esc_html__( 'Outbound connections to %s are being blocked by your server or a security plugin. Please ask your host (or check WP_HTTP_BLOCK_EXTERNAL / WP_ACCESSIBLE_HOSTS in wp-config.php) to allow requests to this host, then try again.', 'listzen' ),
				$host
			);
		}

		if ( false !== strpos( $haystack, 'timed out' ) || false !== strpos( $haystack, 'timeout' ) ) {
			return sprintf(
				/* translators: %s: licensing server hostname */
				esc_html__( 'The licensing server (%s) did not respond in time. Please check your internet connection and try again.', 'listzen' ),
				$host
			);
		}

		if ( false !== strpos( $haystack, 'could not resolve' ) || false !== strpos( $haystack, 'resolve host' ) || false !== strpos( $haystack, 'name or service' ) ) {
			return sprintf(
				/* translators: %s: licensing server hostname */
				esc_html__( 'Could not resolve %s. Please check your server\'s DNS configuration and try again.', 'listzen' ),
				$host
			);
		}

		if ( false !== strpos( $haystack, 'ssl' ) || false !== strpos( $haystack, 'certificate' ) ) {
			return esc_html__( 'A secure connection to the licensing server could not be established. Please ensure your server\'s CA bundle is up to date.', 'listzen' );
		}

		return $message ? $message : esc_html__( 'An error occurred, please try again.', 'listzen' );
	}
}
