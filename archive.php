<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Helpers\Fns;
use Listzen\Modules\Pagination;

get_header();
$listzen_blog_classes = listzen_option( 'listzen_blog_masonry' ) ? 'listzen-masonry-grid' : '';
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$listzen_blog_style = isset( $_GET['style'] ) ? sanitize_text_field( wp_unslash( $_GET['style'] ) ) : listzen_option( 'listzen_blog_style' );
?>
	<div id="primary" class="content-area">
		<div class="container">
			<div class="row">
				<div class="<?php echo esc_attr( Fns::content_columns() ); ?>">
					<main id="main" class="site-main" role="main">
						<div class="row <?php echo esc_attr( $listzen_blog_classes ); ?> ">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();
									get_template_part( 'template-parts/blog/content', $listzen_blog_style );
								endwhile;
							else :
								get_template_part( 'template-parts/content', 'none' );
							endif;
							?>
						</div>
						<?php Pagination::pagination(); ?>
					</main><!-- #main -->
				</div><!-- .col- -->
				<?php get_sidebar(); ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #primary -->
<?php
get_footer();
