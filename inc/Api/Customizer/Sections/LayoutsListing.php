<?php
/**
 * Theme Customizer - Service Archive
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
 * LayoutsService class
 */
class LayoutsListing extends Customizer {

	use LayoutControlsTraits;

	protected $section_id = 'listzen_listing_archive_layout_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_id,
			'title' => __( 'Listing Archive Layout', 'listzen' ),
			'panel' => 'listzen_layouts_panel',
		] );

		Customize::add_controls( $this->section_id, $this->get_controls() );
	}

	public function get_controls() {
		$controls = $this->get_layout_controls( 'listing_archive' );

//		unset( $controls['listing_archive_layout'] );
		unset( $controls['listing_archive_sidebar'] );

		return $controls;
	}

}
