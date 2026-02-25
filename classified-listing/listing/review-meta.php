<?php
/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/listing/review-meta.php.
 *
 * @author RadiousTheme
 * @package classified-listing/Templates
 * @version 1.0.0
 */

use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use Listzen\Helpers\Fns as ThemeFns;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );


if ( '0' === $comment->comment_approved ) { ?>
    <p class="meta">
        <em class="rtcl-review-awaiting-approval">
            <?php esc_html_e( 'Your review is awaiting approval', 'listzen' ); ?>
        </em>
    </p>
<?php } else { ?>
    <div class="media-author">
        <span class="rtcl-review-author"><?php comment_author(); ?> </span>
    </div>
    <div class="comment-meta-info">
        <time class="rtcl-review-published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>">
            <?php echo esc_html( Functions::datetime( 'time-elapsed', get_comment_date( Functions::date_format() ) ) ); ?>
        </time>
        <?php
        if ( ThemeFns::is_cl_pro_active() && $rating && Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox' ) ) {
            listzen_html( Fns::get_rating_html( $rating ) );
        }
        ?>
    </div>
    <?php
}
