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
class Header extends Customizer {
	protected $section_header = 'listzen_header_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_header,
			'panel'       => 'listzen_header_panel',
			'title'       => __( 'Main Menu Section', 'listzen' ),
			'description' => __( 'Header Section', 'listzen' ),
			'priority'    => 21,
			'edit-point'  => ''
		] );
		Customize::add_controls( $this->section_header, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_header_controls', [

			'listzen_header_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Choose Layout', 'listzen' ),
				'default'   => '1',
				'edit-link' => '.site-branding',
				'choices'   => Fns::image_placeholder( 'header', 6, 'svg' )
			],

			'listzen_menu_alignment' => [
				'type'    => 'select',
				'label'   => __( 'Menu Alignment', 'listzen' ),
				'default' => '',
				'choices' => [
					''                       => __( 'Menu Alignment', 'listzen' ),
					'justify-content-start'  => __( 'Left Alignment', 'listzen' ),
					'justify-content-center' => __( 'Center Alignment', 'listzen' ),
					'justify-content-end'    => __( 'Right Alignment', 'listzen' ),
				]
			],

			'listzen_header_width' => [
				'type'    => 'select',
				'label'   => __( 'Header Width', 'listzen' ),
				'default' => 'box',
				'choices' => [
					'box'      => __( 'Box Width', 'listzen' ),
					'full'     => __( 'Full Width', 'listzen' ),
					'max-2110' => __( 'Max width (2110px)', 'listzen' ),
					'max-1920' => __( 'Max width (1920px)', 'listzen' ),
				]
			],

			'listzen_header_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Header Max Width (PX)', 'listzen' ),
				'description' => __( 'Enter a number greater than 1440. Remove value for 100%', 'listzen' ),
				'condition'   => [ 'listzen_header_width', '==', 'full' ]
			],

			'listzen_sticy_header' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Header', 'listzen' ),
				'description' => __( 'Show header at the top when scrolling down', 'listzen' ),
			],

			'listzen_tr_header' => [
				'type'    => 'switch',
				'label'   => __( 'Transparent Header', 'listzen' ),
				'default' => 0
			],

			'listzen_tr_header_shadow' => [
				'type'        => 'switch',
				'label'       => __( 'Header Dark Shadow', 'listzen' ),
				'default'     => 1,
				'description' => __( 'It works only for the transparent header.', 'listzen' ),
			],

			'listzen_header_border' => [
				'type'    => 'switch',
				'label'   => __( 'Header Border', 'listzen' ),
				'default' => 1
			],
			'listzen_header_sep1'   => [
				'type'      => 'separator',
				'edit-link' => '.menu-icon-wrapper',
			],
			'listzen_header1'       => [
				'type'  => 'heading',
				'label' => __( 'Menu Icon Wrapper', 'listzen' ),
			],

			'listzen_header_login_link' => [
				'type'    => 'switch',
				'label'   => __( 'User Login ?', 'listzen' ),
				'default' => 0,
			],

			'listzen_header_login_style' => [
				'type'      => 'select',
				'label'     => __( 'Login Style', 'listzen' ),
				'condition' => [ 'listzen_header_login_link' ],
				'default'   => 'link',
				'choices'   => [
					'link'  => __( 'Link to page', 'listzen' ),
					'popup' => __( 'Open Popup', 'listzen' ),
				]
			],

			'listzen_header_search' => [
				'type'    => 'switch',
				'label'   => __( 'Search Icon ?', 'listzen' ),
				'default' => 0,
			],

			'listzen_header_bar' => [
				'type'        => 'switch',
				'label'       => __( 'Hamburger Menu', 'listzen' ),
				'description' => __( 'It will be hide only for desktop.', 'listzen' ),
				'default'     => 0,
			],

			'listzen_header_separator' => [
				'type'    => 'switch',
				'label'   => __( 'Icon Separator', 'listzen' ),
				'default' => 0,
			],

			'listzen_offcanvas_social' => [
				'type'    => 'switch',
				'label'   => __( 'Offcanvas Social', 'listzen' ),
				'default' => 0,
			],

			'listzen_header_button' => [
				'type'    => 'switch',
				'label'   => __( 'Header Button ?', 'listzen' ),
				'default' => 0
			],

			'listzen_header_button_url' => [
				'type'      => 'text',
				'label'     => __( 'Button Link', 'listzen' ),
				'condition' => [ 'listzen_header_button' ],
			],

			'listzen_header_listing_button' => [
				'type'    => 'switch',
				'label'   => __( 'Add Listing Button ?', 'listzen' ),
				'default' => 0
			],

			'listzen_header_listing_button_url' => [
				'type'      => 'text',
				'label'     => __( 'Add Listing Custom Link (optional)', 'listzen' ),
				'condition' => [ 'listzen_header_listing_button' ],
			],

			'listzen_hamburger_style' => [
				'type'    => 'select',
				'label'   => __( 'Hamburger icon animation', 'listzen' ),
				'default' => '3',
				'choices' => [
					'1' => __( 'Animation 1', 'listzen' ),
					'2' => __( 'Animation 2', 'listzen' ),
					'3' => __( 'Animation 3', 'listzen' ),
					'4' => __( 'Animation 4', 'listzen' ),
				]
			],

			'listzen_header_sep2' => [
				'type' => 'separator',
			],

			'listzen_menu_icon_order' => [
				'type'    => 'repeater',
				'label'   => __( 'Menu Icon Order', 'listzen' ),
				'default' => 'hamburg, login, search, button, button2',
				'use_as'  => 'sort',
			],

		] );

	}


}
