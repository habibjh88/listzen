<?php
/**
 * Helpers methods
 * List all your static functions you wish to use globally on your theme
 *
 * @package listzen
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Listzen\Modules\PostMeta;
use Listzen\Options\Opt;
use Listzen\Helpers\Fns;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

/*Allow HTML for the kses post*/
function listzen_html( $html, $context = '', $echo = true ) {
	// Define reusable tag configurations
	$base_tags = [
		'a'      => [ 'href' => [], 'class' => [], 'rel' => [], 'title' => [], 'target' => [] ],
		'b'      => [],
		'img'    => [ 'src' => [], 'alt' => [], 'class' => [], 'width' => [], 'height' => [], 'srcset' => [] ],
		'p'      => [ 'class' => [] ],
		'span'   => [ 'class' => [], 'style' => [], 'title' => [] ],
		'strong' => [],
		'br'     => [],
	];

	// Context-specific tag adjustments
	$tags_by_context = [
		'plain'       => [ 'a' => [ 'href' => [] ], 'b' => [], 'span' => [ 'class' => [] ], 'p' => [ 'class' => [] ] ],
		'social'      => [ 'a' => [ 'href' => [] ], 'b' => [] ],
		'allow_link'  => array_merge( $base_tags, [ 'img' => $base_tags['img'] ] ),
		'allow_title' => array_merge( $base_tags, [ 'strong' => [], 'br' => [] ] ),
		'default'     => array_merge( $base_tags, [
			'abbr'       => [ 'title' => [] ],
			'blockquote' => [ 'cite' => [] ],
			'code'       => [],
			'del'        => [ 'datetime' => [], 'title' => [] ],
			'div'        => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h1'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h2'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h3'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h4'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h5'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'h6'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
			'i'          => [ 'class' => [] ],
			'li'         => [ 'class' => [] ],
			'ol'         => [ 'class' => [] ],
			'ul'         => [ 'class' => [] ],
			'iframe'     => [
				'class'                 => [],
				'id'                    => [],
				'name'                  => [],
				'src'                   => [],
				'title'                 => [],
				'frameBorder'           => [],
				'width'                 => [],
				'height'                => [],
				'scrolling'             => [],
				'allow'                 => [],
				'allowvr'               => [],
				'allowFullScreen'       => [],
				'webkitallowfullscreen' => [],
				'mozallowfullscreen'    => [],
				'loading'               => [],
			],
		] ),
	];

	// Determine tags for the given context
	$tags = $tags_by_context[ $context ] ?? $tags_by_context['default'];

	// If echo is false, return the sanitized HTML
	if ( ! $echo ) {
		return wp_kses( $html, $tags );
	}

	// Echo the sanitized HTML
	echo wp_kses( $html, $tags );
}

if ( ! function_exists( 'listzen_utils' ) ) {
	/**
	 * Retrieve a predefined utility value by key for the Listzen plugin.
	 *
	 * @param $key
	 *
	 * @return string|void
	 */
	function listzen_utils( $key ) {
		$utils = [
			'post_view_key' => 'listzen_post_views'
		];

		if ( isset( $utils[ $key ] ) ) {
			return $utils[ $key ];
		}
	}
}

if ( ! function_exists( 'listzen_class_list' ) ) {
	/**
	 * Class list
	 *
	 * @param $clsses
	 *
	 * @return string
	 */
	function listzen_class_list( $clsses ) {
		return trim( implode( ' ', $clsses ) );
	}
}

if ( ! function_exists( 'listzen_custom_menu_cb' ) ) {
	/**
	 * Callback function for the main menu
	 *
	 * @param $args
	 *
	 * @return string|void
	 */
	function listzen_custom_menu_cb( $args ) {
		extract( $args );
		$add_menu_link = admin_url( 'nav-menus.php' );

		$menu_text = sprintf(
		/* translators: %s: is Menu Name */
			__( "Add %s Menu", "listzen" ),
			$theme_location
		);

		if ( ! current_user_can( 'manage_options' ) ) {
			$add_menu_link = home_url();
			$menu_text     = __( 'Home', 'listzen' );
		}

		// see wp-includes/nav-menu-template.php for available arguments

		$link = $link_before . '<a href="' . $add_menu_link . '">' . $before . $menu_text . $after . '</a>' . $link_after;

		// We have a list
		if ( false !== stripos( $items_wrap, '<ul' ) || false !== stripos( $items_wrap, '<ol' ) ) {
			$link = "<li>$link</li>";
		}

		$output = sprintf( $items_wrap, $menu_id, $menu_class, $link );
		if ( ! empty ( $container ) ) {
			$output = "<$container class='$container_class' id='$container_id'>$output</$container>";
		}

		if ( $echo ) {
			echo wp_kses_post( $output );
		}

		return $output;
	}
}

