<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates/listing
 * @version       3.0.0
 *
 * @var Form $form
 * @var array $fields
 * @var int $listing_id
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! is_a( $form, Form::class ) ) {
	return;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$fields_all = $form->getFieldAsGroup( FBField::CUSTOM );
if ( empty( $fields_all['rtcl_faqs'] ) ) {
	return;
}
$fields        = [ $fields_all['rtcl_faqs'] ];
$section_title = '';
if ( count( $fields ) ) :
	$fields = FBHelper::reOrderCustomField( $fields );
	ob_start();

	foreach ( $fields as $index => $field ) {
		$field = new FBField( $field );
		if ( ! $field->isSingleViewAble() ) {
			continue;
		}
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( empty( $value ) ) {
			continue;
		}

		if ( ! empty( $field->getLabel() ) ) {
			$section_title = $field->getLabel();
		}

		if ( ! empty( $value ) && is_array( $value ) ) {
			?>
            <div class="rtcl-faq-repeater-items">
				<?php
				$count = 1;
				foreach ( $value as $rValueIndex => $rValues ) {
					$values    = array_values( $rValues );
					$is_active = $count == 1 ? 'active' : '';
					if ( empty( $values[0] ) || empty( $values[1] ) ) {
						continue;
					}
					?>
                    <div class="faq-item <?php echo esc_attr( $is_active ) ?>">
                        <div class="faq-heading">
							<?php echo esc_html( $values[0] ); ?>
                            <button class="collapse-btn">
                                <i class="iconPlus rt-icon-plus"></i>
                                <i class="iconMinus rt-icon-minus-small"></i>
                            </button>
                        </div>

                        <div class="faq-content">
							<?php echo esc_html( $values[1] ); ?>
                        </div>
                    </div>
					<?php
					$count ++;
				}
				?>
            </div>
			<?php
		}


	}


	$fieldData = ob_get_clean();
	if ( $fieldData ) :
		?>
        <div class="rtcl-single-faqs-wrapper rtcl-content-section">
            <div class="rtcl-section-heading-simple">
				<?php printf(
					"<h3>%s</h3>",
					$section_title ? esc_html( $section_title ) : esc_html__( "Frequently Asked Question", "listzen" )
				); ?>
            </div>
            <div class="rtcl-faqs-content">
				<?php Functions::print_html( $fieldData, true ); ?>
            </div>
        </div>
	<?php
	endif;
	?>
<?php
endif;
