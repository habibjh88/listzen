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

		?>
        <div class="rtcl-listings rtcl-ajax-listings <?php echo esc_attr( $class ); ?> ">
			<?php

			while ( $the_loops->have_posts() ) :
				$the_loops->the_post();
				$_id                 = get_the_ID();
				$post_meta           = get_post_meta( $_id );
				$listing             = new Listing( $_id );
				$phone               = get_post_meta( $_id, 'phone', true );
				$listing_title       = null;
				$listing_meta        = null;
				$listing_description = null;
				$img                 = null;
				$labels              = null;
				$u_info              = null;
				$time                = null;
				$location            = null;
				$category            = null;
				$price               = null;
				$types               = null;
				$img_position_class  = '';
				$custom_field        = null;
				$author_image        = null;
				$listing_rating      = null;
				?>

                <div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item', 'rtcl-listing-card', 'style-category-thumb', $img_position_class ] ); ?>>
					<?php
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
								"<div class='listing-thumb'>%s<a href='%s' title='%s'>%s</a></div>",
								$mark_as_sold,
								get_the_permalink(),
								esc_html( get_the_title() ),
								$the_thumbnail
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
							' <h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a> </h3>',
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

					ob_start();
					?>
                    <div class="rtin-bottom">
                        <div class="rtcl-meta-buttons-withtext">
							<?php if ( $phone && $instance['rtcl_show_phone'] ) : ?>
                                <div class="rtin-phn rtin-el-button">
                                    <a class="rtcl-phone-reveal not-revealed" href="tel:<?php echo esc_attr( $phone ); ?>" data-phone="<?php echo esc_attr( $phone ); ?>"><i
                                                class="rtcl-icon rt-icon-phone" aria-hidden="true"></i><span><?php esc_html_e( 'Show Phone No',
												'listzen' ); ?></span></a>
                                </div>
							<?php endif; ?>
							<?php if ( Functions::is_enable_favourite() && $instance['rtcl_show_favourites'] ) : ?>
                                <div class="rtin-fav rtcl-text-el-button">
									<?php echo wp_kses_post( Functions::get_favourites_link( $_id ) );; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                                </div>
							<?php endif; ?>

							<?php
							if ( rtcl()->has_pro() ) {
								if ( ! empty( $instance['rtcl_show_quick_view'] ) ) {
									?>
                                    <div class="rtin-quick-view rtcl-text-el-button">
                                        <a class="rtcl-quick-view" href="#" data-listing_id="<?php echo absint( $_id ); ?>">
                                            <i class="rt-icon-search-2"></i><span><?php esc_html_e( 'Quick View',
													'listzen' ); ?></span>
                                        </a>
                                    </div>
									<?php
								}
							}
							?>

							<?php
							if ( rtcl()->has_pro() ) {
								if ( ! empty( $instance['rtcl_show_compare'] ) ) {
									$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? array_map( 'absint', $_SESSION['rtcl_compare_ids'] ) : [];
									$selected_class = '';
									if ( is_array( $compare_ids ) && in_array( $_id, $compare_ids ) ) {
										$selected_class = ' selected';
									}
									?>
                                    <div class="rtin-compare rtcl-text-el-button">
                                        <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                           data-listing_id="<?php echo absint( $_id ); ?>">
                                            <i class="rt-icon-compare-alt"></i><span><?php esc_html_e( 'Compare',
													'listzen' ); ?></span>
                                        </a>
                                    </div>
									<?php
								}
							}
							?>
                        </div>
                    </div>
					<?php
					$rtin_bottom        = ob_get_clean();
					$item_content_right = sprintf(
						'<div class="rtin-right"> %s </div>',
						$rtin_bottom
					);
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
