
<?php
/**
 * Template part for displaying header search
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="listzen-header-search" class="header-search">
	<button type="button" aria-label="close button" class="close">
        <i class="rt-icon-cross-strong"></i>
    </button>
	<form method="get" class="header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" value="<?php echo get_search_query(); ?>" name="s"
		       placeholder="<?php echo esc_attr__( 'Search blog posts...', 'listzen' ); ?>">
        <input type="hidden" name="post_type" value="post">
		<button type="submit" aria-label="submit button" class="search-btn"><i class="rt-icon-search"></i></button>
	</form>
</div>