<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
</div><!-- #content -->
<?php listzen_footer(); ?>

</div><!-- #page -->
<?php listzen_scroll_top(); ?>
<?php wp_footer(); ?>
<div class="listzen-mouse-cursor outer"></div>
<div class="listzen-mouse-cursor inner"></div>
</body>
</html>
