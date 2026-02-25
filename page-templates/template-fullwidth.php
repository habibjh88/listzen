<?php
/**
 * Template Name: Fullwidth Template No title
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package RadiusDocs
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use RT\RadiusDocs\Helpers\Fns;

get_header(); ?>
    <div id="primary" class="content-area">
        <div class="container-fluid">

            <main id="main" class="site-main" role="main">

				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail-wrap">
                                <figure class="post-thumbnail">
									<?php the_post_thumbnail( 'full', [ 'loading' => 'lazy' ] ); ?>
									<?php edit_post_link( 'Edit' ); ?>
                                </figure><!-- .post-thumbnail -->
                            </div>
						<?php endif; ?>
                        <div class="entry-content">
							<?php
							the_content();

							wp_link_pages(
								[
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listzen' ),
									'after'  => '</div>',
								]
							);
							?>
                        </div><!-- .entry-content -->

                    </article><!-- #post-## -->

					<?php

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile;

				?>

            </main><!-- #main -->

        </div><!-- .container -->
    </div>

<?php
get_footer();
