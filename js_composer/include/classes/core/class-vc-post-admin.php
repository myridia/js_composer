<?php
/**
 * Ability to interact with post data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Vc_Post_Admin class.
 *
 * @since 4.4
 */
class Vc_Post_Admin {
	/**
	 * Add hooks required to save, update and manipulate post
	 */
	public function init() {
		// hooks for backend editor.
		add_action( 'save_post', [ $this, 'save' ] );
		// hooks for frontend editor.
		add_action( 'wp_ajax_vc_save', [ $this, 'save_front_editor' ] );
		add_action( 'wp_ajax_vc_preview', [ $this, 'preview_front_editor' ] );

		add_filter( 'content_save_pre', 'wpb_remove_custom_html' );

		add_filter( 'wp_trash_post', [
			$this,
			'check_empty_fields_for_trash',
		]);
		add_action( 'wp_ajax_vc_create_new_category', [ $this, 'create_new_category' ] );
		add_action( 'wp_ajax_vc_get_tags', [ $this, 'get_tags' ] );
	}

	/**
	 * Check if post title and content are empty and if yes set post title while moving to trash.
	 *
	 * @since 8.2
	 * @param int $post_id
	 * @return void
	 */
	public function check_empty_fields_for_trash( $post_id ) {
		if ( empty( get_the_title( $post_id ) ) && empty( get_the_content( $post_id ) ) ) {
			wp_update_post( [
				'ID' => $post_id,
				'post_title' => 'Auto Draft',
			] );
		}
	}

	/**
	 * Update post frontend editor ajax processing.
	 *
	 * @since 8.3
	 * @throws Exception
	 */
	public function save_front_editor() {
		$post_id = intval( vc_post_param( 'post_id' ) );
		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie()->canEdit( $post_id )->validateDie();

		if ( 0 === $post_id ) {
			wp_send_json_error();
		}

		$this->update_post_data( $post_id );

		wp_send_json_success();
	}

	/**
	 * Update post frontend editor ajax processing.
	 *
	 * @since 8.3
	 */
	public function preview_front_editor() {
		$post_id = intval( vc_post_param( 'post_id' ) );

		$autosave = wp_get_post_autosave( $post_id );

		if ( $autosave ) {
			$autosave_id = $autosave->ID;
		} else {
			$autosave_id = _wp_put_post_revision( $post_id, true );
		}

		if ( is_wp_error( $autosave_id ) ) {
			wp_send_json_error( $autosave_id->get_error_message() );
		} else {
			try {
				$this->update_post_data( $autosave_id, true );
			} catch ( Exception $e ) {
				wp_send_json_error( $e->getMessage() );
			}
		}

		wp_send_json_success();
	}

	/**
	 * Update post_content, title and etc.
	 *
	 * @since 7.4
	 * @param int $post_id
	 * @param bool $is_autosave
	 * @throws Exception
	 */
	public function update_post_data( $post_id, $is_autosave = false ) {
		ob_start();

		if ( ! vc_post_param( 'content' ) ) {
			return;
		}

		$post = get_post( $post_id );

		/**
		 * Filter post data before we update it with our plugin.
		 *
		 * @since 7.7
		 * @param WP_Post $post
		 */
		$post = apply_filters( 'vc_before_update_post_data', $post );

		$post = $this->set_post_content( $post );

		$post = $this->set_post_title( $post );

		$post = $this->set_post_status( $post );

		$post = $this->set_post_excerpt( $post );

		$post = $this->set_post_author( $post );

		$post = $this->set_post_comments( $post );

		$post = $this->set_post_pingbacks( $post );

		$post = $this->set_post_template( $post );

		$post = $this->set_post_featured_image( $post );

		$post = $this->set_post_categories( $post );

		$post = $this->set_post_tags( $post );

		if ( ! $is_autosave ) {
			$post = $this->set_post_name( $post );
		}

		if ( vc_user_access()->part( 'unfiltered_html' )->checkStateAny( true, null )->get() ) {
			kses_remove_filters();
		}
		remove_filter( 'content_save_pre', 'balanceTags', 50 );

		wp_update_post( $post );

		$this->setPostMeta( $post_id );

		wp_cache_flush();
		ob_clean();
	}

