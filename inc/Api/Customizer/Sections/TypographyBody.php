<?php
/**
 * Theme Customizer - Body Typography
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
 * TypographyBody class
 */
class TypographyBody extends Customizer {

	protected $section_id = 'listzen_body_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Body Typography', 'listzen' ),
			'description' => __( 'Body Typography Section', 'listzen' ),
			'panel'       => 'listzen_typography_panel',
			'priority'    => 1
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_body_typo_section', [

			'listzen_body_typo' => [
				'type'    => 'typography',
				'label'   => __( 'Body Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '400',
						'size'          => '15',
						'lineheight'    => '26',
					]
				)
			],

		] );

	}

}
