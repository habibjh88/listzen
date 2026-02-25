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
use Listzen\Modules\Thumbnail;

?>
<article data-post-id="<?php the_ID(); ?>" <?php post_class( listzen_post_class( '',true ) ); ?>>
	<div class="single-inner-wrapper">
		<?php Thumbnail::get_thumbnail( 'full', true ); ?>
		<div class="entry-wrapper">
			<?php listzen_single_entry_header(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<?php listzen_single_entry_footer(); ?>
			<?php listzen_author_info(); ?>
		</div>
	</div>
</article>

