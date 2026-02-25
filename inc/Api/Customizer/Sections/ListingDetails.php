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
use Listzen\Helpers\CLFns;
use Listzen\Helpers\Fns;
use Listzen\Framework\Customize\Customize;
use Rtcl\Helpers\Functions;

/**
 * Blog class
 */
class ListingDetails extends Customizer {

	protected $section_listing = 'listzen_listing_single_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing,
			'title'       => __( 'Listing Details', 'listzen' ),
			'description' => __( 'Listing details section', 'listzen' ),
			'priority'    => 28,
		] );

		Customize::add_controls( $this->section_listing, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		$listing_settings = apply_filters( 'listzen_listing_details_controls', [

			'listzen_listing_layout' => [
				'type'        => 'select',
				'label'       => __( 'Listing Layout', 'listzen' ),
				'description' => __( 'Choose listing layout style', 'listzen' ),
				'default'     => 'default',
				'choices'     => [
					'default'     => __( 'Default', 'listzen' ),
					'layout-2'    => __( 'Layout 2', 'listzen' ),
					'layout-3' => __( 'Layout 3', 'listzen' ),
				]
			],


			'listzen_lisging1'              => [
				'type'  => 'heading',
				'label' => __( 'Gallery Settings', 'listzen' ),
			],
			'listzen_listing_gallery_style' => [
				'type'        => 'select',
				'label'       => __( 'Gallery Style', 'listzen' ),
				'description' => __( 'Choose listing gallery layout style', 'listzen' ),
				'default'     => 'gallery',
				'choices'     => [
					'gallery'       => __( 'Slider — Gallery (Default)', 'listzen' ),
					'gallery-full'  => __( 'Slider — Full Width ', 'listzen' ),
					'gallery-split' => __( 'Gallery - Split View', 'listzen' ),
				]
			],
			'listzen_listing_video_separate' => [
				'type'    => 'switch',
				'label'   => __( 'Separate gallery video', 'listzen' ),
				'default' => 1,
			],

			'listzen_listing_gallery_height' => [
				'type'        => 'text',
				'label'       => __( 'Gallery height', 'listzen' ),
				'description' => __( 'Enter gallery height. E.g. 600px', 'listzen' ),
			],

			'listzen_listing_business_hour_pos' => [
				'type'        => 'select',
				'label'       => __( 'Business hour position', 'listzen' ),
				'default'     => 'sidebar',
				'choices'     => [
					'sidebar'       => __( 'Sidebar', 'listzen' ),
					'content'       => __( 'With Content', 'listzen' ),
				]
			],

		] );

		if ( Functions::is_gallery_slider_enabled() ) {
			$listing_settings['listzen_listing_slider_thumb_gallery'] = [
				'type'    => 'switch',
				'label'   => __( 'Enable thumbs gallery', 'listzen' ),
				'default' => 1,
			];

			$listing_settings['listzen_listing_slider_cols'] = [
				'type'        => 'select',
				'label'       => __( 'Slider Columns', 'listzen' ),
				'description' => __( 'Select the number of columns for the listing gallery slider. Note: When "Default" is selected, the column count may vary based on the layout.', 'listzen' ),
				'default'     => '1',
				'choices'     => [
					'1' => __( 'Default - One column', 'listzen' ),
					'2' => __( 'Two column', 'listzen' ),
					'3' => __( 'Three column', 'listzen' ),
					'4' => __( 'Four column', 'listzen' ),
					'5' => __( 'Five column', 'listzen' ),
					'6' => __( 'Six column', 'listzen' ),
				]
			];
		}

		return $listing_settings;
	}


}
