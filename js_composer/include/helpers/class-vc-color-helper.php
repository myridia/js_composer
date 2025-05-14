<?php
/**
 * Color helper manager.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Author: Arlo Carreon <http://arlocarreon.com>
 * Info: http://mexitek.github.io/phpColors/
 * License: http://arlo.mit-license.org/
 *
 * @modified by js_composer
 * @since 4.8
 */
class Vc_Color_Helper {
	/**
	 * A color utility that helps manipulate HEX colors.
	 *
	 * @var string
	 */
	private $hex;
	/**
	 * A color utility that helps manipulate HSL colors.
	 *
	 * @var string
	 */
	private $hsl;
	/**
	 * A color utility that helps manipulate RGB colors.
	 *
	 * @var string
	 */
	private $rgb;

	/**
	 * Auto darkens/lightens by 10% for sexily-subtle gradients.
	 * Set this to FALSE adjust automatic shade to be between given color
	 * and black (for darken) or white (for lighten)
	 */
	const DEFAULT_ADJUST = 10;

	/**
	 * Instantiates the class with a HEX value
	 *
	 * @param string $hex
	 *
	 * @throws Exception "Bad color format".
	 */
	public function __construct( $hex ) {
		// Strip # sign is present.
		$color = str_replace( '#', '', $hex );

		// Make sure it's 6 digits.
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		} elseif ( strlen( $color ) !== 6 ) {
			throw new Exception( 'HEX color needs to be 6 or 3 digits long' );
		}

