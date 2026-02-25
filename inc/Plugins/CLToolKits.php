<?php
/**
 * ClassifiedListing.
 *
 * @link https://jetpack.com/
 */

namespace Listzen\Plugins;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Listzen\Helpers\Fns;
use Listzen\Traits\SingletonTraits;
use RtclPro\Helpers\Fns as CLFnsPro;
class CLToolKits {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		if ( ! Fns::is_cl_toolkits_active() ) {
			return;
		}

		add_filter( 'rtcl_el_category_box_style', [ __CLASS__, 'el_category_box_style' ], 99 );

		//Listing Showcase
		add_filter( 'el_listing_widget_content_visibility_fields', [ __CLASS__, 'modify_listing_visibility_fields' ], 10, 2 );
		add_filter( 'rtcl_el_location_box_style', [ __CLASS__, 'el_listings_location_style' ], 20 );
		add_filter( 'classified_listing_toolkits_pricing_styles', [ __CLASS__, 'toolkits_pricing_styles' ] );
		add_filter( 'rtcl_el_listings_list_style', [ __CLASS__, 'el_listings_style' ], 99 );
		add_filter( 'rtcl_el_listings_grid_style', [ __CLASS__, 'el_listings_grid_style' ], 99 );
		add_filter( 'rtcl_el_listing_widget_general_field', [ __CLASS__, 'widget_general_field' ], 10, 2 );
		add_filter( 'rtcl_el_search_general_fields', [ __CLASS__, 'rtcl_el_search_general_fields' ], 10, 2 );

		if ( Fns::is_cl_pro_active() && CLFnsPro::is_enable_quick_view() ) {
			add_action( 'rtcl_quick_view_summary', [ __CLASS__, 'quick_view_summary_content' ], 99 );
		}
	}

	static function quick_view_summary_content( $listing ) {
		if ( ! $listing ) {
			return;
		}
		?>
        <div class="rtcl single-rtcl_listing">

            <div class="rtcl-content-section rtcl-content-section">
                <div class="rtcl-section-heading-simple"><h4 style="margin-top: 10px;"><?php echo esc_html__( 'Description', 'listzen' ) ?></h4></div>
	            <?php $listing->the_content(); ?>
            </div>

	        <?php do_action( 'rtcl_single_listing_amenities', $listing ); ?>
        </div>
		<?php
	}

	public static function el_category_box_style( $styles ) {

		$styles['style-3'] = __( "Style 3", 'listzen' );
		$styles['style-4'] = __( "Style 4", 'listzen' );
		$styles['style-5'] = __( "Style 5", 'listzen' );
		$styles['style-6'] = __( "Style 6", 'listzen' );

		return $styles;
	}

	public static function toolkits_pricing_styles( $styles ) {

		$styles['view-4'] = __( "Style 4", 'listzen' );
		$styles['view-5'] = __( "Style 5", 'listzen' );

		return $styles;
	}

	public static function modify_listing_visibility_fields( $fields, $object ) {

		$new_item = [
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_show_phone_number',
				'label'     => __( 'Phone Number', 'listzen' ),
				'label_on'  => 'On',
				'label_off' => 'Off',
				'default'   => 'yes',
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_show_author_image',
				'label'     => __( 'Author Image', 'listzen' ),
				'label_on'  => 'On',
				'label_off' => 'Off',
				'default'   => 'yes',
			],
			[
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rtcl_show_rating',
				'label'     => __( 'Show Rating', 'listzen' ),
				'label_on'  => 'On',
				'label_off' => 'Off',
				'default'   => 'yes',
			]
		];

		array_splice( $fields, count( $fields ) - 1, 0, $new_item );

		return $fields;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listings_location_style( $data ) {

		$data['style-4'] = esc_html__( 'Style 4', 'listzen' );
		$data['style-5'] = esc_html__( 'Style 5', 'listzen' );
		$data['style-6'] = esc_html__( 'Style 6', 'listzen' );
		$data['style-7'] = esc_html__( 'Style 7', 'listzen' );

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [array] $data array for list style.
	 *
	 * @return array
	 */
	public static function el_listings_style( $data = [] ) {
		$data['style-6'] = [
			'title' => esc_html__( 'Style 6', 'listzen' ),
			'url'   => get_template_directory_uri() . "/assets/images/cl-toolkits/list-style-06.png",
		];
		$data['style-7'] = [
			'title' => esc_html__( 'Style 7', 'listzen' ),
			'url'   => get_template_directory_uri() . "/assets/images/cl-toolkits/list-style-06.png",
		];

		return $data;
	}

	public static function el_listings_grid_style( $data = [] ) {
		$data['style-6'] = [
			'title' => esc_html__( 'Style 6', 'listzen' ),
			'url'   => get_template_directory_uri() . "/assets/images/cl-toolkits/list-style-06.png",
		];

		return $data;
	}

	public static function widget_general_field( $fields, $object ) {

		foreach ( $fields as &$control ) {
			if ( isset( $control['id'] ) && $control['id'] === 'rtcl_listings_column' ) {

				// Remove old simple condition
				unset( $control['condition'] );

				// Add advanced OR conditions
				$control['conditions'] = [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'rtcl_listings_view',
							'operator' => '==',
							'value'    => 'grid',
						],
						[
							'name'     => 'rtcl_listings_style',
							'operator' => '==',
							'value'    => 'style-6',
						],
					],
				];
			}
		}
		unset( $control ); // break reference

		return $fields;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $style main style.
	 *
	 * @return array
	 */
	public static function rtcl_el_search_general_fields( $fields, $object ) {
		$newFields = [];

		foreach ( $fields as $field ) {
			$newFields[] = $field;

			if ( isset( $field['id'] ) && $field['id'] === 'search_oriantation' ) {
				$newFields[] = [
					'type'         => Controls_Manager::SELECT2,
					'id'           => 'custom_preset',
					'label'        => __( 'Style', 'listzen' ),
					'options'      => [
						'preset-1' => __( 'Preset 1 (Radius)', 'listzen' ),
						'preset-2' => __( 'Preset 1 (Separate)', 'listzen' ),
						'preset-3' => __( 'Preset 3 (Separate border)', 'listzen' ),
					],
					'render_type'  => 'template',
					'prefix_class' => 'rtcl-search-',
					'condition'    => [
						'search_style'       => 'standard',
						'search_oriantation' => 'inline',
					],
				];
			}
		}

		return $newFields;
	}
}