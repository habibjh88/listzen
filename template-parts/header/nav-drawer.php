<?php
/**
 * Template part for displaying header offcanvas
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Options\Opt;
use Listzen\Helpers\Fns;
?>


<div class="listzen-offcanvas-drawer layout-1">
	<div class="offcanvas-logo site-branding">
		<?php listzen_site_logo( 'main' ); ?>
	</div>

	<div class="drawer-close-btn trigger-off-canvas listzen-cursor">
		<span class="cross-line top-left"></span>
		<span class="cross-line top-right"></span>
		<span class="cross-line bottom-left"></span>
		<span class="cross-line bottom-right"></span>
	</div>
	<div class="menu-wrapper">
		<?php if ( listzen_option( 'listzen_about_label' ) || listzen_option( 'listzen_about_text' ) ) { ?>
			<div class="offcanvas-about offcanvas-address">
				<?php
				if ( listzen_option( 'listzen_about_label' ) ) {
					?>
					<label><?php echo esc_html( listzen_option( 'listzen_about_label' ) ); ?></label><?php } ?>
				<?php
				if ( listzen_option( 'listzen_about_text' ) ) {
					?>
					<p><?php echo esc_html( listzen_option( 'listzen_about_text' ) ); ?></p><?php } ?>
			</div>
		<?php } ?>

		<div class="navigation-menu-wrap">
			<nav class="offcanvas-navigation" role="navigation">
				<?php
				if ( has_nav_menu( 'primary' ) ) :
					wp_nav_menu(
						[
							'theme_location' => 'primary',
							'walker'         => new Listzen\Core\WalkerNav(),
						]
					);
				endif;
				?>
			</nav><!-- .listzen-navigation -->
		</div>
	</div>

	<?php listzen_menu_icons_group(); ?>
	<div class="offcanvas-contact">
		<?php listzen_contact_info( 'offcanvas' ); ?>
	</div>

	<?php if ( listzen_option( 'listzen_offcanvas_social' ) ) { ?>
		<div class="offcanvas-social-icon">
			<?php listzen_get_social_html( 'color' ); ?>
		</div>
	<?php } ?>

</div><!-- .container -->

<div class="listzen-body-overlay"></div>
