<?php
/**
 * Theme Customizer
 *
 * @package listzen
 */

namespace Listzen\Api;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Api\Customizer\Pannels;
use Listzen\Helpers\Fns;
use Listzen\Traits\SingletonTraits;

/**
 * Customizer class
 */
class Customizer {

	use SingletonTraits;

	public $customizeClasses;
	public static $default_value = [];
	public $namespace = 'Listzen\\Api\\Customizer\\Sections\\';

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		new Pannels();
		add_action( 'init', [ $this, 'register_controls' ] );
		add_action( 'init', [ $this, 'get_controls_default_value' ] );
		add_action( 'init', [ $this, 'add_controls' ], 0 );
	}

	/**
	 * Add customize controls
	 *
	 * @return string[]
	 */
	public function add_controls() {
		$this->customizeClasses = [
			'General',
			'SiteIdentity',
			'Header',
			'Topbar',
			'navDrawer',
			'Banner',
			'TypographyBody',
			'TypographyHeading',
			'TypographyMenu',
			'Blog',
			'BlogSingle',
			'Contact',
			'Socials',
			'ColorSite',
			'ColorTopbar',
			'ColorHeader',
			'ColorBanner',
			'ColorFooter',
			'Labels',
			'LayoutsPage',
			'LayoutsBlogs',
			'LayoutsSingle',
			'LayoutsError',
			'Footer',
			'Error',
//			'ZControllerExample',
		];

		if(Fns::is_cl_active()){
			$this->customizeClasses[] =  'Listing';
			$this->customizeClasses[] =  'ListingDetails';
			$this->customizeClasses[] =  'LayoutsListing';
			$this->customizeClasses[] =  'LayoutsListingSingle';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$this->customizeClasses[] = 'LayoutsWooSingle';
			$this->customizeClasses[] = 'LayoutsWooArchive';
		}
	}

	/**
	 * Register all controls dynamically
	 *
	 * @param string $section_general
	 */
	public function register_controls() {

		foreach ( $this->customizeClasses as $class ) {
			$full_class = $this->namespace . $class;
			if ( class_exists( $full_class ) ) {
				$control = new $full_class();
				if ( method_exists( $control, 'register' ) ) {
					$control->register();
				}
			}
		}
	}

	/**
	 * Get controls default value
	 *
	 * @return void
	 */

	public function get_controls_default_value() {
		foreach ( $this->customizeClasses as $class ) {
			$full_class = $this->namespace . $class;
			if ( class_exists( $full_class ) ) {
				$control = new $full_class();
				if ( method_exists( $control, 'get_controls' ) ) {
					$controls = $control->get_controls();
					foreach ( $controls as $id => $control ) {
						self::$default_value[ $id ] = $control['default'] ?? '';
					}
				}
			}
		}
	}


}
