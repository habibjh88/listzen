<?php

namespace Listzen\Setup;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Helpers\Constants;
use Listzen\Helpers\Fns;
use Listzen\Options\Opt;
use Listzen\Traits\SingletonTraits;
use Rtcl\Helpers\Link;

/**
 * Enqueue Class
 */
class Enqueue {

	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'listzen_admin_enqueue' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 12 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 888 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_gutenberg_style' ] ); //Gutenberg compatibility
	}

	/**
	 * Register all scripts
	 *
	 * @return void
	 */
	public function register_scripts() {
		wp_register_style( 'listzen-gfonts', $this->fonts_url(), [], Constants::get_version() );

		$script_optimize = listzen_option( 'listzen_script_optimize' );

		$dependency = [ 'jquery' ];
		if ( function_exists( 'rtcl' ) ) {
			$dependency[] = 'rtcl-public';
		}

		if ( $script_optimize ) {
			//Listzen Bundle
			wp_register_script( 'listzen-script', listzen_get_js( 'listzen-bundle.min' ), $dependency, Constants::get_version(), true );
		} else {
			//Register Library
			//wp_register_script( 'listzen-appear', listzen_get_js( 'appear', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-counterup', listzen_get_js( 'counterup', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-enllax', listzen_get_js( 'jquery.enllax.min', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-magnific-popup', listzen_get_js( 'magnific-popup', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-newsticker', listzen_get_js( 'newsticker', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-nice-select', listzen_get_js( 'nice-select', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-parallex', listzen_get_js( 'parallex', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-slick', listzen_get_js( 'slick', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			wp_register_script( 'listzen-waypoints', listzen_get_js( 'waypoints', 'library' ), [ 'jquery' ], Constants::get_version(), true );
			//Listzen Main Scripts
			wp_register_script( 'listzen-script', listzen_get_js( 'scripts', 'js', true ), $dependency, Constants::get_version(), true );
		}

		//Listzen Localize Scripts
		wp_localize_script( 'listzen-script', 'listzenParam', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'listzen_nonce' )
		] );
	}

	/**
	 * Enqueue all necessary scripts and styles for the theme
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// CSS.
		wp_enqueue_style( 'listzen-gfonts' );
		wp_enqueue_style( 'listzen-style', get_stylesheet_uri(), [], Constants::get_version());
		wp_enqueue_style( 'listzen-main', listzen_get_css( 'style', true ), [], Constants::get_version() );
		if ( is_rtl() ) {
			wp_enqueue_style( 'listzen-rtl', listzen_get_css( 'rtl', true ), [ 'listzen-main' ], Constants::get_version() );
		}
		// JS.
		if ( listzen_option( 'listzen_blog_masonry' ) && ( is_home() || is_archive() ) ) {
			wp_enqueue_script( 'jquery-masonry' );
		}

		wp_enqueue_script( 'listzen-script' );
		wp_enqueue_script( 'listzen-appear' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( Fns::is_cl_active() ) {
			//form.js enqueue
			$form_page_url = Link::get_listing_form_page_link();
			$form_page_id  = url_to_postid( $form_page_url ?? '' ); //43
			if ( is_page( $form_page_id ) ) {
				wp_enqueue_script( 'listzen-form', listzen_get_js( 'form' ), [ 'jquery' ], Constants::get_version(), true );
			}
		}
	}

	/**
	 * Making google font url
	 *
	 * @return string
	 */
	public function fonts_url() {
		if ( 'off' === _x( 'on', 'Google font: on or off', 'listzen' ) ) {
			return '';
		}

		// Default variable.
		$subsets = '';

		$body_font = json_decode( listzen_option( 'listzen_body_typo' ), true );
		$menu_font = json_decode( listzen_option( 'listzen_menu_typo' ), true );
		$h_font    = json_decode( listzen_option( 'listzen_all_heading_typo' ), true );

		$bodyFont = $body_font['font'] ?? 'Inter'; // Body Font.
		$menuFont = $menu_font['font'] ?? $bodyFont; // Menu Font.
		$hFont    = $h_font['font'] ?? $body_font; // Heading Font.
		$hFontW   = $h_font['regularweight'] ?? null;

		$heading_fonts = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

		foreach ( $heading_fonts as $heading ) {
			$heading_font         = json_decode( listzen_option( "listzen_heading_{$heading}_typo" ), true );
			${$heading . '_font'} = $heading_font;
			${$heading . 'Font'}  = ''; // Assign default value if not exist the value.
			if ( ! empty( $heading_font['font'] ) ) {
				${$heading . 'Font'}  = $heading_font['font'] == 'Inherit' ? $hFont : $heading_font['font'];
				${$heading . 'FontW'} = $heading_font['font'] == 'Inherit' ? $hFontW : $heading_font['regularweight'];
			}
		}

		$check_families = [];
		$font_families  = [];

		// Body Font
		$font_families[]  = $bodyFont . ':300,400,500,600,700,800,900';
		$check_families[] = $bodyFont;

		// Menu Font
		if ( ! in_array( $menuFont, $check_families ) ) {
			$font_families[]  = $menuFont . ':300,400,500,600,700,800,900';
			$check_families[] = $menuFont;
		}

		// Heading Font
		if ( ! in_array( $hFont, $check_families ) ) {
			$font_families[]  = $hFont . ':300,400,500,600,700,800,900';
			$check_families[] = $hFont;
		}

		// Check all heading fonts
		foreach ( $heading_fonts as $heading ) {
			$hDynamic = ${$heading . '_font'};
			if ( ! empty( $hDynamic['font'] ) ) {
				if ( ! in_array( ${$heading . 'Font'}, $check_families ) ) {
					$font_families[]  = ${$heading . 'Font'} . ':' . ${$heading . 'FontW'};
					$check_families[] = ${$heading . 'Font'};
				}
			}
		}

		$final_fonts = array_unique( $font_families );
		$query_args  = [
			'family'  => urlencode( implode( '|', $final_fonts ) ),
			'display' => urlencode( 'fallback' ),
		];

		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );
	}

	public function enqueue_gutenberg_style() {
		wp_enqueue_style( 'listzen-guten', listzen_get_css( 'gutenberg' ), [], Constants::get_version() );
	}


	/**
	 * Admin Enqueue Scripts
	 *
	 * @param $hook
	 *
	 * @return void
	 */
	public function listzen_admin_enqueue( $hook ) {

		if ( 'nav-menus.php' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script( 'listzen-admin', listzen_get_js( 'admin' ), [ 'jquery' ], Constants::get_version(), true );
		}


		if ( in_array( $hook, [ 'edit-tags.php', 'term.php' ] ) ) {
			wp_enqueue_style( 'listzen-admin', listzen_get_css( 'admin' ), [], Constants::get_version() );
		}

		// Only run on the post editing screen
		if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
			return;
		}

		// Get current post type
		$post_type = get_post_type();

		if ( $post_type === 'rtcl_listing' && ( $hook == 'post.php' || $hook === 'post-new.php' ) ) {
			wp_enqueue_script( 'listzen-form', listzen_get_js( 'form' ), [ 'jquery' ], Constants::get_version(), true );
			wp_enqueue_style( 'listzen-admin', listzen_get_css( 'admin' ), [], Constants::get_version() );
		}
	}
}
