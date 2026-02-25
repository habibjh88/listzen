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
global $listzenRepeater;
$listzenRepeater = [];
$fields = $form->getFieldAsGroup( FBField::CUSTOM );
unset( $fields['rtcl_amenities'] );
unset( $fields['rtcl_faqs'] );
unset( $fields['rtcl_food_menu'] );
unset( $fields['rtcl_services'] );

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
		$icon = $field->getIconData();

		if ( 'repeater' === $field->getElement() ) {
			$repeaterFields = $field->getData( 'fields', [] );

			$listzenRepeater[] = $field->getName();
			continue;
		}
		?>
        <div class="rtcl-cfp-item rtcl-cfp-<?php echo esc_attr( $field->getElement() ); ?>" data-name="<?php echo esc_attr( $field->getName() ); ?>"
             data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
			<?php
			if ( $field->getElement() === 'url' ) {
				$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : '';
				?>
                <a href="<?php echo esc_url( $value ); ?>"
                   target="<?php echo esc_attr( $field->getTarget() ); ?>"<?php echo esc_html( $nofollow ); ?>><?php echo esc_html( $field->getLabel() ); ?></a>
				<?php
			} else {
				if ( ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) || ! empty( $field->getLabel() ) ) {
					?>
                    <div class="rtcl-cfp-label-wrap">
						<?php
						if ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) {
							?>
                            <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ); ?>"></i></div>
							<?php
						}
						if ( ! empty( $field->getLabel() ) ) {
							?>
                            <div class='cfp-label'><?php echo esc_html( $field->getLabel() ); ?></div>
							<?php
						}
						?>
                    </div>
				<?php } ?>
                <div class="cfp-value">
					<?php
						Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) );
					?>
                </div>
			<?php } ?>
        </div>
		<?php
	}


	$fieldData = ob_get_clean();
	if ( $fieldData ) :
		?>
        <div class="single-listing-custom-fields-action rtcl-content-section">
        <div class="rtcl-single-custom-fields">
            <div class="rtcl-section-heading-simple">
                <h3><?php echo esc_html( apply_filters( 'rtcl_custom_fields_section_title', __( 'Overview', 'listzen' ) ) ); ?></h3>
            </div>
            <div class="rtcl-cf-properties">
				<?php Functions::print_html( $fieldData, true ); ?>
            </div>
        </div>
        </div>
	<?php
	endif;
	?>
<?php
endif;
