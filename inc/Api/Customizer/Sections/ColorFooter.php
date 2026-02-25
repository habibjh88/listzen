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
 * ColorFooter class
 */
class ColorFooter extends Customizer {
	protected $section_footer_color = 'listzen_footer_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_footer_color,
			'panel'       => 'listzen_color_panel',
			'title'       => __( 'Footer Colors', 'listzen' ),
			'description' => __( 'Footer Color Section', 'listzen' ),
			'priority'    => 8
		] );

		Customize::add_controls( $this->section_footer_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_footer_color_controls', [
			'listzen_footer_color1'           => [
				'type'  => 'heading',
				'label' => __( 'Main Footer', 'listzen' ),
			],
			'listzen_footer_bg'               => [
				'type'  => 'color',
				'label' => __( 'Footer Background', 'listzen' ),
			],
			'listzen_footer_text_color'             => [
				'type'  => 'color',
				'label' => __( 'Footer Text', 'listzen' ),
			],
			'listzen_footer_link_color'             => [
				'type'  => 'color',
				'label' => __( 'Footer Link', 'listzen' ),
			],
			'listzen_footer_link_hover_color'       => [
				'type'  => 'color',
				'label' => __( 'Footer Link - Hover', 'listzen' ),
			],
			'listzen_footer_widget_title_color'     => [
				'type'  => 'color',
				'label' => __( 'Widget Title', 'listzen' ),
			],
			'listzen_footer_input_border_color'     => [
				'type'  => 'color',
				'label' => __( 'Input/List/Table Border Color', 'listzen' ),
			],
			'listzen_footer_copyright_color1' => [
				'type'  => 'heading',
				'label' => __( 'Copyright Area', 'listzen' ),
			],
			'listzen_copyright_bg'            => [
				'type'  => 'color',
				'label' => __( 'Copyright Background', 'listzen' ),
			],
			'listzen_copyright_text_color'          => [
				'type'  => 'color',
				'label' => __( 'Copyright Text', 'listzen' ),
			],
			'listzen_copyright_link_color'          => [
				'type'  => 'color',
				'label' => __( 'Copyright Link', 'listzen' ),
			],
			'listzen_copyright_link_hover_color'    => [
				'type'  => 'color',
				'label' => __( 'Copyright Link - Hover', 'listzen' ),
			],
		] );


	}

}
