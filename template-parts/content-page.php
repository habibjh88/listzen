<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Modules\Thumbnail;
use Listzen\Options\Opt;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

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
		if ( ! ( Opt::$has_banner && Opt::$breadcrumb_title ) ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		}

		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listzen' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
