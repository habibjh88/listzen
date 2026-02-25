<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
?>
    <div id="primary" class="content-area">
        <div class="container">
            <div class="row align-stretch">
                <div class="<?php echo esc_attr( Fns::content_columns() ); ?>">
                    <main id="main" class="site-main" role="main">
                        <div class="row blog-wrapper-row <?php echo esc_attr( $listzen_blog_classes ); ?>">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();
									get_template_part( 'template-parts/blog/content', Fns::blog_layout() );
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

