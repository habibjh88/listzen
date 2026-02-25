<?php

namespace Listzen\Helpers;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Options\Opt;

// phpcs:disable WordPress.Security.NonceVerification.Recommended

/**
 * Theme Functions
 */
class Fns {

	/**
	 * Filters whether post thumbnail can be displayed.
	 *
	 * @param bool $show_post_thumbnail Whether to show post thumbnail.
	 *
	 */
	public static function can_show_post_thumbnail() {
		return apply_filters(
			'listzen_can_show_post_thumbnail',
			! post_password_required() && ! is_attachment() && has_post_thumbnail()
		);
	}

	/**
	 * Social icon for the site
	 * @return mixed|null
	 */
	public static function get_socials() {
		return apply_filters( 'listzen_socials_icon', [
			'facebook'  => [
				'title' => __( 'Facebook', 'listzen' ),
				'url'   => listzen_option( 'facebook' ),
			],
			'twitter'   => [
				'title' => __( 'Twitter', 'listzen' ),
				'url'   => listzen_option( 'twitter' ),
			],
			'linkedin'  => [
				'title' => __( 'Linkedin', 'listzen' ),
				'url'   => listzen_option( 'linkedin' ),
			],
			'youtube'   => [
				'title' => __( 'Youtube', 'listzen' ),
				'url'   => listzen_option( 'youtube' ),
			],
			'pinterest' => [
				'title' => __( 'Pinterest', 'listzen' ),
				'url'   => listzen_option( 'pinterest' ),
			],
			'instagram' => [
				'title' => __( 'Instagram', 'listzen' ),
				'url'   => listzen_option( 'instagram' ),
			],
			'skype'     => [
				'title' => __( 'Skype', 'listzen' ),
				'url'   => listzen_option( 'skype' ),
			],
			'tiktok'    => [
				'title' => __( 'TikTok', 'listzen' ),
				'url'   => listzen_option( 'tiktok' ),
			],
		] );

	}

	/**
	 * Get Sidebar lists
	 *
	 * @return array
	 */
	public static function sidebar_lists( $default_title = null ) {
		$sidebar_fields            = [];
		$sidebar_fields['default'] = $default_title ?? esc_html__( 'Choose Sidebar', 'listzen' );

		foreach ( self::default_sidebar() as $id => $sidebar ) {
			$sidebar_fields[ $sidebar['id'] ] = $sidebar['name'];
		}

		return $sidebar_fields;
	}

	/**
	 * Get image presets
	 *
	 * @param $name
	 * @param int $total
	 * @param string $type
	 *
	 * @return array
	 */
	public static function image_placeholder( $name, $total = 1, $type = 'png' ) {
		$presets = [];
		for ( $i = 1; $i <= $total; $i ++ ) {
			$image_name    = "customize/$name-$i.$type";
			$presets[ $i ] = [
				'image' => listzen_get_img( $image_name ),
				'name'  => __( 'Style', 'listzen' ) . ' ' . $i,
			];
		}

		return apply_filters( 'listzen_image_placeholder', $presets );
	}


	/**
	 * Convert HEX to RGB color
	 *
	 * @param $hex
	 *
	 * @return string
	 */
	public static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = "$r, $g, $b";

