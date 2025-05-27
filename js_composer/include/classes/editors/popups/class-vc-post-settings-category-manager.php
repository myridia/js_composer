<?php
/**
 * Post settings category manager
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Post_Settings_Category_Manager.
 */
class Vc_Post_Settings_Category_Manager {
	/**
	 * Categories with sorted way.
	 *
	 * @since 8.2
	 * @var array Sorted categories
	 */
	private $sorted_categories = [];

	/**
	 * Categories that is selected.
	 *
	 * @since 8.2
	 * @var array Selected categories
	 */
	private $selected_categories = [];

	/**
	 * Constructor
	 *
	 * @since 8.2
	 * @param int $post_id The ID of the post.
	 */
	public function __construct( $post_id ) {
		$this->get_sorted_and_selected_categories( $post_id );
	}

	/**
	 * Render category options with optional indentation and selection from class properties.
	 *
	 * @since 8.2
	 * @param array $categories          Array of category objects.
	 * @param int   $level               Current indentation level.
	 * @param bool  $include_selected    Whether to include the selected attribute.
	 */
	public function render_category_options_with_indent( $categories = null, $level = 0, $include_selected = true ) {
		$categories = $categories ? $categories : $this->sorted_categories;

		foreach ( $categories as $category ) {
			$indent = str_repeat( '&nbsp;', $level * 3 );
			$selected = $include_selected && in_array( $category->term_id, $this->selected_categories ) ? ' selected' : '';
			echo '<option value="' . esc_attr( $category->term_id ) . '"' . esc_attr( $selected ) . '>' . esc_html( $indent . $category->name ) . '</option>';
			if ( ! empty( $category->children ) ) {
				$this->render_category_options_with_indent( $category->children, $level + 1, $include_selected );
			}
		}
	}

	/**
	 * Sort categories hierarchically.
	 *
	 * @since 8.2
	 * @param array $categories Array of category objects.
	 * @param array $into       Array to store sorted categories.
	 * @param int   $parent_id  Parent category ID.
	 */
	public function sort_categories_hierarchically( array &$categories, array &$into, $parent_id = 0 ) {
		foreach ( $categories as $i => $category ) {
			if ( $category->parent === $parent_id ) {
				$into[ $category->term_id ] = $category;
				unset( $categories[ $i ] );
			}
		}
		foreach ( $into as $top_category ) {
			$top_category->children = [];
			$this->sort_categories_hierarchically( $categories, $top_category->children, $top_category->term_id );
		}
	}

	/**
	 * Get sorted and selected categories for a post.
	 *
	 * @since 8.2
	 * @param int $post_id The ID of the post.
	 */
	public function get_sorted_and_selected_categories( $post_id ) {
		$categories = get_categories( [
			'taxonomy' => 'category',
			'hide_empty' => false,
		] );

		$this->sort_categories_hierarchically( $categories, $this->sorted_categories );

		$this->selected_categories = wp_get_post_categories( $post_id, [ 'fields' => 'ids' ] );
		if ( ! is_array( $this->selected_categories ) ) {
			$this->selected_categories = [];
		}
	}
}