if ( ! function_exists( 'listzen_hamburger' ) ) {
	/**
	 * Hamburger menu
	 *
	 * @param $style
	 *
	 * @return string
	 */
	function listzen_hamburger() {
		$style = listzen_option( 'listzen_hamburger_style', '3' );
		switch ( $style ) {
			case '1':
				$icon = '<div class="listzen-hamburger style1"><span></span><span></span><span></span></div>';
				break;
			case '2':
				$icon = '<div class="listzen-hamburger style2"><span></span><span></span><span></span><span></span><span></span><span></span></div>';
				break;
			case '3':
				$icon = '<div class="listzen-hamburger style3"><span></span><span></span><span></span><span></span></div>';
				break;
			case '4':
				$icon = '<div class="listzen-hamburger style4"><span></span><span></span><span></span></div>';
				break;
		}

		return $icon;
	}
}

if ( ! function_exists( 'listzen_menu_icons_group' ) ) {
	/**
	 * Get menu icon group
	 *
	 * @return void
	 */
	function listzen_menu_icons_group( $args = [] ) {
		$default_args = [
			'hamburg'              => listzen_option( 'listzen_header_bar' ),
			'search'               => listzen_option( 'listzen_header_search' ),
			'login'                => listzen_option( 'listzen_header_login_link' ),
			'login_text'           => false,
			'button'               => listzen_option( 'listzen_header_button' ),
			'button_link'          => listzen_option( 'listzen_header_button_url' ),
			'button_label'         => listzen_option( 'listzen_header_button_label' ),
			'listing_button'       => listzen_option( 'listzen_header_listing_button' ),
			'listing_button_link'  => listzen_option( 'listzen_header_listing_button_url' ),
			'listing_button_label' => listzen_option( 'listzen_header_listing_button_label' ),
			'has_separator'        => listzen_option( 'listzen_header_separator' ),
		];
		$args         = wp_parse_args( $args, $default_args );
		$has_button   = ( $args['button'] && $args['button_label'] ) || ( $args['listing_button'] && $args['listing_button_label'] );
		$menu_classes = '';

		if ( $args['has_separator'] ) {
			$menu_classes .= 'has-separator ';
		}

		if ( $has_button ) {
			$menu_classes .= 'has-button ';
		}

		$has_hamburg    = $args['hamburg'] ? 'has-hamburger' : 'no-hamburger';
		$is_popup_login = listzen_option( 'listzen_header_login_style' );
		?>
        <div class="menu-icon-wrapper d-flex ml-auto align-items-center">
            <ul class="d-flex gap-15 align-items-center <?php echo esc_attr( $menu_classes ) ?>">
				<?php
				$add_listing_url = $args['listing_button_link'] ?? '';

				if ( Fns::is_cl_active() && empty( $add_listing_url ) ) {
					$add_listing_url = Link::get_listing_form_page_link();
				}

				$icon_items = [];

				//No need the condition. Because it should show in the mobile by-default
				$icon_items['hamburg'] = sprintf(
					'<li class="hamburger-icon %s"><a class="menu-bar trigger-off-canvas" href="#">%s</a></li>',
					esc_attr( $has_hamburg ),
					listzen_hamburger()
				);

				if ( $args['search'] ) {
					$icon_items['search'] = sprintf(
						'<li class="listzen-search-popup"><a class="menu-search-bar listzen-search-trigger" href="%s" aria-label="%s"><i class="rt-icon-search"></i></a></li>',
						'#header-search',
						'search popup'
					);
				}

				if ( $args['login'] && ! Functions::is_account_page()) {
					$login_icon     = listzen_option( 'listzen_get_login_label' ) ? "<i class='rt-icon-user-3'></i>" : listzen_option( 'listzen_get_login_label' );
					$login_text     = sprintf( "<span class='mobile-hide'>%s</span>",
						is_user_logged_in() ? __( 'My Account', 'listzen' ) : __( 'Login', 'listzen' )
					);
					$is_popup_login = ( ! is_user_logged_in() && $is_popup_login ) ? "listzen-popup-login" : "";

					if ( Fns::is_cl_active() ) {
						$myaccount_page_id = Functions::get_option_item( 'rtcl_advanced_settings', 'myaccount' );
						$login_url         = get_permalink( $myaccount_page_id );
					} else {
						$login_url = wp_login_url();
					}

					$icon_items['login'] = sprintf(
						'<li class="listzen-user-login mobile-hide"><a class="%s" href="%s" aria-label="user login">%s %s</a></li>',
						esc_attr( $is_popup_login ),
						esc_url( $login_url ),
						$login_icon,
						$args['login_text'] ? $login_text : ""
					);
				}

				if ( $args['button'] && $args['button_label'] ) {
					$icon_items['button'] = sprintf(
						'<li class="listzen-button mobile-hide"><a class="btn" href="%s">%s</a></li>',
						esc_url( $args['button_link'] ),
						$args['button_label']
					);
				}

				if ( $args['listing_button'] && $args['listing_button_label'] ) {
					$icon_items['button2'] = sprintf(
						'<li class="listzen-button add-listing-btn"><a class="btn" href="%s"><i class="rt-icon-plus"></i><span class="">%s</span></a></li>',
						esc_url( $add_listing_url ),
						$args['listing_button_label']
					);
				}

				$icon_order = explode( ',', listzen_option( 'listzen_menu_icon_order' ) );
				$icon_order = array_map( 'trim', $icon_order );
				$button_pos = array_search( 'button', $icon_order );

				foreach ( $icon_order as $index => $icon ) {
					if ( ! isset( $icon_items[ $icon ] ) ) {
						continue;
					}
					$icon = $icon_items[ $icon ];
					if ( ( $button_pos - 1 ) == $index ) {
						$icon = str_replace( 'class="', 'class="button-prev ', $icon );
					}
					listzen_html( $icon );
				}
				?>
            </ul>
        </div>

		<?php
		if ( $is_popup_login && ! is_user_logged_in() && class_exists(Functions::class) && ! Functions::is_account_page() ) {
			?>
            <div class="listzen-popup" style="display:none;">
                <div class="listzen-popup-overlay"></div>
                <div class="listzen-popup-content">
                    <span class="listzen-popup-close">&times;</span>
					<?php Functions::login_form(); ?>
                </div>
            </div>
            <?php
		}
	}
}

