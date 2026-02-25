<?php
/**
 * Theme Customizer - Heading Typography
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
 * TypographyHeading class
 */
class TypographyHeading extends Customizer {

	protected $section_id = 'listzen_heading_typo_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_id,
			'title'       => __( 'Heading Typography', 'listzen' ),
			'description' => __( 'Heading Typography Section', 'listzen' ),
			'panel'       => 'listzen_typography_panel',
			'priority'    => 2
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_heading_typo_section', [

			'listzen_all_heading_typo' => [
				'type'    => 'typography',
				'label'   => __( 'All Headings Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '700',
						'size'          => '44',
						'lineheight'    => '54',
					]
				)
			],

			'listzen_heading_h1_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H1 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'listzen_heading_h2_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H2 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'listzen_heading_h3_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H3 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'listzen_heading_h4_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H4 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'listzen_heading_h5_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H5 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

			'listzen_heading_h6_typo' => [
				'type'    => 'typography',
				'label'   => __( 'H6 Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Inter',
						'regularweight' => '',
						'size'          => '',
						'lineheight'    => '',
					]
				)
			],

		] );

	}

}
