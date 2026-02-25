<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<form role="search" method="get" class="listzen-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-box">
		<input type="text" class="search-query form-control" placeholder="<?php esc_attr_e( 'Search here ...', 'listzen' ) ?>" value="<?php echo get_search_query(); ?>" name="s">
		<button class="item-btn" type="submit">
			<span class="listzen-icon-search"><i class="rt-icon-search"></i></span>
		</button>
	</div>
</form>
