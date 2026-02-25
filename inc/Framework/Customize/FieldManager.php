<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace Listzen\Framework\Customize;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Listzen\Framework\Customize\Controls\AlfaColorControl;
use Listzen\Framework\Customize\Controls\HeadingControl;
use Listzen\Framework\Customize\Controls\Select2Control;
use Listzen\Framework\Customize\Controls\SliderControl;
use Listzen\Framework\Customize\Controls\DropdownPostsControl;
use Listzen\Framework\Customize\Controls\GalleryControl;
use Listzen\Framework\Customize\Controls\ImageRadioControl;
use Listzen\Framework\Customize\Controls\TextRadioControl;
use Listzen\Framework\Customize\Controls\RepeaterControl;
use Listzen\Framework\Customize\Controls\SeparatorControl;
use Listzen\Framework\Customize\Controls\SwitchControl;
use Listzen\Framework\Customize\Controls\NoticeControl;
use Listzen\Framework\Customize\Controls\BackgroundAttrControl;
use Listzen\Framework\Customize\Controls\TinyMCE_Control;
use Listzen\Framework\Customize\Controls\GoogleFontsControls;
use Listzen\Framework\Customize\Controls\Sanitization;

use WP_Customize_Control;
use WP_Customize_Date_Time_Control;
use WP_Customize_Media_Control;
use Listzen\Traits\SingletonTraits;

class FieldManager {
	use SingletonTraits;

	public static $conditions = [];

	public function __construct() {
		// add_action( 'customize_preview_init', [ $this, 'listzen_customizer_live_preview' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customizer_switch_select_control_script' ] );
	}

	public static function add_customizer_controls( $wp_customize, $fields, $fields_group ) {
		self::add_customizer_fields( $wp_customize, $fields );
		self::add_customizer_fields_group( $wp_customize, $fields_group );
	}


	public static function customizer_switch_select_control_script() {
		wp_enqueue_script( 'listzen-customizer-condition', listzen_get_assets( 'customize/js/customizer-condition.js' ), [ 'jquery', 'customize-controls' ], '1.0', true );
		wp_localize_script( 'listzen-customizer-condition', 'listzenCustomizeObj', self::$conditions );
	}

	public static function add_customizer_fields( $wp_customize, $fields ) {
		if ( empty( $fields ) ) {
			return;
		}
		foreach ( $fields as $id => $field ) {
			$field['id'] = $id;
			if ( method_exists( __CLASS__, $field['type'] ) ) {
				self::{$field['type']}( $wp_customize, $field );
			}
		}
	}

	/**
	 * Add Customizer Fields group
	 *
	 * @param $wp_customize
	 * @param $fields_group
	 *
	 * @return void
	 */
	public static function add_customizer_fields_group( $wp_customize, $fields_group ) {
		if ( empty( $fields_group ) ) {
			return;
		}
		foreach ( $fields_group as $section_id => $fields ) {
			foreach ( $fields as $id => $field ) {
				// Check condition
				if ( ! empty( $field['condition'] ) ) {
					self::$conditions[ $id ] = $field['condition'];
				}
				$field['section'] = $section_id;
				$field['id']      = $id;
				// Field generate
				if ( method_exists( __CLASS__, $field['type'] ) ) {
					self::{$field['type']}( $wp_customize, $field );

				}
				// Add Edit Link
				if ( ! empty( $field['edit-link'] ) && isset( $wp_customize->selective_refresh ) ) {
					self::edit_link( $wp_customize, $field );
				}
			}
		}
	}

	/**
	 * Check condition and return actual array
	 *
	 * @param $control_args
	 * @param $field
	 *
	 * @return mixed
	 */
	public static function cehck_condition( $control_args, $field ): mixed {

		if ( ! empty( $field['condition'] ) ) {
			$control_args['condition'] = $field['condition'];
		}

		return $control_args;
	}

