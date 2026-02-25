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
 * ColorTopbar class
 */
class ColorTopbar extends Customizer {
	protected $section_topbar_color = 'listzen_top_bar_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_topbar_color,
			'panel'       => 'listzen_color_panel',
			'title'       => __( 'Top Bar Colors', 'listzen' ),
			'description' => __( 'Top Bar Color Section', 'listzen' ),
			'priority'    => 3
		] );

		Customize::add_controls( $this->section_topbar_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_header_color_controls', [


			'listzen_topbar_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Topbar Color', 'listzen' ),
			],

			'listzen_topbar_active_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Hover Color', 'listzen' ),
			],

			'listzen_topbar_bg_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Topbar Background', 'listzen' ),
			],


		] );


	}

}
