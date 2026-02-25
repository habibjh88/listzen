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
 * TinyMCE Custom Control (requires WP 4.8+)
 */
class TinyMCE_Control extends WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'tinymce_editor';

	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'listzen-custom-controls-js', listzen_get_assets('customize/js/customizer.js'), [ 'jquery' ], '1.2', true );
		wp_enqueue_style( 'listzen-custom-controls-css', listzen_get_assets('customize/css/customizer.css'), [], '1.0', 'all' );
		wp_enqueue_editor();
	}

	/**
	 * Pass our TinyMCE toolbar string to JavaScript
	 */
	public function to_json() {
		parent::to_json();
		$this->json['listzenTinymcetoolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';
		$this->json['listzenTinymcetoolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="tinymce-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_html( $this->value() ); ?></textarea>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}

