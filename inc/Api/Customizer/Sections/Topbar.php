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
 * HeaderTopbar class
 */
class Topbar extends Customizer {
	protected $section_topbar = 'listzen_top_bar_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_topbar,
			'panel'       => 'listzen_header_panel',
			'title'       => __( 'Topbar Section', 'listzen' ),
			'description' => __( 'Top Bar Section', 'listzen' ),
			'priority'    => 2
		] );

		Customize::add_controls( $this->section_topbar, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_top_bar_controls', [

			'listzen_top_bar' => [
				'type'      => 'switch',
				'label'     => __( 'Topbar Visibility', 'listzen' ),
				'default'   => 0,
				'edit-link' => '.topbar-row',
			],
			'listzen_topbar_style' => [
				'type'      => 'image_select',
				'label'     => __( 'Topbar Style', 'listzen' ),
				'default'   => '1',
				'choices'   => Fns::image_placeholder( 'topbar', 2, 'svg' ),
				'condition' => [ 'listzen_top_bar' ]
			],
			'listzen_topbar_address' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Address ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],
			'listzen_topbar_phone' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Phone ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],
			'listzen_topbar_email' => [
				'type'    => 'switch',
				'label'   => __( 'Topbar Email ?', 'listzen' ),
				'default' => 1,
				'condition' => [ 'listzen_top_bar' ]
			],

		] );

	}

}
