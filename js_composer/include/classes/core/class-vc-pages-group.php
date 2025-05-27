<?php
/**
 * Show the groups of the pages likes pages with tabs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vs_Pages_Group.
 *
 * @since 4.5
 */
class Vc_Pages_Group extends Vc_Page {
	/**
	 * The active page in the group.
	 *
	 * @var Vc_Page
	 */
	protected $activePage;

	/**
	 * The list of pages in the group.
	 *
	 * @var mixed
	 */
	protected $pages;

	/**
	 * The path to the template used for rendering.
	 *
	 * @var mixed
	 */
	protected $templatePath;

	/**
	 * Get the currently active page.
	 *
	 * @return mixed
	 */
	public function getActivePage() {
		return $this->activePage;
	}

	/**
	 * Set the currently active page.
	 *
	 * @param Vc_Page $activePage
	 *
	 * @return $this
	 */
	public function setActivePage( Vc_Page $activePage ) {
		$this->activePage = $activePage;

		return $this;
	}

	/**
	 * Get the pages in the group.
	 *
	 * @return mixed
	 */
	public function getPages() {
		return $this->pages;
	}

	/**
	 * Set the pages in the group.
	 *
	 * @param mixed $pages
	 *
	 * @return $this
	 */
	public function setPages( $pages ) {
		$this->pages = $pages;

		return $this;
	}

	/**
	 * Get the path to the template used for rendering.
	 *
	 * @return mixed
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}

	/**
	 * Set the path to the template used for rendering.
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
	 * Render html output for current page.
	 */
	public function render() {
		vc_include_template( $this->getTemplatePath(), [
			'pages' => $this->getPages(),
			'active_page' => $this->activePage,
			'page' => $this,
		] );
	}
}
