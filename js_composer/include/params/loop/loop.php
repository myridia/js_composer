<?php
/**
 * Param type 'loop'
 *
 * Used to create checkbox field with true/false logic to check specific condition.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Generates a loop form field.
 *
 * @param array $settings
 * @param array $value
 *
 * @return string
 * @since 4.2
 */
function vc_loop_form_field( $settings, $value ) {
	$query_builder = new VcLoopSettings( $value );
	$params = $query_builder->getContent();
	$loop_info = '';
	$parsed_value = [];
	if ( is_array( $params ) ) {
		foreach ( $params as $key => $param ) {
			$param_value_render = vc_loop_get_value( $param );
			if ( ! empty( $param_value_render ) ) {
				$parsed_value[] = $key . ':' . ( is_array( $param['value'] ) ? implode( ',', $param['value'] ) : $param['value'] );
				$loop_info .= ' <b>' . $query_builder->getLabel( $key ) . '</b>: ' . $param_value_render . ';';
			}
		}
	}
	if ( ! isset( $settings['settings'] ) ) {
		$settings['settings'] = [];
	}

	return '<div class="vc_loop"><input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value  ' . esc_attr( $settings['param_name'] . ' ' . $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( join( '|', $parsed_value ) ) . '"/><a href="javascript:;" class="button vc_loop-build ' . esc_attr( $settings['param_name'] ) . '_button" data-settings="' . rawurlencode( wp_json_encode( $settings['settings'] ) ) . '">' . esc_html__( 'Build query', 'js_composer' ) . '</a><div class="vc_loop-info">' . $loop_info . '</div></div>';
}

/**
 * Get loop query value.
 *
 * @param array $param
 *
 * @return string
 * @since 4.2
 */
function vc_loop_get_value( $param ) {
	$value = [];
	$selected_values = (array) $param['value'];
	if ( isset( $param['options'] ) && is_array( $param['options'] ) ) {
		foreach ( $param['options'] as $option ) {
			if ( is_array( $option ) && isset( $option['value'] ) ) {
				if ( in_array( ( ( '-' === $option['action'] ? '-' : '' ) . $option['value'] ), $selected_values, true ) ) {
					$value[] = $option['action'] . $option['name'];
				}
			} elseif ( is_array( $option ) && isset( $option[0] ) ) {
				if ( in_array( $option[0], $selected_values, true ) ) {
					$value[] = $option[1];
				}
			} elseif ( in_array( $option, $selected_values, true ) ) {
				$value[] = $option;
			}
		}
	} else {
		$value[] = $param['value'];
	}

	return implode( ', ', $value );
}

/**
 * Parses loop settings and creates WP_Query according to manual
 *
 * @since 4.2
 * @link http://codex.wordpress.org/Class_Reference/WP_Query
 */
class VcLoopQueryBuilder {
	/**
	 * Default query arguments.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $args = [
		'post_status' => 'publish',
		// show only published posts #1098.
	];

	/**
	 * VcLoopQueryBuilder constructor.
	 *
	 * @param array $data
	 * @since 4.2
	 */
	public function __construct( $data ) {
		foreach ( $data as $key => $value ) {
			$method = 'parse_' . $key;
			if ( method_exists( $this, $method ) ) {
				$this->$method( $value );
			}
		}
	}

	/**
	 * Pages count.
	 *
	 * @param int|string $value
	 * @since 4.2
	 */
	protected function parse_size( $value ) {
		$this->args['posts_per_page'] = 'All' === $value ? - 1 : (int) $value;
	}

	/**
	 * Sorting field.
	 *
	 * @param string $value
	 * @since 4.2
	 */
	protected function parse_order_by( $value ) {
		$this->args['orderby'] = $value;
	}

	/**
	 * Sorting order.
	 *
	 * @param string $value
	 * @since 4.2
	 */
	protected function parse_order( $value ) {
		$this->args['order'] = $value;
	}

	/**
	 * By post types.
	 *
	 * @param string|array  $value
	 * @since 4.2
	 */
	protected function parse_post_type( $value ) {
		$this->args['post_type'] = $this->stringToArray( $value );
	}

	/**
	 * By author.
	 *
	 * @param int|array $value
	 * @since 4.2
	 */
	protected function parse_authors( $value ) {
		$this->args['author'] = $value;
	}

	/**
	 * By categories.
	 *
	 * @param string $value Comma-separated list of category IDs.
	 * @since 4.2
	 */
	protected function parse_categories( $value ) {
		$values = explode( ',', $value );
		$cat_in = [];
		$cat_not_in = [];
		foreach ( $values as $cat ) {
			if ( (int) $cat > 0 ) {
				$cat_in[] = $cat;
			} else {
				$cat_not_in[] = abs( $cat );
			}
		}
		if ( ! empty( $cat_in ) ) {
			$this->args['category__in'] = $cat_in;
		}

		if ( ! empty( $cat_not_in ) ) {
			$this->args['category__not_in'] = $cat_not_in;
		}
	}

	/**
	 * By taxonomies.
	 *
	 * @param string $value Comma-separated list of taxonomy term IDs.
	 * @since 4.2
	 */
	protected function parse_tax_query( $value ) {
		$terms = $this->stringToArray( $value );
		if ( empty( $this->args['tax_query'] ) ) {
			$this->args['tax_query'] = [ 'relation' => 'AND' ];
		}
		$negative_term_list = [];
		foreach ( $terms as $term ) {
			if ( (int) $term < 0 ) {
				$negative_term_list[] = abs( $term );
			}
		}

		$not_in = [];
		$in = [];

		// phpcs:ignore
		$terms = get_terms( VcLoopSettings::getTaxonomies(), array( 'include' => array_map( 'abs', $terms ) ) );
		foreach ( $terms as $t ) {
			if ( in_array( (int) $t->term_id, $negative_term_list, true ) ) {
				$not_in[ $t->taxonomy ][] = $t->term_id;
			} else {
				$in[ $t->taxonomy ][] = $t->term_id;
			}
		}

		foreach ( $in as $taxonomy => $terms ) {
			$this->args['tax_query'][] = [
				'field' => 'term_id',
				'taxonomy' => $taxonomy,
				'terms' => $terms,
				'operator' => 'IN',
			];
		}
		foreach ( $not_in as $taxonomy => $terms ) {
			$this->args['tax_query'][] = [
				'field' => 'term_id',
				'taxonomy' => $taxonomy,
				'terms' => $terms,
				'operator' => 'NOT IN',
			];
		}
	}

	/**
	 * By tags ids.
	 *
	 * @param string $value Comma-separated list of tag IDs.
	 * @since 4.2
	 */
	protected function parse_tags( $value ) {
		$in = $not_in = [];
		$tags_ids = $this->stringToArray( $value );
		foreach ( $tags_ids as $tag ) {
			$tag = (int) $tag;
			if ( $tag < 0 ) {
				$not_in[] = abs( $tag );
			} else {
				$in[] = $tag;
			}
		}
		$this->args['tag__in'] = $in;
		$this->args['tag__not_in'] = $not_in;
	}

	/**
	 * Sets the argument to ignore sticky posts.
	 *
	 * @param mixed $value
	 * @since 4.2
	 */
	protected function parse_ignore_sticky_posts( $value ) {
		if ( ! empty( $value ) ) {
			$this->args['ignore_sticky_posts'] = true;
		}
	}

	/**
	 * By posts ids
	 *
	 * @param string|array $value Comma-separated list or array of post IDs.
	 * @since 4.2
	 */
	protected function parse_by_id( $value ) {
		$in = $not_in = [];
		$ids = $this->stringToArray( $value );
		foreach ( $ids as $id ) {
			$id = (int) $id;
			if ( $id < 0 ) {
				$not_in[] = abs( $id );
			} else {
				$in[] = $id;
			}
		}
		$this->args['post__in'] = $in;
		$this->args['post__not_in'] = $not_in;
	}

	/**Excludes specific post ID(s) from the query results.
	 *
	 * @param int|array $id Post ID or an array of post IDs to exclude.
	 * @since 4.2
	 */
	public function excludeId( $id ) {
		if ( ! isset( $this->args['post__not_in'] ) ) {
			$this->args['post__not_in'] = [];
		}
		if ( is_array( $id ) ) {
			$this->args['post__not_in'] = array_merge( $this->args['post__not_in'], $id );
		} else {
			$this->args['post__not_in'][] = $id;
		}
	}

	/**
	 * Converts string to array. Filters empty arrays values.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	protected function stringToArray( $value ) {
		$valid_values = [];
		$list = preg_split( '/\,[\s]*/', $value );
		foreach ( $list as $v ) {
			if ( strlen( $v ) > 0 ) {
				$valid_values[] = $v;
			}
		}

		return $valid_values;
	}

	/**
	 * Builds the query arguments.
	 *
	 * @return array
	 */
	public function build() {
		$args = apply_filters( 'wpb_loop_query_build_args', $this->args );

		return [
			$args,
			new WP_Query( $args ),
		];
	}
}

