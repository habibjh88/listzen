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
 * Error class
 */
class Error extends Customizer {
	protected $section_labels = 'listzen_error_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_labels,
			'title'       => __( 'Error Page', 'listzen' ),
			'description' => __( 'Error section.', 'listzen' ),
			'priority'    => 39
		] );
		Customize::add_controls( $this->section_labels, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_labels_controls', [

			'listzen_error_image' => [
				'type'         => 'image',
				'label'        => __( 'Error Image', 'listzen' ),
				'description'  => __( 'Upload error image for your site.', 'listzen' ),
				'button_label' => __( 'Error image', 'listzen' ),
			],

			'listzen_error_heading' => [
				'type'        => 'text',
				'label'       => __( 'Error Heading', 'listzen' ),
				'default'     => __( 'Oops, something went wrong.', 'listzen' ),
			],

			'listzen_error_text' => [
				'type'        => 'text',
				'label'       => __( 'Error Text', 'listzen' ),
				'default'     => __( 'Sorry! This Page Is Not Available!', 'listzen' ),
			],

			'listzen_error_button_text' => [
				'type'        => 'text',
				'label'       => __( 'Error Button Text', 'listzen' ),
				'default'     => __( 'Back To Home Page', 'listzen' ),
			],

		] );
	}


}
