<?php

namespace Listzen\Custom;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Helpers\Fns;

use Listzen\Modules\Thumbnail;
use Listzen\Traits\SingletonTraits;
use Listzen\Options\Opt;

/**
 * MenuMeta Class
 */
class MenuMeta {

	use SingletonTraits;

	/**
	 * Hooks for Menu Meta
	 */
	public function __construct() {
		// Menu Customization.
		add_filter( 'wp_setup_nav_menu_item', [ $this, 'add_menu_item_image' ] );
		add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'menu_customize' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'menu_update' ], 10, 2 );
		add_filter( 'wp_get_nav_menu_items', [ $this, 'menu_modify' ], 11, 3 );
	}

	/**
	 * @param $menu_item
	 *
	 * @return mixed
	 */
	public function add_menu_item_image( $menu_item ) {
		$menu_item->listzen_menu_image = get_post_meta( $menu_item->ID, 'listzen_menu_image', true );
		$menu_item->listzen_menu_qs    = get_post_meta( $menu_item->ID, 'listzen_menu_qs', true );
		$menu_item->listzen_mega_menu  = get_post_meta( $menu_item->ID, 'listzen_mega_menu', true );

		return $menu_item;
	}

	/**
	 * Menu Customize
	 *
	 * @param $item_id
	 * @param $item
	 *
	 * @return void
	 */
	public function menu_customize( $item_id, $item ) {
		?>

		<?php if ( $item->menu_item_parent < 1 ) : ?>
			<p class="description mega-menu-wrapper widefat">
				<label for="listzen_mega_menu-<?php echo esc_attr( $item_id ); ?>" class="widefat">
					<?php echo esc_html__( 'Make as Mega Menu', 'listzen' ); ?><br>
					<select class="widefat" id="listzen_mega_menu-<?php echo esc_attr( $item_id ); ?>" name="listzen_mega_menu[<?php echo esc_attr( $item_id ); ?>]">
						<?php
						$options = [
							''                      => __( 'Choose Mega Menu', 'listzen' ),
							'mega-menu'             => __( 'Mega menu', 'listzen' ),
							'mega-menu hide-header' => __( 'Mega menu - Hide Col Title', 'listzen' ),
						];

						foreach ( $options as $value => $label ) {
							$selected = ( $item->listzen_mega_menu == $value ) ? ' selected="selected"' : '';
							echo '<option value="' . esc_attr( $value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $label ) . '</option>';
						}
						?>
					</select>
				</label>
			</p>
		<?php endif; ?>

		<p class="description widefat">
			<label class="widefat" for="listzen_menu_qs_<?php echo esc_attr( $item_id ); ?>">
				<?php echo esc_html__( 'Query String', 'listzen' ); ?><br>
				<input type="text"
					   class="widefat"
					   id="listzen_menu_qs_<?php echo esc_attr( $item_id ); ?>"
					   name="listzen_menu_qs[<?php echo esc_attr( $item_id ); ?>]"
					   value="<?php echo esc_html( $item->listzen_menu_qs ); ?>"
				/>
			</label>
		</p>

		<p class="field-custom description description-wide">
			<label for="listzen-menu-image-<?php echo esc_attr( $item->ID ); ?>">
				Image<br>
				<input type="hidden" class="listzen-menu-image" id="listzen-menu-image-<?php echo esc_attr( $item->ID ); ?>" name="listzen_menu_image[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_url( $item->listzen_menu_image ); ?>"/>
				<img src="<?php echo esc_url( $item->listzen_menu_image ); ?>" style="max-width: 100px; display: <?php echo esc_attr($item->listzen_menu_image ? 'block' : 'none'); ?>" class="listzen-menu-image-preview"/>
				<br>
				<button class="button upload-listzen-menu-image"><?php echo esc_html__('Upload Image', 'listzen');?></button>
				<button class="button remove-listzen-menu-image" style="display: <?php echo esc_attr($item->listzen_menu_image ? 'inline-block' : 'none'); ?>;"><?php echo esc_html__('Remove', 'listzen');?></button>
			</label>
		</p>
		<?php
	}

	/**
	 * Menu Update
	 *
	 * @param $menu_id
	 * @param $menu_item_db_id
	 *
	 * @return void
	 */
	public function menu_update( $menu_id, $menu_item_db_id ) {
		$_mega_menu         = isset( $_POST['listzen_mega_menu'][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST['listzen_mega_menu'][ $menu_item_db_id ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$query_string_value = isset( $_POST['listzen_menu_qs'][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST['listzen_menu_qs'][ $menu_item_db_id ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$menu_image         = isset( $_POST['listzen_menu_image'][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST['listzen_menu_image'][ $menu_item_db_id ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		update_post_meta( $menu_item_db_id, 'listzen_mega_menu', $_mega_menu );
		update_post_meta( $menu_item_db_id, 'listzen_menu_qs', $query_string_value );
		update_post_meta( $menu_item_db_id, 'listzen_menu_image', $menu_image );
	}

	/**
	 * Modify Menu item
	 *
	 * @param $items
	 * @param $menu
	 * @param $args
	 *
	 * @return mixed
	 */
	public function menu_modify( $items, $menu, $args ) {
		foreach ( $items as $item ) {
			$menu_query_string = get_post_meta( $item->ID, 'listzen_menu_qs', true );
			if ( $menu_query_string ) {
				$item->url = add_query_arg( $menu_query_string, '', $item->url );
			}
		}

		return $items;
	}
}