/**
 * Class VcLoopSettings
 *
 * @since 4.2
 */
class VcLoopSettings {
	/**
	 * Parsed content from the loop settings.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $content = [];
	/**
	 * Available parts of loop for WP_Query object.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $parts;
	/**
	 * List of query parts that can be used in WP_Query.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $query_parts = [
		'size',
		'order_by',
		'order',
		'post_type',
		'ignore_sticky_posts',
		'authors',
		'categories',
		'tags',
		'tax_query',
		'by_id',
	];
	/**
	 * Contains the settings for each part of the loop.
	 *
	 * @var array
	 */
	public $settings = [];

	/**
	 * VcLoopSettings constructor.
	 *
	 * @param string $value
	 * @param array $settings
	 * @since 4.2
	 */
	public function __construct( $value, $settings = [] ) {
		$this->parts = [
			'size' => esc_html__( 'Post count', 'js_composer' ),
			'order_by' => esc_html__( 'Order by', 'js_composer' ),
			'order' => esc_html__( 'Sort order', 'js_composer' ),
			'post_type' => esc_html__( 'Post types', 'js_composer' ),
			'ignore_sticky_posts' => esc_html__( 'Ignore Sticky posts', 'js_composer' ),
			'authors' => esc_html__( 'Author', 'js_composer' ),
			'categories' => esc_html__( 'Categories', 'js_composer' ),
			'tags' => esc_html__( 'Tags', 'js_composer' ),
			'tax_query' => esc_html__( 'Taxonomies', 'js_composer' ),
			'by_id' => esc_html__( 'Individual posts/pages', 'js_composer' ),
		];
		$this->settings = $settings;
		// Parse loop string.
		$data = $this->parseData( $value );
		foreach ( $this->query_parts as $part ) {
			$value = isset( $data[ $part ] ) ? $data[ $part ] : '';
			$locked = 'true' === $this->getSettings( $part, 'locked' );
			// Predefined value check.
			if ( ! is_null( $this->getSettings( $part, 'value' ) ) && $this->replaceLockedValue( $part ) && ( true === $locked || 0 === strlen( (string) $value ) ) ) {
				$value = $this->settings[ $part ]['value'];
			} elseif ( ! is_null( $this->getSettings( $part, 'value' ) ) && ! $this->replaceLockedValue( $part ) && ( true === $locked || 0 === strlen( (string) $value ) ) ) {
				$value = implode( ',', array_unique( explode( ',', $value . ',' . $this->settings[ $part ]['value'] ) ) );
			}
			// Find custom method for parsing.
			if ( method_exists( $this, 'parse_' . $part ) ) {
				$method = 'parse_' . $part;
				$this->content[ $part ] = $this->$method( $value );
			} else {
				$this->content[ $part ] = $this->parseString( $value );
			}
			// Set locked if value is locked by settings.
			if ( $locked ) {
				$this->content[ $part ]['locked'] = true;
			}
			if ( 'true' === $this->getSettings( $part, 'hidden' ) ) {
				$this->content[ $part ]['hidden'] = true;
			}
		}
	}

