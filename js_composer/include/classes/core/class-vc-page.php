<?php
/**
 * Base page class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Page
 */
class Vc_Page {
	/**
	 * The slug of the page.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * The title of the page.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * The path to the template file for the page.
	 *
	 * @var string
	 */
	protected $templatePath;

	/**
	 * Ajax save flag.
	 *
	 * @var bool
	 */
	protected $is_ajax_save = false;

	/**
	 * Get the slug of the page.
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Set the slug of the page.
	 *
	 * @param mixed $slug
	 *
	 * @return $this;
	 */
	public function setSlug( $slug ) {
		$this->slug = (string) $slug;

		return $this;
	}

	/**
	 * Get the title of the page.
	 *
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set the title of the page.
	 *
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle( $title ) {
		$this->title = (string) $title;

		return $this;
	}

	/**
	 * Get the title of the page.
	 *
	 * @return mixed
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}

	/**
	 * Set the path to the template file for the page.
	 *
	 * @param mixed $templatePath
	 *
	 * @return $this
	 */
	public function setTemplatePath( $templatePath ) {
		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 * Render the page using the specified template.
	 */
	public function render() {
		vc_include_template( $this->getTemplatePath(), [
			'page' => $this,
		] );
	}

	/**
	 * Set ajax save.
	 */
	public function set_ajax_save() {
		$this->is_ajax_save = true;
	}

	/**
	 * Get ajax save file
	 */
	public function get_ajax_save() {
		return $this->is_ajax_save;
	}
}
