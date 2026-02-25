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
use Listzen\Helpers\Fns;
use Listzen\Framework\Customize\Customize;

/**
 * Header class
 */
class navDrawer extends Customizer {
	protected $section_header = 'listzen_navdrawer_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_header,
			'panel'       => 'listzen_header_panel',
			'title'       => __( 'Nav Drawer Section', 'listzen' ),
			'description' => __( 'Nav Drawer Section', 'listzen' ),
			'priority'    => 3,
			'edit-point'  => ''
		] );
		Customize::add_controls( $this->section_header, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_nav_drawer_controls', [

			'listzen_nav_drawer_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'listzen' ),
				'default'   => '1',
				'edit-link' => '.site-branding',
				'choices'   => Fns::image_placeholder( 'nav-drawer', 1, 'svg' )
			],

			'listzen_offcavas_about_us' => [
				'type'    => 'switch',
				'label'   => __( 'About Us ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],

			'listzen_offcavas_phone' => [
				'type'    => 'switch',
				'label'   => __( 'Phone ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],

			'listzen_offcavas_email' => [
				'type'    => 'switch',
				'label'   => __( 'Email ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],

			'listzen_offcavas_address' => [
				'type'    => 'switch',
				'label'   => __( 'Address ?', 'listzen' ),
				'condition' => [ 'listzen_top_bar' ]
			],
		] );

	}


}
