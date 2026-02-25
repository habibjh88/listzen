<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

	<div id="primary" class="content-area">
		<div class="container">
			<main id="main" class="site-main error-404" role="main">
				<?php
				if ( ! empty( listzen_option( 'listzen_error_image' ) ) ) {
					echo wp_get_attachment_image( listzen_option( 'listzen_error_image' ), 'full', true );
				} else {
					listzen_get_img( '404.svg', true, 'width="1007" height="530"' ) . "' alt='";
				}
				?>

				<div class="error-info">
					<h2 class="error-title"><?php listzen_html( listzen_option( 'listzen_error_heading' ), 'allow_title' ); ?></h2>
					<p><?php listzen_html( listzen_option( 'listzen_error_text' ), 'allow_title' ); ?></p>

					<div class="listzen-button">
						<a class="btn btn-secondary btn-404" href="<?php echo esc_url( home_url() ); ?>">
							<div class="btn-wrap">
								<?php listzen_html( listzen_option( 'listzen_error_button_text' ), 'allow_title' ); ?>
							</div>
						</a>
					</div>

				</div>
			</main><!-- #main -->
		</div><!-- container - -->
	</div><!-- #primary -->

<?php
get_footer();