if ( ! function_exists( 'listzen_require' ) ) {
	/**
	 * Require any file. If the file will available in the child theme, the file will load from the child theme
	 *
	 * @param $filename
	 * @param string $dir
	 *
	 * @return false|void
	 */
	function listzen_require( $filename, string $dir = 'inc' ) {
		$dir        = trailingslashit( $dir );
		$child_file = get_stylesheet_directory() . DIRECTORY_SEPARATOR . $dir . $filename;

		if ( file_exists( $child_file ) ) {
			$file = $child_file;
		} else {
			$file = get_template_directory() . DIRECTORY_SEPARATOR . $dir . $filename;
		}

		if ( file_exists( $file ) ) {
			require_once $file;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'listzen_readmore_text' ) ) {
	/**
	 * Creates continue reading text.
	 *
	 * @return string
	 */
	function listzen_readmore_text() {
		$read_more_label = listzen_option( 'listzen_blog_read_more', 'Read More' );

		return sprintf(
			'%s <span class="screen-reader-text">%s</span>',
			esc_html( $read_more_label ),
			get_the_title()
		);
	}
}

if ( ! function_exists( 'listzen_get_file' ) ) {
	/**
	 * Get File Path
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function listzen_get_file( $path, $return_path = false ): string {
		$file = ( $return_path ? get_stylesheet_directory() : get_stylesheet_directory_uri() ) . $path;
		if ( ! file_exists( $file ) ) {
			$file = ( $return_path ? get_template_directory() : get_template_directory_uri() ) . $path;
		}

		return $file;
	}
}

if ( ! function_exists( 'listzen_get_img' ) ) {
	/**
	 * Get Image Path
	 *
	 * @param $filename
	 * @param $echo
	 * @param $image_meta
	 *
	 * @return string|void
	 */
	function listzen_get_img( $filename, $echo = false, $image_meta = '' ) {
		$path      = '/assets/images/' . $filename;
		$image_url = listzen_get_file( $path );

		if ( $echo ) {
			if ( ! strpos( $filename, '.svg' ) ) {
				$getimagesize = wp_getimagesize( listzen_get_file( $path, true ) );
				$image_meta   = $getimagesize[3] ?? $image_meta;
			}
			listzen_html( '<img ' . $image_meta . ' src="' . esc_url( $image_url ) . '"/>' );
		} else {
			return $image_url;
		}
	}
}

if ( ! function_exists( 'listzen_get_css' ) ) {
	/**
	 * Get CSS Path
	 *
	 * @param $filename
	 * @param bool $check_rtl
	 *
	 * @return string
	 */
	function listzen_get_css( $filename, $check_rtl = false ) {
		$min    = WP_DEBUG ? '.css' : '.min.css';
		$is_rtl = $check_rtl && is_rtl() ? 'css-rtl' : 'css';
		$path   = "/assets/$is_rtl/" . $filename . $min;

		return listzen_get_file( $path );
	}
}

if ( ! function_exists( 'listzen_get_js' ) ) {
	/**
	 * Get JS Path
	 *
	 * @param $filename
	 * @param $folder_name
	 * @param $check_minify
	 *
	 * @return string
	 */
	function listzen_get_js( $filename, $folder_name = 'js', $check_minify = false ) {
		$min  = ( defined( 'WP_SCRIPT_DEBUG' ) && WP_SCRIPT_DEBUG && $check_minify ) ? '.min.js' : '.js';
		$path = "/assets/$folder_name/$filename$min";

		return listzen_get_file( $path );
	}
}

if ( ! function_exists( 'listzen_get_assets' ) ) {
	/**
	 * Get JS Path
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	function listzen_get_assets( $filename, $return_path = false ) {
		$path = '/assets/' . $filename;

		return listzen_get_file( $path, $return_path );
	}
}

if ( ! function_exists( 'listzen_option' ) ) {
	/**
	 * Get Customize Options value by key
	 *
	 * @param $key
	 * @param $default
	 * @param $return_array //make an array explode by ',' comma
	 *
	 * @return false|mixed|string|string[]
	 */
	function listzen_option( $key, $default = '', $return_array = false ) {
		if ( ! empty( Opt::$options[ $key ] ) ) {
			$opt_val = Opt::$options[ $key ];
			if ( $return_array && $opt_val ) {
				$opt_val = explode( ',', trim( $opt_val, ', ' ) );
			}

			return $opt_val;
		}

		if ( $default ) {
			return $default;
		}

		return false;
	}
}

if ( ! function_exists( 'listzen_get_social_html' ) ) {
	/**
	 * Get Social markup
	 *
	 * @return void
	 */

	function listzen_get_social_html( $brand_color_or_bg = '' ) {
		ob_start();
		foreach ( Fns::get_socials() as $id => $item ) {
			if ( empty( $item['url'] ) ) {
				continue;
			}

			$icon_class = '';
			if ( 'color' === $brand_color_or_bg ) {
				$icon_class = $id . '-color';
			} elseif ( 'bg' == $brand_color_or_bg ) {
				$icon_class = $id . '-bg';
			}
			$icon = $id === 'twitter' ? 'twitter-x' : $id;
			?>
            <a class="social-link <?php echo esc_attr( $icon_class ); ?>" target="_blank"
               href="<?php echo esc_url( $item['url'] ) ?>"
               aria-label="social link">
                <i class="rt-icon-<?php echo esc_attr( $icon ) ?>"></i>
            </a>
			<?php
		}
		$output = ob_get_clean();
		listzen_html( $output );
	}
}

if ( ! function_exists( 'listzen_site_logo' ) ) {
	/**
	 * Newfit Site Logo
	 */
	function listzen_site_logo( $logo_type = '', $custom_title = '' ) {
		$main_logo   = listzen_option( 'listzen_logo' );
		$logo_light  = listzen_option( 'listzen_logo_light' );
		$logo_light  = $logo_light ?: $main_logo;
		$logo_mobile = listzen_option( 'listzen_logo_mobile' );
		$site_logo   = Opt::$has_tr_header ? $logo_light : $main_logo;

		$mobile_logo = $logo_mobile ?? $site_logo;
		$logo_class  = "main-logo";
		$logo_class  .= ! empty( $logo_mobile ) ? ' has-mobile-logo' : ' no-mobile-logo';
		$site_title  = $custom_title ?: get_bloginfo( 'name', 'display' );

		if ( ! $site_logo && $mobile_logo ) {
			$site_logo = $mobile_logo;
		}
		if ( $logo_type ) {
			if ( $logo_type == 'mobile' ) {
				$site_logo  = $mobile_logo;
				$logo_light = $main_logo = null;
			} elseif ( $logo_type == 'light' ) {
				$site_logo   = $logo_light;
				$mobile_logo = $main_logo = null;
			} else {
				$site_logo   = $main_logo;
				$mobile_logo = $logo_light = null;
			}
		}

		?>

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
           class="<?php echo esc_attr( $logo_class ); ?>">
			<?php
			if ( ! empty( $site_logo ) ) {
				echo wp_get_attachment_image( $site_logo, 'full', null, [ 'class' => 'site-logo site-main-logo' ] );
				if ( ! empty( $mobile_logo ) ) {
					echo wp_get_attachment_image( $mobile_logo, 'full', null, [ 'class' => 'site-mobile-logo site-main-logo' ] );
				}
			} else {
				echo esc_html( $site_title );
			}
			?>
        </a>

		<?php
	}
}

if ( ! function_exists( 'listzen_scroll_top' ) ) {
	/**
	 * Back-to-top button
	 *
	 * @return void
	 */
	function listzen_scroll_top() {
		if ( listzen_option( 'listzen_back_to_top' ) ) {
			?>
            <a href="#" class="scrollToTop"><i class="rt-icon-angle-small-up"></i></a>
			<?php
		}
	}
}

if ( ! function_exists( 'listzen_entry_footer' ) ) {
	/**
	 * Listzen Entry Footer
	 *
	 * @return void
	 *
	 */
	function listzen_entry_footer( $label = '', $icon = null ) {
		if ( empty( $label ) ) {
			$label = listzen_readmore_text();
		}
		if ( null === $icon ) {
			$icon = is_rtl() ? 'rt-icon-arrow-left' : 'rt-icon-arrow-right';
		}
		?>
        <footer class="entry-footer listzen-button">
            <a class="btn post-read-more"
               href="<?php echo esc_url( get_permalink() ) ?>">
				<?php listzen_html( $label, 'plain' ) ?>

				<?php if ( $icon && listzen_option( 'listzen_blog_readmore_visibility' ) ) : ?>
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
				<?php endif; ?>
            </a>
        </footer>
		<?php
	}
}
if ( ! function_exists( 'listzen_single_entry_footer' ) ) {
	/**
	 * Listzen Single Entry Footer
	 *
	 * @return void
	 *
	 */
	function listzen_single_entry_footer() {
		if ( ( has_tag() && listzen_option( 'listzen_single_tag_visibility' ) ) || listzen_option( 'listzen_single_share_visibility' ) ) { ?>
            <footer class="entry-footer d-flex align-items-start justify-content-between">
				<?php
				$tags = PostMeta::posted_in( 'post_tag' );
				if ( listzen_option( 'listzen_single_tag_visibility' ) && $tags ) { ?>
                    <div class="post-tags">
						<?php if ( listzen_option( 'listzen_tags_label' ) ) {
							printf( "<span class='post-footer-label'>%s</span>", esc_html( listzen_option( 'listzen_tags_label' ) ) );
						} ?>

                        <div class="post-footer-meta">
							<?php Fns::print_html_all( $tags ); ?>
                        </div>
                    </div>
				<?php }
				if ( listzen_option( 'listzen_single_share_visibility' ) ) { ?>
                    <div class="post-share">
						<?php if ( listzen_option( 'listzen_social_label' ) ) {
							printf( "<span class='post-footer-label'>%s</span>", esc_html( listzen_option( 'listzen_social_label' ) ) );
						} ?>
						<?php listzen_post_share(); ?>
                    </div>
				<?php } ?>
            </footer>
			<?php
		}
	}
}

if ( ! function_exists( 'listzen_author_info' ) ) {
	/**
	 * Listzen Entry Profile
	 *
	 * @return void
	 *
	 */
	function listzen_author_info() {
		if ( ! listzen_option( 'listzen_single_profile_visibility' ) ) {
			return;
		}
		$author             = get_current_user_id();
		$author_description = get_user_meta( $author, 'description', true );
		$author_designation = get_user_meta( $author, 'listzen_designation', true );
		$_rtcl_social       = get_user_meta( $author, '_rtcl_social', true );
		//	$_rtcl_social = get_user_meta( $author);

		$author_fb     = $_rtcl_social['facebook'] ?? '';
		$author_tw     = $_rtcl_social['twitter'] ?? '';
		$author_you    = $_rtcl_social['youtube'] ?? '';
		$author_ins    = $_rtcl_social['instagram'] ?? '';
		$author_lk     = $_rtcl_social['linkedin'] ?? '';
		$author_pin    = $_rtcl_social['pinterest'] ?? '';
		$author_reddit = $_rtcl_social['reddit'] ?? '';
		$author_tiktok = $_rtcl_social['tiktok'] ?? '';

		?>

        <div class="author-info-wrapper">
            <div class="author-image">
				<?php echo get_avatar( $author, 150 ); ?>
            </div>
            <div class="author-description">
                <div class="profile-info">
					<?php if ( ! empty( $author_designation ) ) : ?>
                        <span class="profile-designation"><?php echo esc_html( $author_designation ); ?></span>
					<?php endif; ?>
                </div>

                <h3 class="author-name"><?php the_author_posts_link(); ?></h3>

				<?php if ( $author_description ) : ?>
                    <div class="author-bio"><?php echo esc_html( $author_description ); ?></div>
				<?php endif; ?>

                <ul class="author-socials">
					<?php if ( ! empty( $author_fb ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_fb ); ?>"><i class="rt-icon-facebook"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_tw ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_tw ); ?>"><i class="rt-icon-twitter-x"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_lk ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_lk ); ?>"><i class="rt-icon-linkedin"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_tiktok ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_tiktok ); ?>"><i class="rt-icon-tiktok"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_you ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_you ); ?>"><i class="rt-icon-youtube"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_ins ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_ins ); ?>"><i class="rt-icon-instagram"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_pin ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_pin ); ?>"><i class="rt-icon-pinterest"></i></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $author_reddit ) ) : ?>
                        <li><a href="<?php echo esc_url( $author_reddit ); ?>"><i class="rt-icon-reddit"></i></a></li>
					<?php endif; ?>
                </ul>
            </div>
        </div>

		<?php
	}
}

