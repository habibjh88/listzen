<?php
/**
 * Template part for displaying content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Modules\PostMeta;
use Listzen\Modules\Thumbnail;
?>

<article data-post-id="<?php the_ID(); ?>" <?php post_class( listzen_post_class('listzen-post-card') ); ?>>
	<div class="article-inner-wrapper">

		<?php Thumbnail::get_thumbnail(); ?>

		<div class="entry-wrapper">
			<header class="entry-header">
				<?php
				if ( listzen_option( 'listzen_blog_above_meta_visibility' ) ) {
					listzen_separate_meta();
				}

				the_title( sprintf( '<h3 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' );
				?>
			</header>
			<?php

			if ( listzen_option( 'listzen_meta_visibility' ) ) {
				PostMeta::get_meta();
			}

			if ( listzen_option( 'listzen_blog_content_visibility' ) ) : ?>
				<div class="entry-content">
					<?php listzen_entry_content() ?>
				</div>
			<?php endif; ?>

			<?php
			if ( listzen_option( 'listzen_blog_readmore_visibility' ) ) {
				listzen_entry_footer();
			}
			?>
		</div>
	</div>
</article>
