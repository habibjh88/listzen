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
 * Banner class
 */
class Banner extends Customizer {

	protected $section_breadcrumb = 'listzen_breadcrumb_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_breadcrumb,
			'title'       => __( 'Banner - Breadcrumb', 'listzen' ),
			'description' => __( 'Banner Section', 'listzen' ),
			'priority'    => 22
		] );

		Customize::add_controls( $this->section_breadcrumb, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		$baradcrumbSettings = [
			'listzen_banner' => [
				'type'    => 'switch',
				'label'   => __( 'Banner Visibility', 'listzen' ),
				'default' => 0
			],

			'listzen_banner_color_mode' => [
				'type'      => 'select',
				'label'     => __( 'Banner Color Mode', 'listzen' ),
				'default'   => '',
				'choices'   => [
					''               => __( 'Default', 'listzen' ),
					'banner-dark'    => __( 'Dark', 'listzen' ),
					'banner-light'   => __( 'Light', 'listzen' ),
					'banner-primary' => __( 'Primary', 'listzen' ),
				],
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_breadcrumb_alignment' => [
				'type'      => 'select',
				'label'     => __( 'Banner Alignment', 'listzen' ),
				'default'   => '',
				'choices'   => [
					''                   => __( 'Alignment Default', 'listzen' ),
					'align-items-left'   => __( 'Alignment Left', 'listzen' ),
					'align-items-center' => __( 'Alignment Center', 'listzen' ),
					'align-items-end'    => __( 'Alignment right', 'listzen' ),
				],
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_banner_image' => [
				'type'         => 'image',
				'label'        => __( 'Banner Background Image', 'listzen' ),
				'description'  => __( 'Upload Banner Image', 'listzen' ),
				'button_label' => __( 'Banner', 'listzen' ),
				'condition'    => [ 'listzen_banner' ]
			],

			'listzen_banner_image_attr' => [
				'type'      => 'bg_attribute',
				'condition' => [ 'listzen_banner' ],
				'default'   => json_encode(
					[
						'position'   => 'center center',
						'attachment' => 'scroll',
						'repeat'     => 'no-repeat',
						'size'       => 'cover',
					]
				)
			],

			'listzen_banner_height' => [
				'type'        => 'number',
				'label'       => __( 'Banner Height (px)', 'listzen' ),
				'description' => __( 'Height can be differ for transparent header.', 'listzen' ),
				'default'     => '',
				'condition'   => [ 'listzen_banner' ]
			],

			'listzen_banner_padding_top' => [
				'type'      => 'number',
				'label'     => __( 'Banner Padding Top (px)', 'listzen' ),
				'default'   => '',
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_banner_padding_bottom' => [
				'type'      => 'number',
				'label'     => __( 'Banner Padding Bottom (px)', 'listzen' ),
				'default'   => '',
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_banner1' => [
				'type'      => 'heading',
				'label'     => __( 'Breadcrumb Settings', 'listzen' ),
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_breadcrumb_title' => [
				'type'      => 'switch',
				'label'     => __( 'Banner Title', 'listzen' ),
				'condition' => [ 'listzen_banner' ],
				'default'   => 1,
			],

			'listzen_breadcrumb' => [
				'type'      => 'switch',
				'label'     => __( 'Banner Breadcrumb', 'listzen' ),
				'default'   => 1,
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_breadcrumb_border' => [
				'type'      => 'switch',
				'label'     => __( 'Breadcrumb Border', 'listzen' ),
				'default'   => 1,
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_breadcrumb_thumb_enable' => [
				'type'      => 'switch',
				'label'     => __( 'Use post thumbnail as background', 'listzen' ),
				'default'   => 0,
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_banner2' => [
				'type'      => 'heading',
				'label'     => __( 'Breadcrumb Title', 'listzen' ),
				'condition' => [ 'listzen_banner' ]
			],

			'listzen_banner_title_single_post' => [
				'type'      => 'text',
				'label'     => __( 'Blog Details Title', 'listzen' ),
				'default'   => '',
				'condition' => [ 'listzen_banner' ]
			],

		];

		foreach ( Fns::get_post_types() as $ptype => $ptype_name ) {
			$ptype = str_replace( '-', '_', $ptype );
			if ( 'page' === $ptype ) {
				continue;
			}
			$baradcrumbSettings[ 'listzen_banner_title_' . trim( $ptype ) ] = [
				'type'      => 'text',
				'label'     => $ptype_name . __( ' Archive Title', 'listzen' ),
				'condition' => [ 'listzen_banner' ]
			];
		}

		return apply_filters( 'listzen_top_bar_controls', $baradcrumbSettings );

	}

}
