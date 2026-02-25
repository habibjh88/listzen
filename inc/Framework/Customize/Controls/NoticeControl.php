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
 * Simple Notice Custom Control
 */
class NoticeControl extends WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'simple_notice';

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$allowed_html = [
			'a'      => [
				'href'   => [],
				'title'  => [],
				'class'  => [],
				'target' => [],
			],
			'br'     => [],
			'em'     => [],
			'strong' => [],
			'i'      => [
				'class' => [],
			],
			'span'   => [
				'class' => [],
			],
			'code'   => [],
		];
		?>
		<div class="simple-notice-custom-control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo wp_kses( $this->description, $allowed_html ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}