	/**
	 * Determines if a part's locked value should be replaced.
	 *
	 * @param mixed $part
	 *
	 * @return bool
	 * @since 4.2
	 */
	protected function replaceLockedValue( $part ) {
		return in_array( $part, [
			'size',
			'order_by',
			'order',
		], true );
	}

	/**
	 * Retrieves the label for a given key.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 * @since 4.2
	 */
	public function getLabel( $key ) {
		return isset( $this->parts[ $key ] ) ? $this->parts[ $key ] : $key;
	}

	/**
	 * Retrieves a specific setting value.
	 *
	 * @param string $part
	 * @param string $name
	 *
	 * @return null
	 * @since 4.2
	 */
	public function getSettings( $part, $name ) {
		$settings_exists = isset( $this->settings[ $part ] ) && is_array( $this->settings[ $part ] );

		return $settings_exists && isset( $this->settings[ $part ][ $name ] ) ? $this->settings[ $part ][ $name ] : null;
	}

	/**
	 * Parses a string value into an array.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parseString( $value ) {
		return [ 'value' => $value ];
	}

	/**
	 * Parses a dropdown value and options into an array.
	 *
	 * @param string $value
	 * @param array $options
	 *
	 * @return array
	 * @since 4.2
	 */
	protected function parseDropDown( $value, $options = [] ) {
		return [
			'value' => $value,
			'options' => $options,
		];
	}

