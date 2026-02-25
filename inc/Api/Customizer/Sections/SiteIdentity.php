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
 * SiteIdentity class
 */
class SiteIdentity extends Customizer {

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_controls( 'title_tagline', $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_title_tagline_controls', [

			'listzen_logo' => [
				'type'         => 'image',
				'label'        => __( 'Main Logo', 'listzen' ),
				'description'  => __( 'Upload main logo for your site.', 'listzen' ),
				'button_label' => __( 'Logo', 'listzen' ),
			],

			'listzen_logo_light' => [
				'type'         => 'image',
				'label'        => __( 'Light Logo', 'listzen' ),
				'description'  => __( 'Upload light logo for transparent header. It should a white logo', 'listzen' ),
				'button_label' => __( 'Light Logo', 'listzen' ),
			],

			'listzen_logo_mobile' => [
				'type'         => 'image',
				'label'        => __( 'Mobile Logo', 'listzen' ),
				'description'  => __( 'Upload, if you need a different logo for mobile device..', 'listzen' ),
				'button_label' => __( 'Mobile Logo', 'listzen' ),
			],

			'listzen_logo_width_height' => [
				'type'      => 'text',
				'label'     => __( 'Main Logo Dimension', 'listzen' ),
				'description'     => __( 'Enter the width and height value separate by comma (,). Eg. 120px,45px', 'listzen' ),
				'transport' => '',
				'default' => 'none,54px',
			],

			'listzen_mobile_logo_width_height' => [
				'type'      => 'text',
				'label'     => __( 'Mobile Logo Dimension', 'listzen' ),
				'description'     => __( 'Enter the width and height value separate by comma (,). Eg. 120px,45px', 'listzen' ),
				'transport' => '',
			],

		] );

	}

}
