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
$listzen_topinfo    = ( listzen_option( 'listzen_contact_address' ) || listzen_option( 'listzen_phone' ) || listzen_option( 'listzen_email' ) ) ? true : false;
$listzen_fullwidth = Opt::$header_width == 'full' ? '-fluid' : '';
?>

<div class="listzen-topbar">
    <div class="topbar-container container<?php echo esc_attr( $listzen_fullwidth ) ?>">
        <div class="row topbar-row">
			<?php if ( $listzen_topinfo ) { ?>
                <div class="topbar-contact mr-auto">
					<?php listzen_contact_info( 'topbar' ) ?>
                </div>
			<?php } ?>
            <ul class="topbar-right ml-auto">
                <li class="social-icon">
					<?php if ( listzen_option( 'listzen_follow_us_label' ) ) { ?>
                        <label><?php echo esc_html( listzen_option( 'listzen_follow_us_label' ) ) ?></label>
					<?php } ?>
					<?php listzen_get_social_html(); ?>
                </li>
            </ul>
        </div>
    </div>
</div>
