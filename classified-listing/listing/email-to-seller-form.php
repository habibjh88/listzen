<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form id="rtcl-contact-form" class="form-vertical">
    <div class="rtcl-form-group">
        <input type="text" name="name" class="rtcl-form-control" id="rtcl-contact-name"
               value="<?php echo is_user_logged_in() ? esc_attr( wp_get_current_user()->user_login ) : '' ?>"
               placeholder="<?php esc_attr_e( "Name *", "listzen" ) ?>"
               required/>
    </div>
    <div class="rtcl-form-group">
        <input type="email" name="email" class="rtcl-form-control" id="rtcl-contact-email"
               value="<?php echo is_user_logged_in() ? esc_attr( wp_get_current_user()->user_email ) : '' ?>"
               placeholder="<?php esc_attr_e( "Email *", "listzen" ) ?>"
               required/>
    </div>
    <div class="rtcl-form-group">
        <input type="text" name="phone" class="rtcl-form-control" id="rtcl-contact-phone"
               placeholder="<?php esc_attr_e( "Phone", "listzen" ) ?>"/>
    </div>
    <div class="rtcl-form-group">
        <textarea class="rtcl-form-control" name="message" id="rtcl-contact-message" rows="3"
                  placeholder="<?php esc_attr_e( "Message*", "listzen" ) ?>"
                  required></textarea>
    </div>
	<?php if ( ! empty( listzen_option( 'listing_contact_terms_condition' ) ) ) : ?>
        <div class="rtcl-form-group">
            <label class="terms-and-condition" for="rtcl-terms-condition">
                <input type="checkbox" name="terms" class="form-control" id="rtcl-terms-condition" required/>
				<?php esc_html_e( "I've read and accept the ", "listzen" ) ?>
            </label>
            <a target="_blank" href="<?php echo esc_url( listzen_option( 'listing_contact_terms_condition' ) ) ?>"><?php esc_html_e( "terms & condition", "listzen" ) ?></a>
        </div>
	<?php endif; ?>
    <div id="rtcl-contact-g-recaptcha"></div>
    <p id="rtcl-contact-message-display"></p>

    <button type="submit" class="rtcl-btn rtcl-btn-primary">
        <?php esc_html_e( "Submit", "listzen" ) ?>
    </button>
</form>
