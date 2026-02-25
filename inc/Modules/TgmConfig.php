<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.1.0
 */

namespace Listzen\Modules;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

require_once get_template_directory() . '/inc/Lib/class-tgm-plugin-activation.php';

class TgmConfig {

	use SingletonTraits;

	public $base;
	public $path;

	public function __construct() {
		$this->base = 'listzen';
		$this->path = get_template_directory() . '/plugin-bundle/';

		add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
	}

	public function register_required_plugins() {
		$plugins = [
			// Bundled
			[
				'name'     => 'Listzen Core',
				'slug'     => 'listzen-core',
				'source'   => 'listzen-core.zip',
				'required' => true,
				'version'  => '1.0.1'
			],
			[
				'name'     => esc_html__( 'Elementor Page Builder', 'listzen' ),
				'slug'     => 'elementor',
				'required' => true,
			],
			[
				'name'     => esc_html__( 'Classified Listing', 'listzen' ),
				'slug'     => 'classified-listing',
				'required' => true,
			],
			[
				'name'     => 'Classified Listing Pro',
				'slug'     => 'classified-listing-pro',
				'source'   => 'classified-listing-pro.zip',
				'required' => true,
				'version'  => '4.0.6',
			],
			[
				'name'     => 'Classified Listing Store',
				'slug'     => 'classified-listing-store',
				'source'   => 'classified-listing-store.zip',
				'required' => true,
				'version'  => '3.0.4',
			],
			[
				'name'     => esc_html__( 'Review Schema', 'listzen' ),
				'slug'     => 'review-schema',
				'required' => false,
			],
			[
				'name'     => esc_html__( 'Classified Listing Toolkits', 'listzen' ),
				'slug'     => 'classified-listing-toolkits',
				'required' => true,
			],
			[
				'name'     => esc_html__( 'WP Fluent Forms', 'listzen' ),
				'slug'     => 'fluentform',
				'required' => true,
			],
			[
				'name'     => 'One Click Demo Import',
				'slug'     => 'one-click-demo-import',
				'required' => false,
			],
		];

		$config = [
			'id'           => $this->base,
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path,
			// Default absolute path to bundled plugins.
			'menu'         => $this->base . '-install-plugins',
			// Menu slug.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}
}
