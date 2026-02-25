<?php
namespace Listzen\Modules;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Svg Class
 */
class Svg {

	/**
	 * Get SVG icon
	 *
	 * @param $query
	 *
	 * @return string
	 */
	public static function get_svg( $name, $echo = true, $rotate = '' ) {
		//@formatter:off
		$svg_list = apply_filters(
			'listzen_svg_icon_list',
			[
				'search'           => '<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M225.474 0C101.151 0 0 101.151 0 225.474c0 124.33 101.151 225.474 225.474 225.474 124.33 0 225.474-101.144 225.474-225.474C450.948 101.151 349.804 0 225.474 0zm0 409.323c-101.373 0-183.848-82.475-183.848-183.848S124.101 41.626 225.474 41.626s183.848 82.475 183.848 183.848-82.475 183.849-183.848 183.849z" fill="currentColor" opacity="1" data-original="currentColor"></path><path d="M505.902 476.472 386.574 357.144c-8.131-8.131-21.299-8.131-29.43 0-8.131 8.124-8.131 21.306 0 29.43l119.328 119.328A20.74 20.74 0 0 0 491.187 512a20.754 20.754 0 0 0 14.715-6.098c8.131-8.124 8.131-21.306 0-29.43z" fill="currentColor" opacity="1" data-original="currentColor"></path></g></svg>',
			]
		);
		//@formatter:on

		if ( isset( $svg_list[ $name ] ) ) {
			$rotate_style = '';
			if ( $rotate ) {
				$rotate_style = 'style=transform:rotate(' . $rotate . 'deg);';
			}

			$icon = '<span ' . esc_attr( $rotate_style ) . " class='listzen-icon-{$name}'>{$svg_list[ $name ]}</span>";

			if ( $echo ) { ?>
				<span <?php echo esc_attr( $rotate_style ) ?> class='listzen-icon-<?php echo esc_attr( $name ) ?>'><?php echo wp_kses_post( $svg_list[ $name ] ) ?></span>
				<?php
			} else {
				return $icon;
			}
		}

		return '';
	}

}
