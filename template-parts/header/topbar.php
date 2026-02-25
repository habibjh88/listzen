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
use Listzen\Options\Opt;

if ( ! Opt::$has_top_bar ) {
	return;
}
$listzen_fullwidth = Opt::$header_width == 'full' ? '-fluid' : '';
?>

<div class="listzen-topbar">
	<div class="topbar-container container<?php echo esc_attr( $listzen_fullwidth ) ?>">
		<div class="row topbar-row">
			<nav id="topbar-menu" class="listzen-navigation pr-10" role="navigation">
				<?php
				wp_nav_menu( [
					'theme_location' => 'topbar',
					'menu_class'     => 'listzen-navbar',
					'items_wrap'     => '<ul id="%1$s" class="%2$s listzen-topbar-menu">%3$s</ul>',
					'fallback_cb'    => 'listzen_custom_menu_cb',
					'walker'         => has_nav_menu( 'primary' ) ? new Listzen\Core\WalkerNav() : '',
				] );
				?>
			</nav><!-- .topbar-navigation -->

			<ul class="topbar-right d-flex gap-15 align-items-center">
				<li class="social-icon">
					<?php if ( listzen_option( 'listzen_follow_us_label' ) ) { ?>
						<label><?php echo esc_html(listzen_option( 'listzen_follow_us_label' )) ?></label>
					<?php } ?>
					<?php listzen_get_social_html(); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
