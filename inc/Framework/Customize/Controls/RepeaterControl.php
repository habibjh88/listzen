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
 * Sortable Repeater Custom Control
 */
class RepeaterControl extends WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'sortable_repeater';
	/**
	 * User as 'repeater' OR 'sort'
	 */
	public $use_as = 'repeater';
	/**
	 * Button labels
	 */
	public $button_labels = [];

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = [], $options = [] ) {
		parent::__construct( $manager, $id, $args );
		// Merge the passed button labels with our default labels
		$this->button_labels = wp_parse_args(
			$this->button_labels,
			[
				'add' => __( 'Add', 'listzen' ),
			]
		);
	}

	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'listzen-custom-controls-js',
			listzen_get_assets('customize/js/customizer.js'),
			[ 'jquery', 'jquery-ui-core' ],
			'1.2',
			true
		);
		wp_enqueue_style( 'listzen-custom-controls-css', listzen_get_assets('customize/css/customizer.css'), [], '1.0', 'all' );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$_class = $this->use_as == 'sort' ? 'as_sort' : 'as_repeater';
		?>
		<div class="sortable_repeater_control <?php echo esc_attr( $_class ); ?>">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>"
			       value="<?php echo esc_attr( $this->value() ); ?>"
			       class="customize-control-sortable-repeater" <?php $this->link(); ?> />
			<div class="sortable">
				<div class="repeater">
					<input type="text" value="" class="repeater-input"/>
					<span class="dashicons dashicons-sort"></span>
					<?php if ( 'sort' !== $this->use_as ) : ?>
						<a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( 'sort' !== $this->use_as ) : ?>
				<button class="button customize-control-sortable-repeater-add"
				        type="button"><?php echo esc_html( $this->button_labels['add'] ); ?></button>
			<?php endif; ?>
		</div>
		<?php
	}
}

