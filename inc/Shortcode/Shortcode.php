<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace Listzen\Shortcode;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Listzen\Traits\SingletonTraits;

/**
 * ThemeJetpack Class
 */
class Shortcode {

    use SingletonTraits;

    public static $shortcode_list = [
            'header'    => [
                    'tag'      => 'listzen_listing_header',
                    'callback' => 'render_listing_header',
            ],
            'sidebar'   => [
                    'tag'      => 'listzen_listing_sidebar',
                    'callback' => 'render_listing_sidebar',
            ],
            'video'     => [
                    'tag'      => 'listzen_listing_video',
                    'callback' => 'render_listing_video',
            ],
            'faqs'      => [
                    'tag'      => 'listzen_listing_faqs',
                    'callback' => 'render_listing_faqs',
            ],
            'amenities' => [
                    'tag'      => 'listzen_listing_amenities',
                    'callback' => 'render_listing_amenities',
            ],
            'foodmenu'  => [
                    'tag'      => 'listzen_listing_foodmenu',
                    'callback' => 'render_listing_foodmenu',
            ],

            'services' => [
                    'tag'      => 'listzen_listing_services',
                    'callback' => 'render_listing_services',
            ],
    ];

    /**
     * register default hooks and actions for WordPress
     *
     * @return
     */
    public function __construct() {
        add_filter(
                'rtcl/fb/single_layout/fields',
                function ( $fields ) {
                    $shortcode_hints = '';
                    foreach ( self::$shortcode_list as $label => $shortcode ) {
                        $shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$shortcode['tag']}]";
                    }

                    $fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

                    return $fields;
                }
        );

        foreach ( self::$shortcode_list as $shortcode ) {
            add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
        }
    }

    public function render_listing_header() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            ?>
            <div class="rtcl-listing-gallery-top-wrapper">
                <?php
                do_action( 'rtcl_single_listing_title' );
                do_action( 'rtcl_single_listing_gallery' );
                ?>
            </div>
            <?php
            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing sidebar
     *
     * @return false|string
     */
    public function render_listing_sidebar() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            echo '<div id="rtcl-sidebar" class="rtcl-sidebar-wrapper"><div class="rtcl-sidebar-inner">';
            do_action( 'rtcl_single_listing_sidebar' );
            echo '</div></div>';

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing video
     *
     * @return false|string
     */
    public function render_listing_video() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            do_action( 'rtcl_single_listing_separate_video', $listing );

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing faqs
     *
     * @return false|string
     */
    public function render_listing_faqs() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            do_action( 'rtcl_single_listing_faqs', $listing );

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing amenities
     *
     * @return false|string
     */
    public function render_listing_amenities() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            do_action( 'rtcl_single_listing_amenities', $listing );

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing foodmenu
     *
     * @return false|string
     */
    public function render_listing_foodmenu() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            do_action( 'rtcl_single_listing_food_menu', $listing );

            return ob_get_clean();
        }

        return '';
    }

    /**
     * Listing services
     *
     * @return false|string
     */
    public function render_listing_services() {
        global $listing;

        if ( ! $listing ) {
            return '';
        }

        if ( is_singular( 'rtcl_listing' ) ) {
            ob_start();
            do_action( 'rtcl_single_listing_services', $listing );

            return ob_get_clean();
        }

        return '';
    }
}
