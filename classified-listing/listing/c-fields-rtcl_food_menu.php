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
$fields_all = $form->getFieldAsGroup( FBField::CUSTOM );
if ( empty( $fields_all['rtcl_food_menu'] ) ) {
	return;
}
$fields        = [ $fields_all['rtcl_food_menu'] ];
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
            <div class="rtcl-faq-repeater-items rtcl-food-menu-repeater">
				<?php
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
