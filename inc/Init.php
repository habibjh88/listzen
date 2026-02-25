<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package listzen
 */

namespace Listzen;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * Theme Init Class
 */
final class Init {

	use SingletonTraits;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->register();
	}

	/**
	 * Instantiate all class
	 * @return void
	 */
	public function register() {
		Framework\Customize\Customize::instance();
		Framework\Customize\FieldManager::instance();
		Core\Sidebar::instance();
		Options\Opt::instance();
		Options\Layouts::instance();
		Setup\Setup::instance();
		Setup\Menus::instance();
		Setup\Enqueue::instance();
		Custom\Hooks::instance();
		Custom\Ajax::instance();
		Custom\Extras::instance();
		Custom\DynamicStyles::instance();
		Custom\MenuMeta::instance();
		Api\Customizer::instance();
		Api\Gutenberg::instance();
		Plugins\ThemeJetpack::instance();
		Plugins\ClassifiedListing::instance();
		Plugins\CLToolKits::instance();
		Modules\TgmConfig::instance();
		Shortcode\Shortcode::instance();

		if ( is_admin() ) {
//			new Utility\Utility();
		}
	}

}
