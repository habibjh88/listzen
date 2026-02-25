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
use Listzen\Helpers\CLFns;
use Listzen\Helpers\Fns;
use Listzen\Traits\SingletonTraits;
use Rtcl\Controllers\BusinessHoursController as BHS;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns as FnsPro;
use Rtcl\Helpers\Text;
use Rtcl\Helpers\Utility;
use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use Rtcl\Services\FormBuilder\FBHelper;
use Listzen\Modules\IconList;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;
use Rtcl\Controllers\SocialProfilesController;

/**
 * ThemeJetpack Class
 */
class ClassifiedListing {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		if ( ! Fns::is_cl_active() ) {
			return;
		}

		// Filter hooks
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		add_filter( 'rtcl_listing_extra_class', [ $this, 'add_listing_classes' ] );

		add_filter( 'rtcl_show_page_title', '__return_false' );
		add_filter( 'rtcl_filter_form_items', [ $this, 'add_custom_filter_form_items' ] );
		add_filter( 'rtcl_ajax_filter_item_class', [ $this, 'add_rtcl_ajax_filter_item_class' ], 10, 2 );
		add_filter( 'rtcl_get_listing_display_options', [ $this, 'listing_display_options' ] );
		add_filter( 'rtcl_archive_listings_grid_view_per_row', [ $this, 'listing_column_view_per_row' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_archive_listing_settings_options', [ $this, 'rtcl_listing_map_settings_options' ] );
		add_filter( 'rtcl_single_listing_settings_options', [ $this, 'rtcl_single_listing_settings_options' ] );

		// Action hooks
		add_action( 'rtcl_after_content_wrapper', [ $this, 'listing_archive_map_markup' ] );
		add_action( 'rtcl_before_content_wrapper', [ $this, 'rtcl_before_content_wrapper' ] );

		// Template action hooks
		add_action( 'rtcl_listing_loop_item', [ $this, 'listing_loop_item_author_image_and_rating' ], 15 );
		add_action( 'rtcl_listing_loop_action', [ $this, 'filter_button' ], 5 );

		// Listing Single / Details page template hooks
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_gallery' ], 30 );
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_meta' ], 10 );
		add_action( 'rtcl_single_listing_content', [ $this, 'single_listing_meta' ], 10 );
		add_action( 'rtcl_single_listing_content', [ $this, 'add_listing_info_on_top' ], 2 );
		add_action( 'rtcl_single_listing_content', [ $this, 'add_single_listing_hour' ], 12 );
		add_action( 'rtcl_single_listing_content', [ $this, 'listing_action' ], 30 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [ TemplateHooks::class, 'add_single_listing_inner_sidebar_action' ], 20 );
		add_action( 'rtcl_single_listing_title', [ $this, 'add_listing_single_title' ] );
		add_action( 'rtcl_single_listing_gallery', [ $this, 'add_listing_single_gallery' ] );
		add_action( 'rtcl_single_listing_store_info', [ $this, 'add_store_info' ] );
		add_action( 'rtcl_listing_the_actions', [ $this, 'modify_listing_action_args' ], 30 );
		add_action( 'rtcl_before_main_content', [ $this, 'rtcl_before_main_content' ], 1 );
		remove_action( 'rtcl_listing_seller_information', [ TemplateHooks::class, 'seller_email' ], 30 );
		add_action( 'rtcl_single_listing_button_group', [ $this, 'seller_email' ], 20 );
		add_action( 'rtcl_listing_seller_information', [ $this, 'social_profiles' ], 55 );
		add_action( 'rtcl_listing_seller_information', [ __CLASS__, 'seller_email_address' ], 21 );
		add_action( 'rtcl_single_listing_sidebar', [ $this, 'rtcl_business_hour' ] );
		add_action( 'rtcl_single_listing_amenities', [ $this, 'listing_single_amenities' ] );
		add_action( 'rtcl_single_listing_services', [ $this, 'listing_single_services' ] );
		add_action( 'rtcl_single_listing_services', [ $this, 'rtcl_single_listing_repeater' ] );
		add_action( 'rtcl_single_listing_faqs', [ $this, 'listing_single_faqs' ] );
		add_action( 'rtcl_single_listing_food_menu', [ $this, 'listing_single_food_menu' ] );
		add_action( 'rtcl_single_listing_separate_video', [ $this, 'listing_single_video' ] );
		add_action( 'listzen_listing_tags', [ $this, 'listing_single_tag' ] );

		// Listing Review
		add_filter( 'formatted_rtcl_price', [ $this, 'formatted_price' ], 0, 7 );
		add_filter( 'rtcl_compare_icon_class', [ $this, 'compare_icon_class_cbf' ] );
		add_filter( 'rtcl_quick_view_icon_class', [ $this, 'quick_view_icon_class_cbf' ] );
		add_filter( 'rtcl_chat_icon_class', [ $this, 'chat_icon_class' ] );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		add_action( 'rtcl_before_main_content', [ $this, 'output_main_wrapper_start' ], 8 );

		add_filter( 'rtcl_get_listing_detail_page_display_options', [ $this, 'listing_detail_page_display_options' ], 8 );

		if ( rtcl()->has_pro() ) {
			add_action( 'rtcl_listing_author_badges', [ $this, 'rtcl_listing_author_badges' ], 1 );
			remove_action( 'rtcl_listing_seller_information', [ ProTemplateHooks::class, 'add_chat_link' ], 40 );
			add_action( 'rtcl_single_listing_button_group', [ ProTemplateHooks::class, 'add_chat_link' ], 10 );
			remove_action( 'rtcl_listing_seller_information', [ ProTemplateHooks::class, 'add_user_online_status' ], 50 );
			remove_action( 'rtcl_review_after_meta', [ ProTemplateHooks::class, 'review_display_rating' ], 10 );
			remove_action( 'rtcl_listing_seller_information', [ ProTemplateHooks::class, 'add_user_login_link' ], 1 );
			if ( FnsPro::registered_user_only( 'listing_seller_information' ) && ! is_user_logged_in() ) {
				add_action( 'rtcl_listing_seller_information', [ $this, 'add_user_login_link' ], 1 );
			}
		}
	}

	public static function output_main_wrapper_start() {
		?>
		<div class="rtcl-content-wrapper rtclScrollTarget">
		<?php
	}

	public static function add_user_login_link() {
		$redirect_to    = add_query_arg( 'redirect_to', get_the_permalink(), Link::get_my_account_page_link() );
		$is_popup_login = ( ! is_user_logged_in() && listzen_option( 'listzen_header_login_style' ) ) ? 'listzen-popup-login' : '';
		?>
		<div class='list-group-item'>
			<?php
			echo wp_kses(
				sprintf(
					__( "Please <a class='%1\$s' href='%2\$s'>login</a> to view the seller information.", 'listzen' ),
					esc_attr( $is_popup_login ),
					$is_popup_login ? '#' : esc_url( $redirect_to )
				),
				[
					'a' => [
						'href'  => [],
						'class' => [],
					],
				]
			);
			?>
		</div>
		<?php
	}

	public function compare_icon_class_cbf() {
		return 'rt-icon-compare-alt';
	}

	public function chat_icon_class() {
		return 'rt-icon-comment';
	}

	public function quick_view_icon_class_cbf() {
		return 'rt-icon-search-2';
	}

	public function formatted_price( $price, $price2, $decimals, $decimal_separator, $thousand_separator, $original_price, $args ) {
		global $listing;
		if ( isset( $args['listing_id'] ) ) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			$listing = rtcl()->factory->get_listing( $args['listing_id'] );
		}

		if ( $listing == null ) {
			return $price;
		}

		$is_shorthand = listzen_option( 'listing_price_shorthand' );
		$precision    = listzen_option( 'listing_price_precision' );

		if ( 'short' === $is_shorthand ) {
			$price = CLFns::rt_number_shorten( $price, $precision );
		} elseif ( 'short-lac' === $is_shorthand ) {
			$price = CLFns::number_to_lac( $price );
		} elseif ( 'indian-comma' === $is_shorthand ) {
			$price = CLFns::money_format_india( $price );
		}

		return $price;
	}

