<?php
/**
 * Theme Customizer - Menu Typography
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
 * TypographyMenu class
 */
class TypographyMenu extends Customizer {

	protected $section_id = 'listzen_menu_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Menu Typography', 'listzen' ),
			'description' => __( 'Menu Typography Section', 'listzen' ),
			'panel'       => 'listzen_typography_panel',
			'priority'    => 3
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_menu_typo_section', [

			'listzen_menu_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Menu Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '600',
						'size'          => '15',
						'lineheight'    => '22',
					]
				)
			],

		] );

	}

}
