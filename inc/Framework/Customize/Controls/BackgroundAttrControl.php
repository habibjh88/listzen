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
 * Slider Custom Control
 */
class BackgroundAttrControl extends WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'bg_attribute_control';

	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'listzen-custom-controls-js', listzen_get_assets('customize/js/customizer.js'), [ 'jquery', 'jquery-ui-core' ], '1.2', true );
		wp_enqueue_style( 'listzen-custom-controls-css', listzen_get_assets('customize/css/customizer.css'), [], '1.0', 'all' );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$current_value = json_decode( $this->value() );
		?>
		<div class="listzen-background-attributes">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<div class="bg-attribute">
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>"
				       name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>"
				       class="customize-control-background-atts" <?php $this->link(); ?> />

				<div class="bg-attr-item">
					<label>Position</label>
					<select class="listzen-bg-position" control-name="<?php echo esc_attr( $this->id ); ?>">
						<option value="">Default</option>
						<option value="center center" <?php selected( 'center center', $current_value->position ); ?>>Center Center</option>
						<option value="center left" <?php selected( 'center left', $current_value->position ); ?>>Center Left</option>
						<option value="center right" <?php selected( 'center right', $current_value->position ); ?>>Center Right</option>
						<option value="top center" <?php selected( 'top center', $current_value->position ); ?>>Top Center</option>
						<option value="top left" <?php selected( 'top left', $current_value->position ); ?>>Top Left</option>
						<option value="top right" <?php selected( 'top right', $current_value->position ); ?>>Top Right</option>
						<option value="bottom center" <?php selected( 'bottom center', $current_value->position ); ?>>Bottom Center</option>
						<option value="bottom left" <?php selected( 'bottom left', $current_value->position ); ?>>Bottom Left</option>
						<option value="bottom right" <?php selected( 'bottom right', $current_value->position ); ?>>Bottom Right</option>
						<option value="initial" <?php selected( 'initial', $current_value->position ); ?>>Custom</option>
					</select>
				</div>

				<div class="bg-attr-item">
					<label>Attachment</label>
					<select class="listzen-bg-attachment" data-setting="background_attachment">
						<option value="">Default</option>
						<option value="scroll" <?php selected( 'scroll', $current_value->attachment ); ?>>Scroll</option>
						<option value="fixed" <?php selected( 'fixed', $current_value->attachment ); ?>>Fixed</option>
					</select>
				</div>

				<div class="bg-attr-item">
					<label>Repeat</label>
					<select class="listzen-bg-repeat" data-setting="background_repeat">
						<option value="">Default</option>
						<option <?php selected( 'no-repeat', $current_value->repeat ); ?> value="no-repeat">No-repeat</option>
						<option <?php selected( 'repeat', $current_value->repeat ); ?> value="repeat">Repeat</option>
						<option <?php selected( 'repeat-x', $current_value->repeat ); ?> value="repeat-x">Repeat-x</option>
						<option <?php selected( 'repeat-y', $current_value->repeat ); ?> value="repeat-y">Repeat-y</option>
					</select>
				</div>

				<div class="bg-attr-item">
					<label>Size</label>
					<select class="listzen-bg-size" data-setting="background_size">
						<option value="">Default</option>
						<option <?php selected( 'auto', $current_value->size ); ?> value="auto">Auto</option>
						<option <?php selected( 'cover', $current_value->size ); ?> value="cover">Cover</option>
						<option <?php selected( 'contain', $current_value->size ); ?> value="contain">Contain</option>
					</select>
				</div>


			</div>
		</div>
		<?php
	}
}

