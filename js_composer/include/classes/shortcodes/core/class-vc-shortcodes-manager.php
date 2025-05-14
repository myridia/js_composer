<?php
/**
 * Shortcodes Manager.
 *
 * @package WPBakery
 * @noinspection PhpIncludeInspection
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Prefix for custom shortcodes.
 */
define( 'VC_SHORTCODE_CUSTOMIZE_PREFIX', 'vc_theme_' );
/**
 * Prefix for custom shortcodes before.
 */
define( 'VC_SHORTCODE_BEFORE_CUSTOMIZE_PREFIX', 'vc_theme_before_' );
/**
 * Prefix for custom shortcodes after.
 */
define( 'VC_SHORTCODE_AFTER_CUSTOMIZE_PREFIX', 'vc_theme_after_' );
/**
 * Prefix for custom filter tag.
 */
define( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'vc_shortcodes_css_class' );

require_once $this->path( 'SHORTCODES_DIR', 'core/class-wpbakery-visualcomposer-abstract.php' );
require_once $this->path( 'SHORTCODES_DIR', 'core/class-wpbakeryshortcode.php' );
require_once $this->path( 'SHORTCODES_DIR', 'core/class-wbpakeryshortcodefishbones.php' );
require_once $this->path( 'SHORTCODES_DIR', 'core/class-wpbakeryshortcodescontainer.php' );

/**
 * Class Vc_Shortcodes_Manager
 *
 * @since 4.9
 */
class Vc_Shortcodes_Manager {
	/**
	 * Shortcode classes.
	 *
	 * @var array
	 */
	private $shortcode_classes = [
		'default' => [],
	];
	/**
	 * Tag.
	 *
	 * @var string
	 */
	private $tag;
	/**
	 * Core singleton class
	 *
	 * @var self - pattern realization
	 */
	private static $instance;

	/**
	 * Get the instance of Vc_Shortcodes_Manager
	 *
	 * @return self
	 */
	public static function getInstance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get tag.
	 *
	 * @return string
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * Set tag.
	 *
	 * @param string $tag
	 * @return $this
	 */
	public function setTag( $tag ) {
		$this->tag = $tag;

		return $this;
	}

	/**
	 * Get shortcode element classes.
	 *
	 * @param string $tag
	 * @return \WPBakeryShortCodeFishBones
	 * @throws \Exception
	 */
	public function getElementClass( $tag ) {
		$currentScope = WPBMap::getScope();
		if ( isset( $this->shortcode_classes[ $currentScope ], $this->shortcode_classes[ $currentScope ][ $tag ] ) ) {
			return $this->shortcode_classes[ $currentScope ][ $tag ];
		}
		if ( ! isset( $this->shortcode_classes[ $currentScope ] ) ) {
			$this->shortcode_classes[ $currentScope ] = [];
		}
		$settings = WPBMap::getShortCode( $tag );
		if ( empty( $settings ) ) {
			throw new Exception( 'Element must be mapped in system' );
		}
		require_once vc_path_dir( 'SHORTCODES_DIR', 'wordpress-widgets.php' );

		$class_name = ! empty( $settings['php_class_name'] ) ? $settings['php_class_name'] : 'WPBakeryShortCode_' . $settings['base'];

		$autoloaded_dependencies = VcShortcodeAutoloader::includeClass( $class_name );

		if ( ! $autoloaded_dependencies ) {
			$file = vc_path_dir( 'SHORTCODES_DIR', str_replace( '_', '-', $settings['base'] ) . '.php' );
			if ( is_file( $file ) ) {
				require_once $file;
			}
		}

		if ( class_exists( $class_name ) && is_subclass_of( $class_name, 'WPBakeryShortCode' ) ) {
			$shortcode_class = new $class_name( $settings );
		} else {
			$shortcode_class = new WPBakeryShortCodeFishBones( $settings );
		}
		$this->shortcode_classes[ $currentScope ][ $tag ] = $shortcode_class;

		return $shortcode_class;
	}

	/**
	 * Get shortcode element classes.
	 *
	 * @return \WPBakeryShortCodeFishBones
	 * @throws \Exception
	 */
	public function shortcodeClass() {
		return $this->getElementClass( $this->tag );
	}

	/**
	 * Get template.
	 *
	 * @param string $content
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function template( $content = '' ) {
		return $this->getElementClass( $this->tag )->contentAdmin( [], $content );
	}

	/**
	 * Get settings.
	 *
	 * @param string $name
	 *
	 * @return null
	 * @throws \Exception
	 */
	public function settings( $name ) {
		$settings = WPBMap::getShortCode( $this->tag );

		return isset( $settings[ $name ] ) ? $settings[ $name ] : null;
	}

	/**
	 * Rendering.
	 *
	 * @param array $atts
	 * @param null $content
	 * @param null $tag
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function render( $atts, $content = null, $tag = null ) {
		if ( null !== $tag ) {
			_deprecated_argument( __METHOD__, '7.9', '$tag' );
		}
		return $this->getElementClass( $this->tag )->output( $atts, $content );
	}

	/**
	 * Build shortcodes assets.
	 */
	public function buildShortcodesAssets() {
		$elements = WPBMap::getAllShortCodes();
		foreach ( $elements as $tag => $settings ) {
			$element_class = $this->getElementClass( $tag );
			$element_class->enqueueAssets();
			$element_class->printIconStyles();
		}
	}

	/**
	 * Build shortcodes assets for editable.
	 */
	public function buildShortcodesAssetsForEditable() {
		$elements = WPBMap::getAllShortCodes(); // @todo create pull to use only where it is set inside function. BC problem
		foreach ( $elements as $tag => $settings ) {
			$element_class = $this->getElementClass( $tag );
			$element_class->printIconStyles();
		}
	}

	/**
	 * Check if shortcode class is initialized.
	 *
	 * @param string $tag
	 * @return bool
	 */
	public function isShortcodeClassInitialized( $tag ) {
		$currentScope = WPBMap::getScope();

		return isset( $this->shortcode_classes[ $currentScope ], $this->shortcode_classes[ $currentScope ][ $tag ] );
	}

	/**
	 * Unset element class.
	 *
	 * @param string $tag
	 * @return bool
	 */
	public function unsetElementClass( $tag ) {
		$currentScope = WPBMap::getScope();
		unset( $this->shortcode_classes[ $currentScope ][ $tag ] );

		return true;
	}
}
