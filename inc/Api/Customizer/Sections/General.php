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
 * General class
 */
class General extends Customizer {

	protected $section_general = 'listzen_general_section';

	/**
	 * Register controls
	 *
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_general,
			'title'       => __( 'General', 'listzen' ),
			'description' => __( 'General Section', 'listzen' ),
			'priority'    => 20,
		] );
		Customize::add_controls( $this->section_general, $this->get_controls() );
	}

	/**
	 * Get controls
	 *
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'listzen_general_controls', [

			'container_width' => [
				'type'        => 'number',
				'label'       => __( 'Container Width', 'listzen' ),
				'default'     => '1280',
				'description' => __( 'Enter more than 1024', 'listzen' ),
			],

			'listzen_script_optimize' => [
				'type'    => 'switch',
				'label'   => __( 'Enable Script Optimize', 'listzen' ),
			],

			'listzen_svg_enable' => [
				'type'    => 'switch',
				'default' => '1',
				'label'   => __( 'Enable SVG Upload', 'listzen' ),
			],

			'listzen_back_to_top' => [
				'type'  => 'switch',
				'label' => __( 'Back to Top', 'listzen' ),
			],

			'listzen_remove_admin_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Remove Admin Bar', 'listzen' ),
				'description' => __( 'This option not work for administrator role.', 'listzen' ),
			],

			'listzen_image_srcset' => [
				'type'        => 'switch',
				'label'       => __( 'Disable Image Srcset', 'listzen' ),
				'description' => esc_html__( 'Disables the srcset attribute for responsive image sizes in WordPress.', 'listzen' ),
			],

			'listzen_preloader' => [
				'type'  => 'switch',
				'label' => __( 'Preloader', 'listzen' ),
			],

			'listzen_preloader_logo' => [
				'type'         => 'image',
				'label'        => __( 'Preloader Logo', 'listzen' ),
				'description'  => __( 'Upload preloader logo for your site.', 'listzen' ),
				'button_label' => __( 'Logo', 'listzen' ),
				'condition'    => [ 'listzen_preloader' ],
			],

			'preloader_bg_color' => [
				'type'      => 'color',
				'label'     => __( 'Preloader Background Color', 'listzen' ),
				'condition' => [ 'listzen_preloader' ],
			],

			'listzen_blend' => [
				'type'    => 'select',
				'label'   => __( 'Full Site Image Blend', 'listzen' ),
				'default' => 'default',
				'choices' => [
					'default'         => esc_html__( 'Default', 'listzen' ),
					'listzen-blend' => esc_html__( 'Blend (Grayscale)', 'listzen' ),
				],
			],

			'listzen_radius'     => [
				'type'        => 'text',
				'default'     => '10px',
				'label'       => __( 'Site Radius', 'listzen' ),
				'description' => __( 'Default 15px. Enter radius with unit. ex. 10px', 'listzen' ),
			],
			'listzen_btn_radius' => [
				'type'        => 'text',
				'default'     => '5px',
				'label'       => __( 'Button Radius', 'listzen' ),
				'description' => __( 'Default 5px. Enter radius with unit. ex. 4px', 'listzen' ),
			],

		] );
	}

}
