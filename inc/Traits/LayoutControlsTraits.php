<?php
/**
 * LayoutControls
 */

namespace Listzen\Traits;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Do not allow directly accessing this file.
use Listzen\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * LayoutControlsTraits Trait for customize layout
 */
trait LayoutControlsTraits {
	public function get_layout_controls( $prefix = '' ) {

		$_left_text  = __( 'Left Sidebar', 'listzen' );
		$_right_text = __( 'Right Sidebar', 'listzen' );
		$left_text   = $_left_text;
		$right_text  = $_right_text;
		$image_left  = 'sidebar-left.png';
		$image_right = 'sidebar-right.png';

		if ( is_rtl() ) {
			$left_text   = $_right_text;
			$right_text  = $_left_text;
			$image_left  = 'sidebar-right.png';
			$image_right = 'sidebar-left.png';
		}

		return apply_filters( "listzen_{$prefix}_layout_controls", [

			$prefix . '_layout' => [
				'type'    => 'image_select',
				'label'   => __( 'Choose Layout', 'listzen' ),
				'default' => 'right-sidebar',
				'choices' => [
					'left-sidebar'  => [
						'image' => listzen_get_img( $image_left ),
						'name'  => $left_text,
					],
					'full-width'    => [
						'image' => listzen_get_img( 'sidebar-full.png' ),
						'name'  => __( 'Full Width', 'listzen' ),
					],
					'right-sidebar' => [
						'image' => listzen_get_img( $image_right ),
						'name'  => $right_text,
					],
				]
			],

			$prefix . '_sidebar' => [
				'type'    => 'select',
				'label'   => __( 'Choose a Sidebar', 'listzen' ),
				'default' => 'default',
				'choices' => Fns::sidebar_lists()
			],

			$prefix . '_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Page Background Image', 'listzen' ),
				'description'  => __( 'Upload Background Image', 'listzen' ),
				'button_label' => __( 'Background Image', 'listzen' ),
			],

			$prefix . '_page_bg_color' => [
				'type'        => 'color',
				'label'       => __( 'Page Background Color', 'listzen' ),
				'description' => __( 'Inter Background Color', 'listzen' ),
			],

			$prefix . '_header_heading' => [
				'type'  => 'heading',
				'label' => __( 'Header Settings', 'listzen' ),
			],

			$prefix . '_header_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Header Layout', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'1'       => __( 'Layout 1', 'listzen' ),
					'2'       => __( 'Layout 2', 'listzen' ),
					'3'       => __( 'Layout 3', 'listzen' ),
					'4'       => __( 'Layout 4', 'listzen' ),
					'5'       => __( 'Layout 5', 'listzen' ),
					'6'       => __( 'Layout 6', 'listzen' ),
				],
			],

			$prefix . '_top_bar' => [
				'type'    => 'select',
				'label'   => __( 'Top Bar', 'listzen' ),
				'default' => 'default',
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'on'      => __( 'On', 'listzen' ),
					'off'     => __( 'Off', 'listzen' ),
				]
			],

			$prefix . '_nav_drawer_style' => [
				'type'    => 'select',
				'label'   => __( 'Nav Drawer Layout', 'listzen' ),
				'default' => 'default',
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'1'       => __( 'Layout 1', 'listzen' ),
					'2'       => __( 'Layout 2', 'listzen' ),
					'3'       => __( 'Layout 3', 'listzen' ),
				],
			],

			$prefix . '_banner_heading' => [
				'type'  => 'heading',
				'label' => __( 'Banner Settings', 'listzen' ),
			],

			$prefix . '_banner' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Visibility', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'on'      => __( 'On', 'listzen' ),
					'off'     => __( 'Off', 'listzen' ),
				],
			],

			$prefix . '_banner_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Layout', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'1'       => __( 'Layout 1', 'listzen' ),
					'2'       => __( 'Layout 2', 'listzen' ),
				],
			],

			$prefix . '_breadcrumb_title' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Title', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'on'      => __( 'On', 'listzen' ),
					'off'     => __( 'Off', 'listzen' ),
				],
			],

			$prefix . '_breadcrumb' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Banner Breadcrumb', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'on'      => __( 'On', 'listzen' ),
					'off'     => __( 'Off', 'listzen' ),
				],
			],

			$prefix . '_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Image', 'listzen' ),
				'description'  => __( 'Upload Banner Image', 'listzen' ),
				'button_label' => __( 'Banner Image', 'listzen' ),
			],

			$prefix . '_banner_color' => [
				'type'        => 'color',
				'label'       => __( 'Banner Background Color', 'listzen' ),
				'description' => __( 'Inter Background Color', 'listzen' ),
			],

			$prefix . '_footer_heading' => [
				'type'  => 'heading',
				'label' => __( 'Footer Settings', 'listzen' ),
			],

			$prefix . '_footer_style' => [
				'type'    => 'select',
				'default' => 'default',
				'label'   => __( 'Footer Layout', 'listzen' ),
				'choices' => [
					'default' => __( '--Default--', 'listzen' ),
					'1'       => __( 'Layout 1', 'listzen' ),
					'2'       => __( 'Layout 2', 'listzen' ),
					'3'       => __( 'Layout 3', 'listzen' ),
					'4'       => __( 'Layout 4', 'listzen' ),
					'5'       => __( 'Layout 5', 'listzen' ),
				],
			],

		] );
	}

}
