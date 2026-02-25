<?php
/**
 * Template part for single thumbn pagination
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$listzen_previous = get_previous_post();
$listzen_next     = get_next_post();
$listzen_cols     = ( $listzen_previous && $listzen_next ) ? 'two-cols' : 'one-cols';
?>
<?php if ( listzen_option( 'listzen_single_navigation_visibility' ) ) { ?>
    <div class="single-post-pagination <?php echo esc_attr( $listzen_cols ) ?>">
		<?php if ( $listzen_previous ):
			$listzen_prev_image = get_the_post_thumbnail_url( $listzen_previous, 'thumbnail' ); ?>

            <div class="post-navigation prev">
                <a href="<?php echo esc_url( get_permalink( $listzen_previous ) ); ?>" class="nav-title">
                    <i class="rt-icon-arrow-left"></i>
					<?php esc_html_e( 'Previous Post: ', 'listzen' ) ?>
                </a>

                <a href="<?php echo esc_url( get_permalink( $listzen_previous ) ); ?>" class="link pg-prev">
					<?php if ( $listzen_prev_image ) : ?>
                        <div class="post-thumb" style="background-image:url(<?php echo esc_url( $listzen_prev_image ) ?>)"></div>
					<?php endif; ?>
                    <p class="item-title"><?php listzen_html( get_the_title( $listzen_previous ) ); ?></p>
                </a>
            </div>
		<?php endif; ?>

		<?php if ( $listzen_next ):
			$listzen_next_image = get_the_post_thumbnail_url( $listzen_next, 'thumbnail' ); ?>

            <div class="post-navigation next text-right">
                <a href="<?php echo esc_url( get_permalink( $listzen_next ) ); ?>" class="nav-title">
					<?php esc_html_e( 'Next Post: ', 'listzen' ) ?>
                    <i class="rt-icon-arrow-<?php esc_attr( is_rtl() ? 'left' : 'right' ); ?>"></i>
                </a>
                <a href="<?php echo esc_url( get_permalink( $listzen_next ) ); ?>" class="link pg-next">
                    <p class="item-title"><?php listzen_html( get_the_title( $listzen_next ) ); ?></p>
					<?php if ( $listzen_next_image ) : ?>
                        <div class="post-thumb" style="background-image:url(<?php echo esc_url( $listzen_next_image ) ?>)"></div>
					<?php endif; ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
<?php } ?>
