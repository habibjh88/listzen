<?php
/**
 *
 * @package ClassifiedListing/Templates
 * @version 5.2.0
 * @var Form $form
 * @var string $fieldUuid
 * @var FBField $field
 * @var Listing $field
 */

use Listzen\Helpers\CLFns;
use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Models\Listing;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
global $listing;

if ( ! is_a( $field, FBField::class ) || ! is_a( $listing, Listing::class ) ) {
	return;
}
$value = $field->getFormattedCustomFieldValue( $listing->get_id() );

if ( empty( $value ) ) {
	return;
}

$repeaterItemFields      = $field->getField();


$is_collapsable = isset( $repeaterItemFields['collapsable'] ) && $repeaterItemFields['collapsable'] == 'yes' ? 'rtcl-is-collapsable' : 'rtcl-not-collapsable';
$layout         = isset( $repeaterItemFields['layout'] ) ? 'layout_' . $repeaterItemFields['layout'] : '';
$icon           = $field->getIconData();

$food_menu_wrapper_class = $food_menu_inner = '';
if ( 'rtcl_food_menu' === $field->getName() ) {
	$food_menu_wrapper_class = 'rtcl-faqs-content';
	$food_menu_inner = 'rtcl-faq-repeater-items';
	$is_collapsable = 'rtcl-single-faqs-wrapper rtcl-content-section';
	$layout = '';
}

?>
<div class="rtcl-sl-element <?php echo esc_attr( $is_collapsable . ' ' . $layout ); ?>">
	<?php
	if ( ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) || ! empty( $field->getLabel() ) ) {
		?>
        <div class="rtcl-slf-label-wrap rtcl-repeater-group-title">
			<?php
			if ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) {
				?>
                <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ); ?>"></i></div>
				<?php
			}
			if ( ! empty( $field->getLabel() ) ) {
				?>
                <div class='rtcl-slf-label'><?php echo esc_html( $field->getLabel() ); ?></div>
				<?php
			}
			?>
        </div>
	<?php } ?>
    <div class="rtcl-slf-value <?php echo esc_attr( $food_menu_wrapper_class); ?>">
		<?php
		$repeaterFields = $field->getData( 'fields', [] );
		if ( ! empty( $repeaterFields ) && is_array( $value ) ) {
			?>
            <div class="rtcl-slf-repeater-items <?php echo esc_attr( $food_menu_inner ); ?>">
				<?php


				if ( $food_menu_inner ) {
                    echo "<div class='rtcl-food-menu-repeater'>";
					$count = 1;


					$food_menus = CLFns::group_menu_items( $value );

					foreach ( $food_menus as $rValues ) {
						$is_active   = $count == 1 ? 'active' : '';
						$group_title = $rValues['group_title'] ?? "Menu";
						$items       = $rValues['items'] ?? [];
						if ( empty( $items ) ) {
							continue;
						}
						?>
                        <div class="faq-item <?php echo esc_attr( $is_active ) ?>">
                            <div class="faq-heading">
								<?php echo esc_html( $group_title ); ?>
                                <button class="collapse-btn">
                                    <i class="iconPlus rt-icon-plus"></i>
                                    <i class="iconMinus rt-icon-minus-small"></i>
                                </button>
                            </div>

                            <div class="faq-content">
								<?php
								foreach ( $items as $index => $item ) {
									?>
                                    <div class="food-menu-item">
                                        <div class="menu-image">
											<?php echo wp_get_attachment_image( $item['menu_img'][0], 'thumbnail' ); ?>
                                        </div>
                                        <div class="menu-desc">
                                            <div class="menu-title">
												<?php echo esc_html( $item['menu_name'] ); ?>

                                                <div class="menu-price">
													<?php echo esc_html( $item['menu_price'] ); ?>
                                                </div>
                                            </div>
                                            <div class="menu-content">
												<?php echo esc_html( $item['menu_desc'] ); ?>
                                            </div>
                                        </div>
                                    </div>
									<?php
								}
								?>
                            </div>
                        </div>
						<?php
						$count ++;
					}
                    echo "</div>";
				} else {
					foreach ( $value as $rValueIndex => $rValues ) {
						?>
                        <div class="rtcl-slf-repeater-item">
							<?php
							foreach ( $repeaterFields as $repeaterField ) {
								$rField = new FBField( $repeaterField );
								$rValue = 'file' === $rField->getElement() ? ( ! empty( $rValues[ $rField->getName() ] )
								                                               && is_array( $rValues[ $rField->getName() ] )
									? FBHelper::getFieldAttachmentFiles( $listing->get_id(), $rField->getField(), $rValues[ $rField->getName() ], true )
									: [] ) : ( $rValues[ $rField->getName() ] ?? '' );
								?>
                                <div class="rtcl-slf-repeater-field <?php echo esc_attr( $rField->getElement() ) ?>"
                                     data-name="<?php echo esc_attr( $field->getName() ); ?>"
                                     data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
									<?php
									$rIcon = $rField->getIconData();
									if ( ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) )
									     || ! empty( $rField->getLabel() )
									) {
										?>
                                        <div class="rtcl-slf-label-wrap">
											<?php
											if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
												?>
                                                <div class="rtcl-field-icon"><i
                                                            class="<?php echo esc_attr( $rIcon['class'] ); ?>"></i>
                                                </div>
												<?php
											}
											if ( ! empty( $rField->getLabel() ) ) {
												?>
                                                <div
                                                        class='rtcl-slf-label'><?php echo esc_html( $rField->getLabel() ); ?></div>
												<?php
											}
											?>
                                        </div>
									<?php } ?>
                                    <div class="rtcl-slf-value">
										<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
                                    </div>
                                </div>
								<?php
							}
							?>
                        </div>
						<?php
					}

				}


				?>
            </div>
			<?php
		}
		?>
    </div>
</div>