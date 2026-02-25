<?php

namespace Listzen\Helpers;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Rtcl\Helpers\Functions;
use RtclAgent;
use RtclPro\Helpers\Fns;
use Listzen\Helpers\Fns as ThemeFns;

/**
 * Theme Functions
 */
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
class CLFns {
	/**
	 * Get Listing author image
	 *
	 * @param $listing
	 * @param $size
	 * @param $default
	 * @param $args
	 *
	 * @return void
	 */

	static public function get_listing_author_image( $listing, $size = 150 ) {
		$pp_id = absint( get_user_meta( $listing->get_owner_id(), '_rtcl_pp_id', true ) );
		// Generate author image
		if ( $pp_id ) {
			$author_image = wp_get_attachment_image( $pp_id, [ $size, $size ], false, [ 'class' => 'author-avatar' ] );
		} else {
			$author_image = get_avatar( $listing->get_author_id(), $size, '', '', [ 'class' => 'author-avatar' ] );
		}

		// Wrap with user link if allowed
		if ( $listing->can_add_user_link() ) {
			echo '<a href="' . esc_url( $listing->get_the_author_url() ) . '">';
            listzen_html( $author_image );
			echo '</a>';
		} else {
            listzen_html( $author_image );
		}
	}

	static function has_rtcl_agent() {
		return class_exists( RtclAgent::class );
	}

	/**
	 * Check if author_image enable from settings
	 *
	 * @return bool
	 */
	static function can_show_author_image() {
		$display_option = Functions::get_display_options();

		return in_array( 'author_image', $display_option );
	}


	/**
	 * Check if rating enable from settings
	 *
	 * @return bool
	 */
	static function can_show_rating() {
		$display_option = Functions::get_display_options();

		return in_array( 'rating', $display_option );
	}


	public static function listng_categories( $listing ) {
		if ( $listing->has_category() && $listing->can_show_category() ) :
			$categories = $listing->get_categories();
			if ( ! empty( $categories ) ) {
				?>
                <div class="listing-categories">
					<?php
					foreach ( $categories as $category ) {
						if ( ! empty( $glue ) ) {
							listzen_html( $glue );
						}
						//$cat_image   = get_term_meta( $category->term_id, '_rtcl_image', true );
						$cat_icon    = get_term_meta( $category->term_id, '_rtcl_icon', true );
						$_icon_class = $cat_icon ?: ' rt-icon-folder-open';
						?>
                        <a class="listzen-term" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
							<?php printf( "<i class='rtcl-icon rtcl-icon-%s'></i>", esc_attr( $_icon_class ) ); ?>
							<?php echo esc_html( $category->name ); ?>
                        </a>
						<?php
						$glue = '<span class="rtcl-delimiter">,</span>';
					}
					?>
                </div>
			<?php }
		endif;
	}

	/**
	 * Check if author_image enable from settings
	 *
	 * @return bool
	 */
	public static function can_show_phone_number() {
		$display_option = Functions::get_display_options();

		return in_array( 'phone_number', $display_option );
	}

