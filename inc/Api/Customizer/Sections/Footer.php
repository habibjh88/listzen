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
 * Footer class
 */
class Footer extends Customizer {
	protected $section_footer = 'listzen_footer_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_footer,
			'title'       => __( 'Footer', 'listzen' ),
			'description' => __( 'Footer Section', 'listzen' ),
			'priority'    => 38
		] );

		Customize::add_controls( $this->section_footer, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_footer_controls', [

			'listzen_footer_display' => [
				'type'        => 'switch',
				'label'       => __( 'Footer Display', 'listzen' ),
				'description' => __( 'Show footer display', 'listzen' ),
				'default'     => 1,
			],

			'listzen_footer_style' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'listzen' ),
				'default' => '1',
				'choices' => Fns::image_placeholder( 'footer', 3 )
			],

			'listzen_footer_width' => [
				'type'    => 'select',
				'label'   => __( 'Footer Width', 'listzen' ),
				'default' => '',
				'choices' => [
					''       => __( 'Box Width', 'listzen' ),
					'-fluid' => __( 'Full Width', 'listzen' ),
				]
			],

			'listzen_footer_max_width' => [
				'type'        => 'number',
				'label'       => __( 'Footer Max Width (PX)', 'listzen' ),
				'description' => __( 'Enter a number greater than 992.', 'listzen' ),
				'condition'   => [ 'listzen_footer_width', '==', '-fluid' ]
			],

			'listzen_sticy_footer' => [
				'type'        => 'switch',
				'label'       => __( 'Sticky Footer', 'listzen' ),
				'description' => __( 'Show footer at the top when scrolling down', 'listzen' ),
			],

			'listzen_social_footer' => [
				'type'    => 'switch',
				'label'   => __( 'Copyright Social Icon', 'listzen' ),
				'default' => 1,
			],

			'listzen_contact_footer' => [
				'type'        => 'switch',
				'label'       => __( 'Get Started Button', 'listzen' ),
				'description' => __( 'Show footer at Get Started Button. This options available for only Footer layout 3.', 'listzen' ),
				'default'     => 1,
			],

			'listzen_contact_button_url' => [
				'type'      => 'text',
				'label'     => __( 'Contact Link', 'listzen' ),
				'condition' => [ 'listzen_contact_footer' ]
			],

			'listzen_footer_copyright' => [
				'type'        => 'tinymce',
				'label'       => __( 'Footer Copyright Text', 'listzen' ),
				'default'     => __( 'Copyright© [y] Listzen by <a href="#">RadiusTheme</a>', 'listzen' ),
				'description' => __( 'Add [y] flag anywhere for dynamic year.', 'listzen' ),
			],

		] );

	}


}