	public function listing_single_tag( $listing ) {
		if ( ! $listing->get_tags() ) {
			return;
		}
		if ( Functions::is_tag_disabled() ) {
			return;
		}
		$listing_id = $listing->get_id();
		?>
		<div class="rtcl-content-section rtcl-listing-tags-wrapper">
			<div class="rtcl-section-heading-simple">
				<?php printf( '<h3>%s</h3>', esc_html__( 'Tags', 'listzen' ) ); ?>
			</div>
			<?php Functions::print_html( CLFns::get_post_terms( $listing_id, rtcl()->tag ), true ); ?>
		</div>
		<?php
	}

	public function listing_single_video( $listing ) {
		if ( ! CLFns::is_separate_video() ) {
			return;
		}
		if ( ! FBHelper::isEnabled() ) {
			return;
		}

		$video_urls = [];

		$is_gallery_disabled    = apply_filters( 'rtcl_disable_gallery_video', Functions::is_video_gallery_disabled() );
		$is_video_urls_disabled = Functions::is_video_urls_disabled();

		if (
			( FBHelper::isEnabled() && ! $is_gallery_disabled )
			|| ( ! FBHelper::isEnabled() && ! $is_video_urls_disabled && ! $is_gallery_disabled )
		) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ( is_array( $video_urls ) && ! empty( $video_urls ) ) ? $video_urls : [];
		}

