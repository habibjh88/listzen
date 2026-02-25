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
$isSliderEnable       = Functions::is_gallery_slider_enabled();
$gallery_size         = 'rtcl-gallery';
$gallery_style        = CLFns::listing_gallery_style();
$slider_cols          = absint( CLFns::listing_slider_cols() );
global $has_rtcl_gallery_clomun;
$dataOptions             = [];
$has_rtcl_gallery_clomun = '';

if ( $slider_cols > 1 ) {

	$slider_cols_lg = $slider_cols <= 3 ? $slider_cols : 3;
	$dataOptions    = [
		'slidesPerView' => $slider_cols,
		'spaceBetween'  => 10,
		'allowTouchMove' => false,
		'watchSlidesProgress'=> true,
		'breakpoints'   => [
			100  => [
				'slidesPerView' => 1,
			],
			600  => [
				'slidesPerView' => 2,
			],
			900  => [
				'slidesPerView' => $slider_cols_lg,
			],
			1300 => [
				'slidesPerView' => $slider_cols,
			],
		],
	];

	$has_rtcl_gallery_clomun = 'rtcl-has-column';
}

if ( $total_gallery_item ) :
	$dataOptions['threshold'] = 15;
	?>
    <div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper mb-4" data-options="<?php echo esc_attr( wp_json_encode( $dataOptions ) ); ?>">
        <!-- Slider -->
        <div class="rtcl-main-slider-wrapper">
            <div class="rtcl-main-slider-inner">
                <div class="rtcl-slider<?php echo esc_attr( $isSliderEnable ? '' : ' off' ) ?>">
                    <div class="swiper-wrapper">
						<?php
						//Listing Video
						if ( ! CLFns::is_separate_video() ) {
							CLFns::listing_video( $videos, $images, $listing );
						}

						//Listing Image Gallery
						if ( $total_gallery_image ) {
							foreach ( $images as $index => $image ) :
								$image_attributes = wp_get_attachment_image_src( $image->ID, $gallery_size );
								$image_meta = wp_get_attachment_metadata( $image->ID );
								$image_full = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                                <div class="swiper-slide rtcl-slider-item <?php echo esc_attr( $has_rtcl_gallery_clomun ) ?>">
                                    <img src="<?php echo esc_html( $image_attributes[0] ); ?>"
                                         data-attachment-id="<?php echo esc_attr( $image->ID ) ?>"
                                         data-src="<?php echo esc_attr( $image_full[0] ) ?>"
                                         data-large_image="<?php echo esc_attr( $image_full[0] ) ?>"
                                         data-large_image_width="<?php echo esc_attr( $image_full[1] ) ?>"
                                         data-large_image_height="<?php echo esc_attr( $image_full[2] ) ?>"
                                         alt="<?php echo esc_attr( $listing->get_the_title() ); ?>"
                                         data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                         class="rtcl-responsive-img"/>
                                </div>
							<?php endforeach;
						} ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
		<?php if ( $isSliderEnable && CLFns::is_slider_gallery_enabled() && $total_gallery_item > 1 ): ?>
            <!-- Slider nav -->
            <div class="rtcl-slider-nav" data-slider-options="<?php echo esc_attr( wp_json_encode( [ 'spaceBetween' => 10, 'allowTouchMove' => false ] ) ); ?>">
                <div class="swiper-wrapper">
					<?php
					if ( $total_gallery_videos && ! CLFns::is_separate_video() ) {
						foreach ( $videos as $index => $video_url ) {
							$video_image = Functions::get_embed_video_thumbnail_url( $video_url, 'mqdefault' );

							if ( ! Fns::is_image_url_valid( $video_image ) || stripos( $video_image, 'placeholder.jpg' ) !== false ) {
								$featured_image = get_post_thumbnail_id( $listing->get_id() );
								$video_image    = wp_get_attachment_image_url( $featured_image, $gallery_size );
							}
							?>
                            <div class="swiper-slide rtcl-slider-thumb-item rtcl-slider-video-thumb">
                                <img src="<?php echo esc_url( $video_image ) ?>"
                                     class="rtcl-gallery-thumbnail"
                                     alt="<?php echo esc_attr__( 'Preview Thumbnail', 'listzen' ); ?>"/>
                            </div>
							<?php
						}
					}
					if ( $total_gallery_image ) {
						foreach ( $images as $index => $image ) : ?>
                            <div class="swiper-slide rtcl-slider-thumb-item">
								<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery-thumbnail', false, [ 'alt' => esc_attr( $listing->get_the_title() ) ] ); ?>
                            </div>
						<?php endforeach;
					} ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
		<?php endif; ?>
    </div>
<?php endif;
