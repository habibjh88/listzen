<?php

namespace Listzen\Modules;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Pagination Class
 */
class Pagination {
	public static function pagination( $wp_query = null ) {
		if ( ! $wp_query ) {
			global $wp_query;
		}
		/** Stop execution if there's only 1 page */
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		/**    Add current page to the array */
		if ( $paged >= 1 ) {
			$links[] = $paged;
		}

		/**    Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		echo '<div class="' . esc_attr( listzen_option( 'listzen_blog_pagination_style' ) ) . '"><ul class="pagination-list d-flex flex-wrap align-items-center justify-content-center column-gap-8">' . "\n";

		$previous_text = "<i class='rt-icon-arrow-small-left'></i>";
		$next_text     = "<i class='rt-icon-arrow-small-right'></i>";
		/**    Previous Post Link */
		if ( get_previous_posts_link() ) {
			?>
            <li><?php listzen_html( get_previous_posts_link( $previous_text ) ); ?></li>
			<?php
		}

		/**    Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class = ( 1 == $paged ) ? 'active' : '';

			printf( '<li class="%s"><a href="%s">%s</a></li>' . "\n",
				esc_attr( $class ),
				esc_url( get_pagenum_link( 1 ) ),
				esc_html__( '1', 'listzen' )
			);

			if ( ! in_array( 2, $links ) ) {
				echo '<li>...</li>';
			}
		}

		/**    Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = ( $paged == $link ) ? 'active' : '';
			printf( '<li class="%s"><a href="%s">%s</a></li>' . "\n",
				esc_attr( $class ),
				esc_url( get_pagenum_link( $link ) ),
				esc_html( $link )
			);
		}

		/**    Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ) {
				echo '<li>...</li>' . "\n";
			}

			$class = ($paged == $max) ? 'active' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n",
                esc_attr( $class ),
                esc_url( get_pagenum_link( $max ) ),
                esc_html( $max )
            );
		}

		/**    Next Post Link */
		if ( get_next_posts_link() ) {
			printf( '<li>%s</li>' . "\n", get_next_posts_link( $next_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</ul></div>' . "\n";
	}

}