		$images = $listing->get_images();

		if ( empty( $video_urls ) ) {
			return;
		}

		?>
		<div class="rtcl-content-section rtcl-separate-video-wrapper">
			<div class="rtcl-section-heading-simple">
				<?php printf( '<h3>%s</h3>', esc_html__( 'Video', 'listzen' ) ); ?>
			</div>
			<?php
			CLFns::listing_video( $video_urls, $images, $listing );
			?>
		</div>
		<?php
	}

	function listing_single_faqs( $listing ) {

		$form = $listing->getForm();
		Functions::get_template(
			'listing/c-fields-rtcl_faqs',
			[
				'form'       => $form,
				'listing_id' => $listing->get_id(),
			]
		);
	}

	function listing_single_food_menu( $listing ) {

		$form = $listing->getForm();

		Functions::get_template(
			'listing/c-fields-rtcl_food_menu',
			[
				'form'       => $form,
				'listing_id' => $listing->get_id(),
			]
		);
	}

	function listing_single_services( $listing ) {

		$form = $listing->getForm();

		Functions::get_template(
			'listing/c-fields-rtcl_services',
			[
				'form'       => $form,
				'listing_id' => $listing->get_id(),
			]
		);
	}

	function rtcl_single_listing_repeater( $listing ) {

		$form = $listing->getForm();

		Functions::get_template(
			'listing/c-fields-rtcl_repeater',
			[
				'form'       => $form,
				'listing_id' => $listing->get_id(),
			]
		);
	}

	function listing_single_amenities( $listing ) {

		$form = $listing->getForm();

		Functions::get_template(
			'listing/c-fields-rtcl_amenities',
			[
				'form'       => $form,
				'listing_id' => $listing->get_id(),
			]
		);
	}

	public function rtcl_business_hour() {
		if ( 'sidebar' === CLFns::business_hour_pos() ) {
			?>
			<div class="listzen-business-hour">
				<div class="listzen-section-title">
					<div class="main-title-wrap"><h3 class="main-title"><?php echo esc_html__( 'Open Hours', 'listzen' ); ?></h3></div>
				</div>
				<?php do_action( 'rtcl_single_listing_business_hours' ); ?>
			</div>
			<?php
		}
	}

	public function social_profiles() {
		SocialProfilesController::display_social_profiles();
	}

	public static function seller_email_address( $listing ) {
		$is_email = (bool) Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', 'email', 'multi_checkbox' );

		if ( $is_email && is_a( $listing, Listing::class ) ) {
			$email = get_post_meta( $listing->get_id(), 'email', true );
			?>
			<div tabindex="0" class="rtcl-list-group-item">
					<div class="media" style="display: flex; align-items: center">
						<span class='rt-icon-envelope mr-2 d-flex'></span>
						<div class='media-body'>
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<?php echo esc_html( $email ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php
		}
	}

	public function rtcl_listing_author_badges( $listing ) {
		ProTemplateHooks::add_user_online_status( $listing );
	}

	public function rtcl_before_main_content() {
		global $listing;
		if ( ! $listing ) {
			return;
		}
		$enableBuilder = false;
		if ( method_exists( FBHelper::class, 'isEnableSingleBuilder' ) ) {
			$enableBuilder = FBHelper::isEnableSingleBuilder( $listing );
		}
		if ( $enableBuilder ) {
			return;
		}
		if ( is_singular( 'rtcl_listing' ) ) {
			echo "<div class='rtcl-listing-gallery-top-wrapper'>";
			do_action( 'rtcl_single_listing_title' );
			do_action( 'rtcl_single_listing_gallery' );
			echo '</div>';
		}
	}

	public function add_listing_single_title() {
		?>
		<header class="rtcl-single-listing-header">

			<?php do_action( 'rtcl_single_listing_store_info' ); ?>

			<div class="rtcl-listing-header-content">
				<?php do_action( 'rtcl_single_listing_content' ); ?>
			</div>
		</header>
		<?php
	}

	public function add_listing_single_gallery() {

		if ( ! FBHelper::isEnabled() && Functions::is_gallery_disabled() ) {
			return;
		}

		$gallery_style = CLFns::listing_gallery_style(); // gallery, gallery-full, gallery-split, gallery-grid,

		$_template = 'gallery';
		if ( 'gallery-split' === $gallery_style ) {
			$_template = 'gallery-split';
		}

		global $listing;

		$video_urls = [];
		if ( ! Functions::is_video_urls_disabled() && ! apply_filters( 'rtcl_disable_gallery_video', Functions::is_video_gallery_disabled() ) ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		Functions::get_template(
			"listing/$_template",
			[
				'images'  => $listing->get_images(),
				'videos'  => $video_urls,
				'listing' => $listing,
			]
		);
	}


	public function single_listing_meta() {
		/** @var Listing $listing */
		global $listing;
		?>
		<!-- Meta data -->
		<div class="rtcl-listing-meta">
			<?php $listing->the_meta(); ?>
		</div>
		<?php
	}

	public function rtcl_single_listing_settings_options( $options ) {

		$options['has_compare_icon']  = [
			'title'       => esc_html__( 'Enable Compare Icon', 'listzen' ),
			'type'        => 'checkbox',
			'default'     => 'yes',
			'description' => esc_html__( 'Check this to enable Compare Icon', 'listzen' ),
		];
		$options['has_print_icon']    = [
			'title'       => esc_html__( 'Enable Print Icon', 'listzen' ),
			'type'        => 'checkbox',
			'default'     => 'yes',
			'description' => esc_html__( 'Check this to enable Print Icon', 'listzen' ),
		];
		$options['has_bookmark_icon'] = [
			'title'       => esc_html__( 'Enable Bookmark Icon', 'listzen' ),
			'type'        => 'checkbox',
			'description' => esc_html__( 'Check this to enable Bookmark Icon', 'listzen' ),
		];

		return $options;
	}

	public function modify_listing_action_args( $the_actions ) {
		$the_actions['has_compare_icon']  = (bool) Functions::get_option_item( 'rtcl_single_listing_settings', 'has_compare_icon', 'yes', 'checkbox' );
		$the_actions['has_bookmark_icon'] = (bool) Functions::get_option_item( 'rtcl_single_listing_settings', 'has_bookmark_icon', 'yes', 'checkbox' );
		$the_actions['has_print_icon']    = (bool) Functions::get_option_item( 'rtcl_single_listing_settings', 'has_print_icon', '', 'checkbox' );

		return $the_actions;
	}

	public function add_listing_info_on_top() {
		global $listing;
		?>
		<div class="listing-category-wrap">
			<div class="categoriy-list">
				<?php CLFns::listng_categories( $listing ); ?>
			</div>
			<?php CLFns::listing_rating( $listing ); ?>
			<?php $listing->the_badges(); ?>
		</div>
		<?php
	}

	public function add_single_listing_hour() {
		global $listing;
		$listing_id = $listing->get_id();

		$allBhs = BHS::get_business_hours( $listing_id );
		if ( empty( $allBhs['bhs'] ) ) {
			return;
		}
		$business_hours   = $allBhs['bhs'];
		$current_week_day = absint( gmdate( 'w', current_time( 'timestamp' ) ) );

		$options = [
			'show_closed_day'       => true,
			'highlight_open_period' => true,
			'open_close_separator'  => '&ndash;',
			'open_today_text'       => esc_html__( 'Open Today (24 Hours)', 'listzen' ),
			'closed_today_text'     => esc_html__( 'Closed Today', 'listzen' ),  // Modified by Rashid
		];

		echo "<div class='rtclbh-listing-hour-wrap'>";

		if ( BHS::openStatus( $business_hours ) ) {
			printf( '<div class="rtclbh-status rtclbh-status-open">%s</div>', esc_html__( 'Open', 'listzen' ) );
		} else {
			printf( '<div class="rtclbh-status rtclbh-status-closed">%s</div>', esc_html__( 'Closed', 'listzen' ) );
		}

		foreach ( Options::get_week_days() as $dayKey => $day ) {

			if ( $dayKey != $current_week_day ) {
				continue;
			}

			$dayData = ! empty( $business_hours[ $dayKey ] ) ? $business_hours[ $dayKey ] : '';

			// Show the "Closed" message if there are no open and close hours recorded for the day.
			if ( ! BHS::openToday( $dayData ) ) {
				if ( $options['show_closed_day'] ) {
					printf(
						'<div class="rtclbh-closed %1$s"><div class="day-name">%2$s</div><span class="rtclbh-info">%3$s</span></div>',
						sprintf( 'rtclbh-day-%d%s', absint( $dayKey ), $current_week_day === $dayKey ? ' rtclbh-active' : '' ),
						esc_attr( $day ),
						$current_week_day === $dayKey ? ( ! empty( $options['closed_today_text'] ) ? esc_html( $options['closed_today_text'] ) : esc_html__( 'Closed Today', 'listzen' ) )
							: ( ! empty( $options['closed_24_text'] ) ? esc_html( $options['closed_24_text'] ) : esc_html__( 'Closed', 'listzen' ) )
					);
				}

				// Exit this loop.
				continue;
			}

			if ( BHS::isOpenAllDayLong( $dayData ) ) {
				printf(
					'<div class="rtclbh-opened %1$s"><div class="day-name">%2$s</div><span class="rtclbh-info">%3$s</span></div>',
					sprintf( 'rtclbh-day-%d%s', absint( $dayKey ), $current_week_day === $dayKey ? ' rtclbh-active' : '' ),
					esc_attr( $day ),
					$current_week_day === $dayKey ? ( ! empty( $options['open_today_text'] ) ? esc_html( $options['open_today_text'] ) : esc_html__( 'Open Today (24 Hours)', 'listzen' ) )
						: ( ! empty( $options['open_24_text'] ) ? esc_html( $options['open_24_text'] ) : esc_html__( 'Open (24 Hours)', 'listzen' ) )
				);

				// Exit this loop.
				continue;
			}

			$timePeriods = $dayData['times'];
			// If there are open and close hours recorded for the day, loop thru the open periods.
			foreach ( $timePeriods as $periodIndex => $timePeriod ) {

				if ( BHS::openPeriod( $timePeriod ) ) {
					printf(
						'<div class="rtclbh-period %1$s" %2$s><div class="day-name">%3$s</div><span class="rtclbh-close">%4$s %5$s %6$s</span></div>',
						sprintf(
							'rtclbh-day-%d%s%s',
							absint( $dayKey ),
							$current_week_day === $dayKey ? ' rtclbh-active' : '',
							$options['highlight_open_period'] && $current_week_day === $dayKey && BHS::isOpen( $timePeriod['start'], $timePeriod['end'] ) ? ' rtclbh-opened' : ''
						),
						'data-count="' . absint( $periodIndex ) . '"',
						$periodIndex == 0 ? esc_attr( $day ) : '&nbsp;',
						esc_html( Utility::formatTime( $timePeriod['start'], null, 'H:i' ) ),
						esc_attr( $options['open_close_separator'] ),
						esc_html( Utility::formatTime( $timePeriod['end'], null, 'H:i' ) )
					);

				}
			}
		}

		printf(
			"<div class='see-hours'><a href='#rtclbh-widget'>%s</a></div>",
			esc_html__( 'See hours', 'listzen' )
		);
		echo '</div>';
	}

	public function listing_action() {
		global $listing;
		?>
		<div class="single-listing-custom-fields-action">
			<?php Functions::get_template( 'listing/loop/price' ); ?>
			<?php $listing->the_actions(); ?>
		</div>
		<?php
	}

	public function add_store_info() {
		global $listing;
		if ( ! $listing ) {
			return;
		}
		$store = CLFns::get_store_by_owner_id( $listing->get_owner_id() );
		if ( ! $store ) {
			return;
		}
		?>
		<div class="rtcl-single-store-info">
			<div class="rtcl-store-image">
				<a href="<?php $store->the_permalink(); ?>" class="store-logo">
					<?php listzen_html( $store->get_the_logo() ); ?>
				</a>
				<a href="<?php $store->the_permalink(); ?>" class="store-name d-none">
					<?php $store->the_title(); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Modify listing per view for desktop device with query string.
	 *
	 * @param $per_row
	 *
	 * @return mixed
	 */
	public function listing_column_view_per_row( $per_row ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['cols'] ) ) {
			$per_row['desktop'] = sanitize_text_field( wp_unslash( $_GET['cols'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		return $per_row;
	}

	/**
	 * Enable map and enqueue map on archive page
	 *
	 * @return void
	 */
	public function rtcl_before_content_wrapper() {
		if ( ! CLFns::is_map_enable() ) {
			return;
		}
		global $rtcl_has_map_data;
		$rtcl_has_map_data = 1;

		wp_enqueue_script( 'rtcl-map' );
	}

	/**
	 * Append html markup on the listing archive page for the map
	 *
	 * @return void
	 */
	public function listing_archive_map_markup() {
		if ( ! CLFns::is_map_enable() ) {
			return;
		}
		if ( ! ( is_post_type_archive( 'rtcl_listing' ) || is_tax( get_object_taxonomies( 'rtcl_listing' ) ) ) ) {
			return;
		}
		?>
		<div class="rtcl-map-wrapper">
			<div class="rtcl-search-map rtcl-archive-map-embed">
				<div class="rtcl-map-view" data-map-type="search"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add switch to listing archive settings to enable/disable map
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public function rtcl_listing_map_settings_options( $options ) {

		$options['enable_archive_map'] = [
			'title'       => esc_html__( 'Enable Map', 'listzen' ),
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Enable Map', 'listzen' ),
			'description' => esc_html__( 'Enable the map display on the archive page.', 'listzen' ),
		];

		return $options;
	}

	/**
	 * Add Custom Toggle filter button on listing archive page
	 *
	 * @return void
	 */
	public function filter_button() {

		?>
		<button class="rtcl-listing-filter-collapse">
			<i class="rt-icon-filter"></i>
			<span><?php echo esc_html__( 'Filter', 'listzen' ); ?></span>
		</button>
		<?php
	}

	/**
	 * Add custom fields in Ajax Filter builder
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public function add_custom_filter_form_items( $options ) {
		$custom_fields = [
			[
				'label' => esc_html__( 'Disable Collapse ?', 'listzen' ),
				'id'    => 'disable_collapse',
				'type'  => 'switch',
			],
			[
				'label' => esc_html__( 'Hide Title ?', 'listzen' ),
				'id'    => 'hide_title',
				'type'  => 'switch',
			],
		];

		// Add custom fields to all sections that have 'fields'
		foreach ( $options as &$section ) {
			if ( ! empty( $section['fields'] ) && is_array( $section['fields'] ) ) {
				$section['fields'] = array_merge( $section['fields'], $custom_fields );
			}
		}

		// Add select type to category and location
		foreach ( [ 'category', 'location' ] as $key ) {
			if ( ! empty( $options[ $key ]['fields'] ) && is_array( $options[ $key ]['fields'] ) ) {
				foreach ( $options[ $key ]['fields'] as &$field ) {
					if ( isset( $field['id'] ) && $field['id'] === 'type' ) {
						$field['options']['rt-select'] = 'Select';
						break;
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Add Listing Classes
	 *
	 * @param $classses
	 *
	 * @return mixed
	 */
	public function add_listing_classes( $classses ) {

		if ( CLFns::listing_archive_style() ) {
			$classses[] = 'style-' . CLFns::listing_archive_style();
		}

		return $classses;
	}

	/**
	 * Add custom class for Ajax filter
	 *
	 * @param $opt_name
	 * @param $itemData
	 *
	 * @return mixed|string
	 */
	public function add_rtcl_ajax_filter_item_class( $opt_name, $itemData ) {

		if ( ! empty( $itemData['hide_title'] ) ) {
			$opt_name = $opt_name . ' should-hide-title';
		}
		if ( ! empty( $itemData['disable_collapse'] ) ) {
			$opt_name = $opt_name . ' disable-collapse';
		}

		return $opt_name;
	}

	/**
	 * Add options item in listing archive settings
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public function listing_display_options( $options ) {

		$options['author_image'] = esc_html__( 'Author Image', 'listzen' );
		$options['rating']       = esc_html__( 'Rating', 'listzen' );
		$options['phone_number'] = esc_html__( 'Phone Number', 'listzen' );

		return $options;
	}

	public function listing_detail_page_display_options( $options ) {
		$options['email'] = esc_html__( 'Listing owner email', 'listzen' );

		return $options;
	}

	/**
	 * Listing agent name and image
	 *
	 * @return void
	 */
	public function listing_loop_item_author_image_and_rating() {
		global $listing;
		if ( CLFns::can_show_author_image() ) {
			?>
			<div class="rtcl-author-info">
				<?php CLFns::get_listing_author_image( $listing ); ?>
			</div>
			<?php
		}
		if ( CLFns::can_show_rating() ) {
			CLFns::listing_rating( $listing );
		}
	}

	/**
	 * Add custom icons to CL icon library
	 *
	 * @param $icons_lists
	 *
	 * @return array
	 */
	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons        = IconList::get_icons();
		$icons_with_space = array_map(
			function ( $icon ) {
				return ' ' . $icon;
			},
			$new_icons
		);

		return array_merge( $icons_lists, $icons_with_space );
	}

	public static function seller_email( $listing ) {

		if ( is_a( $listing, Listing::class ) && Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' )
			 && $email = get_post_meta( $listing->get_id(), 'email', true )
		) {
			if ( is_user_logged_in() && get_current_user_id() === $listing->get_author_id() ) {
				return;
			}
			?>
			<div class='rtcl-do-email rtcl-list-group-item'>
				<a class="rtcl-do-email-link" href='#'>
					<span class='rtcl-icon rt-icon-envelope mr-2'></span>
					<span><?php echo esc_html( Text::get_single_listing_email_button_text() ); ?></span>
				</a>
				<?php $listing->email_to_seller_form(); ?>
			</div>
			<?php
		}
	}
}
