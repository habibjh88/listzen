<?php

namespace Listzen\Setup;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Traits\SingletonTraits;

/**
 * Setup Class for the theme
 */
class Setup {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return void
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
	}

	/**
	 * Setup Theme
	 * @return void
	 */
	public function setup() {
		load_theme_textdomain( 'listzen', get_template_directory() . '/languages' );

		$this->add_theme_support();
		add_theme_support( 'rtcl' );

		global $listzen_menu_hide_in;
		$listzen_menu_hide_in = 1025;
	}

	/**
	 * Add Theme Support
	 * @return void
	 */
	private function add_theme_support() {

		//Default Theme Support options better have
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'custom-logo' );
		add_theme_support( "custom-header" );
		add_theme_support( "custom-background" );
		add_theme_support('responsive-embeds');

		//Add woocommerce support and woocommerce override
		add_theme_support( 'woocommerce' );

		//Activate Post formats if you need
		add_theme_support( 'post-formats', [
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		] );
	}

	/**
	 * Define a max content width to allow WordPress to properly resize your images
	 *
	 * @return void
	 */
	public function content_width() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$GLOBALS['content_width'] = apply_filters( 'content_width', 1440 );
	}

}
