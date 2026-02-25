<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace Listzen\Framework\Customize;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Customize {
	use SingletonTraits;

	// Get our default values
	/**
	 * @var array|mixed
	 */
	private static $panels = [];
	private static $sections = [];
	private static $fields = [];
	private static $fields_group = [];
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
		// Register Settings / Controls
		add_action( 'customize_register', [ $this, 'add_customizer_controls' ] );
	}

	/**
	 * Add Panels
	 *
	 * @param $wp_customize
	 *
	 * @return void
	 */
	public function add_customizer_panels( $wp_customize ) {
		if ( empty( self::$panels ) ) {
			return;
		}
		// Layout Panel
		foreach ( self::$panels as $panel ) {
			$args = [
				'title'       => $panel['title'] ?? '',
				'description' => $panel['description'] ?? '',
				'priority'    => $panel['priority'] ?? 10,
			];
			$wp_customize->add_panel( $panel['id'], $args );
		}
	}

	/**
	 * Add sections
	 *
	 * @param $wp_customize
	 *
	 * @return void
	 */
	public function add_customizer_sections( $wp_customize ) {

		if ( empty( self::$sections ) ) {
			return;
		}
		foreach ( self::$sections as $section ) {
			$args = [
				'title'    => $section['title'] ?? '',
				'priority' => $section['priority'] ?? '10',
			];

			if ( ! empty( $section['panel'] ) ) {
				$args['panel'] = $section['panel'];
			}

			if ( ! empty( $section['preview_url'] ) ) {
				$args['preview_url'] = $section['preview_url'];
			}


			$wp_customize->add_section( $section['id'], $args );
		}
	}

	/**
	 * Add customizer Settings
	 *
	 * @param $wp_customize
	 *
	 * @return void
	 */
	public function add_customizer_controls( $wp_customize ) {
		FieldManager::add_customizer_controls( $wp_customize, self::$fields, self::$fields_group );
	}

	/**
	 * Add Panel
	 *
	 * @param $panel
	 *
	 * @return void
	 */
	public static function add_panel( $panel ) {
		self::$panels[] = $panel;
	}

	/**
	 * Add Panels
	 *
	 * @param $panels
	 *
	 * @return void
	 */
	public static function add_panels( $panels ) {
		foreach ( $panels as $panel ) {
			self::$panels[] = $panel;
		}
	}

	/**
	 * Add sections
	 *
	 * @param $section
	 *
	 * @return void
	 */
	public static function add_section( $section ) {
		self::$sections[] = $section;
	}

	/**
	 * Add Controls
	 *
	 * @param $field
	 *
	 * @return void
	 */
	public static function add_control( $field ) {
		self::$fields[] = $field;
	}


	/**
	 * Add Controls
	 *
	 * @param $field
	 *
	 * @return void
	 */
	public static function add_controls( $section, $fields ) {
		self::$fields_group[ $section ] = $fields;
	}
}
