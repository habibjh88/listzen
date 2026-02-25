<?php
/**
 * Theme Customizer - Header
 *
 * @package listzen
 */

namespace Listzen\Api\Customizer\Sections;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Api\Customizer;
use Listzen\Framework\Customize\Customize;

/**
 * ColorHeader class
 */
class ColorHeader extends Customizer {
	protected $section_header_color = 'listzen_header_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {

		Customize::add_section( [
			'id'          => $this->section_header_color,
			'panel'       => 'listzen_color_panel',
			'title'       => __( 'Header Colors', 'listzen' ),
			'description' => __( 'Header Color Section', 'listzen' ),
			'priority'    => 4
		] );

		Customize::add_controls( $this->section_header_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_header_color_controls', [

			'listzen_menu_heading1' => [
				'type'  => 'heading',
				'label' => __( 'Default Menu', 'listzen' ),
			],

			'listzen_menu_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Menu Color', 'listzen' ),
			],

			'listzen_menu_active_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Menu Hover & Active Color', 'listzen' ),
			],

			'listzen_menu_bg_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Menu Wrapper Background', 'listzen' ),
			],

			'listzen_sub_menu_bg_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Sub Menu Background Color', 'listzen' ),
			],

			'listzen_menu_heading2' => [
				'type'  => 'heading',
				'label' => __( 'Transparent Menu', 'listzen' ),
			],

			'listzen_tr_menu_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'TR Menu Color', 'listzen' ),
			],

			'listzen_tr_menu_active_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'TR Menu Hover & Active Color', 'listzen' ),
			],

			'listzen_menu_heading4' => [
				'type'  => 'heading',
				'label' => __( 'Others Style', 'listzen' ),
			],

			'listzen_menu_border_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Menu Border Color', 'listzen' ),
			],


		] );


	}

}