if ( ! function_exists( 'listzen_entry_content' ) ) {
	/**
	 * Entry Content
	 *
	 * @return void
	 */
	function listzen_entry_content( $limit = '', $more = '' ) {
		$length = $limit ?: listzen_option( 'listzen_excerpt_limit' );
		listzen_html( wp_trim_words( get_the_excerpt(), $length, $more ) );
	}
}


if ( ! function_exists( 'listzen_separate_meta' ) ) {
	/**
	 * Get separate meta
	 *
	 * @return string
	 */
	function listzen_separate_meta( $post_type = 'post', $includes = [ 'category', 'date' ] ) {
		?>
        <div class="post-separate-meta">
			<?php
			PostMeta::get_meta( $post_type, [
//			'with_list' => false,
				'with_icon' => false,
				'include'   => $includes,
			] );
			?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'listzen_sidebar' ) ) {
	/**
	 * Get Sidebar conditionally
	 *
	 * @param $sidebar_id
	 *
	 * @return false|void
	 */
	function listzen_sidebar( $sidebar_id ) {
		$sidebar_from_layout = Opt::$sidebar;

		if ( 'default' !== $sidebar_from_layout && is_active_sidebar( $sidebar_from_layout ) ) {
			$sidebar_id = $sidebar_from_layout;
		}
		if ( ! is_active_sidebar( $sidebar_id ) ) {
			return false;
		}

		if ( Fns::layout() == 'full-width' ) {
			return false;
		}

		$sidebar_cols = Fns::sidebar_columns();
		?>
        <aside id="sidebar" class="listzen-widget-area sidebar-sticky <?php echo esc_attr( $sidebar_cols ) ?>"
               role="complementary">
            <div class="sidebar-inner-wrapper">
				<?php dynamic_sidebar( $sidebar_id ); ?>
            </div>
        </aside><!-- #sidebar -->
		<?php
	}
}

