<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var boolean $can_add_favourites
 * @var boolean $can_report_abuse
 * @var boolean $social
 * @var integer $has_compare_icon
 * @var integer $has_bookmark_icon
 * @var integer $has_print_icon
 * @var integer $listing_id
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Text;
use \Listzen\Helpers\CLFns;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! $can_add_favourites && ! $can_report_abuse && ! $social && ! $has_compare_icon && ! $has_bookmark_icon && ! $has_print_icon ) {
	return;
}
?>
    <ul class='rtcl-single-listing-action'>
		<?php do_action( 'rtcl_single_action_before_list_item', $listing_id ); ?>

		<?php if ( $has_compare_icon ) : ?>
            <li>
				<?php CLFns::listing_compare( $listing_id ); ?>
            </li>
		<?php endif; ?>

		<?php if ( $can_add_favourites ): ?>
            <li id="rtcl-favourites">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo Functions::get_favourites_link( $listing_id ); ?>
            </li>
		<?php endif; ?>

		<?php if ( $can_report_abuse ): ?>
            <li>
				<?php if ( is_user_logged_in() ): ?>
                    <a href="javascript:void(0)" data-toggle="modal" id="rtcl-report-abuse-modal-link">
                        <i class='rtcl-icon rt-icon-trash'></i>
                        <span class="abuse-label"><?php echo esc_html( Text::report_abuse() ); ?></span>
                    </a>
				<?php else: ?>
                    <a href="javascript:void(0)" class="rtcl-require-login">
                        <i class='rtcl-icon rt-icon-trash'></i>
                        <span class="abuse-label"><?php echo esc_html( Text::report_abuse() ); ?></span>
                    </a>
				<?php endif; ?>
            </li>
		<?php endif; ?>

		<?php if ( $social ): ?>
            <li class="rtcl-sidebar-social">
                <a href="javascript:void(0)">
                    <i class="rt-icon-share"></i>
                </a>
                <div class="social-icon">
					<?php echo wp_kses_post( $social ); ?>
                </div>
            </li>
		<?php endif; ?>

		<?php if ( $has_print_icon ) : ?>
            <li>
                <a href="#" onclick="window.print();">
                    <i class="rt-icon-print"></i>
                    <span class="print-label"><?php echo esc_html__( "Print", "listzen" ) ?></span>
                </a>
            </li>
		<?php endif; ?>

		<?php if ( $has_bookmark_icon ) : ?>
            <li>
                <a href="javascript:void(0);" id="rtcl-bookmark-button">
                    <i class="rt-icon-bookmark"></i>
                    <span class="bookmark-label"><?php echo esc_html__( "Bookmark", "listzen" ) ?></span>
                </a>
            </li>
		<?php endif; ?>

	    <?php do_action( 'rtcl_single_action_after_list_item', $listing_id ); ?>

    </ul>

<?php do_action( 'rtcl_single_listing_after_action', $listing_id ); ?>

<?php if ( $can_report_abuse ) { ?>
    <div class="rtcl-popup-wrapper" id="rtcl-report-abuse-modal">
        <div class="rtcl-popup">
            <div class="rtcl-popup-content">
                <div class="rtcl-popup-header">
                    <h5 class="rtcl-popup-title" id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'listzen' ); ?></h5>
                    <a href="#" class="rtcl-popup-close">×</a>
                </div>
                <div class="rtcl-popup-body">
                    <form id="rtcl-report-abuse-form">
                        <div class="rtcl-form-group">
                            <label class="rtcl-field-label" for="rtcl-report-abuse-message">
								<?php esc_html_e( 'Your Complaint', 'listzen' ); ?>
                                <span class="rtcl-star">*</span>
                            </label>
                            <textarea name="message" class="rtcl-form-control" id="rtcl-report-abuse-message" rows="3"
                                      placeholder="<?php esc_attr_e( 'Message... ', 'listzen' ); ?>"
                                      required></textarea>
                        </div>
                        <div id="rtcl-report-abuse-g-recaptcha"></div>
                        <div id="rtcl-report-abuse-message-display"></div>
                        <button type="submit"
                                class="rtcl-btn rtcl-btn-primary"><?php esc_html_e( 'Submit', 'listzen' ); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>