<?php

namespace Listzen\Custom;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Helpers\CLFns;
use Listzen\Helpers\Fns;
use Listzen\Traits\SingletonTraits;
use Listzen\Options\Opt;

/**
 * Extras.
 */
class Extras {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'after_switch_theme', [ $this, 'rewrite_flush' ] );
		add_action( 'wp_head', [ $this, 'social_share_meta' ] );
		add_action( 'template_redirect', [ $this, 'w3c_validator' ] );
	}

	/**
	 * Body Class added
	 *
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function body_class( $classes ) {

		// Adds a class of group-blog to blogs with more than 1 published author.

		$classes[] = 'listzen-header-' . Opt::$header_style;
		$classes[] = 'header-width-' . Opt::$header_width;
		$classes[] = 'listzen-footer-' . Opt::$footer_style;
		$classes[] = 'listzen-banner-' . Opt::$banner_style;

		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		if ( Opt::$has_tr_header ) {
			$classes[] = 'has-trheader';
		} else {
			$classes[] = 'no-trheader';
		}

		if ( Opt::$has_tr_header && ! empty( Opt::$header_tr_color ) ) {
			$classes[] = Opt::$header_tr_color;
		}

		if ( listzen_option( 'listzen_tr_header_shadow' ) ) {
			$classes[] = 'has-menu-shadow';
		}


		if ( Fns::has_banner() && ! is_front_page() ) {
			$classes[] = 'has-banner';
		} else {
			$classes[] = 'no-banner';
		}

		if ( Fns::layout() ) {
			$classes[] = 'layout-' . Fns::layout();
		}

		if ( listzen_option( 'listzen_sticy_header' ) ) {
			$classes[] = 'has-sticky-header';
		}

		if ( is_singular( 'post' ) && listzen_option( 'listzen_single_entry_image_radius' ) ) {
			$classes[] = 'has-image-radius';
		}

		if ( listzen_option( 'listzen_single_blockquote_icon' ) ) {
			$classes[] = 'has-blockquote-icon';
		}
		if ( Fns::is_cl_active() ) {
			if ( is_post_type_archive( 'rtcl_listing' ) || is_tax( get_object_taxonomies( 'rtcl_listing' ) ) ) {
				$classes[] = CLFns::is_listing_italic() ? 'rtcl-italic' : '';
				$classes[] = CLFns::is_collapsible_sidebar() ? 'rtcl-sidebar-collapsed' : '';
				$classes[] = CLFns::is_map_enable() ? 'rtcl-map-enable' : '';
				$classes[] = CLFns::map_position();
			}

			if ( is_singular( 'rtcl_listing' ) ) {
				$classes[] = 'rtcl-thumb-' . CLFns::listing_gallery_style();
				$classes[] = 'rtcl-layout-' . CLFns::listing_style();
			}
		}

		return $classes;
	}


	/**
	 * Flush Rewrite on CPT activation
	 *
	 * @return empty
	 */
	public function rewrite_flush() {
		// Flush the rewrite rules only on theme activation
		flush_rewrite_rules();
	}


	/**
	 * Input meta code in head for social share
	 *
	 * @return void
	 */
	public function social_share_meta() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		$title = get_the_title();

		if ( is_singular( 'post' ) ) {
			$link = get_the_permalink() . '?v=' . time();
			echo '<meta property="og:url" content="' . esc_url( $link ) . '" />';
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />';

			if ( ! empty( $post->post_content ) ) {
				echo '<meta property="og:description" content="' . esc_attr( wp_trim_words( wp_strip_all_tags( $post->post_content ), 150 ) ) . '" />';
			}
			$attachment_id = get_post_thumbnail_id( $post->ID );
			if ( ! empty( $attachment_id ) ) {
				$thumbnail = wp_get_attachment_image_src( $attachment_id, 'full' );
				if ( ! empty( $thumbnail ) ) {
					$attachment   = get_post( $attachment_id );
					$thumbnail[0] .= '?v=' . time();
					echo '<meta property="og:image" content="' . esc_attr( $thumbnail[0] ) . '" />';
					echo '<link itemprop="thumbnailUrl" href="' . esc_url( $thumbnail[0] ) . '">';
					echo '<meta property="og:image:type" content="' . esc_attr( $attachment->post_mime_type ) . '">';
				}
			}
			echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
			echo '<meta name="twitter:card" content="summary" />';
			echo '<meta property="og:updated_time" content="' . esc_attr( time() ) . '" />';
		}
	}

	/**
	 * W3C validator passing code
	 *
	 * @return void
	 */
	public function w3c_validator() {
		ob_start( function ( $buffer ) {
			return str_replace( [ '<script type="text/javascript">', "<script type='text/javascript'>" ], '<script>', $buffer );
		} );
		ob_start( function ( $buffer ) {
			return str_replace( [ "<script type='text/javascript' src" ], '<script src', $buffer );
		} );
		ob_start( function ( $buffer ) {
			return str_replace( [ 'type="text/css"', "type='text/css'", 'type="text/css"', ], '', $buffer );
		} );
		ob_start( function ( $buffer ) {
			return str_replace( [ '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"', ], '<iframe', $buffer );
		} );
		ob_start( function ( $buffer ) {
			return str_replace( [ 'aria-required="true"', ], '', $buffer );
		} );
	}

}
