<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

use Listzen\Helpers\CLFns;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use Rtcl\Models\Listing;
use RtclPro\Controllers\Hooks\TemplateHooks;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="rtcl rtcl-listings-sc-wrapper rtcl-elementor-widget">
    <div class="rtcl-listings-wrapper">
		<?php
		$class = '';
		$class .= ! empty( $view ) ? 'rtcl-' . $view . '-view ' : 'rtcl-list-view ';
		$class .= ! empty( $style ) ? 'rtcl-' . $style . '-view ' : 'rtcl-style-1-view ';
		$class .= ! empty( $instance['rtcl_listings_column'] ) ? 'columns-' . $instance['rtcl_listings_column'] . ' ' : 'columns-1';
		$class .= ! empty( $instance['rtcl_col_sm'] ) ? 'tab-columns-' . $instance['rtcl_col_sm'] . ' ' : 'tab-columns-2';
		$class .= ! empty( $instance['rtcl_col_xs'] ) ? 'mobile-columns-' . $instance['rtcl_col_xs'] . ' ' : 'mobile-columns-2';
		?>
        <div class="rtcl-listings rtcl-ajax-listings <?php echo esc_attr( $class ); ?> ">
			<?php

			while ( $the_loops->have_posts() ) :
				$the_loops->the_post();
				$_id                     = get_the_ID();
				$post_meta               = get_post_meta( $_id );
				$listing                 = new Listing( $_id );
				$listing_title           = null;
				$listing_meta            = null;
				$listing_description     = null;
				$img                     = null;
				$labels                  = null;
				$time                    = null;
				$location                = null;
				$category                = null;
				$price                   = null;
				$types                   = null;
				$img_position_class      = '';
				$item_details_page_link  = null;
				$item_content_right      = null;
				$item_content_right_meta = null;
				$custom_field            = null;
				$author_image            = null;
				$listing_rating          = null;
				$views_html              = $views_html ?? null;
				?>

                <div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item', 'rtcl-listing-card', 'style-category-thumb', $img_position_class ] ); ?>>

					<?php
					$button_icon = 0;
					ob_start();
					if ( $instance['rtcl_show_favourites'] ) {
						$button_icon ++;
						?>
                        <div class="rtcl-fav rtcl-el-button">
							<?php echo wp_kses_post( Functions::get_favourites_link( $_id ) );; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
					<?php } ?>
					<?php
					$dispaly_favourites = ob_get_clean();
					?>

					<?php
					ob_start();
					if ( rtcl()->has_pro() ) {
						if ( ! empty( $instance['rtcl_show_quick_view'] ) ) :
							?>
                            <div class="rtcl-el-button">
                                <a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'listzen' ); ?>"
                                   data-listing_id="<?php echo absint( $_id ); ?>">
                                    <i class="rt-icon-search-2"></i>
                                </a>
                            </div>
							<?php
							$button_icon ++;
						endif;
					}
					$dispaly_quick_view = ob_get_clean();
					?>

					<?php ob_start(); ?>
					<?php
					if ( rtcl()->has_pro() ) {
						if ( ! empty( $instance['rtcl_show_compare'] ) ) :
							?>
                            <div class="rtcl-el-button">
								<?php
								$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? array_map( 'absint', $_SESSION['rtcl_compare_ids'] ) : [];
								$selected_class = '';
								if ( is_array( $compare_ids ) && in_array( $_id, $compare_ids ) ) {
									$selected_class = ' selected';
								}
								?>
                                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                   title="<?php esc_attr_e( 'Compare', 'listzen' ); ?>"
                                   data-listing_id="<?php echo absint( $_id ); ?>">
                                    <i class="rt-icon-compare-alt"></i>
                                </a>
                            </div>
							<?php
							$button_icon ++;
						endif;
					}
					?>
					<?php
					$dispaly_compare = ob_get_clean();

					$action_button_layout = $instance['rtcl_action_button_layout'];
					$button               = sprintf( '<div class="rtcl-meta-buttons-wrap %s-layout meta-button-count-%s">%s %s %s</div>', $action_button_layout,
						$button_icon, $dispaly_favourites, $dispaly_quick_view, $dispaly_compare );

					if ( $instance['rtcl_show_image'] ) {
						ob_start();
						if ( rtcl()->has_pro() ) {
							TemplateHooks::sold_out_banner();
						}
						$mark_as_sold = ob_get_clean();

						$image_size    = $instance['rtcl_thumb_image_size'];
						$the_thumbnail = $listing->get_the_thumbnail( $image_size );

						if ( $the_thumbnail ) {
							$img = sprintf(
								"<div class='listing-thumb'><div class='listing-thumb-inner'>%s<a href='%s' title='%s'>%s</a>%s</div> %s </div>",
								$mark_as_sold,
								get_the_permalink(),
								esc_html( get_the_title() ),
								$the_thumbnail,
								$button,
								$button
							);
						}
					}
					if ( $instance['rtcl_show_labels'] ) {
						$labels = $listing->badges();
					}

					if ( $instance['rtcl_show_price'] ) {
						$price_html = $listing->get_price_html();
						$price      = sprintf( '<div class="item-price">%s</div>', $price_html );
					}

					$listing_meta = CLFns::toolkit_listing_meta( $listing, $instance );

					if ( $instance['rtcl_show_category'] ) {
						$category = sprintf(
							'<div class="category">%s</div>',
							$listing->the_categories( false, true )
						);
					}

					if ( $instance['rtcl_show_title'] ) {
						$listing_title = sprintf(
							'<h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a></h3>',
							get_the_permalink(),
							esc_html( get_the_title() )
						);
					}

					if ( $instance['rtcl_show_description'] ) {
						$excerpt = get_the_excerpt( $_id );

						if ( $instance['rtcl_content_limit'] ) {
							$listing_description = sprintf(
								'<div class="rtcl-short-description"> %s </div>',
								wp_kses_post( wp_trim_words( wpautop( $excerpt ), $instance['rtcl_content_limit'] ) )
							);
						} else {
							$listing_description = sprintf(
								'<div class="rtcl-short-description"> %s </div>',
								wp_kses_post( wpautop( $excerpt ) )
							);
						}
					}

					if ( $types || $views_html ) {
						$item_content_right_meta = sprintf(
							'<ul class="rtcl-listing-meta-data"> %s %s</ul>',
							$types,
							$views_html,
						);
					}
					if ( $item_content_right_meta || $item_details_page_link ) {
						$item_content_right = sprintf(
							'<div class="rtin-right"> %s %s</div>',
							$item_content_right_meta,
							$item_details_page_link
						);
					}
					if ( ! empty( $instance['rtcl_show_custom_fields'] ) ) {
						ob_start();
						if ( rtcl()->has_pro() ) {
							TemplateHooks::loop_item_listable_fields();
						}
						$custom_field = ob_get_clean();
					}
					$show_author  = $instance['rtcl_show_author_image'] ?? true;
					$show_arating = $instance['rtcl_show_rating'] ?? true;

					if ( $show_author ) {
						ob_start();
						?>
                        <div class="rtcl-author-info">
							<?php CLFns::get_listing_author_image( $listing ); ?>
                        </div>
						<?php
						$author_image = ob_get_clean();
					}
					if ( $show_arating ) {
						ob_start();
						CLFns::listing_rating( $listing );
						$listing_rating = ob_get_clean();
					}

					$item_content   = sprintf(
						'<div class="item-content">%s %s %s %s %s %s %s %s %s </div>%s',
						$author_image,
						$listing_rating,
						$labels,
						$category,
						$listing_title,
						$custom_field,
						$listing_meta,
						$listing_description,
						$price,
						$item_content_right
					);
					$final_contents = sprintf( '%s <div class="rtin-content-area">%s</div>', $img, $item_content );
					echo wp_kses_post( $final_contents );
					?>

                </div>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>

        </div>
		<?php if ( ! empty( $instance['rtcl_listing_pagination'] ) ) { ?>
			<?php Pagination::pagination( $the_loops, true ); ?>
		<?php } ?>
    </div>
</div>