	/**
	 * Parses a multi-select value and options into an array.
	 *
	 * @param string $value
	 * @param array $options
	 *
	 * @return array
	 * @since 4.2
	 */
	protected function parseMultiSelect( $value, $options = [] ) {
		return [
			'value' => explode( ',', trim( $value, ',' ) ),
			'options' => $options,
		];
	}

	/**
	 * Parses the 'order_by' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_order_by( $value ) {
		return $this->parseDropDown( $value, [
			[
				'date',
				esc_html__( 'Date', 'js_composer' ),
			],
			'ID',
			[
				'author',
				esc_html__( 'Author', 'js_composer' ),
			],
			[
				'title',
				esc_html__( 'Title', 'js_composer' ),
			],
			[
				'modified',
				esc_html__( 'Modified', 'js_composer' ),
			],
			[
				'rand',
				esc_html__( 'Random', 'js_composer' ),
			],
			[
				'comment_count',
				esc_html__( 'Comment count', 'js_composer' ),
			],
			[
				'menu_order',
				esc_html__( 'Menu order', 'js_composer' ),
			],
		] );
	}

	/**
	 * Parses the 'order' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_order( $value ) {
		return $this->parseDropDown( $value, [
			[
				'ASC',
				esc_html__( 'Ascending', 'js_composer' ),
			],
			[
				'DESC',
				esc_html__( 'Descending', 'js_composer' ),
			],
		] );
	}

	/**
	 * Parses the 'post_type' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_post_type( $value ) {
		$options = [];
		$args = [
			'public' => true,
		];
		$post_types = get_post_types( $args );
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' !== $post_type ) {
				$options[] = $post_type;
			}
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Parses the 'ignore_sticky_posts' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 */
	public function parse_ignore_sticky_posts( $value ) {
		return [
			'value' => [ $value ],
			'options' => [
				[
					'1',
					'Yes',
				],
			],
		];
	}

