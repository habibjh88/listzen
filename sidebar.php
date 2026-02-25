<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Listzen\Helpers\Fns;

if ( is_singular( 'post' ) && is_active_sidebar( Fns::default_sidebar( 'single' ) ) ) {
	listzen_sidebar( Fns::default_sidebar( 'single' ) );
}  elseif ( is_page() && is_active_sidebar( Fns::default_sidebar( 'page' ) ) ) {
	listzen_sidebar( Fns::default_sidebar( 'page' ) );
} else {
	listzen_sidebar( Fns::default_sidebar( 'main' ) );
}

