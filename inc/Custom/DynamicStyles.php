<?php

namespace Listzen\Custom;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Helpers\CLFns;
use Listzen\Helpers\Constants;
use Listzen\Helpers\Fns;
use Listzen\Options\Opt;
use Listzen\Traits\SingletonTraits;

/**
 * DynamicStyles Class
 */
class DynamicStyles {

	use SingletonTraits;

	protected $meta_data;

	/**
	 * Class Construct
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ], 999 ); //Gutenberg compatibility
	}


	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->meta_data = get_post_meta( get_the_ID(), "listzen_layout_meta_data", true );
		$dynamic_css     = $this->inline_style();
		wp_register_style( 'listzen-dynamic', false, 'listzen-main', Constants::get_version() );
		wp_enqueue_style( 'listzen-dynamic' );
		wp_add_inline_style( 'listzen-dynamic', $this->minify_css( $dynamic_css ) );
	}

	/**
	 * Minify CSS
	 *
	 * @param $css
	 *
	 * @return array|string|string[]|null
	 */
	function minify_css( $css ) {
		$css = preg_replace( '/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css ); // Remove comments
		$css = preg_replace( '/\s+/', ' ', $css ); // Remove multiple spaces
		$css = preg_replace( '/\s*([\{\};])\s*/', '$1', $css ); // Remove spaces around { } ; : ,

		return $css;
	}

	/**
	 * Make Inline CSS
	 *
	 * @return false|string
	 */
	private function inline_style() {

		$primary_color     = listzen_option( 'listzen_primary_color', '#FE6254' );
		$primary_dark      = listzen_option( 'listzen_primary_dark', '#EE4131' );
		$primary_light     = listzen_option( 'listzen_primary_light', '#FF786C' );
		$primary_soft      = listzen_option( 'listzen_primary_soft', '#FFEEEA' );
		$secondary_color   = listzen_option( 'listzen_secondary_color', '#111111' );
		$body_bg_color     = listzen_option( 'listzen_body_bg_color', '#FFFFFF' );
		$body_color        = listzen_option( 'listzen_body_color', '#444444' );
		$border_color      = listzen_option( 'listzen_border_color', '#e7e7e7' );
		$title_color       = listzen_option( 'listzen_title_color', '#111111' );
		$rating_color      = listzen_option( 'listzen_rating_color', '#F9BA19' );
		$button_color      = listzen_option( 'listzen_button_color', '#ffffff' );
		$button_text_color = listzen_option( 'listzen_button_text_color', '#00030C' );
		$meta_color        = listzen_option( 'listzen_meta_color', '#47494c' );
		$meta_light        = listzen_option( 'listzen_meta_light', '#d3d9e1' );
		$gray_color        = listzen_option( 'listzen_gray', '#DDDDDD' );
		$gray_light        = listzen_option( 'listzen_gray_light', '#f8f8f8' );
		$gray_dark         = listzen_option( 'listzen_gray_dark', '#7a7d81' );

		//Menu
		$menu_color         = listzen_option( 'listzen_menu_color', $title_color );
		$_menu_active_color = listzen_option( 'listzen_menu_active_color', $primary_color );
		$_menu_bg_color     = listzen_option( 'listzen_menu_bg_color', '#FFFFFF' );
		$_sub_menu_bg_color = listzen_option( 'listzen_sub_menu_bg_color', '#FFFFFF' );
		$tr_menu_color      = listzen_option( 'listzen_tr_menu_color', '#FFFFFF' );
		$tr_menu_active     = listzen_option( 'listzen_tr_menu_active_color', $primary_color );

		$container_width = listzen_option( 'container_width', '1280' );

		$listzen_radius     = Fns::customize_radius( 'listzen_radius' );
		$listzen_btn_radius = Fns::customize_radius( 'listzen_btn_radius' );

		$listing_container_width = CLFns::listing_container_width();
		$listing_gallery_height  = CLFns::listing_gallery_height();

		//phpcs:disable
		ob_start(); ?>

        :root {
        --listzen-primary-color: <?php echo esc_html( $primary_color ); ?>;
        --listzen-primary-dark: <?php echo esc_html( $primary_dark ); ?>;
        --listzen-primary-light: <?php echo esc_html( $primary_light ); ?>;
        --listzen-primary-soft: <?php echo esc_html( $primary_soft ); ?>;
        --listzen-secondary-color: <?php echo esc_html( $secondary_color ); ?>;
        --listzen-body-bg-color: <?php echo esc_html( $body_bg_color ); ?>;
        --listzen-body-color: <?php echo esc_html( $body_color ); ?>;
        --listzen-border-color: <?php echo esc_html( $border_color ); ?>;
        --listzen-title-color: <?php echo esc_html( $title_color ); ?>;
        --listzen-rating-color: <?php echo esc_html( $rating_color ); ?>;
        --listzen-button-color: <?php echo esc_html( $button_color ); ?>;
        --listzen-button-text-color: <?php echo esc_html( $button_text_color ); ?>;
        --listzen-meta-color: <?php echo esc_html( $meta_color ); ?>;
        --listzen-meta-light: <?php echo esc_html( $meta_light ); ?>;
        --listzen-gray: <?php echo esc_html( $gray_color ); ?>;
        --listzen-gray-light: <?php echo esc_html( $gray_light ); ?>;
        --listzen-gray-dark: <?php echo esc_html( $gray_dark ); ?>;
        --listzen-tr-1: <?php echo esc_html( "rgba(0, 0, 0, 0.1)" ); ?>;
        --listzen-tr-2: <?php echo esc_html( "rgba(0, 0, 0, 0.2)" ); ?>;
        --listzen-tr-3: <?php echo esc_html( "rgba(0, 0, 0, 0.3)" ); ?>;
        --listzen-body-rgb: <?php echo esc_html( Fns::hex2rgb( $body_color ) ); ?>;
        --listzen-title-rgb: <?php echo esc_html( Fns::hex2rgb( $title_color ) ); ?>;
        --listzen-primary-rgb: <?php echo esc_html( Fns::hex2rgb( $primary_color ) ); ?>;
        --listzen-secondary-rgb: <?php echo esc_html( Fns::hex2rgb( $secondary_color ) ); ?>;
        --listzen-container-width: <?php echo esc_html( $container_width < 992 ? 992 : $container_width ); ?>px;
        --listzen-radius: <?php echo esc_html( $listzen_radius ); ?>;
        --listzen-btn-radius:<?php echo esc_html( $listzen_btn_radius ); ?>;
        --listzen-menu-color: <?php echo esc_html( $menu_color ); ?>;
        --listzen-menu-active-color: <?php echo esc_html( $_menu_active_color ); ?>;
        --listzen-menu-bg-color: <?php echo esc_html( $_menu_bg_color ); ?>;
        --listzen-submenu-bg-color: <?php echo esc_html( $_sub_menu_bg_color ); ?>;
        --listzen-tr-menu: <?php echo esc_html( $tr_menu_color ); ?>;
        --listzen-tr-menu-hover: <?php echo esc_html( $tr_menu_active ); ?>;
		<?php if ( $listing_container_width ) : ?>
            --rtcl-archive-width: <?php echo esc_html( $listing_container_width ) ?>;
		<?php endif; ?>

		<?php if ( $listing_gallery_height ) : ?>
            @media (min-width: 1024px) {
            --rtcl-gallery-height: <?php echo esc_html( $listing_gallery_height ) ?>;
            }
		<?php endif; ?>

        }

        body {
        color: <?php echo esc_html( $body_color ); ?>;
        }

		<?php
		//phpcs:enable
		$this->site_fonts();
		$this->topbar_css();
		$this->header_css();
		$this->banner_css();
		$this->content_padding_css();
		$this->footer_css();
		$this->site_background();

		return ob_get_clean();
	}

	/**
	 * Topbar Style
	 *
	 * @return void
	 */
	protected function topbar_css() {
		$_topbar_active_color = listzen_option( 'listzen_topbar_active_color' );
		self::css( 'body .site-header .listzen-topbar .topbar-container *:not(.dropdown-menu *)', 'color', 'listzen_topbar_color' );
		self::css( 'body .site-header .listzen-topbar .topbar-container svg:not(.dropdown-menu svg)', 'fill', 'listzen_topbar_color' );

		if ( ! empty( $_topbar_active_color ) ) : ?>
            body .site-header .listzen-topbar .topbar-container a:hover:not(.dropdown-menu a:hover),
            body .listzen-topbar #topbar-menu ul ul li.current_page_item > a,
            body .listzen-topbar #topbar-menu ul ul li.current-menu-ancestor > a,
            body .listzen-topbar #topbar-menu ul.listzen-topbar-menu > li.current-menu-item > a,
            body .listzen-topbar #topbar-menu ul.listzen-topbar-menu > li.current-menu-ancestor > a {
            color: <?php echo esc_attr( $_topbar_active_color ); ?>;
            }

            body .site-header .listzen-topbar .topbar-container a:hover:not(.dropdown-menu a:hover svg) svg,
            body .listzen-topbar #topbar-menu ul ul li.current-menu-ancestor > a svg,
            body .listzen-topbar #topbar-menu ul.listzen-topbar-menu > li.current-menu-item > a svg,
            body .listzen-topbar #topbar-menu ul.listzen-topbar-menu > li.current-menu-ancestor > a svg {
            fill: <?php echo esc_attr( $_topbar_active_color ); ?>;
            }
		<?php endif; ?>