	/**
	 * Parses the 'authors' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_authors( $value ) {
		$options = $not_in = [];
		if ( empty( $value ) ) {
			return $this->parseMultiSelect( $value, $options );
		}
		$list = explode( ',', $value );
		foreach ( $list as $id ) {
			if ( (int) $id < 0 ) {
				$not_in[] = abs( $id );
			}
		}
		$users = get_users( [ 'include' => array_map( 'abs', $list ) ] );
		foreach ( $users as $user ) {
			$options[] = [
				'value' => (string) $user->ID,
				'name' => $user->data->user_nicename,
				'action' => in_array( (int) $user->ID, $not_in, true ) ? '-' : '+',
			];
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Parses the 'categories' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_categories( $value ) {
		$options = $not_in = [];
		if ( empty( $value ) ) {
			return $this->parseMultiSelect( $value, $options );
		}
		$list = explode( ',', $value );
		foreach ( $list as $id ) {
			if ( (int) $id < 0 ) {
				$not_in[] = abs( $id );
			}
		}
		$list = get_categories( [ 'include' => array_map( 'abs', $list ) ] );
		foreach ( $list as $obj ) {
			$options[] = [
				'value' => (string) $obj->cat_ID,
				'name' => $obj->cat_name,
				'action' => in_array( (int) $obj->cat_ID, $not_in, true ) ? '-' : '+',
			];
		}
		if ( empty( $list ) ) {
			$value = '';
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Parses the 'tags' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_tags( $value ) {
		$options = $not_in = [];
		if ( empty( $value ) ) {
			return $this->parseMultiSelect( $value, $options );
		}
		$list = explode( ',', $value );
		foreach ( $list as $id ) {
			if ( (int) $id < 0 ) {
				$not_in[] = abs( $id );
			}
		}
		$list = get_tags( [ 'include' => array_map( 'abs', $list ) ] );
		foreach ( $list as $obj ) {
			$options[] = [
				'value' => (string) $obj->term_id,
				'name' => $obj->name,
				'action' => in_array( (int) $obj->term_id, $not_in, true ) ? '-' : '+',
			];
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Parses the 'tax_query' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_tax_query( $value ) {
		$options = $not_in = [];
		if ( empty( $value ) ) {
			return $this->parseMultiSelect( $value, $options );
		}
		$list = explode( ',', $value );
		foreach ( $list as $id ) {
			if ( (int) $id < 0 ) {
				$not_in[] = abs( $id );
			}
		}
		// phpcs:ignore
		$list = get_terms( self::getTaxonomies(), array( 'include' => array_map( 'abs', $list ) ) );
		foreach ( $list as $obj ) {
			$options[] = [
				'value' => (string) $obj->term_id,
				'name' => $obj->name,
				'action' => in_array( (int) $obj->term_id, $not_in, true ) ? '-' : '+',
			];
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Parses the 'by_id' value.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public function parse_by_id( $value ) {
		$options = $not_in = [];
		if ( empty( $value ) ) {
			return $this->parseMultiSelect( $value, $options );
		}
		$list = explode( ',', $value );
		foreach ( $list as $id ) {
			if ( (int) $id < 0 ) {
				$not_in[] = abs( $id );
			}
		}
		$list = get_posts( [
			'post_type' => 'any',
			'include' => array_map( 'abs', $list ),
		] );

		foreach ( $list as $obj ) {
			$options[] = [
				'value' => (string) $obj->ID,
				'name' => $obj->post_title,
				'action' => in_array( (int) $obj->ID, $not_in, true ) ? '-' : '+',
			];
		}

		return $this->parseMultiSelect( $value, $options );
	}

	/**
	 * Renders the content as JSON.
	 *
	 * @since 4.2
	 */
	public function render() {
		echo wp_json_encode( $this->content );
	}

