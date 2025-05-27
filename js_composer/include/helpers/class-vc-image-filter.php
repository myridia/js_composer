<?php
/**
 * Image filter helper.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class vcImageFilter
 */
class vcImageFilter {

	/**
	 * Processed image.
	 *
	 * @var resource
	 */
	private $image;

	/**
	 * Run constructor
	 *
	 * @param resource &$image GD image resource.
	 */
	public function __construct( &$image ) {
		$this->image = $image;
	}

	/**
	 * Get the current image resource
	 *
	 * @return resource
	 */
	public function getImage() {
		return $this->image;
	}


	/**
	 * Apply a sepia filter to the image.
	 *
	 * @return vcImageFilter
	 */
	public function sepia() {
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 100, 50, 0 );

		return $this;
	}

	/**
	 * Apply a modified sepia filter to the image.
	 *
	 * @return vcImageFilter
	 */
	public function sepia2() {
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 10 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 20 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 60, 30, - 15 );

		return $this;
	}

	/**
	 * Sharpen the image.
	 *
	 * @return vcImageFilter
	 */
	public function sharpen() {
		$gaussian = [
			[
				1.0,
				1.0,
				1.0,
			],
			[
				1.0,
				- 7.0,
				1.0,
			],
			[
				1.0,
				1.0,
				1.0,
			],
		];
		imageconvolution( $this->image, $gaussian, 1, 4 );

		return $this;
	}

	/**
	 * Apply an emboss effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function emboss() {
		$gaussian = [
			[
				- 2.0,
				- 1.0,
				0.0,
			],
			[
				- 1.0,
				1.0,
				1.0,
			],
			[
				0.0,
				1.0,
				2.0,
			],
		];

		imageconvolution( $this->image, $gaussian, 1, 5 );

		return $this;
	}

	/**
	 * Apply a "cool" filter effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function cool() {
		imagefilter( $this->image, IMG_FILTER_MEAN_REMOVAL );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 50 );

		return $this;
	}

	/**
	 * Apply a light enhancement filter to the image.
	 *
	 * @return vcImageFilter
	 */
	public function light() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 10 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 100, 50, 0, 10 );

		return $this;
	}

	/**
	 * Apply an aqua tint filter to the image.
	 *
	 * @return vcImageFilter
	 */
	public function aqua() {
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 70, 0, 30 );

		return $this;
	}

	/**
	 * Apply a fuzzy blur effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function fuzzy() {
		$gaussian = [
			[
				1.0,
				1.0,
				1.0,
			],
			[
				1.0,
				1.0,
				1.0,
			],
			[
				1.0,
				1.0,
				1.0,
			],
		];

		imageconvolution( $this->image, $gaussian, 9, 20 );

		return $this;
	}

	/**
	 * Apply a contrast and brightness boost to the image.
	 *
	 * @return vcImageFilter
	 */
	public function boost() {
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 35 );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 10 );

		return $this;
	}

	/**
	 * Apply a grayscale filter with enhanced contrast.
	 *
	 * @return vcImageFilter
	 */
	public function gray() {
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 60 );
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );

		return $this;
	}

	/**
	 * Apply an antique effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function antique() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 0 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 30 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 75, 50, 25 );

		return $this;
	}

	/**
	 * Apply a black and white filter with slight brightness and contrast adjustments.
	 *
	 * @return vcImageFilter
	 */
	public function blackwhite() {
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 10 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 20 );

		return $this;
	}

	/**
	 * Apply a second version of the contrast boost filter with color adjustments.
	 *
	 * @return vcImageFilter
	 */
	public function boost2() {
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 35 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 25, 25, 25 );

		return $this;
	}

	/**
	 * Apply a blur effect with additional contrast adjustments.
	 *
	 * @return vcImageFilter
	 */
	public function blur() {
		imagefilter( $this->image, IMG_FILTER_SELECTIVE_BLUR );
		imagefilter( $this->image, IMG_FILTER_GAUSSIAN_BLUR );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 15 );
		imagefilter( $this->image, IMG_FILTER_SMOOTH, - 2 );

		return $this;
	}

	/**
	 * Apply a vintage effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function vintage() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 10 );
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 40, 10, - 15 );

		return $this;
	}

	/**
	 * Concentrate and smooth the image using a Gaussian blur.
	 *
	 * @return vcImageFilter
	 */
	public function concentrate() {
		imagefilter( $this->image, IMG_FILTER_GAUSSIAN_BLUR );
		imagefilter( $this->image, IMG_FILTER_SMOOTH, - 10 );

		return $this;
	}

	/**
	 * Apply a colorization effect with purples and blues, with a contrast reduction.
	 *
	 * @return vcImageFilter
	 */
	public function hermajesty() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 10 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 5 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 80, 0, 60 );

		return $this;
	}

	/**
	 * Apply a soft warm glow effect to the image.
	 *
	 * @return vcImageFilter
	 */
	public function everglow() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 30 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 5 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 30, 30, 0 );

		return $this;
	}

	/**
	 * Apply a tender pinkish color tint with selective blur.
	 *
	 * @return vcImageFilter
	 */
	public function freshblue() {
		imagefilter( $this->image, IMG_FILTER_CONTRAST, - 5 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 20, 0, 80, 60 );

		return $this;
	}

	/**
	 * Apply a tender pinkish color tint with selective blur.
	 *
	 * @return vcImageFilter
	 */
	public function tender() {
		imagefilter( $this->image, IMG_FILTER_CONTRAST, 5 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 80, 20, 40, 50 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 40, 40, 100 );
		imagefilter( $this->image, IMG_FILTER_SELECTIVE_BLUR );

		return $this;
	}

	/**
	 * Apply a dreamy, colorized filter with multiple negations and a Gaussian blur.
	 *
	 * @return vcImageFilter
	 */
	public function dream() {
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 150, 0, 0, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 50, 0, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_GAUSSIAN_BLUR );

		return $this;
	}

	/**
	 * Apply a frozen effect to the image with blue colorization and Gaussian blur.
	 *
	 * @return vcImageFilter
	 */
	public function frozen() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 15 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 0, 100, 50 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 0, 100, 50 );
		imagefilter( $this->image, IMG_FILTER_GAUSSIAN_BLUR );

		return $this;
	}

	/**
	 * Apply a deep forest-like color filter with smoothing.
	 *
	 * @return vcImageFilter
	 */
	public function forest() {
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 0, 150, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 0, 150, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_SMOOTH, 10 );

		return $this;
	}

	/**
	 * Apply a rain effect using Gaussian blur, mean removal, and colorization.
	 *
	 * @return vcImageFilter
	 */
	public function rain() {
		imagefilter( $this->image, IMG_FILTER_GAUSSIAN_BLUR );
		imagefilter( $this->image, IMG_FILTER_MEAN_REMOVAL );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 80, 50, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_SMOOTH, 10 );

		return $this;
	}

	/**
	 * Apply an orange peel effect with colorization, smoothing, and gamma correction.
	 *
	 * @return vcImageFilter
	 */
	public function orangepeel() {
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 100, 20, - 50, 20 );
		imagefilter( $this->image, IMG_FILTER_SMOOTH, 10 );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 10 );
		imagefilter( $this->image, IMG_FILTER_CONTRAST, 10 );
		imagegammacorrect( $this->image, 1, 1.2 );

		return $this;
	}

	/**
	 * Darken the image with grayscale and brightness reduction.
	 *
	 * @return vcImageFilter
	 */
	public function darken() {
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 50 );

		return $this;
	}

	/**
	 * Apply a summer effect with green colorization and a negative inversion.
	 *
	 * @return vcImageFilter
	 */
	public function summer() {
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 0, 150, 0, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 25, 50, 0, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );

		return $this;
	}

	/**
	 * Apply a retro effect with grayscale and light colorization.
	 *
	 * @return vcImageFilter
	 */
	public function retro() {
		imagefilter( $this->image, IMG_FILTER_GRAYSCALE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 100, 25, 25, 50 );

		return $this;
	}

	/**
	 * Apply a washed-out effect with brightness adjustment and color negation.
	 *
	 * @return vcImageFilter
	 */
	public function country() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, - 30 );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, 50, 50, 50, 50 );
		imagegammacorrect( $this->image, 1, 0.3 );

		return $this;
	}

	/**
	 * Apply a washed effect with brightness adjustment and color negation.
	 *
	 * @return vcImageFilter
	 */
	public function washed() {
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 30 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_COLORIZE, - 50, 0, 20, 50 );
		imagefilter( $this->image, IMG_FILTER_NEGATE );
		imagefilter( $this->image, IMG_FILTER_BRIGHTNESS, 10 );
		imagegammacorrect( $this->image, 1, 1.2 );

		return $this;
	}
}
