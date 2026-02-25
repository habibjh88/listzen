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
 * Blog class
 */
class Blog extends Customizer {

	protected $section_blog = 'listzen_blog_section';


	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_blog,
			'title'       => __( 'Blog Settings', 'listzen' ),
			'description' => __( 'Blog Section', 'listzen' ),
			'priority'    => 25,
		] );

		Customize::add_controls( $this->section_blog, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		$blog_layout = [
			'default' => __( 'Default From Theme', 'listzen' ),
			'grid-1'  => __( 'Grid 1', 'listzen' ),
			'list-1'  => __( 'List 1', 'listzen' ),
		];

		$blog_settings = apply_filters( 'listzen_blog_controls', [
			'listzen_blog_visibility' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'listzen' ),
			],

			'listzen_meta_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Meta Visibility', 'listzen' ),
				'default' => 1,
			],

			'listzen_blog_above_meta_visibility' => [
				'type'  => 'switch',
				'label' => __( 'Title Above Meta Visibility', 'listzen' ),
			],

			'listzen_blog_content_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Entry Content Visibility', 'listzen' ),
				'default' => 1,
			],

			'listzen_blog_readmore_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Read More Visibility', 'listzen' ),
				'default' => 1,
			],

			'listzen_blog_readmore_icon_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Read More Icon Visibility', 'listzen' ),
				'default' => 0,
			],

			'listzen_different_category_color' => [
				'type'    => 'switch',
				'label'   => __( 'Enable Different Category Color', 'listzen' ),
				'default' => 1
			],

			'listzen_blog_hr1' => [
				'type' => 'separator',
			],

			'listzen_blog_style' => [
				'type'        => 'select',
				'label'       => __( 'Blog Style', 'listzen' ),
				'description' => __( 'This option works only for blog layout', 'listzen' ),
				'default'     => 'default',
				'edit-link' => '.blog-wrapper-row',
				'choices'     => $blog_layout
			],

			'listzen_blog_column' => [
				'type'        => 'select',
				'label'       => __( 'Grid Column', 'listzen' ),
				'description' => __( 'This option works only for large device', 'listzen' ),
				'default'     => 'default',
				'choices'     => [
					'default'            => __( 'Default From Theme', 'listzen' ),
					'col-lg-12'          => __( '1 Column', 'listzen' ),
					'col-lg-6'           => __( '2 Column', 'listzen' ),
					'col-lg-4 col-md-6'  => __( '3 Column', 'listzen' ),
					'col-lg-3 col-md-6'  => __( '4 Column', 'listzen' ),
					'col-lg-20 col-md-4' => __( '5 Column', 'listzen' ),
				]
			],

			'listzen_excerpt_limit' => [
				'type'    => 'text',
				'label'   => __( 'Content Limit', 'listzen' ),
				'default' => '20',
			],

			'listzen_blog_pagination_style' => [
				'type'        => 'select',
				'label'       => __( 'Pagination Style', 'listzen' ),
				'description' => __( 'This option works only for blog pagination style', 'listzen' ),
				'default'     => 'pagination-area',
				'choices'     => [
					'pagination-area'   => __( 'Default', 'listzen' ),
					'pagination-area-2' => __( 'Style 2', 'listzen' ),
				]
			],

			'listzen_blog_masonry' => [
				'type'  => 'switch',
				'label' => __( 'Enable Masonry Layout', 'listzen' ),
			],

			'listzen_meta_heading' => [
				'type'  => 'heading',
				'label' => __( 'Post Meta Settings', 'listzen' ),
			],

			'listzen_blog_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Meta Style', 'listzen' ),
				'default' => 'meta-style-default',
				'choices' => Fns::meta_style()
			],

			'listzen_blog_meta' => [
				'type'        => 'select2',
				'label'       => __( 'Choose Meta', 'listzen' ),
				'description' => __( 'You can sort meta by drag and drop', 'listzen' ),
				'placeholder' => __( 'Choose Meta', 'listzen' ),
				'multiselect' => true,
				'default'     => 'author,date,category',
				'choices'     => Fns::blog_meta_list(),
			],
		] );

		return $blog_settings;
	}


}
