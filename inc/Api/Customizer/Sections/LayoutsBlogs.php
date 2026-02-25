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
use Listzen\Traits\LayoutControlsTraits;

/**
 * LayoutsBlogs class
 */
class LayoutsBlogs extends Customizer {

	use LayoutControlsTraits;

	protected $section_blog_layout = 'listzen_blog_layout_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'    => $this->section_blog_layout,
			'title' => __( 'Blog Layout', 'listzen' ),
			'panel' => 'listzen_layouts_panel',
		] );
		Customize::add_controls( $this->section_blog_layout, $this->get_controls() );
	}

	public function get_controls() {
		return $this->get_layout_controls( 'blog' );
	}

}
