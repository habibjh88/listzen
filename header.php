<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package listzen
 */
use Listzen\Options\Opt;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!--Preloader-->
<?php listzen_preloader(); ?>
<div id="listzen-sticky-placeholder"></div>
<div class="listzen-focus"></div>
<div id="page" class="site">

	<?php listzen_header(); ?>
    <div id="content" class="site-content <?php echo esc_attr( listzen_option( 'listzen_blend' ) ); ?>">
		<?php get_template_part( 'template-parts/content-banner', Opt::$banner_style ); ?>
