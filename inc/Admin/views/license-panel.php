<?php
/**
 * Theme License Panel
 *
 * @package Listzen
 */

use Listzen\Admin\LicenseController;
use Listzen\Helpers\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$listzen_license     = LicenseController::instance();
$listzen_license_key = $listzen_license->get_license_key();
$listzen_active      = $listzen_license->is_license_active();
$listzen_status      = $listzen_active ? 'valid' : 'invalid';
$listzen_doc_url     = Constants::EDD_STORE_URL;
$listzen_data        = $listzen_license->get_license_data();
$listzen_expires_raw = ! empty( $listzen_data['license_expires'] ) ? $listzen_data['license_expires'] : '';
$listzen_expires     = '';
if ( $listzen_active && $listzen_expires_raw ) {
	if ( 'lifetime' === $listzen_expires_raw ) {
		$listzen_expires = esc_html__( 'Lifetime license', 'listzen' );
	} else {
		$ts = strtotime( $listzen_expires_raw );
		if ( $ts ) {
			$listzen_expires = sprintf(
				/* translators: %s: expiration date */
				esc_html__( 'Expires: %s', 'listzen' ),
				date_i18n( get_option( 'date_format' ), $ts )
			);
		}
	}
}
?>
<div class="listzen-license-panel" data-status="<?php echo esc_attr( $listzen_status ); ?>">
	<div class="listzen-license-card">
		<div class="listzen-license-header">
			<div class="listzen-license-header-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
			</div>
			<div class="listzen-license-header-text">
				<h2><?php esc_html_e( 'Theme License', 'listzen' ); ?></h2>
				<p><?php esc_html_e( 'Activate your license to receive automatic updates, premium features, and priority support.', 'listzen' ); ?></p>
			</div>
		</div>

		<div class="listzen-license-body">
			<div class="listzen-license-status-row">
				<span class="listzen-license-status-label"><?php esc_html_e( 'License status', 'listzen' ); ?></span>
				<span class="listzen-license-status-badge <?php echo $listzen_active ? 'is-active' : 'is-inactive'; ?>">
					<span class="listzen-status-dot" aria-hidden="true"></span>
					<span class="listzen-status-text">
						<?php echo $listzen_active ? esc_html__( 'Activated', 'listzen' ) : esc_html__( 'Not Activated', 'listzen' ); ?>
					</span>
				</span>
			</div>

			<div class="listzen-license-field">
				<label for="listzen_license_key" class="listzen-license-field-label">
					<?php esc_html_e( 'License key', 'listzen' ); ?>
				</label>
				<div class="listzen-license-input-wrap">
					<input
						type="password"
						id="listzen_license_key"
						name="license_key"
						class="listzen-license-input"
						value="<?php echo esc_attr( $listzen_license_key ); ?>"
						placeholder="<?php esc_attr_e( 'Enter your license key', 'listzen' ); ?>"
						autocomplete="off"
						spellcheck="false"
						<?php echo $listzen_active ? 'readonly' : ''; ?>
					/>
					<button
						type="button"
						class="listzen-license-toggle"
						aria-label="<?php esc_attr_e( 'Toggle license key visibility', 'listzen' ); ?>"
					>
						<svg class="listzen-icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
						<svg class="listzen-icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
					</button>
				</div>
				<p class="listzen-license-hint">
					<?php
					printf(
						/* translators: %s: account URL */
						wp_kses(
							/* translators: %s: account URL */
							__( 'Your license key can be found in your <a href="%s" target="_blank" rel="noopener">account dashboard</a> after purchase.', 'listzen' ),
							[
								'a' => [
									'href'   => [],
									'target' => [],
									'rel'    => [],
								],
							]
						),
						esc_url( $listzen_doc_url . '/checkout/purchase-history/' )
					);
					?>
				</p>
			</div>

			<div class="listzen-license-actions">
				<button
					type="button"
					class="button button-primary listzen-license-btn listzen-license-activate"
					data-action="license_activate"
					<?php echo $listzen_active ? 'disabled' : ''; ?>
				>
					<span class="listzen-btn-spinner" aria-hidden="true"></span>
					<span class="listzen-btn-text"><?php esc_html_e( 'Activate License', 'listzen' ); ?></span>
				</button>

				<button
					type="button"
					class="button listzen-license-btn listzen-license-deactivate"
					data-action="license_deactivate"
					<?php echo $listzen_active ? '' : 'disabled'; ?>
				>
					<span class="listzen-btn-spinner" aria-hidden="true"></span>
					<span class="listzen-btn-text"><?php esc_html_e( 'Deactivate License', 'listzen' ); ?></span>
				</button>
			</div>

			<div class="listzen-license-notice" role="status" aria-live="polite" hidden></div>

			<?php if ( $listzen_expires ) : ?>
				<p class="listzen-license-expires"><?php echo esc_html( $listzen_expires ); ?></p>
			<?php endif; ?>
		</div>

		<div class="listzen-license-footer">
			<span class="listzen-license-footer-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
			</span>
			<span>
				<?php
				printf(
					/* translators: 1: docs link, 2: support link */
					wp_kses(
						/* translators: 1: docs link, 2: support link */
						__( 'Need help? Read the <a href="%1$s" target="_blank" rel="noopener">documentation</a> or <a href="%2$s" target="_blank" rel="noopener">contact support</a>.', 'listzen' ),
						[
							'a' => [
								'href'   => [],
								'target' => [],
								'rel'    => [],
							],
						]
					),
					esc_url( 'https://radiustheme.com/demo/wordpress/themes/listzen/docs' ),
					esc_url( $listzen_doc_url . '/ticket-support/' )
				);
				?>
			</span>
		</div>
	</div>
</div>
