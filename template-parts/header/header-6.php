<?php
/**
 * Template part for displaying header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Helpers\Fns;
use Listzen\Options\Opt;

$listzen_menu_classes = Fns::menu_alignment( 'start' );
$listzen_fullwidth   = Opt::$header_width == 'full' ? '-fluid' : '';
?>

    <div class="main-header-section">
        <div class="header-container container<?php echo esc_attr( $listzen_fullwidth ) ?>">
            <div class="d-flex navigation-menu-wrap align-middle m-0">
                <div class="site-branding">
					<?php listzen_site_logo(); ?>
                </div><!-- .site-branding -->
                <nav class="listzen-navigation  <?php echo esc_attr( $listzen_menu_classes ) ?>" role="navigation">
					<?php
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_class'     => 'listzen-navbar',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						'fallback_cb'    => 'listzen_custom_menu_cb',
						'walker'         => has_nav_menu( 'primary' ) ? new Listzen\Core\WalkerNav() : '',
					] );
					?>
                </nav><!-- .listzen-navigation -->
				<?php listzen_menu_icons_group( [ 'login_text' => true ] ); ?>
            </div><!-- .row -->
        </div><!-- .container -->
    </div>
<?php

