<?php

namespace Listzen\Modules;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Helpers\Fns;

/**
 * PostMeta Class
 */
class PostMeta {

	/**
	 * Get Post Meta
	 *
	 * @param $args
	 *
	 * @return void
	 */
	public static function get_meta( $post_tyle = 'post', $args = [] ) {

		$meta_list       = listzen_option( 'listzen_blog_meta', '', true );
		$category_source = self::posted_in();
		$tag_source      = self::posted_in( 'post_tag' );

		if ( listzen_option( 'listzen_blog_above_meta_visibility' ) ) {
			if ( false !== ( $key = array_search( 'date', $meta_list ) ) ) {
				unset( $meta_list[ $key ] );
			}

			if ( false !== ( $key = array_search( 'category', $meta_list ) ) ) {
				unset( $meta_list[ $key ] );
			}
		}

		$default_args = [
			'with_list'     => true,
			'with_icon'     => true,
			'include'       => $meta_list,
			'class'         => '',
			'author_prefix' => listzen_option( 'listzen_author_prefix' ),
			'with_avatar'   => listzen_option( 'listzen_meta_user_avatar' ),
			'avatar_size'   => 30,
			'category'      => 'category',
			'tag'           => 'post_tag',
		];

		$args = wp_parse_args( $args, $default_args );

		$comments_number = get_comments_number();
		/* translators: used comment as singular and plular */
		$comments_text = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'listzen' ), number_format_i18n( $comments_number ) );

		$_meta_data = [];
		$output     = '';

		$_meta_data['author'] = self::posted_by( $args['author_prefix'] );
		$_meta_data['date']   = self::posted_on();
		if ( ! empty( $category_source ) ) {
			$_meta_data['category'] = $category_source;
		}
		if ( ! empty( $tag_source ) ) {
			$_meta_data['tag'] = $tag_source;
		}
		$_meta_data['comment'] = $comments_text;

		$_meta_data['view'] = self::post_views_count();

		$_meta_data['reading'] = self::reading_time_count( get_the_content(), true );

		$meta_list = $args['include'] ?? array_keys( $_meta_data );

		if ( $args['with_list'] ) {
			$output .= '<div class="blog-post-meta ' . $args['class'] . '"><ul class="entry-meta">';
		}
		foreach ( $meta_list as $key ) {
			if ( empty( $_meta_data[ $key ] ) ) {
				continue;
			}
			$meta  = $_meta_data[ $key ];
			$icons = self::get_icon( $key );

			if ( $args['with_avatar'] && 'author' === $key ) {
				$icons = get_avatar( get_the_author_meta( 'ID' ), $args['avatar_size'], '', 'Avater Image' );
			}

			$output .= ( $args['with_list'] ) ? '<li class="' . $key . '">' : '';
			$output .= '<span class="meta-inner ' . $key . '">';
			$output .= $args['with_icon'] ? $icons : null;
			$output .= $meta;
			$output .= '</span>';
			$output .= ( $args['with_list'] ) ? '</li>' : '';
		}

		if ( $args['with_list'] ) {
			$output .= '</ul></div>';
		}

		Fns::print_html_all( $output );
	}

	/**
	 * Get Post Author
	 *
	 * @param $prefix
	 *
	 * @return string
	 */
	public static function posted_by( $prefix = '' ) {

		return sprintf(
		// Translators: %1$s is the prefix, %2$s is the author's byline.
			esc_html__( '%1$s %2$s', 'listzen' ),
			$prefix ? '<span class="prefix">' . $prefix . '</span>' : '',
			'<span class="byline"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . esc_html( get_the_author() ) . '</a></span>'
		);
	}

	public static function post_views_count( $text = '', $post_id = 0 ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		$views_class = '';
		$count_key   = listzen_utils( 'post_view_key' );
		$view_count  = get_post_meta( $post_id, $count_key, true );

		if ( ! empty( $view_count ) ) {
			if ( $view_count > 1000 ) {
				$views_class = 'very-high';
			} elseif ( $view_count > 100 ) {
				$views_class = 'high';
			} elseif ( $view_count > 5 ) {
				$views_class = 'rising';
			}
		} elseif ( $view_count == '' ) {
			$view_count = 0;
		} else {
			$view_count = 0;
		}

		if ( $view_count == 1 ) {
			$listzen_view_html = esc_html__( 'View', 'listzen' );
		} else {
			$listzen_view_html = esc_html__( 'Views', 'listzen' );
		}
		$view_count           = number_format_i18n( (int) $view_count );
		$listzen_views_html = '<span class="view-number" >' . $view_count . '</span> ' . $listzen_view_html;

		return '<span class="meta-views meta-item ' . $views_class . '">' . $listzen_views_html . '</span> ';
	}


	/**
	 * Post reading time calculate
	 *
	 * @param $content
	 * @param $is_zero
	 * @param $reading_suffix
	 *
	 * @return string
	 */
	public static function reading_time_count( $content = '', $is_zero = false, $reading_suffix = '' ) {
		global $post;
		$post_content = $content ?? $post->post_content;
		$word         = str_word_count( wp_strip_all_tags( strip_shortcodes( $post_content ) ) );
		$m            = floor( $word / 200 );
		$s            = floor( $word % 200 / ( 200 / 60 ) );
		if ( $is_zero && $m < 10 ) {
			$m = '0' . $m;
		}
		if ( $is_zero && $s < 10 ) {
			$s = '0' . $s;
		}
		$suffix = $reading_suffix ? ' ' . $reading_suffix : null;

		/* translators: used time as singular and plular */
		$text = sprintf( _n( '%s Min', '%s Mins', $m, 'listzen' ), $m );

		if ( $m < 1 ) {
			/* translators: used time as singular and plular */
			$text = sprintf( _n( '%s Second', '%s Seconds', $s, 'listzen' ), $s );
		}

		return $text . $suffix;
	}

	/**
	 * Prints HTML with meta information for the current post-date/time.
	 *
	 * @return string
	 */
	public static function posted_on() {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		return sprintf( '<span class="posted-on">%s</span>', $time_string );
	}


	public static function posted_in( $taxonomy = 'category' ) {
		$post_id = get_the_ID();
		$terms   = get_the_terms( $post_id, $taxonomy );

		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		if ( empty( $terms ) ) {
			return false;
		}

		$links = [];

		foreach ( $terms as $term ) {
			$meta_color      = get_term_meta( $term->term_id, 'listzen_category_color', true );
			$color_pref      = Fns::isColorDark( $meta_color ) ? 'dark-bg' : 'bright-bg';
			$meta_color_code = $meta_color ? '--listzen-cat-color:#' . ltrim( $meta_color, '#' ) : '';
			$has_bg          = $meta_color ? "has-bg $color_pref" : '';
			$link            = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) ) {
				return $link;
			}

			$term_class = listzen_class_list( [
				$term->slug,
				$has_bg
			] );

			$links[] = '<a class="' . esc_attr( $term_class ) . '" style="' . esc_attr( $meta_color_code ) . '" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
		}

		$before = "<span class='{$taxonomy}-links'>";
		$after  = "</span>";
		$sep    = self::list_item_separator();

		return $before . implode( $sep, $links ) . $after;
	}

	/**
	 * List Itesm Separator
	 *
	 * @return string
	 */
	public static function list_item_separator() {
		/* translators: Used between list items, there is a space after the comma. */
		return sprintf(
			"<span class='%s'>%s</span>",
			'sp',
			__( ', ', 'listzen' )
		);
	}


	public static function get_icon( $icon ) {
		switch ( $icon ) {
			case 'author':
				return "<i class='rt-icon-user'></i>";
			case 'date' :
				return "<i class='rt-icon-calendar'></i>";
			case 'category' :
				return "<i class='rt-icon-folder'></i>";
			case 'tag' :
				return "<i class='rt-icon-tags'></i>";
			case 'view' :
				return "<i class='rt-icon-eye'></i>";
			case 'reading' :
				return "<i class='rt-icon-signal-alt'></i>";
			default:
				return "<i class='rt-icon-{$icon}'></i>";
		}

	}
}
