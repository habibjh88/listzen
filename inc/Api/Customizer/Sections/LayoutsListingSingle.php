<?php
/**
 * Theme Customizer - Service Single
 *
 * @package listzen
 */

namespace Listzen\Api\Customizer\Sections;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Api\Customizer;
use Listzen\Framework\Customize\Customize;
use Listzen\Traits\LayoutControlsTraits;

/**
 * LayoutsServiceSingle class
 */
class LayoutsListingSingle extends Customizer {

	use LayoutControlsTraits;

	protected $section_id = 'listzen_listing_single_layout_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_id,
			'title' => __( 'Listing Single Layout', 'listzen' ),
			'panel' => 'listzen_layouts_panel',
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	public function get_controls() {
		$controls = $this->get_layout_controls( 'rtcl_listing' );

		unset( $controls['rtcl_listing_layout'] );
		unset( $controls['rtcl_listing_sidebar'] );

		return $controls;
	}

}
