<?php
/**
 * Template part for displaying banner content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */

use Listzen\Modules\Breadcrumb;
use Listzen\Options\Opt;
use Listzen\Helpers\Fns;
use Rtcl\Helpers\Breadcrumb as RtclBreadcrumb;
use Rtcl\Helpers\Functions;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! Fns::has_banner() ) {
	return;
}

if ( Fns::is_cl_active() && Functions::is_account_page() ) {
	return;
}

if ( class_exists( RtclBreadcrumb::class ) ) {
	$breadcrumbs = new RtclBreadcrumb();
} else {
	$breadcrumbs = new Breadcrumb();
}
if ( listzen_option( 'listzen_breadcrumb_thumb_enable' ) && is_single() && has_post_thumbnail( get_the_ID() ) ) {
	$image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
} else {
	$_image_url = wp_get_attachment_image_src( Opt::$banner_image, 'full' );
	$image_url  = $_image_url[0];
}
$banner_image_css = '';
$banner_image_css .= isset( $image_url ) ? "background-image:url({$image_url});" : '';
$banner_image_css .= Fns::customize_image_attr_css( 'listzen_banner_image_attr' );
$has_image        = isset( $image_url );
$classes          = Fns::class_list( [
	'listzen-breadcrumb-wrapper',
	$has_image ? 'has-bg' : 'no-bg',
	Opt::$banner_color ? 'has-color' : 'no-color',
	listzen_option( 'listzen_banner_color_mode' ),
] );

$args       = [
	'delimiter' => "<span class='custom-long-dash'></span>",
	'before'    => '',
	'after'     => '',
];
$home_title = _x( 'Home', 'breadcrumb', 'listzen' );
$breadcrumbs->add_crumb( $home_title, home_url() ); //Home crumb
$args['breadcrumb'] = $breadcrumbs->generate();

if ( empty( $args['breadcrumb'] ) ) {
	return;
}

if ( is_single() && ! listzen_option( 'listzen_breadcrumb' ) ) {
	return;
}
?>

<div class="<?php echo esc_attr( $classes ) ?>">
	<?php if ( $has_image ) : ?>
        <span class="banner-image" style="<?php echo esc_attr( $banner_image_css ) ?>"></span>
	<?php endif; ?>
    <div class="container d-flex flex-column <?php echo esc_attr( listzen_option( 'listzen_breadcrumb_alignment' ) ) ?>">
		<?php if ( Opt::$breadcrumb_title && ! is_single() ) : ?>
            <h1 class="bread-title">
				<?php
				$page_title = end( $args['breadcrumb'] );
				if ( is_post_type_archive() || ( is_home() && ! is_front_page() ) ) {
					$post_type     = str_replace( '-', '_', get_post_type() );
					$archive_title = listzen_option( "listzen_banner_title_{$post_type}" );
					if ( $archive_title ) {
						echo esc_html( $archive_title );
					} else if ( is_array( $page_title ) && isset( $page_title[0] ) ) {
						echo esc_html( $page_title[0] );
					}
				} else if ( is_array( $page_title ) && isset( $page_title[0] ) ) {
					echo esc_html( $page_title[0] );
				}
				?>
            </h1>
		<?php endif;

		if ( is_singular( 'post' ) ) {
			$archive_title = listzen_option( "listzen_banner_title_single_post" );
			if ( $archive_title ) {
				echo '<div class="bread-title">' . esc_html( $archive_title ) . '</div>';
			}
		}
		if ( listzen_option( 'listzen_breadcrumb' ) ) {
			if ( class_exists( RtclBreadcrumb::class ) ) {
				Functions::breadcrumb( $args );
			} else {
				$breadcrumbs->print_breadcrumb( $args );
			}
		}
		?>
    </div>
</div>