if ( ! function_exists( 'listzen_post_class' ) ) {
	/**
	 * Get dynamic article classes
	 *
	 * @return string
	 */
	function listzen_post_class( $classes = '', $is_single = false, $check_blog_cols = true, $fixed_layout = '' ) {

		$blog_style                 = $fixed_layout ?: Fns::blog_layout();
		$blog_column                = Fns::blog_cols();
		$blog_above_meta_visibility = listzen_option( 'listzen_blog_above_meta_visibility' );
		$blog_meta_style            = listzen_option( 'listzen_blog_meta_style' );
		$blog_masonry               = listzen_option( 'listzen_blog_masonry', false );
		$single_meta_style          = listzen_option( 'listzen_single_meta_style' );
		$different_cat_color        = listzen_option( 'listzen_different_category_color' );
		$cat_class                  = $different_cat_color ? 'cat-different-color' : '';
		$common_class               = 'listzen-post-card ' . $cat_class;
		$is_grid_or_list            = strpos( $blog_style, "list" ) !== false ? 'blog-list' : 'blog-grid';

		//Set default column
		if ( 'default' === $blog_column ) {
			$is_fullwidth = 'col-12' === Fns::content_columns(); //check is the layout full width
			if ( 'blog-list' == $is_grid_or_list ) {
				$blog_column = 'col-12';
			} else {
				$blog_column = $is_fullwidth ? 'col-md-4' : 'col-md-6';
			}
		}

		if ( $is_single ) {
			$blog_style   = 's-blog-' . $blog_style;
			$meta_style   = $single_meta_style;
			$post_classes = Fns::class_list( [
				'clearfix single-article-content',
				$common_class,
				$meta_style,
				$blog_style
			] );
		} else {
			$common_class .= $blog_above_meta_visibility ? ' is-above-meta' : ' no-above-meta';
			$meta_style   = $blog_meta_style;
			$blog_style   = 'blog-' . $blog_style;
			$masonry      = $blog_masonry ? 'masonry-item' : '';
			$post_classes = Fns::class_list( [
				$common_class,
				$meta_style,
				$blog_style,
				$is_grid_or_list,
				$masonry,
				$check_blog_cols ? Fns::archive_column( $blog_column, $blog_style ) : '',
			] );
		}

		if ( $classes ) {
			$post_classes .= ' ' . $classes;
		}

		return $post_classes;
	}
}

