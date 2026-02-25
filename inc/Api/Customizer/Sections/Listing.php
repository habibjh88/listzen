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
use Listzen\Framework\Customize\Customize;

/**
 * Blog class
 */
class Listing extends Customizer {

	protected $section_listing = 'listzen_listing_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_listing,
			'title'       => __( 'Listing Settings', 'listzen' ),
			'description' => __( 'Listing settings section', 'listzen' ),
			'priority'    => 27,
		] );

		Customize::add_controls( $this->section_listing, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		$listing_settings = apply_filters( 'listzen_listing_controls', [

			'listzen_listing_style' => [
				'type'        => 'select',
				'label'       => __( 'Listing Style', 'listzen' ),
				'description' => __( 'This option works only for listing layout', 'listzen' ),
				'default'     => 'default',
				'choices'     => [
					'default'        => __( 'Default From Theme', 'listzen' ),
					'category-thumb' => __( 'Category Thumbnail', 'listzen' ),
				]
			],

			'listzen_listing_italic' => [
				'type'  => 'switch',
				'label' => __( 'Make Italic', 'listzen' ),
			],

			'listzen_sidebar_collapsed' => [
				'type'  => 'switch',
				'label' => __( 'Make Collapsable Sidebar', 'listzen' ),
			],

			'listzen_container_width' => [
				'type'  => 'text',
				'label' => __( 'Archive container width (max)', 'listzen' ),
				'description' => __( 'Default width 1240px; Enter width with unit. ex: 1600px', 'listzen' ),
			],

			'listing_price_shorthand' => [
				'type'        => 'select',
				'label'       => __( 'Price Display Format', 'listzen' ),
				'description' => __( 'Select how listing prices should be displayed: full price, Indian comma style, or shorthand format.', 'listzen' ),
				'default'     => 'full',
				'choices'     => [
					'full'         => esc_html__( 'Full Price (e.g. 1,000,000)', 'listzen' ),
					'indian-comma' => esc_html__( 'Indian Comma Style (e.g. 10,00,000)', 'listzen' ),
					'short'        => esc_html__( 'Shorthand (K, M, B, T)', 'listzen' ),
					'short-lac'    => esc_html__( 'Shorthand (K, Lac, Cr)', 'listzen' ),
				],
			],

			'listing_price_precision' => [
				'type'  => 'number',
				'label' => __( 'Price precision', 'listzen' ),
				'description' => esc_html__( 'Enter Price precision. Default value is 3. This field will work if you choose short form price.', 'listzen' ),
			],

			'listing_contact_terms_condition' => [
				'type'  => 'text',
				'label' => __( 'Terms and Condition URL/Path for Contact Form', 'listzen' ),
				'description' => esc_html__( 'Enter Price precision. Default value is 3. This field will work if you choose short form price.', 'listzen' ),
			],
		] );

		if ( CLFns::is_map_enable() ) {
			$listing_settings['listzen_map_pos'] = [
				'type'    => 'select',
				'label'   => __( 'Map Position', 'listzen' ),
				'default' => 'side-r',
				'choices' => [
					'side-r' => __( 'Side View (Right)', 'listzen' ),
					'side-l' => __( 'Side View (Left)', 'listzen' ),
					'top'  => __( 'Top View', 'listzen' ),
					'bottom'  => __( 'Bottom View', 'listzen' ),
				]
			];
		}

		return $listing_settings;
	}


}
