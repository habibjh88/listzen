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
use \Listzen\Helpers\CLFns;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! is_a( $form, Form::class ) ) {
	return;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
global $listzenRepeater;
$fields_all = $form->getFieldAsGroup( FBField::CUSTOM );
if ( empty( $listzenRepeater ) ) {
	return;
}
$fields = [];
foreach ( $listzenRepeater as $item ) {
	$fields        = [$fields_all[ $item ]];

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

			$icon = $field->getIconData();

			if ( ! empty( $field->getLabel() ) ) {
				$section_title = $field->getLabel();
			}


			$repeaterFields = $field->getData( 'fields', [] );
			if ( ! empty( $repeaterFields ) && is_array( $value ) ) {
				$should_display_label = count( $repeaterFields ) > 1;
				?>
                <div class="rtcl-cfp-repeater-items <?php echo esc_attr($should_display_label ? 'has-multiple-items' : 'has-one-item'); ?>">
					<?php

					foreach ( $value as $rValueIndex => $rValues ) {
						?>
                        <div class="rtcl-cfp-repeater-item">
							<?php

							foreach ( $repeaterFields as $repeaterField ) {
								$rField = new FBField( $repeaterField );
								$rValue = 'file' === $rField->getElement() ? ( ! empty( $rValues[ $rField->getName() ] )
								                                               && is_array( $rValues[ $rField->getName() ] )
									? FBHelper::getFieldAttachmentFiles( $listing_id, $rField->getField(), $rValues[ $rField->getName() ], true )
									: [] ) : ( $rValues[ $rField->getName() ] ?? '' );
								?>
                                <div class="rtcl-cfp-repeater-field" data-name="<?php echo esc_attr( $field->getName() ); ?>"
                                     data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
									<?php
									$rIcon = $rField->getIconData();
									if ( ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) )
									     || ! empty( $rField->getLabel() )
									) {
										?>
                                        <div class="rtcl-cfp-label-wrap">
											<?php
											if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
												?>
                                                <div class="rtcl-field-icon"><i
                                                            class="<?php echo esc_attr( $rIcon['class'] ); ?>"></i>
                                                </div>
												<?php
											}

											if ( ! empty( $rField->getLabel() ) && $should_display_label ) {
												?>
                                                <div
                                                        class='cfp-label'><?php echo esc_html( $rField->getLabel() ); ?></div>
												<?php
											}
											?>
                                        </div>
									<?php } ?>
                                    <div class="cfp-value">
										<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
                                    </div>
                                </div>
								<?php
							}
							?>
                        </div>
						<?php
					}
					?>
                </div>
				<?php
			}


		}


		$fieldData = ob_get_clean();
		if ( $fieldData ) :
			?>
            <div class="rtcl-single-repeater-wrapper rtcl-content-section">
                <div class="rtcl-section-heading-simple">
					<?php printf(
						"<h3>%s</h3>",
						$section_title ? esc_html( $section_title ) : esc_html__( "Services", "listzen" )
					); ?>
                </div>
                <div class="rtcl-repeater-content listzen-listing-repeater">
					<?php Functions::print_html( $fieldData, true ); ?>
                </div>
            </div>
		<?php
		endif;
		?>
	<?php
	endif;
}
