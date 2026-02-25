<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Helpers\Fns;
use Listzen\Modules\RelatedPost;
get_header();
?>

    <div id="primary" class="content-area">
        <div class="single-post-container">
			<?php while ( have_posts() ) :
				the_post();
				 ?>
                <div class="single-inner">
                    <div class="container">
                        <div class="row content-row">
                            <div class="content-col <?php echo esc_attr( Fns::single_content_colums() ); ?>">
                                <main id="main" class="site-main single-content" role="main">
									<?php
									get_template_part( 'template-parts/blog-details/content', 'single' );
									//post thumbnail navigation
									get_template_part( 'template-parts/custom/single', 'pagination' );

									wp_link_pages( array(
										'before' => '<div class="page-links">' . __( 'Pages:', 'listzen'),
										'after'  => '</div>',
									) );

									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) :
										comments_template();
									endif;
									?>
                                </main><!-- #main -->
                            </div><!-- .col- -->
							<?php get_sidebar(); ?>
                        </div><!-- .row -->
						<?php do_action( 'listzen_after_single_content' ); ?>
                    </div><!-- .container -->
                </div>
			<?php endwhile; ?>
			<?php RelatedPost::listzen_post_related(); ?>
        </div>
    </div><!-- #primary -->
<?php
get_footer();
