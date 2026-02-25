<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace Listzen\Framework\Customize\Controls;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Sanitization Class
 */
class Sanitization {

	/**
	 * Sanitize Text
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public static function sanitize_text( $input ) {

		if ( strpos( $input, ',' ) !== false ) {
			$input = explode( ',', $input );
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ $key ] = sanitize_text_field( $value );
			}
			$input = implode( ',', $input );
		} else {
			$input = sanitize_text_field( $input );
		}

		return $input;
	}

	/**
	 * Sanitize Radio
	 *
	 * @param $input
	 * @param $setting
	 *
	 * @return mixed
	 */
	public static function sanitize_radio( $input, $setting ) {
		// get the list of possible radio box or select options
		$choices = $setting->manager->get_control( $setting->id )->choices;

		if ( array_key_exists( $input, $choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}

	/**
	 * Sanitize Swith
	 *
	 * @param $input
	 *
	 * @return int
	 */
	public static function sanitize_switch( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Sanitize Google Fonts
	 *
	 * @param $input
	 *
	 * @return false|string
	 */

	public static function sanitize_gfonts( $input ) {
		$val = json_decode( $input, true );
		if ( is_array( $val ) ) {
			foreach ( $val as $key => $value ) {
				$val[ $key ] = sanitize_text_field( $value );
			}
			$input = json_encode( $val );
		} else {
			$input = json_encode( sanitize_text_field( $val ) );
		}

		return $input;
	}

	/**
	 * Sanitize Range
	 *
	 * @param $input
	 * @param $setting
	 *
	 * @return mixed
	 */
	public static function sanitize_range( $input, $setting ) {
		$attrs = $setting->manager->get_control( $setting->id )->input_attrs;

		$min  = ( $attrs['min'] ?? $input );
		$max  = ( $attrs['max'] ?? $input );
		$step = ( $attrs['step'] ?? 1 );

		$number = floor( $input / $attrs['step'] ) * $attrs['step'];

		return self::in_range( $number, $min, $max );
	}

	/**
	 * Check Range
	 *
	 * @param $input
	 * @param $min
	 * @param $max
	 *
	 * @return mixed
	 */
	public static function in_range( $input, $min, $max ) {
		if ( $input < $min ) {
			$input = $min;
		}
		if ( $input > $max ) {
			$input = $max;
		}

		return $input;
	}

	/**
	 * Sanitize URL
	 *
	 * @param $input
	 *
	 * @return string
	 */

	public static function sanitize_url( $input ) {
		if ( strpos( $input, ',' ) !== false ) {
			$input = explode( ',', $input );
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ $key ] = esc_url_raw( $value );
			}
			$input = implode( ',', $input );
		} else {
			$input = esc_url_raw( $input );
		}

		return $input;
	}

	/**
	 * Sanitize Integer
	 *
	 * @param $input
	 *
	 * @return int
	 */
	public static function sanitize_integer( $input ) {
		return (int) $input;
	}

	/**
	 * Sanitize Array
	 *
	 * @param $input
	 *
	 * @return mixed|string
	 */
	public static function sanitize_array( $input ) {
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ $key ] = sanitize_text_field( $value );
			}
		} else {
			$input = '';
		}

		return $input;
	}

	/**
	 * Sanitize Date Time
	 *
	 * @param $input
	 * @param $setting
	 *
	 * @return string
	 */
	public static function sanitize_date_time( $input, $setting ) {
		$datetimeformat = 'Y-m-d';
		if ( $setting->manager->get_control( $setting->id )->include_time ) {
			$datetimeformat = 'Y-m-d H:i:s';
		}
		$date = \DateTime::createFromFormat( $datetimeformat, $input );
		if ( $date === false ) {
			$date = \DateTime::createFromFormat( $datetimeformat, $setting->default );
		}

		return $date->format( $datetimeformat );
	}

	/**
	 * Sanitize Input File
	 *
	 * @param $file
	 * @param $setting
	 *
	 * @return mixed
	 */
	public static function sanitize_file( $file, $setting ) {
		// allowed file types
		$mimes = [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
		];

		// check file type from file name
		$file_ext = wp_check_filetype( $file, $mimes );

		// if file has a valid mime type return it, otherwise return default
		return ( $file_ext['ext'] ? $file : $setting->default );
	}
}
