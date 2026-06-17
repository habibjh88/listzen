<?php
/**
 * Bundle Plugin Guard.
 *
 * @package Listzen
 */

namespace Listzen\Admin;

use Listzen\Traits\SingletonTraits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//phpcs:disable WordPress.Security.NonceVerification.Recommended

/**
 * Gates installation of bundled premium plugins behind an active
 * theme license. Already-installed bundles are left alone.
 */
class BundlePluginGuard {

	use SingletonTraits;

	/**
	 * Bundled premium plugin slugs (folder names under wp-content/plugins).
	 *
	 * @var string[]
	 */
	const BUNDLED_SLUGS = [
		'listzen-core',
		'classified-listing-pro',
		'classified-listing-store',
		'rtcl-booking',
	];

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'maybe_block_install' ], 1 );
		add_action( 'admin_init', [ $this, 'maybe_block_activation' ], 1 );
		add_action( 'admin_notices', [ $this, 'show_install_notice' ] );
		add_action( 'admin_footer', [ $this, 'disable_bundled_checkboxes' ] );
		add_filter( 'plugin_action_links', [ $this, 'modify_action_links' ], 100, 2 );
		add_filter( 'network_admin_plugin_action_links', [ $this, 'modify_action_links' ], 100, 2 );
	}

	/**
	 * Intercept the TGMPA install/update action for a bundled plugin
	 * when the theme license is not active and the plugin is not yet
	 * installed. Covers both the per-row install link and the
	 * Install/Update bulk action on the TGMPA install screen.
	 *
	 * @return void
	 */
	public function maybe_block_install() {
		if ( LicenseController::instance()->is_license_active() ) {
			return;
		}

		// Bulk install / update from the TGMPA install screen.
		$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
		if ( ! $action || '-1' === $action ) {
			$action = isset( $_REQUEST['action2'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action2'] ) ) : '';
		}

		if ( 'tgmpa-bulk-install' === $action || 'tgmpa-bulk-update' === $action ) {
			$plugins = isset( $_REQUEST['plugin'] ) ? (array) wp_unslash( $_REQUEST['plugin'] ) : [];
			foreach ( $plugins as $slug ) {
				$slug = sanitize_key( $slug );
				if ( $slug && in_array( $slug, self::BUNDLED_SLUGS, true ) && ! $this->is_plugin_installed( $slug ) ) {
					$this->die_no_license( esc_html__( 'A valid Listzen theme license is required to install the selected bundled plugin(s). Please activate your license first, or uncheck the bundled plugins before continuing.', 'listzen' ) );
				}
			}
			return;
		}

		// Per-row install / update link.
		$slug = isset( $_REQUEST['plugin'] ) && is_scalar( $_REQUEST['plugin'] )
			? sanitize_key( wp_unslash( $_REQUEST['plugin'] ) )
			: '';
		if ( ! $slug || ! in_array( $slug, self::BUNDLED_SLUGS, true ) ) {
			return;
		}

		$is_install = isset( $_REQUEST['tgmpa-install'] ) && 'install-plugin' === $_REQUEST['tgmpa-install'];
		$is_update  = isset( $_REQUEST['tgmpa-update'] ) && 'update-plugin' === $_REQUEST['tgmpa-update'];
		if ( ! $is_install && ! $is_update ) {
			return;
		}

		if ( $this->is_plugin_installed( $slug ) ) {
			return;
		}

		$this->die_no_license( esc_html__( 'A valid Listzen theme license is required to install this bundled plugin. Please activate your license first.', 'listzen' ) );
	}

	/**
	 * Intercept activation of bundled plugins from plugins.php
	 * (single + bulk + TGMPA activate links) when the license is
	 * inactive.
	 *
	 * @return void
	 */
	public function maybe_block_activation() {
		if ( LicenseController::instance()->is_license_active() ) {
			return;
		}

		global $pagenow;

		$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
		if ( ! $action && isset( $_REQUEST['action2'] ) ) {
			$action = sanitize_text_field( wp_unslash( $_REQUEST['action2'] ) );
		}

		// TGMPA activate-plugin link from the install table.
		$tgm_activate = isset( $_GET['tgmpa-activate'] ) ? sanitize_text_field( wp_unslash( $_GET['tgmpa-activate'] ) ) : '';
		if ( 'activate-plugin' === $tgm_activate ) {
			$slug = isset( $_GET['plugin'] ) ? sanitize_key( wp_unslash( $_GET['plugin'] ) ) : '';
			if ( $slug && in_array( $slug, self::BUNDLED_SLUGS, true ) ) {
				$this->die_no_license();
			}
			return;
		}

		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		if ( 'activate' === $action ) {
			$plugin = isset( $_REQUEST['plugin'] ) ? wp_unslash( $_REQUEST['plugin'] ) : '';
			if ( $this->is_bundled_basename( $plugin ) ) {
				$this->die_no_license();
			}
			return;
		}

		if ( 'activate-selected' === $action ) {
			$checked = isset( $_REQUEST['checked'] ) ? (array) wp_unslash( $_REQUEST['checked'] ) : [];
			foreach ( $checked as $plugin ) {
				if ( $this->is_bundled_basename( $plugin ) ) {
					$this->die_no_license();
				}
			}
		}
	}

	/**
	 * Replace the Activate row action on bundled plugins with a
	 * license-required notice when the license is inactive.
	 *
	 * @param array  $actions     Existing row actions.
	 * @param string $plugin_file Plugin basename, e.g. listzen-core/listzen-core.php.
	 *
	 * @return array
	 */
	public function modify_action_links( $actions, $plugin_file ) {
		if ( LicenseController::instance()->is_license_active() ) {
			return $actions;
		}

		if ( ! $this->is_bundled_basename( $plugin_file ) ) {
			return $actions;
		}

		if ( isset( $actions['activate'] ) ) {
			$url               = admin_url( 'themes.php?page=' . LicenseController::MENU_SLUG );
			$actions['activate'] = sprintf(
				'<span style="color:#b91c1c;">%s</span> &middot; <a href="%s">%s</a>',
				esc_html__( 'License required', 'listzen' ),
				esc_url( $url ),
				esc_html__( 'Activate license', 'listzen' )
			);
		}

		return $actions;
	}

	/**
	 * Kill the current request with a license-required message.
	 *
	 * @param string $message Custom body text.
	 *
	 * @return void
	 */
	private function die_no_license( $message = '' ) {
		if ( ! $message ) {
			$message = esc_html__( 'A valid Listzen theme license is required to activate this bundled plugin. Please activate your license first.', 'listzen' );
		}
		$url  = admin_url( 'themes.php?page=' . LicenseController::MENU_SLUG );
		$link = sprintf(
			' <a href="%s"><strong>%s</strong></a>',
			esc_url( $url ),
			esc_html__( 'Go to License', 'listzen' )
		);

		wp_die(
			wp_kses(
				$message . $link,
				[
					'a'      => [ 'href' => [] ],
					'strong' => [],
				]
			),
			esc_html__( 'License Required', 'listzen' ),
			[
				'response'  => 403,
				'back_link' => true,
			]
		);
	}

	/**
	 * Does the given plugin basename belong to one of the bundled
	 * premium plugins?
	 *
	 * @param string $basename Plugin basename, e.g. listzen-core/listzen-core.php.
	 *
	 * @return bool
	 */
	private function is_bundled_basename( $basename ) {
		$basename = (string) $basename;
		if ( '' === $basename ) {
			return false;
		}
		foreach ( self::BUNDLED_SLUGS as $slug ) {
			if ( 0 === strpos( $basename, $slug . '/' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Render a warning notice on plugins.php and the TGMPA install
	 * screen when at least one bundled plugin is still missing.
	 *
	 * @return void
	 */
	public function show_install_notice() {
		if ( LicenseController::instance()->is_license_active() ) {
			return;
		}

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		$is_plugins = ( 'plugins' === $screen->id );
		$is_tgm     = ( 'appearance_page_listzen-install-plugins' === $screen->id );
		if ( ! $is_plugins && ! $is_tgm ) {
			return;
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( ! $this->has_missing_bundle() ) {
			return;
		}

		$url = admin_url( 'themes.php?page=' . LicenseController::MENU_SLUG );
		?>
		<div class="notice notice-warning listzen-bundle-license-notice">
			<p>
				<?php
				printf(
					/* translators: 1: opening anchor tag, 2: closing anchor tag */
					esc_html__( 'A valid Listzen theme license is required to install the bundled premium plugins. %1$sActivate your license%2$s to unlock installation.', 'listzen' ),
					'<a href="' . esc_url( $url ) . '"><strong>',
					'</strong></a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Disable the bulk-action checkboxes for bundled plugins on the
	 * plugins.php list and the TGMPA install screen when the theme
	 * license is inactive. Prevents the user from selecting bundled
	 * rows for the Install / Activate bulk actions.
	 *
	 * @return void
	 */
	public function disable_bundled_checkboxes() {
		if ( LicenseController::instance()->is_license_active() ) {
			return;
		}

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		$is_plugins = ( 'plugins' === $screen->id );
		$is_tgm     = ( 'appearance_page_listzen-install-plugins' === $screen->id );
		if ( ! $is_plugins && ! $is_tgm ) {
			return;
		}

		$slugs = wp_json_encode( array_values( self::BUNDLED_SLUGS ) );
		$title = esc_html__( 'License required to install or activate this bundled plugin.', 'listzen' );
		?>
		<style>.listzen-bundle-locked-row{opacity:.7}.listzen-bundle-locked-row input[type=checkbox]{cursor:not-allowed}</style>
		<script>
		(function(){
			var bundled = <?php echo $slugs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
			var title = <?php echo wp_json_encode( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;

			function matches(value){
				if(!value) return false;
				value = String(value);
				for(var i=0;i<bundled.length;i++){
					var s = bundled[i];
					if(value === s) return true;
					if(value.indexOf(s + '/') === 0) return true;
				}
				return false;
			}

			function lock(cb){
				if(!cb || cb.disabled) return;
				cb.disabled = true;
				cb.checked = false;
				cb.title = title;
				var row = cb.closest ? cb.closest('tr') : null;
				if(row) row.classList.add('listzen-bundle-locked-row');
			}

			function run(){
				var nodes = document.querySelectorAll('input[type=checkbox][name="plugin[]"], input[type=checkbox][name="checked[]"]');
				for(var i=0;i<nodes.length;i++){
					if(matches(nodes[i].value)) lock(nodes[i]);
				}
			}

			if(document.readyState === 'loading'){
				document.addEventListener('DOMContentLoaded', run);
			}else{
				run();
			}
		})();
		</script>
		<?php
	}

	/**
	 * Is the given plugin slug already installed?
	 *
	 * @param string $slug Plugin folder slug.
	 *
	 * @return bool
	 */
	public function is_plugin_installed( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach ( array_keys( get_plugins() ) as $basename ) {
			if ( 0 === strpos( $basename, $slug . '/' ) ) {
				return true;
			}
		}

		return is_dir( WP_PLUGIN_DIR . '/' . $slug );
	}

	/**
	 * Is at least one bundled plugin still missing on this site?
	 *
	 * @return bool
	 */
	private function has_missing_bundle() {
		foreach ( self::BUNDLED_SLUGS as $slug ) {
			if ( ! $this->is_plugin_installed( $slug ) ) {
				return true;
			}
		}
		return false;
	}
}
