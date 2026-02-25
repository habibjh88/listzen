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
$listzen_copyright_center = listzen_option( 'listzen_social_footer' ) ? 'has-footer-social' : 'no-footer-social';
$listzen_social_label     = listzen_option( 'listzen_follow_us_label' );
?>

<?php if ( is_active_sidebar( 'listzen-footer-sidebar' ) ) : ?>
    <div class="footer-widgets-wrapper">
        <div class="footer-container <?php echo esc_attr( $listzen_footer_width ) ?>">
            <div class="footer-widgets row">
                <?php dynamic_sidebar( 'listzen-footer-sidebar' ); ?>
            </div>
        </div>
    </div><!-- .site-info -->
<?php endif; ?>

<?php if ( ! empty( listzen_option( 'listzen_footer_copyright' ) ) ) : ?>
    <div class="footer-copyright-wrapper text-center">
        <div class="footer-container <?php echo esc_attr( $listzen_footer_width . ' ' . $listzen_copyright_center ) ?>">
            <div class="row">
                <div class="col-md-3">
                    <div class="site-branding">
                        <?php listzen_site_logo( 'light' ); ?>
                    </div>
                </div>

                <div class="col-md-6 copyright-text">
                    <div class="copyright-text-inner">
                        <?php listzen_html( str_replace( '[y]', gmdate( 'Y' ), listzen_option( 'listzen_footer_copyright' ) ) ); ?>
                    </div>
                </div>

                <?php if ( listzen_option( 'listzen_social_footer' ) ) : ?>
                    <div class="col-md-3 social-icon">
                        <div class="social-icon-inner">
                            <?php if ( $listzen_social_label ) : ?>
                                <span class="social-label"><?php listzen_html( $listzen_social_label, 'allow_title' ) ?></span>
                            <?php endif; ?>

                            <?php listzen_get_social_html(); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php endif; ?>
