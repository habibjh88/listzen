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
 * ColorSite class
 */
class ColorSite extends Customizer {
	protected $section_site_color = 'listzen_site_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_site_color,
			'panel'       => 'listzen_color_panel',
			'title'       => __( 'Site Colors', 'listzen' ),
			'description' => __( 'Site Color Section', 'listzen' ),
			'priority'    => 2
		] );
		Customize::add_controls( $this->section_site_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_site_color_controls', [

			'listzen_site_color1'       => [
				'type'  => 'heading',
				'label' => __( 'Site Ascent Color', 'listzen' ),
			],
			'listzen_primary_color'     => [
				'type'  => 'color',
				'label' => __( 'Primary Color', 'listzen' ),
			],
			'listzen_primary_dark'      => [
				'type'  => 'color',
				'label' => __( 'Primary Dark', 'listzen' ),
			],
			'listzen_primary_light'     => [
				'type'  => 'color',
				'label' => __( 'Primary Light', 'listzen' ),
			],
			'listzen_primary_soft'      => [
				'type'  => 'color',
				'label' => __( 'Primary Soft', 'listzen' ),
			],
			'listzen_color_separator2'  => [
				'type' => 'separator',
			],
			'listzen_secondary_color'   => [
				'type'  => 'color',
				'label' => __( 'Secondary Color', 'listzen' ),
			],
			'listzen_site_color2'       => [
				'type'  => 'heading',
				'label' => __( 'Others Color', 'listzen' ),
			],
			'listzen_body_bg_color'     => [
				'type'  => 'color',
				'label' => __( 'Body BG Color', 'listzen' ),
			],
			'listzen_body_color'        => [
				'type'  => 'color',
				'label' => __( 'Body Color', 'listzen' ),
			],
			'listzen_border_color'      => [
				'type'  => 'color',
				'label' => __( 'Border Color', 'listzen' ),
			],
			'listzen_title_color'       => [
				'type'  => 'color',
				'label' => __( 'Title Color', 'listzen' ),
			],
			'listzen_rating_color'      => [
				'type'  => 'color',
				'label' => __( 'Rating Color', 'listzen' ),
			],
			'listzen_button_color'      => [
				'type'  => 'color',
				'label' => __( 'Button Color', 'listzen' ),
			],
			'listzen_button_text_color' => [
				'type'  => 'color',
				'label' => __( 'Button Text Color', 'listzen' ),
			],
			'listzen_meta_color'        => [
				'type'  => 'color',
				'label' => __( 'Meta Color', 'listzen' ),
			],
			'listzen_meta_light'        => [
				'type'  => 'color',
				'label' => __( 'Meta Light', 'listzen' ),
			],
			'listzen_gray'              => [
				'type'        => 'color',
				'label'       => __( 'Gray Color', 'listzen' ),
				'description' => __( 'Default: #DDDDDD', 'listzen' ),
			],
			'listzen_gray_light'        => [
				'type'        => 'color',
				'label'       => __( 'Gray Light', 'listzen' ),
				'description' => __( 'Default: #f8f8f8', 'listzen' ),
			],

			'listzen_gray_dark' => [
				'type'        => 'color',
				'label'       => __( 'Gray Dark', 'listzen' ),
				'description' => __( 'Default: #7a7d81', 'listzen' ),
			],
		] );


	}

}
