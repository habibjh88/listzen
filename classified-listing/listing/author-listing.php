<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$author  = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user_id = $author->ID;

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
// Define the query
$paged = Pagination::get_page_number();

$args = [
	'post_type'      => rtcl()->post_type,
	'posts_per_page' => ! empty( $archive_settings['listings_per_page'] ) ? absint( $archive_settings['listings_per_page'] ) : 10,
	'paged'          => $paged,
	'author'         => $user_id,
	/*'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						  [
							  'key'     => '_rtcl_manager_id',
							  'compare' => 'NOT EXISTS'
						  ]
	]*/
];

$user_ads_query = new WP_Query( apply_filters( 'rtcl_user_listing_args', $args ) );

if ( $user_ads_query->have_posts() ) : ?>
    <div class="rtcl-user-listing-list rtcl-user-ad-listing-wrapper">
        <div class="rtcl-section-heading-simple">
            <h3><?php printf( /* translators: User name */ esc_html__( "All ads from \"%s\"", "listzen" ), esc_html( $author->display_name ) ) ?></h3>
        </div>
        <div class="rtcl-listings rtcl-list-view rtcl-listing-wrapper"
             data-pagination='{"max_num_pages":<?php echo esc_attr( $user_ads_query->max_num_pages ) ?>, "current_page": 1, "found_posts":<?php echo esc_attr( $user_ads_query->found_posts ) ?>, "posts_per_page":<?php echo esc_attr( $user_ads_query->query_vars['posts_per_page'] ) ?>}'>
            <!-- the loop -->
			<?php
			while ( $user_ads_query->have_posts() ) : $user_ads_query->the_post();
				$listing = rtcl()->factory->get_listing( get_the_ID() );
				Functions::get_template_part( 'content', 'listing' );
			endwhile; ?>
            <!-- end of the loop -->

            <!-- Use reset postdata to restore original query -->
			<?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>

<?php
if ( ! $user_ads_query->have_posts() ) {
	$paged = Pagination::get_page_number();

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 10,
		'paged'          => $paged,
		'author'         => $user_id,
	];

	$user_post_query = new WP_Query( $args );

	if ( $user_post_query->have_posts() ) : ?>
        <div class="listzen-user-posts">

            <div class="rtcl-section-heading-simple">
                <h3><?php printf( /* translators: User name */ esc_html__( "All ads from \"%s\"", "listzen" ), esc_html( $author->display_name ) ) ?></h3>
            </div>

            <div class="user-post-wrapper">
                <!-- the loop -->
                <div class="total-post-list">
					<?php
					while ( $user_post_query->have_posts() ) : $user_post_query->the_post();
						get_template_part( 'template-parts/blog/content-list', '1' );
					endwhile; ?>
                    <!-- end of the loop -->
                </div>
                <!-- Use reset postdata to restore original query -->
				<?php wp_reset_postdata(); ?>
            </div>
        </div>
	<?php endif;
}