		$this->hsl = self::hexToHsl( $color );
		$this->hex = $color;
		$this->rgb = self::hexToRgb( $color );
	}

	/**
	 * Clamps a given value within a specified range.
	 *
	 * @param mixed $val
	 * @param int $max
	 * @return mixed
	 */
	public static function clamp( $val, $max = 1 ) {
		return min( max( $val, 0 ), $max );
	}

	// ====================
	// = Public Interface =
	// ====================

	/**
	 * Given a HEX string returns a HSL array equivalent.
	 *
	 * @param string $color
	 *
	 * @return array HSL associative array
	 * @throws \Exception
	 */
	public static function hexToHsl( $color ) {

		// Sanity check.
		$color = self::check_hex_private( $color );

		// Convert HEX to DEC.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		$hsl = [];

		$var_r = ( $r / 255.0 );
		$var_g = ( $g / 255.0 );
		$var_b = ( $b / 255.0 );

		$var_min = min( $var_r, $var_g, $var_b );
		$var_max = max( $var_r, $var_g, $var_b );
		$del_max = floatval( $var_max - $var_min );

		$l = ( $var_max + $var_min ) / 2.0;

		$h = 0.0;
		$s = 0.0;

		if ( $del_max > 0 ) {
			if ( $l < 0.5 ) {
				$s = $del_max / ( $var_max + $var_min );
			} else {
				$s = $del_max / ( 2 - $var_max - $var_min );
			}

			switch ( $var_max ) {
				case $var_r:
					$h = ( $var_g - $var_b ) / $del_max + ( $var_g < $var_b ? 6 : 0 );
					break;
				case $var_g:
					$h = ( $var_b - $var_r ) / $del_max + 2;
					break;
				case $var_b:
					$h = ( $var_r - $var_g ) / $del_max + 4;
					break;
			}

			$h /= 6;
		}

		$hsl['H'] = ( $h * 360.0 );
		$hsl['S'] = $s;
		$hsl['L'] = $l;

		return $hsl;
	}

	/**
	 *  Given a HSL associative array returns the equivalent HEX string
	 *
	 * @param array $hsl
	 *
	 * @return string HEX string
	 * @throws Exception "Bad HSL Array".
	 */
	public static function hslToHex( $hsl = [] ) {
		// Make sure it's HSL.
		if ( empty( $hsl ) || ! isset( $hsl['H'] ) || ! isset( $hsl['S'] ) || ! isset( $hsl['L'] ) ) {
			throw new Exception( 'Param was not an HSL array' );
		}

		list( $h, $s, $l ) = [
			fmod( $hsl['H'], 360 ) / 360.0,
			$hsl['S'],
			$hsl['L'],
		];

		if ( ! $s ) {
			$r = $l * 255.0;
			$g = $l * 255.0;
			$b = $l * 255.0;
		} else {

			if ( $l < 0.5 ) {
				$var_2 = $l * ( 1.0 + $s );
			} else {
				$var_2 = ( $l + $s ) - ( $s * $l );
			}

			$var_1 = 2.0 * $l - $var_2;

			$r = self::clamp( round( 255.0 * self::huetorgb_private( $var_1, $var_2, $h + ( 1 / 3 ) ) ), 255 );
			$g = self::clamp( round( 255.0 * self::huetorgb_private( $var_1, $var_2, $h ) ), 255 );
			$b = self::clamp( round( 255.0 * self::huetorgb_private( $var_1, $var_2, $h - ( 1 / 3 ) ) ), 255 );

		}

		// Convert to hex.
		$r = dechex( (int) $r );
		$g = dechex( (int) $g );
		$b = dechex( (int) $b );

		// Make sure we get 2 digits for decimals.
		$r = ( strlen( '' . $r ) === 1 ) ? '0' . $r : $r;
		$g = ( strlen( '' . $g ) === 1 ) ? '0' . $g : $g;
		$b = ( strlen( '' . $b ) === 1 ) ? '0' . $b : $b;

		return $r . $g . $b;
	}

	/**
	 * Given a HEX string returns a RGB array equivalent.
	 *
	 * @param string $color
	 *
	 * @return array RGB associative array
	 * @throws \Exception
	 */
	public static function hexToRgb( $color ) {

		// Sanity check.
		$color = self::check_hex_private( $color );

		// Convert HEX to DEC.
		$rgb['R'] = hexdec( $color[0] . $color[1] );
		$rgb['G'] = hexdec( $color[2] . $color[3] );
		$rgb['B'] = hexdec( $color[4] . $color[5] );

		return $rgb;
	}

	/**
	 *  Given an RGB associative array returns the equivalent HEX string
	 *
	 * @param array $rgb
	 *
	 * @return string RGB string
	 * @throws Exception "Bad RGB Array".
	 */
	public static function rgbToHex( $rgb = [] ) {
		// Make sure it's RGB.
		if ( empty( $rgb ) || ! isset( $rgb['R'] ) || ! isset( $rgb['G'] ) || ! isset( $rgb['B'] ) ) {
			throw new Exception( 'Param was not an RGB array' );
		}

		// Convert RGB to HEX.
		$hex[0] = dechex( $rgb['R'] );
		if ( 1 === strlen( $hex[0] ) ) {
			$hex[0] .= $hex[0];
		}
		$hex[1] = dechex( $rgb['G'] );

		if ( 1 === strlen( $hex[1] ) ) {
			$hex[1] .= $hex[1];
		}
		$hex[2] = dechex( $rgb['B'] );

		if ( 1 === strlen( $hex[2] ) ) {
			$hex[2] .= $hex[2];
		}

		return implode( '', $hex );
	}

	/**
	 * Given a HEX value, returns a darker color. If no desired amount provided, then the color halfway between
	 * given HEX and black will be returned.
	 *
	 * @param int $amount
	 *
	 * @return string Darker HEX value
	 * @throws \Exception
	 */
	public function darken( $amount = self::DEFAULT_ADJUST ) {
		// Darken.
		$darker_hsl = $this->darken_private( $this->hsl, $amount );

		// Return as HEX.
		return self::hslToHex( $darker_hsl );
	}

	/**
	 * Given a HEX value, returns a lighter color. If no desired amount provided, then the color halfway between
	 * given HEX and white will be returned.
	 *
	 * @param int $amount
	 *
	 * @return string Lighter HEX value
	 * @throws \Exception.
	 */
	public function lighten( $amount = self::DEFAULT_ADJUST ) {
		// Lighten.
		$lighter_hsl = $this->lighten_private( $this->hsl, $amount );

		// Return as HEX.
		return self::hslToHex( $lighter_hsl );
	}

	/**
	 * Given a HEX value, returns a mixed color. If no desired amount provided, then the color mixed by this ratio
	 *
	 * @param string $hex2 Secondary HEX value to mix with.
	 * @param int $amount = -100..0..+100.
	 *
	 * @return string mixed HEX value
	 * @throws \Exception
	 */
	public function mix( $hex2, $amount = 0 ) {
		$rgb2 = self::hexToRgb( $hex2 );
		$mixed = $this->mix_private( $this->rgb, $rgb2, $amount );

		// Return as HEX.
		return self::rgbToHex( $mixed );
	}

	/**
	 * Creates an array with two shades that can be used to make a gradient
	 *
	 * @param int $amount Optional percentage amount you want your contrast color.
	 *
	 * @return array An array with a 'light' and 'dark' index
	 * @throws \Exception
	 */
	public function makeGradient( $amount = self::DEFAULT_ADJUST ) {
		// Decide which color needs to be made.
		if ( $this->isLight() ) {
			$light_color = $this->hex;
			$dark_color = $this->darken( $amount );
		} else {
			$light_color = $this->lighten( $amount );
			$dark_color = $this->hex;
		}

		// Return our gradient array.
		return [
			'light' => $light_color,
			'dark' => $dark_color,
		];
	}

	/**
	 * Returns whether or not given color is considered "light"
	 *
	 * @param string|Boolean $color
	 *
	 * @return boolean
	 */
	public function isLight( $color = false ) {
		// Get our color.
		$color = ( $color ) ? $color : $this->hex;

		// Calculate straight from rbg.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		return ( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 > 130 );
	}

	/**
	 * Returns whether or not a given color is considered "dark"
	 *
	 * @param string|Boolean $color
	 *
	 * @return boolean
	 */
	public function isDark( $color = false ) {
		// Get our color.
		$color = ( $color ) ? $color : $this->hex;

		// Calculate straight from rbg.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		return ( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 <= 130 );
	}

	/**
	 * Returns the complimentary color
	 *
	 * @return string Complementary hex color
	 * @throws \Exception
	 */
	public function complementary() {
		// Get our HSL.
		$hsl = $this->hsl;

		// Adjust Hue 180 degrees.
		$hsl['H'] += ( $hsl['H'] > 180 ) ? - 180 : 180;

		// Return the new value in HEX.
		return self::hslToHex( $hsl );
	}

	/**
	 * Returns your color's HSL array
	 */
	public function getHsl() {
		return $this->hsl;
	}

	/**
	 * Returns your original color
	 */
	public function getHex() {
		return $this->hex;
	}

	/**
	 * Returns your color's RGB array
	 */
	public function getRgb() {
		return $this->rgb;
	}

	// ===========================
	// = Private Functions Below =
	// ===========================

	/**
	 * Darkens a given HSL array
	 *
	 * @param array $hsl
	 * @param int $amount
	 *
	 * @return array $hsl
	 */
	private function darken_private( $hsl, $amount = self::DEFAULT_ADJUST ) {
		// Check if we were provided a number.
		if ( $amount ) {
			$hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
			$hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L'] / 100;
		} else {
			// We need to find out how much to darken.
			$hsl['L'] = $hsl['L'] / 2;
		}

		return $hsl;
	}

	/**
	 * Lightens a given HSL array
	 *
	 * @param array $hsl
	 * @param int $amount
	 *
	 * @return array $hsl
	 */
	private function lighten_private( $hsl, $amount = self::DEFAULT_ADJUST ) {
		// Check if we were provided a number.
		if ( $amount ) {
			$hsl['L'] = ( $hsl['L'] * 100.0 ) + $amount;
			$hsl['L'] = ( $hsl['L'] > 100.0 ) ? 1.0 : $hsl['L'] / 100.0;
		} else {
			// We need to find out how much to lighten.
			$hsl['L'] += ( 1.0 - $hsl['L'] ) / 2.0;
		}

		return $hsl;
	}

	/**
	 * Mix 2 rgb colors and return a rgb color
	 *
	 * @param array $rgb1
	 * @param array $rgb2
	 * @param int $amount ranged -100..0..+100.
	 *
	 * @return array $rgb
	 *
	 *    ported from http://phpxref.pagelines.com/nav.html?includes/class.colors.php.source.html
	 */
	private function mix_private( $rgb1, $rgb2, $amount = 0 ) {

		$r1 = ( $amount + 100 ) / 100;
		$r2 = 2 - $r1;

		$rmix = ( ( $rgb1['R'] * $r1 ) + ( $rgb2['R'] * $r2 ) ) / 2;
		$gmix = ( ( $rgb1['G'] * $r1 ) + ( $rgb2['G'] * $r2 ) ) / 2;
		$bmix = ( ( $rgb1['B'] * $r1 ) + ( $rgb2['B'] * $r2 ) ) / 2;

		return [
			'R' => $rmix,
			'G' => $gmix,
			'B' => $bmix,
		];
	}

	/**
	 * Given a Hue, returns corresponding RGB value
	 *
	 * @param int $v1
	 * @param int $v2
	 * @param int $v_h
	 *
	 * @return int
	 */
	private static function huetorgb_private( $v1, $v2, $v_h ) {
		if ( $v_h < 0 ) {
			$v_h++;
		}

		if ( $v_h > 1 ) {
			$v_h--;
		}

		if ( ( 6 * $v_h ) < 1 ) {
			return ( $v1 + ( $v2 - $v1 ) * 6 * $v_h );
		}

		if ( ( 2 * $v_h ) < 1 ) {
			return $v2;
		}

		if ( ( 3 * $v_h ) < 2 ) {
			return ( $v1 + ( $v2 - $v1 ) * ( ( 2 / 3 ) - $v_h ) * 6 );
		}

		return $v1;
	}

	/**
	 * You need to check if you were given a good hex string
	 *
	 * @param string $hex
	 *
	 * @return string Color
	 * @throws Exception "Bad color format".
	 */
	private static function check_hex_private( $hex ) {
		// Strip # sign is present.
		$color = str_replace( '#', '', $hex );

		// Make sure it's 6 digits.
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		} elseif ( strlen( $color ) !== 6 ) {
			throw new Exception( 'HEX color needs to be 6 or 3 digits long' );
		}

		return $color;
	}
}
