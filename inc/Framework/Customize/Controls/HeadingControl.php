<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace Listzen\Framework\Customize\Controls;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use WP_Customize_Control;

/**
 * Toggle Switch Custom Control
 */
class HeadingControl extends WP_Customize_Control {

	public $type = 'custom_heading';

	public $reset;

	public function render_content() {
		?>
		<div class="listzen-framework-custom-headding">
			<?php
			if ( isset( $this->label ) && '' !== $this->label ) {
				echo '<span class="customize-control-heading">' . esc_html( $this->label ) . '</span>';
			}

			if ( isset( $this->reset ) ) {
				?>
				<br>
				<a class="button" href="<?php echo esc_url( home_url( '/wp-admin/customize.php' ) ); ?>?reset_theme_mod=1">
					<?php echo esc_html__( 'Reset Customize', 'listzen' ); ?>
				</a>
			<?php } ?>
		</div>
		<?php
	}
}

