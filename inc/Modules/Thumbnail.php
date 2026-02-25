<?php

namespace Listzen\Modules;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Thumbnail Class
 */
class Thumbnail {

	/**
	 * Get Thumbnail Markup
	 *
	 * @param $size
	 * @param $single
	 *
	 * @return void
	 */
	public static function get_thumbnail( $size = 'large', $single = false, $thumb_cat = '' ) {
		$classes = 'post-' . ( $single ? 'single' : 'grid' );
		self::default_thumbnail( $size, $single, $thumb_cat, $classes );
	}

	/**
	 * Default thumbnail
	 *
	 * @param $size
	 * @param $single
	 * @param $post_format
	 *
	 * @return void
	 */
	public static function default_thumbnail( $size, $single, $thumb_cat, $classes ) {
		if ( ! self::can_show_post_thumbnail() ) {
			return;
		}
		?>
        <div class="post-thumbnail-wrap <?php echo esc_attr( $classes ); ?> ">
            <figure class="post-thumbnail">
				<?php
				if ( $thumb_cat ) {
					listzen_separate_meta( 'post', $thumb_cat );
				}
				?>
				<?php if ( ! $single ) : ?>
                <a class="post-thumb-link alignwide" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php endif; ?>
					<?php the_post_thumbnail( $size, [ 'loading' => 'lazy', 'class' => 'blog-post-image' ] ); ?>
					<?php if ( ! $single ) : ?>
                </a>
			<?php endif; ?>
				<?php edit_post_link( 'Edit' ); ?>
            </figure><!-- .post-thumbnail -->
			<?php
			if ( $single ) {
				self::thumb_description();
			}
			?>
        </div>
		<?php

	}


	/**
	 * Thumbnail Descriptions
	 *
	 * @return void
	 */
	public static function thumb_description( $thumb_id = null ) {
		$image_id = $thumb_id ?: get_post_thumbnail_id();
		if ( wp_get_attachment_caption( $image_id ) ) :
			?>
            <figcaption class="wp-caption-text">
                <i class='rt-icon-camera'></i>
                <span><?php listzen_html( wp_get_attachment_caption( $image_id ) ); ?></span>
            </figcaption>
		<?php
		endif;
	}

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
}
