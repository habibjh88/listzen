<?php
/**
 * Theme Customizer Pannels
 *
 * @package listzen
 */

namespace Listzen\Api\Customizer;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;
use Listzen\Framework\Customize\Customize;

/**
 * Pannels class
 */
class Pannels {

	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
//		$this->add_panels();
		add_action( 'init', [ $this, 'add_panels' ] );
	}

	/**
	 * Add Panels
	 *
	 * @return void
	 */
	public function add_panels() {
		Customize::add_panels(
			[
				[
					'id'          => 'listzen_header_panel',
					'title'       => esc_html__( 'Header', 'listzen' ),
					'description' => esc_html__( 'Listzen Header', 'listzen' ),
					'priority'    => 22,
				],
				[
					'id'          => 'listzen_typography_panel',
					'title'       => esc_html__( 'Typography', 'listzen' ),
					'description' => esc_html__( 'Listzen Typography', 'listzen' ),
					'priority'    => 24,
				],
				[
					'id'          => 'listzen_color_panel',
					'title'       => esc_html__( 'Colors', 'listzen' ),
					'description' => esc_html__( 'Listzen Color Settings', 'listzen' ),
					'priority'    => 25,
				],
				[
					'id'          => 'listzen_layouts_panel',
					'title'       => esc_html__( 'Layout Settings', 'listzen' ),
					'description' => esc_html__( 'Listzen Layout Settings', 'listzen' ),
					'priority'    => 34,
				],
				[
					'id'          => 'listzen_contact_social_panel',
					'title'       => esc_html__( 'Contact & Socials', 'listzen' ),
					'description' => esc_html__( 'Listzen Contact & Socials', 'listzen' ),
					'priority'    => 24,
				],

			]
		);
	}

}