	/**
	 * Save plugin post meta and post fields.
	 *
	 * @param int $post_id
	 *
	 * @since 4.4
	 */
	public function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || vc_is_inline() ) {
			return;
		}
		$this->setPostMeta( $post_id );
	}

	/**
	 * Saves VC Backend editor meta box visibility status.
	 *
	 * If post param 'wpb_vc_js_status' set to true, then methods adds/updated post
	 * meta option with tag '_wpb_vc_js_status'.
	 *
	 * @param int $post_id
	 * @since 4.4
	 */
	public function setJsStatus( $post_id ) { // phpcs:ignore:WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$value = vc_post_param( 'wpb_vc_js_status' );
		if ( null === $value ) {
			delete_post_meta( $post_id, '_wpb_vc_js_status', get_post_meta( $post_id, '_wpb_vc_js_status', true ) );
		} elseif ( '' === get_post_meta( $post_id, '_wpb_vc_js_status' ) ) {
				add_post_meta( $post_id, '_wpb_vc_js_status', $value, true );
		} elseif ( get_post_meta( $post_id, '_wpb_vc_js_status', true ) !== $value ) {
			update_post_meta( $post_id, '_wpb_vc_js_status', $value );
		} elseif ( '' === $value ) {
			delete_post_meta( $post_id, '_wpb_vc_js_status', get_post_meta( $post_id, '_wpb_vc_js_status', true ) );
		}
	}

	/**
	 * Saves VC interface version which is used for building post content.
	 *
	 * @param int $post_id
	 * @since 4.4
	 * @todo check is it used everywhere and is it needed?!
	 * @deprecated 4.4
	 */
	public function setInterfaceVersion( $post_id ) { // phpcs:ignore
		_deprecated_function( '\Vc_Post_Admin::setInterfaceVersion', '4.4', '' );
	}

	/**
	 * Update post frontend editor ajax processing.
	 *
	 * @descripted 8.3
	 * @throws Exception
	 */
	public function saveAjaxFe() { // phpcs:ignore:WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		_deprecated_function( __FUNCTION__, '8.3', 'Vc_Post_Admin::save_front_editor' );
		$this->save_front_editor();
	}

	/**
	 * Set Post Settings meta for VC.
	 *
	 * It is possible to add any data to post settings by adding filter with tag 'vc_hooks_vc_post_settings'.
	 *
	 * @param int $post_id
	 * @since 4.4
	 * vc_filter: vc_hooks_vc_post_settings - hook to override
	 * post meta settings for WPBakery Page Builder (used in grid for example)
	 */
	public function setSettings( $post_id ) { // phpcs:ignore:WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$settings = [];

		$settings = $this->add_is_hide_title_setting( $settings );

		$settings = apply_filters( 'vc_hooks_vc_post_settings', $settings, $post_id, get_post( $post_id ) );
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			update_post_meta( $post_id, '_vc_post_settings', $settings );
		} else {
			delete_post_meta( $post_id, '_vc_post_settings' );
		}
	}

	/**
	 * Add is_hide_title setting to post settings meta.
	 *
	 * @since 8.2
	 * @param array $settings
	 * @return array
	 */
	public function add_is_hide_title_setting( $settings ) {
		$is_hide_post = vc_post_param( 'is_hide_title' );
		if ( null !== $is_hide_post ) {
			$settings['is_hide_title'] = 'true' === $is_hide_post;
		}

		return $settings;
	}

	/**
	 * Set post content.
	 *
	 * @since 7.4
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_content( $post ) {
		$post->post_content = stripslashes( vc_post_param( 'content' ) );

		return $post;
	}

	/**
	 * Set post title.
	 *
	 * @since 7.4
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_title( $post ) {
		$post_title = vc_post_param( 'post_title' );
		if ( null !== $post_title ) {
			$post->post_title = $post_title;
		}

		return $post;
	}

	/**
	 * Set post excerpt.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_excerpt( $post ) {
		$post_excerpt = vc_post_param( 'post_excerpt' );
		if ( null !== $post_excerpt ) {
			$post->post_excerpt = $post_excerpt;
		}

		return $post;
	}

	/**
	 * Set post author.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_author( $post ) {
		$post_author = vc_post_param( 'vc_post_author' );
		if ( null !== $post_author ) {
			$post->post_author = $post_author;
		}

		return $post;
	}

	/**
	 * Set post comments.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_comments( $post ) {
		$post_comments = vc_post_param( 'vc_post_comments' );
		if ( null !== $post_comments ) {
			$post->comment_status = 'true' === $post_comments ? 'open' : 'closed';
		}

		return $post;
	}

	/**
	 * Set post pingbacks.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_pingbacks( $post ) {
		$post_pingbacks = vc_post_param( 'vc_post_pingbacks' );
		if ( null !== $post_pingbacks ) {
			$post->ping_status = 'true' === $post_pingbacks ? 'open' : 'closed';
		}

		return $post;
	}

	/**
	 * Set post status.
	 *
	 * @since 7.4
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	public function set_post_status( $post ) {
		$post_status = vc_post_param( 'post_status' );
		if ( $post_status && 'publish' === $post_status ) {
			if ( vc_user_access()->wpAll( [
				get_post_type_object( $post->post_type )->cap->publish_posts,
				$post->ID,
			] )->get() ) {
				if ( 'private' !== $post->post_status && 'future' !== $post->post_status ) {
					$post->post_status = 'publish';
				}
			} else {
				$post->post_status = 'pending';
			}
		} elseif ( 'draft' === $post_status ) {
			$post->post_status = 'draft';
		}

		return $post;
	}

	/**
	 * Updates the post's template if specified in the POST request.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	protected function set_post_template( $post ) {
		$post_template = vc_post_param( 'vc_post_template' );
		if ( $post_template ) {
			update_post_meta( $post->ID, '_wp_page_template', $post_template );
		}
		return $post;
	}

	/**
	 * Set post featured image.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	protected function set_post_featured_image( $post ) {
		$featured_image = vc_post_param( 'vc_post_featured_image' );
		if ( $featured_image ) {
			set_post_thumbnail( $post->ID, $featured_image );
		} else {
			delete_post_thumbnail( $post->ID );
		}
		return $post;
	}

	/**
	 * Set post name, that acts as a post slug.
	 *
	 * @param WP_Post $post
	 * @return WP_Post $post
	 * @since 8.2
	 */
	protected function set_post_name( $post ) {
		$post_name = vc_post_param( 'post_name' );
		if ( null !== $post_name ) {
			$post->post_name = $post_name;
		}
		return $post;
	}

	/**
	 * Set post tags.
	 *
	 * @param WP_Post $post
	 * @return WP_Post $post
	 * @since 8.2
	 */
	protected function set_post_tags( $post ) {
		$post_tags = vc_post_param( 'vc_post_tags' );
		if ( ! is_array( $post_tags ) ) {
			return $post;
		}

		$tag_names = [];

		foreach ( $post_tags as $tag ) {
			if ( ! empty( $tag['id'] ) ) {
				$term = get_term( intval( $tag['id'] ), 'post_tag' );
				if ( $term && ! is_wp_error( $term ) ) {
					$tag_names[] = $term->name;
				}
			} elseif ( ! empty( $tag['name'] ) ) {
				$tag_names[] = sanitize_text_field( $tag['name'] );
			}
		}

		wp_set_post_tags( $post->ID, $tag_names );

		return $post;
	}

	/**
	 * Updates the post's categories if specified in the POST request.
	 *
	 * @since 8.2
	 * @param WP_Post $post
	 * @return WP_Post $post
	 */
	protected function set_post_categories( $post ) {
		$selected_categories = vc_post_param( 'vc_selected_categories' );
		if ( ! is_array( $selected_categories ) ) {
			return $post;
		}
		wp_set_post_categories( $post->ID, $selected_categories );
		return $post;
	}

	/**
	 * Handle the AJAX request for creating a new category.
	 *
	 * @since 8.2
	 */
	public function create_new_category() {
		if ( ! isset( $_POST['_vcnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_vcnonce'] ) ), 'vc-nonce-vc-admin-nonce' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'js_composer' ) ] );
		}
		$category_name = isset( $_POST['category_name'] ) ? sanitize_text_field( wp_unslash( $_POST['category_name'] ) ) : '';
		$parent_id = isset( $_POST['vc_new-category-parent'] ) ? intval( $_POST['vc_new-category-parent'] ) : 0;

		if ( empty( $category_name ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid category name.', 'js_composer' ) ] );
		}

		$category_id = wp_create_category( $category_name, $parent_id );

		if ( is_wp_error( $category_id ) ) {
			wp_send_json_error( [ 'message' => $category_id->get_error_message() ] );
		}

		wp_send_json_success( [
			'id' => $category_id,
			'name' => $category_name,
		] );
	}

	/**
	 * Handle the AJAX request for getting matched tags.
	 *
	 * @since 8.2
	 */
	public function get_tags() {
		if ( ! isset( $_POST['_vcnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_vcnonce'] ) ), 'vc-nonce-vc-admin-nonce' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'js_composer' ) ] );
		}

		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		$tags = get_terms( [
			'taxonomy' => 'post_tag',
			'hide_empty' => false,
			'search' => $search,
		] );

		if ( is_wp_error( $tags ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Error fetching tags.', 'js_composer' ) ] );
		}

		$response = array_map( function ( $tag ) {
			return [
				'id' => $tag->term_id,
				'name' => $tag->name,
			];
		}, $tags );

		wp_send_json_success( $response );
	}

	/**
	 * Set plugin meta to specific post.
	 *
	 * @since 8.2
	 * @param int $id
	 * @throws Exception
	 */
	protected function setPostMeta( $id ) { // phpcs:ignore:WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		if ( ! vc_user_access()->wpAny( [
			'edit_post',
			$id,
		] )->get() ) {
			return;
		}

		$this->setJsStatus( $id );

		// Get the appropriate ID (revision or original post).
		$id = $this->get_latest_revision_id( $id );

		if ( 'dopreview' !== vc_post_param( 'wp-preview' ) ) {
			$this->setSettings( $id );
		}

		$meta_list = $this->get_post_meta_list();
		$this->setPostMetaByList( $id, $meta_list );

		$types = [
			'default',
			'custom',
		];
		foreach ( $types as $type ) {
			wpbakery()->buildShortcodesCss( $id, $type );
		}
	}

	/**
	 * Returns the latest revision ID if the post is in preview mode; otherwise, the original post ID.
	 *
	 * @param int $post_id Post ID to check.
	 * @return int Latest revision ID or original post ID.
	 */
	public function get_latest_revision_id( $post_id ) {
		// Return the original post ID if not in preview mode.
		if ( 'dopreview' !== vc_post_param( 'wp-preview' ) ) {
			return $post_id;
		}

		// Return the original post ID if revisions are not enabled.
		if ( ! wp_revisions_enabled( get_post( $post_id ) ) ) {
			return $post_id;
		}

		// Retrieve and return the latest revision ID if available.
		$latest_revision = wp_get_post_revisions( $post_id );
		if ( ! empty( $latest_revision ) ) {
			$array_values = array_values( $latest_revision );
			return $array_values[0]->ID;
		}

		// Default to the original post ID.
		return $post_id;
	}

	/**
	 * Get post meta list.
	 *
	 * @since 7.0
	 *
	 * @return array
	 */
	public function get_post_meta_list() {
		// we add value to it in our modules.
		return apply_filters( 'vc_post_meta_list', [] );
	}

	/**
	 * Set post meta by meta list.
	 *
	 * @note we keep this data for meta in regular $_POST
	 * @see include/templates/editors/partials/vc_post_custom_meta.tpl.php
	 * @note we also additionally save data for frontend editor in ajax request to push it in $_POST
	 * and save it than in that method
	 * @see assets/js/frontend_editor/shortcodes_builder.js ShortcodesBuilder::save()
	 * @since 7.0
	 *
	 * @param int $id
	 * @param array $meta_list
	 */
	public function setPostMetaByList( $id, $meta_list ) { // phpcs:ignore:WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		foreach ( $meta_list as $meta_name ) {
			$post_param = vc_post_param( 'vc_post_' . $meta_name );
			// here we add validation for meta value.
			$value = apply_filters( 'vc_base_save_post_' . $meta_name, $post_param, $id );
			if ( null !== $value && empty( $value ) ) {
				delete_metadata( 'post', $id, '_wpb_post_' . $meta_name );
			} elseif ( null !== $value ) {
				update_metadata( 'post', $id, '_wpb_post_' . $meta_name, $value );
			}
		}
	}
}
