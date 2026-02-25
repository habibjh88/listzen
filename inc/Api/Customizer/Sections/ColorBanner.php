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
 * ColorBanner class
 */
class ColorBanner extends Customizer {

	protected $section_banner_color = 'listzen_banner_color_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_banner_color,
			'panel'       => 'listzen_color_panel',
			'title'       => __( 'Banner / Breadcrumb Colors', 'listzen' ),
			'description' => __( 'Banner Color Section', 'listzen' ),
			'priority'    => 6
		] );

		Customize::add_controls( $this->section_banner_color, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_site_color_controls', [

			'listzen_banner_color' => [
				'type'         => 'color',
				'label'        => __( 'Banner Background Color', 'listzen' ),
			],

			'listzen_banner_overlay' => [
				'type'         => 'color',
				'label'        => __( 'Banner Overlay Color', 'listzen' ),
			],

			'listzen_banner_hr' => [
				'type'         => 'separator',
			],

			'listzen_breadcrumb_color' => [
				'type'    => 'color',
				'label'   => __( 'Link Color', 'listzen' ),
			],

			'listzen_breadcrumb_hover' => [
				'type'    => 'color',
				'label'   => __( 'Link Hover Color', 'listzen' ),
			],

			'listzen_breadcrumb_active' => [
				'type'    => 'color',
				'label'   => __( 'Link Active Color', 'listzen' ),
			],

			'listzen_breadcrumb_title_color' => [
				'type'    => 'color',
				'label'   => __( 'Title Color', 'listzen' ),
			],

		] );
	}

}