		<?php
		self::css( 'body .listzen-topbar', 'background-color', 'listzen_topbar_bg_color' );

	}


	/**
	 * Menu Color Style
	 *
	 * @return void
	 */
	protected function header_css() {
		//Logo CSS
		$logo_width        = '';
		$logo_mobile_width = '';

		$logo_dimension        = listzen_option( 'listzen_logo_width_height' );
		$logo_mobile_dimension = listzen_option( 'listzen_mobile_logo_width_height' );
		$menu_border_bottom    = listzen_option( 'listzen_menu_border_color' );

		if ( strpos( $logo_dimension, ',' ) ) {
			$logo_width = explode( ',', $logo_dimension );
		}
		if ( strpos( $logo_mobile_dimension, ',' ) ) {
			$logo_mobile_width = explode( ',', $logo_mobile_dimension );
		}

		//Default Menu
		$_header_border      = listzen_option( 'listzen_header_border' );
		$_breadcrumb_border  = listzen_option( 'listzen_breadcrumb_border' );
		$_preloader_bg_color = listzen_option( 'preloader_bg_color' );
		?>


		<?php //Header Logo CSS ?>
		<?php if ( Opt::$header_width == 'full' ) :
			$h_width = '100%';
			if ( ( $header_width = listzen_option( 'listzen_header_max_width' ) ) > 768 ) {
				$h_width = $header_width . 'px';
			}
			?>
            .header-container,
            .topbar-container {
            width: <?php echo esc_attr( $h_width ); ?>;
            max-width: 100%;
            }
		<?php endif; ?>

		<?php if ( ! empty( $logo_width ) ) : ?>
            .site-branding .site-logo {
            max-width: <?php echo esc_attr( $logo_width[0] ?? '100%' ) ?>;
            max-height: <?php echo esc_attr( $logo_width[1] ?? 'auto' ) ?>;
            object-fit: contain;
            }
		<?php endif; ?>

		<?php
		if ( ! empty( $logo_mobile_width ) ) : ?>
            .site-branding .site-mobile-logo {
            max-width: <?php echo esc_attr( $logo_mobile_width[0] ?? '100%' ) ?>;
            max-height: <?php echo esc_attr( $logo_mobile_width[1] ?? 'auto' ) ?>;
            object-fit: contain;
            }
		<?php endif; ?>


		<?php if ( ! empty( $menu_border_bottom ) ) : ?>
            body .listzen-topbar,
            body .main-header-section,
            body .listzen-breadcrumb-wrapper {
            border-bottom-color: <?php echo esc_attr( $menu_border_bottom ); ?>;
            }
		<?php endif; ?>

		<?php if ( ! $_header_border ) : ?>
            body .main-header-section {border-bottom: none;}
		<?php endif; ?>
		<?php if ( ! $_breadcrumb_border ) : ?>
            body .listzen-breadcrumb-wrapper {border-bottom: none;}
		<?php endif; ?>

		<?php if ( ! empty( $_preloader_bg_color ) ) : ?>
            #preloader {
            background-color: <?php echo esc_attr( $_preloader_bg_color ); ?>;
            }
		<?php endif; ?>

		<?php
	}

	/**
	 * Breadcrumb CSS
	 *
	 * @return void
	 */
	protected function banner_css() {
		$breadcrumb_color                 = listzen_option( 'listzen_breadcrumb_color' );
		$listzen_breadcrumb_hover       = listzen_option( 'listzen_breadcrumb_hover' );
		$breadcrumb_active                = listzen_option( 'listzen_breadcrumb_active' );
		$listzen_breadcrumb_title_color = listzen_option( 'listzen_breadcrumb_title_color' );
		$listzen_banner_overlay         = listzen_option( 'listzen_banner_overlay' );
		$listzen_banner_height          = listzen_option( 'listzen_banner_height' );
		$listzen_banner_padding_top     = listzen_option( 'listzen_banner_padding_top' );
		$listzen_banner_padding_bottom  = listzen_option( 'listzen_banner_padding_bottom' );

		if ( ! empty( $listzen_banner_height ) ) { ?>
            .listzen-breadcrumb-wrapper {
            height: clamp(120px,25vw,<?php echo esc_attr( $listzen_banner_height ); ?>px)}
		<?php }

		if ( ! empty( $listzen_breadcrumb_title_color ) ) { ?>
            .listzen-breadcrumb-wrapper .entry-title {
            color: <?php echo esc_attr( $listzen_breadcrumb_title_color ) ?> !important;
            }
		<?php }

		if ( ! empty( $listzen_banner_overlay ) ) { ?>
            .listzen-breadcrumb-wrapper.has-bg::before {
            background-color: <?php echo esc_attr( $listzen_banner_overlay ) ?>;
            }
			<?php
		}

		if ( ! empty( $breadcrumb_color ) ) { ?>
            .listzen-breadcrumb-wrapper .breadcrumb a,
            .listzen-breadcrumb-wrapper .entry-breadcrumb span a,
            .listzen-breadcrumb-wrapper .entry-breadcrumb .dvdr {
            color: <?php echo esc_attr( $breadcrumb_color ) ?>;
            }
		<?php }

		if ( ! empty( $listzen_breadcrumb_hover ) ) { ?>
            .listzen-breadcrumb-wrapper .breadcrumb a:hover,
            .listzen-breadcrumb-wrapper .entry-breadcrumb span a:hover {
            color: <?php echo esc_attr( $listzen_breadcrumb_hover ) ?>;
            }
		<?php }

		if ( ! empty( $breadcrumb_active ) ) { ?>
            .listzen-breadcrumb-wrapper .breadcrumb li.active .title,
            .listzen-breadcrumb-wrapper .breadcrumb a:hover,
            .listzen-breadcrumb-wrapper .entry-breadcrumb span a:hover,
            .listzen-breadcrumb-wrapper .entry-breadcrumb .current-item,
            .has-trheader .listzen-breadcrumb-wrapper .breadcrumb li.active .title,
            .has-trheader .listzen-breadcrumb-wrapper .breadcrumb a:hover {
            color: <?php echo esc_attr( $breadcrumb_active ) ?>;
            }
		<?php }

		if ( ! empty( Opt::$banner_color ) ) { ?>
            .listzen-breadcrumb-wrapper,
            .listzen-banner-2 .listzen-breadcrumb-wrapper {
            background-color: <?php echo esc_attr( Opt::$banner_color ); ?>;
            }
		<?php }

		if ( ! empty( $listzen_banner_padding_top ) ) { ?>
            .listzen-breadcrumb-wrapper {
            padding-top: <?php echo esc_attr( $listzen_banner_padding_top ) ?>px !important;
            }
		<?php }

		if ( ! empty( $listzen_banner_padding_bottom ) ) { ?>
            .listzen-breadcrumb-wrapper {
            padding-bottom: <?php echo esc_attr( $listzen_banner_padding_bottom ) ?>px !important;
            }
		<?php }

	}

	/**
	 * Content CSS
	 *
	 * @return void
	 */
	protected function content_padding_css() {
		if ( ! empty( Opt::$padding_top ) && 'default' !== Opt::$padding_top ) { ?>
            .content-area {padding-top: <?php echo esc_attr( Opt::$padding_top ); ?>px;}
		<?php }

		if ( ! empty( Opt::$padding_bottom ) && 'default' !== Opt::$padding_bottom ) { ?>
            .content-area {padding-bottom: <?php echo esc_attr( Opt::$padding_bottom ); ?>px;}
		<?php }
	}

	/**
	 * Footer CSS
	 *
	 * @return void
	 */
	protected function footer_css() {
		if ( listzen_option( 'listzen_footer_width' ) && listzen_option( 'listzen_footer_max_width' ) > 1400 ) {
			self::css( '.site-footer .footer-container', 'width', 'listzen_footer_max_width', 'px;max-width: 100%' );
		}
		self::css( 'body .site-footer *:not(a), body .site-footer .widget', 'color', 'listzen_footer_text_color' );
		self::css( 'body .site-footer .footer-sidebar a, body .site-footer .footer-sidebar .widget a, body .site-footer .footer-sidebar .phone-no a', 'color', 'listzen_footer_link_color' );
		self::css( 'body .site-footer a:hover, body .site-footer .footer-sidebar a:hover', 'color', 'listzen_footer_link_hover_color' );
		self::css( 'body .site-footer .footer-widgets-wrapper', 'background-color', 'listzen_footer_bg' );
		self::css( 'body .site-footer .widget :is(td, th, select, .search-box)', 'border-color', 'listzen_footer_input_border_color' );
		self::css( 'body .site-footer .widget-title', 'color', 'listzen_footer_widget_title_color' );
		self::css( 'body .site-footer .footer-copyright-wrapper, body .site-footer label, body .footer-copyright-wrapper .copyright-text', 'color', 'listzen_copyright_text_color' );
		self::css( 'body .site-footer .footer-copyright-wrapper a', 'color', 'listzen_copyright_link_color' );
		self::css( 'body .site-footer .footer-copyright-wrapper a:hover', 'color', 'listzen_copyright_link_hover_color' );
		self::css( 'body .site-footer .footer-copyright-wrapper', 'background-color', 'listzen_copyright_bg' );
	}


	/**
	 * Load site fonts
	 *
	 * @return void
	 */
	protected function site_fonts() {

		$typo_body           = json_decode( listzen_option( 'listzen_body_typo' ), true );
		$typo_menu           = json_decode( listzen_option( 'listzen_menu_typo' ), true );
		$typo_heading        = json_decode( listzen_option( 'listzen_all_heading_typo' ), true );
		$body_font_family    = $typo_body['font'] ?? 'Inter';
		$heading_font_family = $typo_heading['font'] ?? $body_font_family;
		?>
        :root{
        --listzen-body-font: '<?php echo esc_html( $typo_body['font'] ); ?>', sans-serif;
        --listzen-body-fs: <?php echo esc_html( $typo_body['size'] ); ?>px;
        --listzen-heading-font: '<?php echo esc_html( $heading_font_family ); ?>', sans-serif;
        --listzen-menu-font: '<?php echo esc_html( $typo_body['font'] ); ?>', sans-serif;
        }

		<?php
		self::font_css( 'body', $typo_body );
		self::font_css( '.site-header', [ 'font' => $typo_menu['font'] ] );
		self::font_css( '.listzen-navigation ul li a', [
			'size'          => $typo_menu['size'],
			'regularweight' => $typo_menu['regularweight'],
			'lineheight'    => $typo_menu['lineheight']
		] );
		self::font_css( '.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6', [
			'font'          => $typo_heading['font'],
			'regularweight' => $typo_heading['regularweight']
		] );

		$heading_fonts = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];
		foreach ( $heading_fonts as $heading ) {
			$font = json_decode( listzen_option( "listzen_heading_{$heading}_typo" ), true );
			if ( ! empty( $font['font'] ) ) {
				$selector = "$heading, .$heading";
				self::font_css( $selector, $font );
			}
		}
	}


	/**
	 * Generate CSS
	 *
	 * @param $selector
	 * @param $property
	 * @param $theme_mod
	 *
	 * @return string|void
	 */
	public static function css( $selector, $property, $theme_mod, $after_css = '' ) {
		$theme_mod = listzen_option( $theme_mod );

		if ( ! empty( $theme_mod ) ) {
			printf( '%s { %s:%s%s; }', $selector, $property, $theme_mod, $after_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Font CSS
	 *
	 * @param $selector
	 * @param $property
	 * @param $theme_mod
	 * @param $after_css
	 *
	 * @return string
	 */
	public static function font_css( $selector, $font ) {
		$font_weight = $font['regularweight'] ?? '';
		if ( str_contains( $font_weight, 'italic' ) ) {
			$font_weight = str_replace( 'italic', '', $font_weight );
			$font_weight .= ';font-style: italic';
		}
		$css = '';
		$css .= $selector . '{'; //Start CSS
		$css .= ! empty( $font['font'] ) ? "font-family: {$font['font']}, sans-serif;" : '';
		$css .= ! empty( $font['size'] ) ? "font-size: {$font['size']}px;" : '';
		$css .= ! empty( $font['lineheight'] ) ? "line-height: {$font['lineheight']}px;" : '';
		$css .= ! empty( $font_weight ) ? "font-weight: {$font_weight};" : '';
		$css .= '}'; //End CSS

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS is safely generated
        echo wp_strip_all_tags( $css );
	}

	/**
	 * Site background
	 *
	 * @return string
	 */

	function site_background() {
		if ( ! empty( Opt::$pagebgimg ) ) {
			$bg = wp_get_attachment_image_src( Opt::$pagebgimg, 'full' );
			if ( ! empty( $bg[0] ) ) { ?>
                body {
                background-image: url(<?php echo esc_url( $bg[0] ) ?>);
                background-repeat: repeat;
                background-position: top center;
                background-size: 100%;
                }
				<?php
			}
		}
		if ( ! empty( Opt::$pagebgcolor ) && 'default' !== Opt::$pagebgcolor ) { ?>
            body {
            background-color: <?php echo esc_attr( Opt::$pagebgcolor ); ?>;
            }
		<?php }
	}
}
