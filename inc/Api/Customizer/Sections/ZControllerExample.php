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
 * ZControllerExample class for check
 */
class ZControllerExample extends Customizer {

	protected $section_test = 'listzen_test_test_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_test,
			'title'       => __( 'Test Controls', 'listzen' ),
			'description' => __( 'Customize the Test', 'listzen' ),
			'priority'    => 9999
		] );
		Customize::add_controls( $this->section_test, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'listzen_test_test_controls', [

			//Reset button
			'listzen_reset_customize' => [
				'type'  => 'heading',
				'reset' => '1',
			],
			//Reset button

			'listzen_test_heading1' => [
				'type'        => 'heading',
				'label'       => __( 'All controls', 'listzen' ),
				'description' => __( 'All controls are here', 'listzen' ),
			],

			'listzen_test_switch' => [
				'type'  => 'switch',
				'label' => __( 'Choose switch', 'listzen' ),
			],

			'listzen_test_text' => [
				'type'      => 'text',
				'label'     => __( 'Text Default', 'listzen' ),
				'default'   => __( 'Text Default', 'listzen' ),
				'transport' => '',
				'condition' => [ 'listzen_test_switch' ]
			],


			'listzen_test_switch2' => [
				'type'  => 'switch',
				'label' => __( 'Choose switch2', 'listzen' ),
			],
			'listzen_test_url'     => [
				'type'      => 'url',
				'label'     => __( 'url', 'listzen' ),
				'default'   => __( 'url Default', 'listzen' ),
				'transport' => '',
				'condition' => [ 'listzen_test_switch2', '!==', 1 ]
			],

			'listzen_test_select'   => [
				'type'        => 'select',
				'label'       => __( 'Select a Val', 'listzen' ),
				'description' => __( 'Select Discription', 'listzen' ),
				'default'     => 'menu-left',
				'choices'     => [
					'menu-left'   => __( 'Left Alignment', 'listzen' ),
					'menu-center' => __( 'Center Alignment', 'listzen' ),
					'menu-right'  => __( 'Right Alignment', 'listzen' ),
				]
			],
			'listzen_test_textarea' => [
				'type'      => 'textarea',
				'label'     => __( 'Textarea', 'listzen' ),
				'default'   => __( 'Textarea Default', 'listzen' ),
				'transport' => '',
			],

			'listzen_test_select5' => [
				'type'        => 'select',
				'label'       => __( 'Select a Val2', 'listzen' ),
				'description' => __( 'Select Discription', 'listzen' ),
				'default'     => 'menu-center',
				'choices'     => [
					'menu-left'   => __( 'Left Alignment', 'listzen' ),
					'menu-center' => __( 'Center Alignment', 'listzen' ),
					'menu-right'  => __( 'Right Alignment', 'listzen' ),
				]
			],

			'listzen_test_textarea2' => [
				'type'      => 'textarea',
				'label'     => __( 'Textarea2', 'listzen' ),
				'default'   => __( 'Textarea Default', 'listzen' ),
				'transport' => '',
			],


			'listzen_test_checkbox' => [
				'type'  => 'checkbox',
				'label' => __( 'Choose checkbox', 'listzen' ),
			],

			'listzen_test_textarea22' => [
				'type'      => 'textarea',
				'label'     => __( 'Checkbox Textarea2', 'listzen' ),
				'transport' => '',
				'condition' => [ 'listzen_test_checkbox', '==', '1' ]
			],


			'listzen_test_radio' => [
				'type'    => 'radio',
				'label'   => __( 'Choose radio', 'listzen' ),
				'choices' => [
					'menu-left'   => __( 'Left Alignment', 'listzen' ),
					'menu-center' => __( 'Center Alignment', 'listzen' ),
					'menu-right'  => __( 'Right Alignment', 'listzen' ),
				]
			],

			'listzen_test_textarea222' => [
				'type'      => 'textarea',
				'label'     => __( 'listzen_test_radio Textarea2 - menu-center', 'listzen' ),
				'transport' => '',
			],

			'listzen_test_image_choose' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'listzen' ),
				'default' => '1',
				'choices' => $this->get_header_presets()
			],

			'listzen_test_image' => [
				'type'         => 'image',
				'label'        => __( 'Choose Image', 'listzen' ),
				'button_label' => __( 'Logo', 'listzen' ),
			],

			'listzen_test_image_attr' => [
				'type'      => 'bg_attribute',
				'condition' => [ 'listzen_banner' ],
				'default'   => json_encode(
					[
						'position'   => 'center center',
						'attachment' => 'scroll',
						'repeat'     => 'no-repeat',
						'size'       => 'auto',
					]
				)
			],

			'listzen_test_number' => [
				'type'        => 'number',
				'label'       => __( 'Select a Number', 'listzen' ),
				'description' => __( 'Select Number', 'listzen' ),
				'default'     => '5',
			],

			'listzen_test_pages' => [
				'type'  => 'pages',
				'label' => __( 'Choose page', 'listzen' ),
			],


			'listzen_test_color'      => [
				'type'  => 'color',
				'label' => __( 'Choose color', 'listzen' ),
			],
			'listzen_test_alfa_color' => [
				'type'  => 'alfa_color',
				'label' => __( 'Choose alfa_color', 'listzen' ),
			],
			'listzen_test_datetime'   => [
				'type'  => 'datetime',
				'label' => __( 'Choose datetime', 'listzen' ),
			],


			'listzen_test_select2' => [
				'type'        => 'select2',
				'label'       => __( 'Choose Meta', 'listzen' ),
				'placeholder' => __( 'Choose Meta', 'listzen' ),
				'multiselect' => true,
				'choices'     => [
					'author'   => __( 'Author', 'listzen' ),
					'date'     => __( 'Date', 'listzen' ),
					'category' => __( 'Category', 'listzen' ),
					'tag'      => __( 'Tag', 'listzen' ),
					'comment'  => __( 'Comment', 'listzen' ),
				],
			],

			'listzen_test_repeater' => [
				'type'  => 'repeater',
				'label' => __( 'Choose repeater', 'listzen' ),
			],

			'listzen_test_blog_meta_order1' => [
				'type'    => 'repeater',
				'label'   => __( 'Meta Order', 'listzen' ),
				'default' => 'one, two, three, four',
				'use_as'  => 'sort',
			],

			'listzen_test_blog_meta_order2' => [
				'type'    => 'repeater',
				'label'   => __( 'Meta Order', 'listzen' ),
				'default' => 'one, two, three, four',
			],

			'listzen_test_typography2' => [
				'type'    => 'typography',
				'label'   => __( 'Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Open Sans',
						'regularweight' => 'normal',
						'size'          => '16',
						'lineheight'    => '26',
					]
				)
			],

			'listzen_test_typography3' => [
				'type'    => 'typography',
				'label'   => __( 'Typography', 'listzen' ),
				'default' => json_encode(
					[
						'font'          => 'Open Sans',
						'regularweight' => 'normal',
						'size'          => '16',
						'lineheight'    => '26',
					]
				)
			],
		] );
	}

	/**
	 * Get Header Presets
	 * @return array[]
	 */
	public function get_header_presets() {
		if ( ! defined( 'listzen_FRAMEWORK_DIR_URL' ) ) {
			return [];
		}

		return [
			'1' => [
				'image' => listzen_FRAMEWORK_DIR_URL . '/assets/images/header-1.png',
				'name'  => __( 'Style 1', 'listzen' ),
			],
			'2' => [
				'image' => listzen_FRAMEWORK_DIR_URL . '/assets/images/header-1.png',
				'name'  => __( 'Style 2', 'listzen' ),
			],
			'3' => [
				'image' => listzen_FRAMEWORK_DIR_URL . '/assets/images/header-1.png',
				'name'  => __( 'Style 3', 'listzen' ),
			],
		];
	}

}
