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
 * Separator Custom Control
 */
class SeparatorControl extends WP_Customize_Control {

	public $type = 'separator';

	public function render_content() {
		?>
		<p>
		<hr/></p>
		<?php
	}
}
