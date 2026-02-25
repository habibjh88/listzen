<?php
/**
 * Template part for displaying footer
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$listzen_footer_width     = 'container' . listzen_option( 'listzen_footer_width' );
$listzen_copyright_center = listzen_option( 'listzen_social_footer' ) ? 'justify-content-between' : 'justify-content-center';
?>

<?php if ( is_active_sidebar( 'listzen-footer-sidebar' ) ) : ?>
	<div class="footer-widgets-wrapper">

		<div class="footer-shape">
			<ul>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>

		<div class="footer-container <?php echo esc_attr( $listzen_footer_width ) ?>">
			<div class="footer-widgets row">
				<?php dynamic_sidebar( 'listzen-footer-sidebar' ); ?>
			</div>
		</div>
	</div><!-- .site-info -->
<?php endif; ?>

<?php if ( ! empty( listzen_option( 'listzen_footer_copyright' ) ) ) : ?>
	<div class="footer-copyright-wrapper">
		<div class="footer-container <?php echo esc_attr( $listzen_footer_width ) ?>">

			<div class="copyright-text-wrap d-flex align-items-center <?php echo esc_attr( $listzen_copyright_center ); ?>">
				<div class="copyright-text">
					<?php listzen_html( str_replace( '[y]', gmdate( 'Y' ), listzen_option( 'listzen_footer_copyright' ) ) ); ?>
				</div>
				<?php if ( listzen_option( 'listzen_social_footer' ) ) { ?>
					<div class="social-icon d-flex gap-20 align-items-center">
						<div class="social-icon d-flex column-gap-10">
							<?php if ( listzen_option( 'listzen_follow_us_label' ) ) { ?><label><?php listzen_html( listzen_option( 'listzen_follow_us_label' ), 'allow_title' ) ?></label><?php } ?>
							<?php listzen_get_social_html( '#555' ); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

	</div>
<?php endif; ?>
