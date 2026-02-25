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
 * Contact class
 */
class Contact extends Customizer {
	protected $section_contact = 'listzen_contact_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_contact,
			'panel'       => 'listzen_contact_social_panel',
			'title'       => __( 'Contact Information', 'listzen' ),
			'description' => __( 'Contact Address Section', 'listzen' ),
			'priority'    => 1
		] );
		Customize::add_controls( $this->section_contact, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_contact_controls', [

			'listzen_phone' => [
				'type'  => 'text',
				'label' => __( 'Phone', 'listzen' ),
			],

			'listzen_email' => [
				'type'  => 'text',
				'label' => __( 'Email', 'listzen' ),
			],

			'listzen_website' => [
				'type'  => 'text',
				'label' => __( 'Website', 'listzen' ),
			],

			'listzen_contact_address' => [
				'type'        => 'textarea',
				'label'       => __( 'Address', 'listzen' ),
				'description' => __( 'Enter company address here.', 'listzen' ),
			],

			'listzen_about_us' => [
				'type'        => 'textarea',
				'label'       => __( 'About Us Content', 'listzen' ),
				'description' => __( 'Enter company address here.', 'listzen' ),
			],

		] );
	}
}