		return $rgb;
	}

	/**
	 * Modify Color
	 * Add positive or negative $steps. Ex: 30, -50 etc
	 *
	 * @param $hex
	 * @param $steps
	 *
	 * @return string
	 */
	public static function modify_color( $hex, $steps ) {
		$steps = max( - 255, min( 255, $steps ) );
		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}
		// Get decimal values
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		// Adjust number of steps and keep it inside 0 to 255
		$r     = max( 0, min( 255, $r + $steps ) );
		$g     = max( 0, min( 255, $g + $steps ) );
		$b     = max( 0, min( 255, $b + $steps ) );
		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}


	/**
	 * Return Sidebar Column
	 * @return string
	 */
	public static function sidebar_columns() {
		$columns = "col-xl-4";

		return $columns;
	}

	/**
	 * Return content columns
	 * @return string
	 */
	public static function content_columns( $full_width_col = 'col-12' ) {
		$sidebar = Opt::$sidebar === 'default' ? 'listzen-sidebar' : Opt::$sidebar;
		$columns = ! is_active_sidebar( $sidebar ) ? $full_width_col : 'col-xl-8';
		if ( self::layout() === 'full-width' ) {
			$columns = $full_width_col;
		}

		return $columns;
	}

	public static function single_content_colums() {
		$sidebar = Opt::$sidebar === 'default' ? 'listzen-single-sidebar' : Opt::$sidebar;
		$columns = is_active_sidebar( $sidebar ) ? "col-xl-8" : "col-xl-10 col-xl-offset-1";

		if ( self::layout() === 'full-width' ) {
			$columns = "col-xl-10 col-xl-offset-1";
		}

		return $columns;
	}


	/**
	 * Get blog colum
	 * @return mixed|string
	 */
	public static function blog_cols() {
		if ( ! empty( $_GET['cols'] ) ) {
			return sanitize_text_field( wp_unslash( $_GET['cols'] ) );
		}

		return listzen_option( 'listzen_blog_column' );
	}


	/**
	 * Get Archive colum
	 *
	 * @param $column
	 * @param $blog_style
	 *
	 * @return mixed|string
	 */
	public static function archive_column( $column, $blog_style ) {
		if ( ! empty( $_REQUEST['column'] ) ) {
			return sanitize_text_field( wp_unslash( $_REQUEST['column'] ) );
		}
		$blog_colum_opt = $column !== 'default' ? $column : '';

		if ( $blog_colum_opt ) {
			$output = $blog_colum_opt;
		} else {
			$output = 'col-lg-12';
		}

		return $output;
	}

	/**
	 * Get all post type
	 * @return array
	 */
	public static function get_post_types() {
		$post_types = get_post_types(
			[
				'public' => true,
			],
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$exclude = apply_filters( 'listzen_exclude_post_type', [
			'attachment',
			'revision',
			'nav_menu_item',
			'elementor_library',
			'listzen_builder',
			'e-landing-page',
			'e-floating-buttons',
			'elementor-listzen'
		] );

		foreach ( $exclude as $ex ) {
			unset( $post_types[ $ex ] );
		}

		return $post_types;
	}

	/**
	 * Meta Style
	 * @return array
	 */
	public static function meta_style( $exclude = [] ) {
		$meta_style = [
			'meta-style-default' => __( 'Default From Theme', 'listzen' ),
			'meta-style-border'  => __( 'Border Style', 'listzen' ),
			'meta-style-dash'    => __( 'Before Dash ( — )', 'listzen' ),
			'meta-style-dash-bg' => __( 'Before Dash with BG ( — )', 'listzen' ),
			'meta-style-pipe'    => __( 'After Pipe ( | )', 'listzen' ),
		];

		if ( ! empty( $exclude ) && is_array( $exclude ) ) {
			foreach ( $exclude as $item ) {
				unset( $meta_style[ $item ] );
			}
		}

		return $meta_style;
	}

	/**
	 * Menu Alignment Dynamically
	 *
	 * @param $default_align
	 *
	 * @return mixed|string
	 */
	public static function menu_alignment( $default_align = '' ) {
		$default_align = "justify-content-$default_align";
		$menu_align    = Opt::$menu_alignment;

		if ( $menu_align ) {
			return $menu_align;
		} else {
			return $default_align;
		}
	}

	/**
	 * Blog Meta Style
	 * @return array
	 */
	public static function blog_meta_list() {
		$meta_list = [
			'category' => __( 'Category', 'listzen' ),
			'author'   => __( 'Author', 'listzen' ),
			'date'     => __( 'Date', 'listzen' ),
			'tag'      => __( 'Tag', 'listzen' ),
			'comment'  => __( 'Comment', 'listzen' ),
			'reading'  => __( 'Reading', 'listzen' ),
			'view'     => __( 'Views', 'listzen' ),
		];

		return $meta_list;
	}

	/**
	 * Post Social Meta
	 * @return array
	 */
	public static function post_share_list() {
		return [
			'facebook'  => __( 'Facebook', 'listzen' ),
			'twitter'   => __( 'Twitter X', 'listzen' ),
			'linkedin'  => __( 'Linkedin', 'listzen' ),
			'pinterest' => __( 'Pinterest', 'listzen' ),
			'whatsapp'  => __( 'Whatsapp', 'listzen' ),
			'youtube'   => __( 'Youtube', 'listzen' ),
		];
	}

	public static function single_meta_lists() {
		$meta_list = listzen_option( 'listzen_single_meta', '', true );

		return $meta_list;
	}

	/**
	 * Class list
	 *
	 * @param $clsses
	 *
	 * @return string
	 */
	public static function class_list( $clsses ): string {
		return trim( implode( ' ', $clsses ) );
	}

	/**
	 * Get all default sidebar args for theme
	 *
	 * @param $id
	 *
	 * @return array|mixed|null
	 */
	public static function default_sidebar( $id = '' ) {
		$sidebar_lists = [
			'main'   => [
				'id'    => 'listzen-sidebar',
				'name'  => __( 'Main Sidebar', 'listzen' ),
				'class' => 'listzen-sidebar',
			],
			'single' => [
				'id'    => 'listzen-single-sidebar',
				'name'  => __( 'Single Sidebar', 'listzen' ),
				'class' => 'listzen-single-sidebar',
			],
			'page'   => [
				'id'    => 'listzen-page-sidebar',
				'name'  => __( 'Page Sidebar', 'listzen' ),
				'class' => 'listzen-page-sidebar',
			],
			'footer' => [
				'id'    => 'listzen-footer-sidebar',
				'name'  => __( 'Footer Sidebar', 'listzen' ),
				'class' => 'footer-sidebar col-lg-3 col-md-6',
			],
		];
		if ( class_exists( 'WooCommerce' ) ) {
			$sidebar_lists['woo-archive'] = [
				'id'    => 'listzen-woo-archive-sidebar',
				'name'  => __( 'WooCommerce Archive Sidebar', 'listzen' ),
				'class' => 'woo-archive-sidebar',
			];
			$sidebar_lists['woo-single']  = [
				'id'    => 'listzen-woo-single-sidebar',
				'name'  => __( 'WooCommerce Single Sidebar', 'listzen' ),
				'class' => 'woo-single-sidebar',
			];
		}
		$sidebar_lists = apply_filters( 'listzen_sidebar_lists', $sidebar_lists );
		if ( ! $id ) {
			return $sidebar_lists;
		}
		if ( isset( $sidebar_lists[ $id ] ) ) {
			return $sidebar_lists[ $id ]['id'];
		}

		return [];
	}

	/**
	 * Custom Image Attributes
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public static function customize_image_attr_css( $key ) {

		if ( empty( listzen_option( $key ) ) ) {
			return '';
		}

		$bg_attr = json_decode( listzen_option( $key ), ARRAY_A );

		$css = '';
		if ( ! empty( $bg_attr['position'] ) ) {
			$css .= "background-position:{$bg_attr['position']};";
		}
		if ( ! empty( $bg_attr['attachment'] ) ) {
			$css .= "background-attachment:{$bg_attr['attachment']};";
		}
		if ( ! empty( $bg_attr['repeat'] ) ) {
			$css .= "background-repeat:{$bg_attr['repeat']};";
		}
		if ( ! empty( $bg_attr['size'] ) ) {
			$css .= "background-size:{$bg_attr['size']};";
		}

		return $css;
	}

	/**
	 * Sanitize Text Field
	 *
	 * @param $input
	 * @param $default
	 * @param $mode
	 *
	 * @return mixed|string
	 */
	public static function sanitize( $input, $default = '', $mode = '' ) {

		$data = $input ?? $default;

		if ( 'html' === $mode ) {
			return listzen_html( $data, false );
		}

		return sanitize_text_field( $data );
	}

	/**
	 * Prints HTMl.
	 *
	 * @param $html
	 * @param $allHtml
	 *
	 * @return void
	 */
	public static function print_html_all( $html, $allHtml = false ) {
		if ( ! $html ) {
			return;
		}
		if ( $allHtml ) {
			echo stripslashes_deep( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo wp_kses_post( stripslashes_deep( $html ) );
		}
	}

	/**
	 * Get Layout Meta
	 *
	 * @param $post_id
	 * @param $key
	 *
	 * @return mixed|string
	 */
	public static function layout_meta( $post_id, $key ) {
		$meta = get_post_meta( $post_id, "listzen_layout_meta_data", true );

		return $meta[ $key ] ?? '';
	}

	/**
	 * Make color darker by a hex value
	 *
	 * @param $hex
	 *
	 * @return bool|void
	 */
	public static function isColorDark( $hex = '' ) {
		if ( '' == $hex ) {
			return;
		}
		// Remove the hash at the start if it's there
		$hex = str_replace( '#', '', $hex );

		// Convert hex to RGB
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		// Calculate the brightness (luminance)
		// Using the formula for luminance: (0.299 * R + 0.587 * G + 0.114 * B)
		$luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;

		// Return true if the color is dark, otherwise false
		return $luminance < 0.8;
	}

	/**
	 * Check if it has site banner with checking query string
	 * @return bool|null
	 */
	public static function has_banner() {

		if ( isset( $_GET['banner'] ) ) {
			return boolval( sanitize_text_field( wp_unslash( $_GET['banner'] ) ) );
		}

		return Opt::$has_banner;

	}

	/**
	 * Return Customize radius for site and button radius with checking query string
	 *
	 * @param $key
	 *
	 * @return false|mixed|string|string[]
	 */
	public static function customize_radius( $key ) {
		$request_key = str_replace( 'listzen_', '', $key );
		if ( isset( $_GET[ $request_key ] ) ) {
			return sanitize_text_field( wp_unslash( $_GET[ $request_key ] ) );
		}

		return listzen_option( $key );
	}

	/**
	 * Return Customize site layout with checking query string
	 *
	 * @param $key
	 *
	 * @return false|mixed|string|string[]
	 */
	public static function layout() {
		if ( isset( $_GET['layout'] ) ) {
			return sanitize_text_field( wp_unslash( $_GET['layout'] ) );
		}

		if ( is_singular( 'post' ) ) {
			if ( ! is_active_sidebar( Fns::default_sidebar( 'single' ) ) ) {
				return 'full-width';
			}
		}

		if ( is_page() && ! is_active_sidebar( Fns::default_sidebar( 'page' ) ) ) {
			return 'full-width';
		}


		return Opt::$layout;
	}

	/**
	 * Blog Style
	 * default | grid-1 | list-1
	 * @return false|mixed|string|string[]
	 */
	public static function blog_layout() {
		if ( isset( $_GET['style'] ) ) {
			return sanitize_text_field( wp_unslash( $_GET['style'] ) );
		}

		return listzen_option( 'listzen_blog_style' );
	}

	/**
	 * Check if image url is a valid url
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	public static function is_image_url_valid( $url ) {
		if ( empty( $url ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		$response = wp_remote_head( $url, [ 'timeout' => 5 ] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$status_code  = wp_remote_retrieve_response_code( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );

		return $status_code === 200 && strpos( $content_type, 'image/' ) === 0;
	}


	/**
	 * Check if classified Listing plugin is activated or not
	 */
	public static function is_cl_active() {
		return class_exists( 'Rtcl' );
	}


	/**
	 * Check if classified Listing pro plugin is activated or not
	 */
	public static function is_cl_pro_active() {
		return class_exists( 'RtclPro' );
	}


	/**
	 * Check if classified Listing Toolkits plugin is activated or not
	 */

	public static function is_cl_toolkits_active() {
		return self::is_cl_active() && class_exists( 'ClassifiedListingToolkits' );
	}


}