	/**
	 * Retrieves the parsed content.
	 *
	 * @return array
	 * @since 4.2
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Get list of taxonomies which has no tags and categories items.
	 *
	 * @return array
	 * @since 4.2
	 * @static
	 */
	public static function getTaxonomies() {
		$taxonomy_exclude = (array) apply_filters( 'get_categories_taxonomy', 'category' );
		$taxonomy_exclude[] = 'post_tag';
		$taxonomies = [];
		foreach ( get_taxonomies() as $taxonomy ) {
			if ( ! in_array( $taxonomy, $taxonomy_exclude, true ) ) {
				$taxonomies[] = $taxonomy;
			}
		}

		return $taxonomies;
	}

	/**
	 * Builds a default string from settings.
	 *
	 * @param array $settings
	 * @return string
	 * @since 4.2
	 */
	public static function buildDefault( $settings ) {
		if ( ! isset( $settings['settings'] ) || ! is_array( $settings['settings'] ) ) {
			return '';
		}
		$value = '';
		foreach ( $settings['settings'] as $key => $val ) {
			if ( isset( $val['value'] ) ) {
				$value .= ( empty( $value ) ? '' : '|' ) . $key . ':' . $val['value'];
			}
		}

		return $value;
	}

	/**
	 * Builds a WP_Query object from a query string.
	 *
	 * @param string $query
	 * @param bool $exclude_id
	 *
	 * @return array
	 * @since 4.2
	 */
	public static function buildWpQuery( $query, $exclude_id = false ) {
		$data = self::parseData( $query );
		$query_builder = new VcLoopQueryBuilder( $data );
		if ( $exclude_id ) {
			$query_builder->excludeId( $exclude_id );
		}

		return $query_builder->build();
	}

	/**
	 * Parses a query string into data.
	 *
	 * @param string $value
	 *
	 * @return array
	 * @since 4.2
	 */
	public static function parseData( $value ) {
		$data = [];
		$values_pairs = preg_split( '/\|/', $value );
		foreach ( $values_pairs as $pair ) {
			if ( ! empty( $pair ) ) {
				list( $key, $value ) = preg_split( '/\:/', $pair );
				$data[ $key ] = $value;
			}
		}

		return $data;
	}
}

/**
 * Suggestion list for wp_query field
 * Class VcLoopSuggestions
 *
 * @since 4.2
 */
class VcLoopSuggestions {
	/**
	 * Contains the list of suggestions generated by the class.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $content = [];
	/**
	 * List of IDs to be excluded from the suggestions.
	 *
	 * @since 4.2
	 * @var array
	 */
	protected $exclude = [];
	/**
	 * The field for which suggestions are being generated.
	 *
	 * @since 4.2
	 * @var string
	 */
	protected $field;

