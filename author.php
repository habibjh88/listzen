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
use Listzen\Modules\Pagination;
use Listzen\Helpers\Fns;
get_header();
?>
	<div id="primary" class="content-area">
		<div class="container">

			<?php listzen_author_info(); ?>

			<div class="row">
				<div class="<?php echo esc_attr( Fns::content_columns() ); ?>">
					<main id="main" class="site-main" role="main">
						<div class="row listzen-masonry-grid">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();
									get_template_part( 'template-parts/content', listzen_option( 'listzen_blog_style' ) );
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
