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
$fields = $form->getFieldAsGroup( FBField::CUSTOM );
if ( empty( $fields['rtcl_amenities'] ) ) {
	return;
}
$aminities = $fields['rtcl_amenities'];


if ( empty( $aminities['options'] ) ) {
	return;
}
$section_title = ! empty( $aminities['label'] ) ? esc_html( $aminities['label'] ) : esc_html__( 'Amenities & Features', 'listzen' );
$options       = $aminities['options'];
$icon_class    = 'rt-icon-badge-check-alt';
if ( isset( $aminities['icon']['class'] ) ) {
	$icon_class = $aminities['icon']['class'];
}
$aminities = get_post_meta( $listing_id, 'rtcl_amenities' );

?>
<div class="rtcl-listing-aminities rtcl-content-section">
	<div class="rtcl-section-heading-simple">
		<?php
		/*translators: %s: is section title*/
		printf( '<h3>%s</h3>', esc_html( $section_title ) )
		?>
	</div>
	<ul class="amenities-items">
		<?php
		foreach ( $options as $option ) {
			if ( ! in_array( $option['value'], $aminities ) ) {
				continue;
			}
			/*translators %s %s %s: value, class, label */
			printf( '<li class="list-item %s"><i class="%s"></i> %s</li>',
				esc_attr( $option['value'] ),
				esc_attr( $icon_class ),
				esc_html( $option['label'] )
			);
		}
		?>
	</ul>
</div>