	/**
	 * VcLoopSuggestions constructor.
	 *
	 * @param string$field
	 * @param string $query
	 * @param string $exclude
	 *
	 * @since 4.2
	 */
	public function __construct( $field, $query, $exclude ) {
		$this->exclude = explode( ',', $exclude );
		$method_name = 'get_' . preg_replace( '/_out$/', '', $field );
		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $query );
		}
	}

	/**
	 * Gets a list of authors based on the query string.
	 *
	 * @param string $query
	 *
	 * @since 4.2
	 */
	public function get_authors( $query ) {
		$args = ! empty( $query ) ? [
			'search' => '*' . $query . '*',
			'search_columns' => [ 'user_nicename' ],
		] : [];
		if ( ! empty( $this->exclude ) ) {
			$args['exclude'] = $this->exclude;
		}
		$users = get_users( $args );
		foreach ( $users as $user ) {
			$this->content[] = [
				'value' => (string) $user->ID,
				'name' => (string) $user->data->user_nicename,
			];
		}
	}

	/**
	 * Gets a list of categories based on the query string.
	 *
	 * @param string $query
	 *
	 * @since 4.2
	 */
	public function get_categories( $query ) {
		$args = ! empty( $query ) ? [ 'search' => $query ] : [];
		if ( ! empty( $this->exclude ) ) {
			$args['exclude'] = $this->exclude;
		}
		$categories = get_categories( $args );

		foreach ( $categories as $cat ) {
			$this->content[] = [
				'value' => (string) $cat->cat_ID,
				'name' => $cat->cat_name,
			];
		}
	}

	/**
	 * Gets a list of tags based on the query string.
	 *
	 * @param string $query
	 *
	 * @since 4.2
	 */
	public function get_tags( $query ) {
		$args = ! empty( $query ) ? [ 'search' => $query ] : [];
		if ( ! empty( $this->exclude ) ) {
			$args['exclude'] = $this->exclude;
		}
		$tags = get_tags( $args );
		foreach ( $tags as $tag ) {
			$this->content[] = [
				'value' => (string) $tag->term_id,
				'name' => $tag->name,
			];
		}
	}

	/**
	 * Gets a list of taxonomies based on the query string.
	 *
	 * @param string $query
	 *
	 * @since 4.2
	 */
	public function get_tax_query( $query ) {
		$args = ! empty( $query ) ? [ 'search' => $query ] : [];
		if ( ! empty( $this->exclude ) ) {
			$args['exclude'] = $this->exclude;
		}
		// phpcs:ignore
		$tags = get_terms( VcLoopSettings::getTaxonomies(), $args );
		foreach ( $tags as $tag ) {
			$this->content[] = [
				'value' => $tag->term_id,
				'name' => $tag->name . ' (' . $tag->taxonomy . ')',
			];
		}
	}

	/**
	 * Gets a list of posts based on the query string.
	 *
	 * @param string $query
	 *
	 * @since 4.2
	 */
	public function get_by_id( $query ) {
		$args = ! empty( $query ) ? [
			's' => $query,
			'post_type' => 'any',
			'no_found_rows' => true,
			'orderby' => 'relevance',
		] : [
			'post_type' => 'any',
			'no_found_rows' => true,
			'orderby' => 'relevance',
		];
		if ( ! empty( $this->exclude ) ) {
			$args['exclude'] = $this->exclude;
		}
		$args['ignore_sticky_posts'] = true;
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$this->content[] = [
				'value' => $post->ID,
				'name' => $post->post_title,
			];
		}
	}

	/**
	 * Outputs the suggestions as a JSON-encoded string.
	 *
	 * @since 4.2
	 */
	public function render() {
		echo wp_json_encode( $this->content );
	}
}

/**
 * Build WP_Query object from query string.
 * String created by loop controllers
 *
 * @param string $query
 * @param bool $exclude_id
 *
 * @return array
 * @since 4.2
 */
function vc_build_loop_query( $query, $exclude_id = false ) {
	return VcLoopSettings::buildWpQuery( $query, $exclude_id );
}

/**
 * Handles the AJAX request to get loop suggestions based on the provided parameters.
 *
 * @since 4.2
 */
function vc_get_loop_suggestion() {
	vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

	$loop_suggestions = new VcLoopSuggestions( vc_post_param( 'field' ), vc_post_param( 'query' ), vc_post_param( 'exclude' ) );
	$loop_suggestions->render();
	die();
}

/**
 * Handles the AJAX request to get loop settings in JSON format based on the provided parameters.
 *
 * @since 4.2
 */
function vc_get_loop_settings_json() {
	vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

	$loop_settings = new VcLoopSettings( vc_post_param( 'value' ), vc_post_param( 'settings' ) );
	$loop_settings->render();
	die();
}

add_action( 'wp_ajax_wpb_get_loop_suggestion', 'vc_get_loop_suggestion' );
add_action( 'wp_ajax_wpb_get_loop_settings', 'vc_get_loop_settings_json' );

/**
 * Include loop templates for loop settings.
 *
 * @since 4.2
 */
function vc_loop_include_templates() {
	require_once vc_path_dir( 'TEMPLATES_DIR', 'params/loop/templates.html' );
}

add_action( 'admin_footer', 'vc_loop_include_templates' );