if ( ! function_exists( 'listzen_cpt_class' ) ) {
	/**
	 * Get dynamic article classes
	 *
	 * @return string
	 */
	function listzen_cpt_class( $opt_prefix, $is_single = false, $check_blog_cols = true ) {
		$blog_style                 = listzen_option( "listzen_{$opt_prefix}_style" );
		$blog_column                = listzen_option( "listzen_{$opt_prefix}_column" );
		$blog_above_meta_visibility = listzen_option( "listzen_{$opt_prefix}_above_meta_visibility" );
		$blog_meta_style            = listzen_option( "listzen_{$opt_prefix}_meta_style" );
		$blog_masonry               = listzen_option( "listzen_{$opt_prefix}_masonry" );

		$common_class = "listzen-post-card listzen-{$opt_prefix}-item";
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$blog_style = isset( $_GET['style'] ) ? sanitize_text_field( wp_unslash( $_GET['style'] ) ) : $blog_style;

		if ( $is_single ) {
			$blog_style   = 's-blog-' . $blog_style;
			$post_classes = Fns::class_list( [ 'single-article-content', $common_class, $blog_style ] );
		} else {
			$common_class .= $blog_above_meta_visibility ? 'is-above-meta' : 'no-above-meta';
			$meta_style   = $blog_meta_style;
			$blog_style   = 'blog-' . $blog_style;
			$masonry      = $blog_masonry ? 'masonry-item' : '';
			$post_classes = Fns::class_list( [
				$common_class,
				$meta_style,
				$blog_style,
				$masonry,
				$check_blog_cols ? Fns::archive_column( $blog_column, $blog_style ) : '',
			] );
		}

		return $post_classes;
	}
}

if ( ! function_exists( 'listzen_single_separate_meta' ) ) {
	/**
	 * Get above title meta
	 *
	 * @return string
	 */
	function listzen_single_separate_meta( $post_type = 'post', $includes = [ 'category' ] ) {
		?>
        <div class="post-separate-meta">
		<?php
		PostMeta::get_meta( $post_type, [
			'with_list' => false,
			'with_icon' => false,
			'include'   => $includes,
		] );
		?>
        </div><?php
	}
}

