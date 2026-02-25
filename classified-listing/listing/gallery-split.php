<?php
/**
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 * @var array[] $images
 * @var array[] $videos
 * @var Listing $listing
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Listzen\Helpers\Fns;
use Listzen\Helpers\CLFns;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! $listing ) {
	global $listing;
}

if ( ! $listing ) {
	return;
}

$images               = CLFns::get_listing_images( $images, $listing );
$total_gallery_image  = count( $images );
$total_gallery_videos = count( $videos );
$total_gallery_item   = $total_gallery_image + $total_gallery_videos;
$gallery_size         = 'rtcl-gallery';
$gallery_style        = CLFns::listing_gallery_style();
$gallery_cols         = absint( CLFns::listing_slider_cols() );
if ( $total_gallery_item ) :
	?>
    <div class="rtcl-gallery-wrapper mb-4">
        <!-- Slider -->
        <div class="rtcl-main-slider-wrapper">
            <div class="rtcl-main-slider-inner">

                <div class="rtcl-gallery-inner">

					<?php
					//Listing Video
					if ( ! CLFns::is_separate_video() ) {
						CLFns::listing_video( $videos, $images, $listing );
					}

					//Listing Image Gallery
					if ( $total_gallery_image ) {
						$Itemnumber     = 0;
						foreach ( $images as $index => $image ) :
							$image_attributes = wp_get_attachment_image_src( $image->ID, $gallery_size );
							$image_meta = wp_get_attachment_metadata( $image->ID );
							$image_full = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                            <div class="rtcl-gallery-item swiper-slide rtcl-slider-item">
                                <img src="<?php echo esc_html( $image_attributes[0] ); ?>"
                                     data-attachment-id="<?php echo esc_attr( $image->ID ) ?>"
                                     data-src="<?php echo esc_attr( $image_full[0] ) ?>"
                                     data-large_image="<?php echo esc_attr( $image_full[0] ) ?>"
                                     data-large_image_width="<?php echo esc_attr( $image_full[1] ) ?>"
                                     data-large_image_height="<?php echo esc_attr( $image_full[2] ) ?>"
                                     alt="<?php echo esc_attr( $listing->get_the_title() ); ?>"
                                     data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                     class="rtcl-responsive-img"/>
                                <a href="#" class="rtcl-listing-gallery__trigger" data-index="<?php echo esc_attr( $Itemnumber ) ?>"><i class="rt-icon-gallery-alt"></i> </a>
                            </div>
							<?php
							$Itemnumber ++;
						endforeach;
					} ?>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>


<!-- Root element of PhotoSwipe -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation arrows -->
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>