	/**
	 * Heading control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function heading( $wp_customize, $field ) {
		$control_args = [
			'label'   => $field['label'] ?? '',
			'section' => $field['section'] ?? '',
		];
		if ( ! empty( $field['reset'] ) ) {
			$control_args['reset'] = '1';
		}
		$control_args = self::cehck_condition( $control_args, $field );
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'esc_html',
		] );
		$wp_customize->add_control( new HeadingControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Heading control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function simple_notice( $wp_customize, $field ) {
		$control_args = [
			'label'   => $field['label'] ?? '',
			'section' => $field['section'] ?? '',
		];

		$control_args = self::cehck_condition( $control_args, $field );
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'esc_html',
		] );
		$wp_customize->add_control( new NoticeControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Text control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function text( $wp_customize, $field ) {

		$control_args  = [
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'text',
			'active_callback' => $field['callback'] ?? '',
		];

		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	public static function url( $wp_customize, $field ) {
		$control_args  = [
			'label'           => $field['label'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'url',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Number control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function number( $wp_customize, $field ) {
		$control_args  = [
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'number',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Text area Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function textarea( $wp_customize, $field ) {
		$control_args  = [
			'type'            => 'textarea',
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Select Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function select( $wp_customize, $field ) {
		$control_args  = [
			'type'        => 'select',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
			'default'           => $field['default'] ?? '',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Image Select control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function image_select( $wp_customize, $field ) {
		$control_args  = [
			'type'        => 'image_select',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_radio' ],
		] );
		$wp_customize->add_control( new ImageRadioControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Image
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function image( $wp_customize, $field ) {
		$button_label  = $field['button_label'] ?? __( 'Image', 'listzen' );
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'mime_type'     => $field['mime_type'] ?? 'image',
			'button_labels' => [
				'select'       => esc_html__( 'Select', 'listzen' ) . ' ' . $button_label,
				'change'       => esc_html__( 'Change', 'listzen' ) . ' ' . $button_label,
				'default'      => esc_html__( 'Default', 'listzen' ) . ' ' . $button_label,
				'remove'       => esc_html__( 'Remove', 'listzen' ) . ' ' . $button_label,
				'placeholder'  => esc_html__( 'No file selected', 'listzen' ),
				'frame_title'  => esc_html__( 'Select', 'listzen' ) . ' ' . $button_label,
				'frame_button' => esc_html__( 'Choose', 'listzen' ) . ' ' . $button_label,
			],
		];

		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Checkbox Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function checkbox( $wp_customize, $field ) {
		$control_args  = [
			'type'        => 'checkbox',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Checkbox Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function text_radio_button( $wp_customize, $field ) {
		$control_args  = [
			'type'        => 'checkbox',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new TextRadioControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Radio Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function radio( $wp_customize, $field ) {
		$control_args  = [
			'type'        => 'radio',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Pages Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function pages( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'type'        => 'dropdown-pages',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Color Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function color( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'type'        => 'color',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		] );
		$wp_customize->add_control( $field['id'], $control_args );
	}


	/**
	 * alfa_color Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function alfa_color( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new AlfaColorControl( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * Datetime Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function datetime( $wp_customize, $field ) {
		$control_args  = [
			'label'              => $field['label'] ?? '',
			'description'        => $field['description'] ?? '',
			'section'            => $field['section'] ?? '',
			'include_time'       => false,
			'allow_past_date'    => true,
			'twelve_hour_format' => true,
			'min_year'           => '2016',
			'max_year'           => '2025',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function select2( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'placeholder' => $field['placeholder'] ?? __( 'Please select...', 'listzen' ),
				'multiselect' => $field['multiselect'] ?? false,
			],
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new Select2Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * slider_control Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function slider_control( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'min' => $field['min'] ?? '0',
				'max' => $field['max'] ?? '100',
			],
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_range' ],
		] );
		$wp_customize->add_control( new SliderControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function dropdown_posts( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'placeholder' => $field['placeholder'] ?? __( 'Select Post', 'listzen' ),
				'multiselect' => $field['multiselect'] ?? false,
			],
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new DropdownPostsControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function switch( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'class'       => $field['class'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_switch' ],
		] );
		$wp_customize->add_control( new SwitchControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * tinymce Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function tinymce( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'wp_kses_post',
		] );
		$wp_customize->add_control( new TinyMCE_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * separator Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function separator( $wp_customize, $field ) {
		$control_args  = [
			'section' => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_switch' ],
		] );
		$wp_customize->add_control( new SeparatorControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Repeater Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function repeater( $wp_customize, $field ) {
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'use_as'        => $field['use_as'] ?? 'repeater',
			'button_labels' => [
				'add' => __( 'Add Item', 'listzen' ),
			],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new RepeaterControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Gallery Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function gallery( $wp_customize, $field ) {
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'button_labels' => [
				'add' => __( 'Add Gallery', 'listzen' ),
			],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_switch' ],
		] );
		$wp_customize->add_control( new GalleryControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Typography Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function typography( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'font_count' => 'all',
				'orderby'    => 'popular',
			],
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_gfonts' ],
		] );
		$wp_customize->add_control( new GoogleFontsControls( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * Background attribute
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function bg_attribute( $wp_customize, $field ) {
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => [ Sanitization::class, 'sanitize_text' ],
		] );
		$wp_customize->add_control( new BackgroundAttrControl( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Customize Edit Link
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function edit_link( $wp_customize, $field ) {
		$wp_customize->selective_refresh->add_partial(
			$field['id'],
			[
				'selector'            => $field['edit-link'],
				'container_inclusive' => true,
				'render_callback'     => '__return_false',
				'fallback_refresh'    => false,
			]
		);
	}
}