if ( ! function_exists( 'listzen_single_entry_header' ) ) {
	/**
	 * Get above title meta
	 *
	 * @return string
	 */
	function listzen_single_entry_header() {
		//Single post meta
		if ( ! empty( Fns::single_meta_lists() ) && listzen_option( 'listzen_single_meta_visibility' ) ) {
			echo "<div class='single-post-meta'>";
			PostMeta::get_meta( 'post', [
				'include'   => Fns::single_meta_lists(),
				'with_icon' => listzen_option( "listzen_single_meta_icon_visibility" ),
			] );
			echo "</div>";
		}
		?>
        <header class="entry-header">
			<?php the_title( '<h1 class="entry-title default-max-width">', '</h1>' ); ?>
        </header>
		<?php
	}
}

if ( ! function_exists( 'listzen_get_avatar_url' ) ) :
	function listzen_get_avatar_url( $get_avatar ) {
		preg_match( "/src='(.*?)'/i", $get_avatar, $matches );

		return $matches[1];
	}
endif;


function listzen_comments_cbf( $comment, $args, $depth ) {
	// Get correct tag used for the comments
	if ( 'div' === $args['style'] ) {
		$tag       = 'div ';
		$add_below = 'comment';
	} else {
		$tag       = 'li ';
		$add_below = 'div-comment';
	} ?>

    <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">

	<?php
	// Switch between different comment types
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' : ?>
            <div class="pingback-entry"><span
                        class="pingback-heading"><?php esc_html_e( 'Pingback:', 'listzen' ); ?></span> <?php comment_author_link(); ?>
            </div>
			<?php
			break;
		default :

			if ( 'div' != $args['style'] ) { ?>
                <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
			<?php } ?>
            <div class="comment-author">
                <div class="vcard">
					<?php
					// Display avatar unless size is set to 0
					if ( $args['avatar_size'] != 0 ) {
						$avatar_size = ! empty( $args['avatar_size'] ) ? $args['avatar_size'] : 70; // set default avatar size
						echo get_avatar( $comment, $avatar_size );
					} ?>
                </div>
                <div class="author-info">
                    <cite class="fn"><?php echo get_comment_author_link(); ?></cite>
                    <div class="comment-meta commentmetadata">
                        <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>"><?php
							/* translators: %1$s is date and %2$s is time */
							printf(
								esc_html( '%1$s at %2$s', 'listzen' ),
								esc_html( get_comment_date() ),
								esc_html( get_comment_time() )
							); ?>
                        </a><?php
						edit_comment_link( __( 'Edit', 'listzen' ), '  ', '' ); ?>
                    </div><!-- .comment-meta -->
                    <div class="comment-details">

                        <div class="comment-text"><?php comment_text(); ?></div><!-- .comment-text -->
						<?php
						// Display comment moderation text
						if ( $comment->comment_approved == '0' ) { ?>
                            <em class="comment-awaiting-moderation"><?php esc_html__( 'Your comment is awaiting moderation.', 'listzen' ); ?></em>
                            <br/><?php
						} ?>

						<?php
						$icon = "<i class='rt-icon-arrow-small-right'></i>";
						// Display comment reply link
						comment_reply_link( array_merge( $args, [
							'add_below'  => $add_below,
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => __( 'Reply', 'listzen' ) . $icon,
						] ) );
						?>

                    </div><!-- .comment-details -->
                </div>

            </div><!-- .comment-author -->

			<?php
			if ( 'div' != $args['style'] ) { ?>
                </div>
			<?php }
			// IMPORTANT: Note that we do NOT close the opening tag, WordPress does this for us
			break;
	endswitch; // End comment_type check.
}

if ( ! function_exists( 'listzen_post_share' ) ) {
	/**
	 * Post Share
	 *
	 * @return void
	 */
	function listzen_post_share() {
		$url       = urlencode( get_permalink() );
		$title     = urlencode( get_the_title() );
		$meta_list = explode( ',', listzen_option( 'listzen_post_share' ) );
		// Your $defaults array
		$defaults       = [
			'facebook'  => [
				'url'  => "http://www.facebook.com/sharer.php?u=$url",
				'icon' => 'facebook',
			],
			'twitter'   => [
				'url'  => "https://twitter.com/intent/tweet?source=$url&text=$title:$url",
				'icon' => 'twitter',
			],
			'linkedin'  => [
				'url'  => "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title",
				'icon' => 'linkedin',
			],
			'pinterest' => [
				'url'  => "http://pinterest.com/pin/create/button/?url=$url&description=$title",
				'icon' => 'pinterest',
			],
			'whatsapp'  => [
				'url'  => 'https://api.whatsapp.com/send?text=' . $title . ' – ' . $url,
				'icon' => 'whatsapp',
			],
			'youtube'   => [
				'url'  => "https://www.youtube.com?text='. $title .'&amp;url='. $url",
				'icon' => 'youtube',
			],
		];
		$category_index = array_intersect_key( $defaults, array_flip( $meta_list ) );
		$sharers        = apply_filters( 'listzen_social_sharing_icons', $category_index );
		?>

        <ul class="social-share-list">
			<?php foreach ( $sharers as $key => $sharer ): ?>
                <li class="social-<?php echo esc_attr( $key ); ?>">
                    <a href="<?php echo esc_url( $sharer['url'] ); ?>" target="_blank">
                        <i class="rt-icon-<?php echo esc_attr( $sharer['icon'] ); ?>"></i>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php
	}
}

