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
 * BlogSingle class
 */
class BlogSingle extends Customizer {
	protected $section_blog_single = 'listzen_blog_single_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_blog_single,
			'title'       => __( 'Blog Single', 'listzen' ),
			'description' => __( 'Blog Single Section', 'listzen' ),
			'priority'    => 26
		] );

		Customize::add_controls( $this->section_blog_single, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {
		return apply_filters( 'listzen_single_controls', [

			'listzen_single_meta' => [
				'type'        => 'select2',
				'label'       => __( 'Choose Single Meta', 'listzen' ),
				'description' => __( 'You can sort meta by drag and drop', 'listzen' ),
				'placeholder' => __( 'Choose Meta', 'listzen' ),
				'multiselect' => true,
				'default'     => 'author,date,category',
				'choices'     => Fns::blog_meta_list(),
			],

			'listzen_single_meta_style' => [
				'type'    => 'select',
				'label'   => __( 'Meta Style', 'listzen' ),
				'default' => 'meta-style-dash',
				'choices' => Fns::meta_style()
			],

			'listzen_post_banner_single_title' => [
				'type'    => 'text',
				'label'   => __( 'Single Banner Title', 'listzen' ),
				'default' => __( 'Post Details', 'listzen' ),
			],

			'listzen_single_visibility_heading' => [
				'type'  => 'heading',
				'label' => __( 'Visibility Section', 'listzen' ),
			],

			'listzen_single_meta_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Meta Visibility', 'listzen' ),
				'default' => 1
			],

			'listzen_single_meta_icon_visibility' => [
				'type'    => 'switch',
				'label'   => __( 'Meta Icon Visibility', 'listzen' ),
				'default' => 0
			],

			'listzen_single_tag_visibility'        => [
				'type'  => 'switch',
				'label' => __( 'Tag Visibility', 'listzen' ),
			],
			'listzen_single_share_visibility'      => [
				'type'  => 'switch',
				'label' => __( 'Share Visibility', 'listzen' ),
			],
			'listzen_single_profile_visibility'    => [
				'type'  => 'switch',
				'label' => __( 'Author Profile Visibility', 'listzen' ),
			],
			'listzen_single_caption_visibility'    => [
				'type'  => 'switch',
				'label' => __( 'Caption Visibility', 'listzen' ),
			],
			'listzen_single_navigation_visibility' => [
				'type'  => 'switch',
				'label' => __( 'Navigation Visibility', 'listzen' ),
			],
			'listzen_single_entry_image_radius'    => [
				'type'  => 'switch',
				'label' => __( 'Content Image Radius', 'listzen' ),
			],
			'listzen_single_blockquote_icon'       => [
				'type'  => 'switch',
				'label' => __( 'Block Quote Icon', 'listzen' ),
			],
			'listzen_post_share'                   => [
				'type'        => 'select2',
				'label'       => __( 'Choose Share Media', 'listzen' ),
				'description' => __( 'You can sort meta by drag and drop', 'listzen' ),
				'placeholder' => __( 'Choose Media', 'listzen' ),
				'multiselect' => true,
				'default'     => 'facebook,twitter,linkedin',
				'choices'     => Fns::post_share_list(),
				'condition'   => [ 'listzen_single_share_visibility' ]
			],

			'listzen_post_single_related_heading' => [
				'type'  => 'heading',
				'label' => __( 'Post Single Related Option', 'listzen' ),
			],

			'listzen_post_related' => [
				'type'    => 'switch',
				'label'   => __( 'Related Visibility', 'listzen' ),
				'default' => 0
			],

			'listzen_post_related_title' => [
				'type'      => 'text',
				'label'     => __( 'Post Related Title', 'listzen' ),
				'default'   => __( 'Related Post', 'listzen' ),
				'condition' => [ 'listzen_post_related' ]
			],

			'listzen_post_related_limit' => [
				'type'      => 'number',
				'label'     => __( 'Related Item Limit', 'listzen' ),
				'default'   => 3,
				'condition' => [ 'listzen_post_related' ]
			],

			'listzen_post_related_query' => [
				'type'        => 'select',
				'label'       => __( 'Query Type', 'listzen' ),
				'description' => __( 'Post Query Type', 'listzen' ),
				'default'     => 'cat',
				'choices'     => [
					'cat'    => esc_html__( 'Posts in the same Categories', 'listzen' ),
					'tag'    => esc_html__( 'Posts in the same Tags', 'listzen' ),
					'author' => esc_html__( 'Posts by the same Author', 'listzen' ),
				],
				'condition'   => [ 'listzen_post_related' ]
			],

			'listzen_post_related_sort' => [
				'type'        => 'select',
				'label'       => __( 'Sort Order', 'listzen' ),
				'description' => __( 'Display Post Order', 'listzen' ),
				'default'     => 'recent',
				'choices'     => [
					'recent'   => esc_html__( 'Recent Posts', 'listzen' ),
					'rand'     => esc_html__( 'Random Posts', 'listzen' ),
					'modified' => esc_html__( 'Last Modified Posts', 'listzen' ),
					'popular'  => esc_html__( 'Most Commented posts', 'listzen' ),
					'views'    => esc_html__( 'Most Viewed posts', 'listzen' ),
				],
				'condition'   => [ 'listzen_post_related' ]
			],

		] );
	}


}
