<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use Listzen\Helpers\CLFns;
use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use RtclPro\Controllers\Hooks\TemplateHooks;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$data = [
	'template'              => 'elementor/listing-ads-slider/slider-header',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );

// $i = 0 ;
while ( $the_loops->have_posts() ) :

	$the_loops->the_post();
	$_id                 = get_the_ID();
	$post_meta           = get_post_meta( $_id );
	$listing             = new Listing( $_id );
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
	$img_position_class  = '';
	$types               = null;
	$custom_field        = null;
	$author_image        = null;
	$listing_rating      = null;
	// $i++;
	?>

    <div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item swiper-slide-customize', 'rtcl-listing-card', 'style-category-thumb', $img_position_class ] ); ?> style="">

		<?php
		$button_icon = 0;
		ob_start();
		if ( $instance['rtcl_show_favourites'] ) {
			$button_icon ++;
			?>
            <div class="rtcl-fav rtcl-el-button">
				<?php echo wp_kses_post( Functions::get_favourites_link( $_id ) ); ?>
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
                    <a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'listzen' ); ?>" data-listing_id="<?php echo absint( $_id ); ?>">
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
                    <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" title="<?php esc_attr_e( 'Compare', 'listzen' ); ?>" data-listing_id="<?php echo absint( $_id ); ?>">
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
		$button               = sprintf( '<div class="rtcl-meta-buttons-wrap %s-layout meta-button-count-%s">%s %s %s</div>', $action_button_layout, $button_icon, $dispaly_favourites, $dispaly_quick_view, $dispaly_compare );


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
					"<div class='listing-thumb'><div class='listing-thumb-inner'>%s<a href='%s' title='%s'>%s</a> %s</div></div>",
					$mark_as_sold,
					get_the_permalink(),
					esc_html( get_the_title() ),
					$the_thumbnail,
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
			$excerpt             = wp_trim_words( get_the_excerpt( $_id ), $instance['rtcl_content_limit'], '' );
			$listing_description = sprintf(
				'<div class="rtcl-short-description"> %s </div>',
				esc_html( wpautop( $excerpt ) )
			);
		}

		$item_content_right = sprintf(
			'<div class="rtin-right">%s </div>',
			$price
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
			'<div class="item-content">%s %s %s %s %s %s %s %s %s</div>',
			$author_image,
			$listing_rating,
			$labels,
			$category,
			$listing_title,
			$custom_field,
			$listing_meta,
			$listing_description,
			$item_content_right
		);
		$final_contents = sprintf( '%s <div class="rtin-content-area">%s</div>', $img, $item_content );
		echo wp_kses_post( $final_contents );
		?>

    </div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>


<?php

$data = [
	'template'              => 'elementor/listing-ads-slider/slider-footer',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