if ( ! function_exists( 'listzen_get_popular_taxonomy' ) ) {
	function listzen_get_popular_taxonomy( $taxonomy = 'category', $number = 10 ) {
		$popular_terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'hide_empty' => true,
			'number'     => $number,
		] );

		if ( ! is_wp_error( $popular_terms ) && ! empty( $popular_terms ) ) {
			foreach ( $popular_terms as $term ) {
				printf(
					'<li><a href="%s">%s (%d)</a></li>',
					esc_url( get_term_link( $term ) ),
					esc_html( $term->name ),
					esc_html( $term->count )
				);
			}
		}
	}
}

if ( ! function_exists( 'listzen_contact_info' ) ) {
	/**
	 * Get Contact Info from Customize
	 *
	 * @param $address
	 * @param $phone
	 * @param $email
	 *
	 * @return void
	 */
	function listzen_contact_info( $context = '' ) {
		$has_aboutus = $has_address = $has_phone = $has_email = true;

		if ( 'topbar' == $context ) {
			$has_aboutus = false;
			$has_address = listzen_option( 'listzen_topbar_address' );
			$has_phone   = listzen_option( 'listzen_topbar_phone' );
			$has_email   = listzen_option( 'listzen_topbar_email' );
		} elseif ( 'offcanvas' == $context ) {
			$has_aboutus = listzen_option( 'listzen_offcavas_about_us' );
			$has_phone   = listzen_option( 'listzen_offcavas_phone' );
			$has_email   = listzen_option( 'listzen_offcavas_email' );
			$has_address = listzen_option( 'listzen_offcavas_address' );
		}

		$aboutus = listzen_option( 'listzen_about_us' );
		$address = listzen_option( 'listzen_contact_address' );
		$phone   = listzen_option( 'listzen_phone' );
		$email   = listzen_option( 'listzen_email' );

		if ( $has_aboutus && $aboutus ) {
			echo "<div class='about-us'>";
			listzen_html( $aboutus );
			echo "</div>";
		}

		if ( $has_address && $address ) { ?>
            <div class="address">
                <i class="rt-icon-marker"></i>
                <span><?php listzen_html( $address ); ?></span>
            </div>
		<?php }

		if ( $has_phone && $phone ) { ?>
            <div class="phone">
                <i class="rt-icon-phone-flip"></i>
                <a href="tel:<?php echo esc_attr( $phone ); ?>">
					<?php echo esc_html( $phone ) ?>
                </a>
            </div>
		<?php }

		if ( $has_email && $email ) { ?>
            <div class="email">
                <i class="rt-icon-envelope"></i>
                <a href="mailto:<?php echo esc_attr( $email ); ?>">
					<?php echo esc_html( $email ); ?>
                </a>
            </div>
		<?php }
	}
}


if ( ! function_exists( 'listzen_preloader' ) ) {
	function listzen_preloader() {
		if ( listzen_option( 'listzen_preloader' ) ) {
			if ( ! empty( listzen_option( 'listzen_preloader_logo' ) ) ) { ?>
                <div id="preloader">
					<?php echo wp_get_attachment_image( listzen_option( 'listzen_preloader_logo' ), 'full', true ); ?>
                </div>
			<?php } else { ?>
                <div id="preloader" class="loader">
                    <div class="cssload-loader">
                        <div class="cssload-inner cssload-one"></div>
                        <div class="cssload-inner cssload-two"></div>
                        <div class="cssload-inner cssload-three"></div>
                    </div>
                </div>
			<?php }
		}
	}
}

/**
 * Print header content depending on builder
 */
if ( ! function_exists( 'listzen_header' ) ) {
	function listzen_header() {

		if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
			return;
		}

		?>
        <header id="masthead" class="site-header" role="banner">
			<?php
			get_template_part( 'template-parts/header/topbar', Opt::$topbar_style );
			get_template_part( 'template-parts/header/header', Opt::$header_style );
			?>
        </header><!-- #masthead -->

		<?php get_template_part( 'template-parts/header/nav-drawer', Opt::$nav_drawer_style ); ?>
		<?php get_template_part( 'template-parts/header/header', 'search' );
	}
}

/**
 * Print footer content depending on builder
 */
if ( ! function_exists( 'listzen_footer' ) ) {
	function listzen_footer() {
		if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
			return;
		}

		if ( listzen_option( 'listzen_footer_display' ) ) {

			$classes = Fns::class_list( [
				'site-footer',
				Opt::$footer_schema,
			] );
			?>
            <footer class="<?php echo esc_attr( $classes ); ?>" role="contentinfo">
				<?php get_template_part( 'template-parts/footer/footer', Opt::$footer_style ); ?>
            </footer><!-- #colophon -->
		<?php }
	}
}