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
 * Labels class
 */
class Labels extends Customizer {
	protected $section_labels = 'listzen_labels_section';

	/**
	 * Register controls
	 * @return void
	 */
	public function register() {
		Customize::add_section( [
			'id'          => $this->section_labels,
			'title'       => __( 'Modify Static Text', 'listzen' ),
			'description' => __( 'You can change all static text of the theme.', 'listzen' ),
			'priority'    => 999
		] );
		Customize::add_controls( $this->section_labels, $this->get_controls() );
	}

	/**
	 * Get controls
	 * @return array
	 */
	public function get_controls() {

		return apply_filters( 'listzen_labels_controls', [

			'listzen_header_labels' => [
				'type'  => 'heading',
				'label' => __( 'Header Labels', 'listzen' ),
			],

			'listzen_get_menu_label' => [
				'type'        => 'text',
				'label'       => __( 'Menu Text', 'listzen' ),
				'default'     => __( 'Menu', 'listzen' ),
				'description' => __( 'Context: Menu Button', 'listzen' ),
			],

			'listzen_get_login_label' => [
				'type'        => 'text',
				'label'       => __( 'Sign In', 'listzen' ),
				'default'     => __( 'Sign In', 'listzen' ),
				'description' => __( 'Context: SignIn Button', 'listzen' ),
			],

			'listzen_header_button_label' => [
				'type'        => 'text',
				'label'       => __( 'Get Started', 'listzen' ),
				'default'     => __( 'Get Started', 'listzen' ),
				'description' => __( 'Context: Get Started', 'listzen' ),
				'condition' => [ 'listzen_header_button' ],
			],
			
			'listzen_header_listing_button_label' => [
				'type'        => 'text',
				'label'       => __( 'Add Listing', 'listzen' ),
				'default'     => __( 'Add Listing', 'listzen' ),
				'description' => __( 'Context: Add Listing', 'listzen' ),
				'condition' => [ 'listzen_header_listing_button' ],
			],

			'listzen_contact_info_label' => [
				'type'        => 'text',
				'label'       => __( 'Contact Info', 'listzen' ),
				'default'     => __( 'Contact Info', 'listzen' ),
				'description' => __( 'Context: Contact Info', 'listzen' ),
			],

			'listzen_follow_us_label' => [
				'type'        => 'text',
				'label'       => __( 'Follow Us On', 'listzen' ),
				'default'     => __( 'Follow Us On', 'listzen' ),
				'description' => __( 'Context: Follow Us On', 'listzen' ),
			],

			'listzen_about_label' => [
				'type'        => 'text',
				'label'       => __( 'About Us', 'listzen' ),
				'description' => __( 'Context: About Us', 'listzen' ),
			],

			'listzen_about_text' => [
				'type'        => 'textarea',
				'label'       => __( 'About Text', 'listzen' ),
				'description' => __( 'Enter about text here.', 'listzen' ),
			],

			'listzen_footer_labels' => [
				'type'  => 'heading',
				'label' => __( 'Footer Labels', 'listzen' ),
			],

			'listzen_ready_label' => [
				'type'        => 'text',
				'label'       => __( 'Are You Ready', 'listzen' ),
				'default'     => __( 'ARE YOU READY TO GET STARTED?', 'listzen' ),
				'description' => __( 'Context: Footer Are You Ready', 'listzen' ),
			],

			'listzen_contact_button_text' => [
				'type'        => 'text',
				'label'       => __( 'Contact Us', 'listzen' ),
				'default'     => __( 'Contact Us', 'listzen' ),
				'description' => __( 'Context: Footer contact button', 'listzen' ),
			],

			'listzen_blog_labels' => [
				'type'  => 'heading',
				'label' => __( 'Blog Labels', 'listzen' ),
			],
			'listzen_author_prefix' => [
				'type'        => 'text',
				'label'       => __( 'By', 'listzen' ),
				'default'     => 'by',
				'description' => __( 'Context: Meta Author Prefix', 'listzen' ),
			],
			'listzen_tags_label'         => [
				'type'        => 'text',
				'label'       => __( 'Tags:', 'listzen' ),
				'default'     => __( 'Tags:', 'listzen' ),
				'description' => __( 'Context: Single blog footer tags label', 'listzen' ),
			],
			'listzen_social_label' => [
				'type'        => 'text',
				'label'       => __( 'Share:', 'listzen' ),
				'default'     => __( 'Share:', 'listzen' ),
				'description' => __( 'Context: Single blog footer Share label', 'listzen' ),
			],
			'listzen_blog_read_more' => [
				'type'        => 'text',
				'label'       => __( 'Blog Read More:', 'listzen' ),
				'default'     => __( 'Continue Reading', 'listzen' ),
				'description' => __( 'Context: Blog read more text', 'listzen' ),
			],

		] );
	}


}