	/**
	 * Return listing style enable by checking the query string value
	 *
	 * @return false|mixed|string|string[]
	 */
	public static function listing_archive_style() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['style'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['style'] ) );
		}

		return listzen_option( 'listzen_listing_style' );
	}


	/**
	 * Return listing style enable by checking the query string value
	 * @options gallery, gallery-full, gallery-split, gallery-grid,
	 * @return false|mixed|string|string[]
	 */
	public static function listing_gallery_style() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['gallery_style'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['gallery_style'] ) );
		}

		return listzen_option( 'listzen_listing_gallery_style' );
	}

	public static function listing_gallery_height() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['gallery_height'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['gallery_height'] ) );
		}

		return listzen_option( 'listzen_listing_gallery_height' );
	}

	public static function listing_slider_cols() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['slider_cols'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return sanitize_text_field( wp_unslash( $_GET['slider_cols'] ) );
		}

		$slider_cols = listzen_option( 'listzen_listing_slider_cols' );

		if ( 'layout-3' === self::listing_style() && $slider_cols == 1 ) {
			$slider_cols = 4; //Default column for layout layout-3
		}

		return $slider_cols;

	}

	public static function listing_style() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['style'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['style'] ) );
		}

		return listzen_option( 'listzen_listing_layout' );
	}

	public static function is_separate_video() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['separate_video'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['separate_video'] ) );
		}

		return listzen_option( 'listzen_listing_video_separate' );
	}

	public static function is_slider_gallery_enabled() {

		if ( ! Functions::is_gallery_slider_enabled() ) {
			return false;
		}

		if ( in_array( self::listing_style(), [ 'layout-2', 'layout-3' ] ) ) {
			return false;
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['thumbs_gallery'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return boolval( sanitize_text_field( wp_unslash( $_GET['thumbs_gallery'] ) ) );
		}

		return boolval( listzen_option( 'listzen_listing_slider_thumb_gallery' ) );
	}

	/**
	 * Return if listing collapsible sidebar enable by checking the query string value
	 *
	 * @return bool
	 */
	public static function is_collapsible_sidebar() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['collapsible'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return boolval( sanitize_text_field( wp_unslash( $_GET['collapsible'] ) ) );
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['layout'] ) && 'fullwidth' === $_GET['layout'] ) {
			return true;
		}

		if ( 'fullwidth' == ThemeFns::layout() ) {
			return true;
		}


		return boolval( listzen_option( 'listzen_sidebar_collapsed' ) );
	}

	/**
	 * Check is listing style italic or not
	 *
	 * @return bool
	 */
	public static function is_listing_italic() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['italic'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return boolval( sanitize_text_field( wp_unslash( $_GET['italic'] ) ) );
		}

		return boolval( listzen_option( 'listzen_listing_italic' ) );
	}

	/**
	 * Return if listing map enable by checking the query string value
	 *
	 * @return bool|int|mixed|null
	 */
	public static function is_map_enable() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['map'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return boolval( sanitize_text_field( wp_unslash( $_GET['map'] ) ) );
		}
		$map_visibility = Functions::get_option_item( 'rtcl_archive_listing_settings', 'enable_archive_map', '' );

		return 'yes' === $map_visibility;
	}

	/**
	 * Return map positioin by checking the query string value
	 *
	 * @return false|mixed|string|string[]
	 */
	public static function map_position() {
		$prefix = 'map-view-';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['map_pos'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $prefix . sanitize_text_field( wp_unslash( $_GET['map_pos'] ) );
		}

		return $prefix . listzen_option( 'listzen_map_pos' );
	}

	/**
	 * Return archive container width by checking the query string value
	 *
	 * @return false|mixed|string|string[]
	 */
	public static function listing_container_width() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['con_width'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_text_field( wp_unslash( $_GET['con_width'] ) );
		}

		return listzen_option( 'listzen_container_width' );
	}

	public static function listing_rating( $listing ) {
		if ( ! ThemeFns::is_cl_pro_active() ) {
			return;
		}
		$average      = $listing->get_average_rating();
		$rating_count = $listing->get_rating_count();

		if ( $rating_count > 0 ) { ?>
            <div class="reviews-rating">
				<?php listzen_html( Fns::get_rating_html( $average, $rating_count ) ); ?>
				<?php

				$reviewCount = sprintf(
				/* translators: %s: number of reviews */
					_n( '(%s Review)', '(%s Reviews)', $rating_count, 'listzen' ),
					number_format_i18n( $rating_count )
				);
				?>

                <span class="reviews-rating-count"><?php echo esc_html( $reviewCount ) ?></span>
            </div>
			<?php
		}
	}

	/**
	 * Get Store object by listing owner id
	 *
	 * @param $author_id
	 *
	 * @return bool|\RtclStore\Models\Store
	 */
	public static function get_store_by_owner_id( $author_id ) {
		if ( ! class_exists( 'RtclStore' ) ) {
			return null;
		}
		$store_id = get_posts( [
			'post_type'      => rtclStore()->post_type,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'   => 'store_owner_id',
					'value' => $author_id,
				]
			],
		] );

		if ( ! empty( $store_id ) ) {
			return rtclStore()->factory->get_store( $store_id[0] );
		}

		return null; // or handle fallback
	}

	/**
	 * Listing comapre button markup
	 *
	 * @param $listing_id
	 *
	 * @return void
	 */
	public static function listing_compare( $listing_id ) {
		if ( ! ( class_exists( 'RtclPro' ) && class_exists( 'RtclStore' ) ) ) {
			return;
		}
		if ( ! Fns::is_enable_compare() ) {
			return;
		}
		$compare_ids = [];
		if ( ! empty( $_SESSION['rtcl_compare_ids'] ) ) {
			$compare_ids = array_map( 'absint', $_SESSION['rtcl_compare_ids'] );
		}

		$selected_class = '';
		if ( is_array( $compare_ids ) && in_array( $listing_id, $compare_ids ) ) {
			$selected_class = ' selected';
		}
		?>
        <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" title="<?php esc_attr_e( "Compare", "listzen" ) ?>"
           data-original-title="<?php esc_attr_e( "Compare", "listzen" ) ?>" data-listing_id="<?php echo absint( $listing_id ) ?>">
            <i class="rt-icon-compare-alt"></i>
            <span class="compare-label"><?php echo esc_html__( "Compare", "listzen" ); ?></span>
        </a>
		<?php
	}

	public static function listing_video( $videos, $images, $listing ) {
		global $has_rtcl_gallery_clomun;
		$total_gallery_videos = count( $videos );
		$gallery_size         = 'rtcl-gallery';
		if ( $total_gallery_videos ) {
			foreach ( $videos as $index => $video_url ) {
				$video_image = Functions::get_embed_video_thumbnail_url( $video_url, 'maxresdefault' );

				if ( ! ThemeFns::is_image_url_valid( $video_image ) || stripos( $video_image, 'placeholder.jpg' ) !== false ) {
					$featured_image = get_post_thumbnail_id( $listing->get_id() );
					foreach ( $images as $key => $item ) {
						if ( isset( $item->ID ) && $item->ID == $featured_image ) {
							unset( $images[ $key ] );
						}
					}
					$video_image = wp_get_attachment_image_url( $featured_image, $gallery_size );
				}
				?>
                <div class="swiper-slide rtcl-slider-item rtcl-slider-video-item <?php echo esc_attr( $has_rtcl_gallery_clomun ) ?>">
                    <iframe class="rtcl-lightbox-iframe"
                            data-src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ) ?>"
                            src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ) ?>"
                            style="width: 100%; height: 400px; margin: 0;padding: 0; background-color: #000"
                            allow="autoplay; fullscreen"
                            frameborder="0" webkitAllowFullScreen
                            mozallowfullscreen allowFullScreen></iframe>

                    <div class="rtcl-video-overlay rtcl-slider-video-thumb rtclVideoPlaceholder">
                        <span class="play-btn">
                            <i class="rt-icon-play-solid"></i>
                        </span>
                        <img src="<?php echo esc_url( $video_image ) ?>" class="rtcl-responsive-img"/>
                    </div>
                </div>
				<?php
			}
		}
	}

	public static function get_post_terms( $post_id, $taxonomy ) {
		$data = '';


		$terms = get_the_terms( $post_id, $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$term_spans = [];

			foreach ( $terms as $term ) {
				$term_spans[] = '<span>' . esc_html( trim( $term->name ) ) . '</span>';
			}

			$data = implode( ' ', $term_spans ); // Use space between spans
		}


		return $data;
	}

	public static function get_display_options_archive() {
		$settings = Functions::get_option( 'rtcl_archive_listing_settings' );
		$key      = 'display_options';

		return ! empty( $settings[ $key ] ) && is_array( $settings[ $key ] ) ? $settings[ $key ] : [];
	}

	/**
	 * Listing image share functionality
	 *
	 * @param $images
	 * @param $listing
	 *
	 * @return array|mixed
	 */
	public static function get_listing_images( $images, $listing ) {

		if ( ! empty( $images ) ) {
			return $images;
		}
		$_share_listing = get_post_meta( $listing->get_id(), 'rtcl_share_gallery', true );

		if ( ! $_share_listing ) {
			return $images;
		}
		$images      = Functions::get_listing_images( intval( $_share_listing ) );
		$featured_id = get_post_thumbnail_id( $listing->get_id() );

		if ( $featured_id && is_array( $images ) ) {
			usort( $images, function ( $a, $b ) use ( $featured_id ) {
				if ( $a->ID == $featured_id ) {
					return - 1;
				}
				if ( $b->ID == $featured_id ) {
					return 1;
				}

				return 0;
			} );
		}

		return $images;
	}

	public static function group_menu_items( $items ) {
		$grouped       = [];
		$current_group = - 1;

		foreach ( $items as $item ) {
			// New group start
			if ( isset( $item['enable_group_title'] ) && $item['enable_group_title'] === 'yes' ) {
				$current_group ++;
				$grouped[ $current_group ] = [
					'group_title' => $item['menu_name'],
					'items'       => []
				];
			} else {
				// Add menu item to current group
				if ( $current_group >= 0 ) {
					$grouped[ $current_group ]['items'][] = $item;
				}
			}
		}

		return $grouped;
	}

	public static function toolkit_listing_meta( $listing, $instance ) {
		$types = $author_html = $time = $location = $views_html = $phone_number = '';
		if ( $instance['rtcl_show_date'] ) {
			$time = sprintf(
				'<li class="date"><i class="rtcl-icon rt-icon-clock" aria-hidden="true"></i>%s</li>',
				$listing->get_the_time()
			);
		}
		if ( $instance['rtcl_show_location'] ) {
			if ( wp_strip_all_tags( $listing->the_locations( false ) ) ) {
				$location = sprintf(
					'<li class="location"><i class="rtcl-icon rt-icon-marker" aria-hidden="true"></i>%s</li>',
					$listing->the_locations( false, true )
				);
			}
		}
		if ( $instance['rtcl_show_phone_number'] ) {
			$phone = get_post_meta( $listing->get_id(), 'phone', true );
			if ( wp_strip_all_tags( $listing->the_locations( false ) ) ) {
				$phone_number = sprintf(
					'<li class="phone"><i class="rtcl-icon rt-icon-phone" aria-hidden="true"></i><a href="tel:%s">%s</a></li>',
					esc_attr( $phone ),
					esc_html( $phone )
				);
			}
		}

		if ( $instance['rtcl_show_user'] ) {
			ob_start();
			if ( ! empty( $instance['rtcl_verified_user_base'] ) ) {
				do_action( 'rtcl_after_author_meta', $listing->get_owner_id() );
			}
			$after_author_meta = ob_get_clean();
			$author_html       = sprintf( '<li class="author" ><i class="rtcl-icon rt-icon-user" aria-hidden="true"></i>%s %s</li>', get_the_author(), $after_author_meta );
		}

		if ( $instance['rtcl_show_views'] ) {
			$views      = absint( get_post_meta( get_the_ID(), '_views', true ) );
			$views_html = sprintf(
				'<li class="view"><i class="rtcl-icon rt-icon-eye" aria-hidden="true"></i>%s</li>',
				sprintf(
				/* translators: %s: views count */
					_n( '%s view', '%s views', $views, 'listzen' ),
					number_format_i18n( $views )
				)
			);
		}

		if ( $instance['rtcl_show_types'] && $listing->get_ad_type() ) {
			$listing_types = Functions::get_listing_types();
			$types         = ! empty( $listing_types[ $listing->get_ad_type() ] ) ? $listing_types[ $listing->get_ad_type() ] : '';
			if ( $types ) {
				$types = sprintf(
					'<li class="rtin-type"><i class="rt-icon-tags" aria-hidden="true"></i>%s</li>',
					$types
				);
			}
		}
		if ( $types || $author_html || $time || $location || $views_html || $phone_number ) {
			return sprintf( '<ul class="rtcl-listing-meta-data">%s %s %s %s %s %s</ul>', $types, $author_html, $time, $location, $phone_number, $views_html );
		}

		return '';
	}

	/**
	 * Remove unnecessary zero after point
	 *
	 * @param $value
	 *
	 * @return mixed|string
	 */
	public static function rt_remove_unnecessary_zero( $value, $return_type = '' ) {
		if ( strpos( $value, '.' ) ) {
			[ $a, $b ] = explode( ".", $value );

			if ( $return_type == '1' ) {
				return $a;
			}

			if ( $return_type == '2' ) {
				return $b;
			}

			if ( ! array_filter( str_split( $b ) ) ) {
				$value = $a;
			} else {
				$value = $a . '.' . rtrim( $b, '0' );
			}
		}

		return $value;
	}

	/**
	 * Number Shorten
	 *
	 * @param $number
	 * @param $precision
	 * @param $divisors
	 *
	 * @return mixed|string
	 */
	public static function rt_number_shorten( $number, $precision = 3, $divisors = null ) {
		$number = str_replace( ',', '', $number );
		if ( $number < 1000 ) {
			return $number;
		}

		$thousand    = _x( 'K', 'Thousand Shorthand', 'listzen' );
		$million     = _x( 'M', 'Million Shorthand', 'listzen' );
		$billion     = _x( 'B', 'Billion Shorthand', 'listzen' );
		$trillion    = _x( 'T', 'Trillion Shorthand', 'listzen' );
		$quadrillion = _x( 'Qa', 'Quadrillion Shorthand', 'listzen' );
		$quintillion = _x( 'Qi', 'Quintillion Shorthand', 'listzen' );

		$shorthand_label = apply_filters( 'listzen_shorthand_price_label', [
			'thousand'    => $thousand,
			'million'     => $million,
			'billion'     => $billion,
			'trillion'    => $trillion,
			'quadrillion' => $quadrillion,
			'quintillion' => $quintillion,
		] );

		// Setup default $divisors if not provided
		if ( ! isset( $divisors ) ) {
			$divisors = [
				pow( 1000, 0 ) => '', // 1000^0 == 1
				pow( 1000, 1 ) => isset( $shorthand_label['thousand'] ) ? $shorthand_label['thousand'] : $thousand,
				pow( 1000, 2 ) => isset( $shorthand_label['million'] ) ? $shorthand_label['million'] : $million,
				pow( 1000, 3 ) => isset( $shorthand_label['billion'] ) ? $shorthand_label['billion'] : $billion,
				pow( 1000, 4 ) => isset( $shorthand_label['trillion'] ) ? $shorthand_label['trillion'] : $trillion,
				pow( 1000, 5 ) => isset( $shorthand_label['quadrillion'] ) ? $shorthand_label['quadrillion'] : $quadrillion,
				pow( 1000, 6 ) => isset( $shorthand_label['quintillion'] ) ? $shorthand_label['quintillion'] : $quintillion,
			];
		}

		// Loop through each $divisor and find the
		// lowest amount that matches
		foreach ( $divisors as $divisor => $shorthand ) {
			if ( abs( $number ) < ( $divisor * 1000 ) ) {
				// We found a match!
				break;
			}
		}

		// We found our match, or there were no matches.
		// Either way, use the last defined value for $divisor.

		$shorthand_price = apply_filters( 'listzen_shorthand_price', number_format( $number / $divisor, $precision ) );

		return self::rt_remove_unnecessary_zero( $shorthand_price ) . "<span class='price-shorthand'>{$shorthand}</span>";
	}

	/**
	 * Number to K, Lac, Cr convert
	 *
	 * @param $number
	 *
	 * @return mixed|string
	 */
	public static function number_to_lac( $number, $precision = 1 ) {
		$number = str_replace( ',', '', $number );

		$hundred   = '';
		$thousand  = _x( 'K', 'Thousand Shorthand', 'listzen' );
		$thousands = _x( 'K', 'Thousands Shorthand', 'listzen' );
		$lac       = _x( ' Lac', 'Lac Shorthand', 'listzen' );
		$lacs      = _x( ' Lacs', 'Lacs Shorthand', 'listzen' );
		$cr        = _x( ' Cr', 'Cr Shorthand', 'listzen' );
		$crs       = _x( ' Crs', 'Crs Shorthand', 'listzen' );

		$shorthand_label = apply_filters( 'listzen_shorthand_price_label_2', [
			'hundred'   => $hundred,
			'thousand'  => $thousand,
			'thousands' => $thousands,
			'lac'       => $lac,
			'lacs'      => $lacs,
			'crore'     => $cr,
			'crores'    => $crs,
		] );

		if ( $number == 0 ) {
			return '';
		} else {
			$n_count = strlen( self::rt_remove_unnecessary_zero( $number, '1' ) ); // 7
			switch ( $n_count ) {
				case 3:
					$val       = $number / 100;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['hundred'] ) ? $shorthand_label['hundred'] : $hundred );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 4:
					$val       = $number / 1000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['thousand'] ) ? $shorthand_label['thousand'] : $thousand );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 5:
					$val       = $number / 1000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['thousands'] ) ? $shorthand_label['thousands'] : $thousands );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 6:
					$val       = $number / 100000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['lac'] ) ? $shorthand_label['lac'] : $lac );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 7:
					$val       = $number / 100000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['lacs'] ) ? $shorthand_label['lacs'] : $lacs );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 8:
					$val       = $number / 10000000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['crore'] ) ? $shorthand_label['crore'] : $cr );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				case 8 < $n_count:
					$val       = $number / 10000000;
					$val       = number_format( $val, $precision );
					$shorthand = ( isset( $shorthand_label['crores'] ) ? $shorthand_label['crores'] : $crs );
					$finalval  = self::rt_remove_unnecessary_zero( $val ) . "<span class='price-shorthand'>{$shorthand}</span>";
					break;
				default:
					$finalval = $number;
			}

			return $finalval;
		}
	}

	/**
	 * @param $num
	 *
	 * @return string
	 */
	public static function money_format_india( $num ) {
		$num  = str_replace( ',', '', $num );
		$nums = explode( ".", $num );

		if ( count( $nums ) > 2 ) {
			return "0";
		} else {
			if ( count( $nums ) == 1 ) {
				$nums[1] = "";
			}
			$num           = $nums[0];
			$explrestunits = "";
			if ( strlen( $num ) > 3 ) {
				$lastthree = substr( $num, strlen( $num ) - 3, strlen( $num ) );
				$restunits = substr( $num, 0, strlen( $num ) - 3 );
				$restunits = ( strlen( $restunits ) % 2 == 1 ) ? "0" . $restunits : $restunits;
				$expunit   = str_split( $restunits, 2 );
				for ( $i = 0; $i < sizeof( $expunit ); $i ++ ) {
					if ( $i == 0 ) {
						$explrestunits .= (int) $expunit[ $i ] . ",";
					} else {
						$explrestunits .= $expunit[ $i ] . ",";
					}
				}
				$thecash = $explrestunits . $lastthree;
			} else {
				$thecash = $num;
			}

			return $thecash . ( $nums[1] ? "." . $nums[1] : '' );
		}
	}

	static function business_hour_pos() {
		return listzen_option( 'listzen_listing_business_hour_pos', 'sidebar' );
	}
}
