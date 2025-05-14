<?php
/**
 * Provide basic functionality for compatibility with ACF vendor.
 *
 * @see https://wordpress.org/plugins/advanced-custom-fields
 *
 * @since 8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Wpb_Acf_Provider.
 *
 * @since 8.1
 */
class Wpb_Acf_Provider {
	/**
	 * Get field value.
	 *
	 * @since 8.1
	 * @param string $field_key
	 * @param int|false $post_id
	 *
	 * @return scalar
	 */
	public function get_field_value( $field_key, $post_id = false ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( $this->is_acf_version( '6.3.0' ) && ! acf_get_setting( 'enable_shortcode' ) ) {
			$value = get_field( $field_key, $post_id );

			if ( is_array( $value ) ) {
				$value = implode( ', ', $value );
			}

			if ( ! is_scalar( $value ) ) {
				$value = false;
			}
		} else {
			$value = do_shortcode( '[acf field="' . $field_key . '" post_id="' . $post_id . '"]' );
		}

		return $value;
	}

	/**
	 * Check ACF version.
	 *
	 * @since 8.1
	 * @param string $version
	 *
	 * @return bool
	 */
	public function is_acf_version( $version ) {
		if ( ! function_exists( 'acf_version_compare' ) || ! function_exists( 'acf_get_db_version' ) ) {
			return false;
		}

		if ( acf_version_compare( acf_get_db_version(), '>=', $version ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get field groups.
	 *
	 * @since 8.3
	 * @return array
	 */
	public function get_field_groups() {
		$groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : apply_filters( 'acf/get_field_groups', [] );
		$groups_param_values = $fields_params = [];
		foreach ( (array) $groups as $group ) {
			$id = isset( $group['id'] ) ? 'id' : ( isset( $group['ID'] ) ? 'ID' : 'id' );
			$groups_param_values[ $group['title'] ] = $group[ $id ];
			$fields = function_exists( 'acf_get_fields' ) ? acf_get_fields( $group[ $id ] ) : apply_filters( 'acf/field_group/get_fields', [], $group[ $id ] );
			$fields_param_value = [];
			foreach ( (array) $fields as $field ) {
				if ( '' === $field['label'] ) {
					$field['label'] = __( '(no label)', 'acf' );
				}

				$fields_param_value[ $field['label'] ] = (string) $field['key'];
			}
			$fields_params[] = [
				'type' => 'dropdown',
				'heading' => esc_html__( 'Field name', 'js_composer' ),
				'param_name' => 'field_from_' . $group[ $id ],
				'value' => $fields_param_value,
				'save_always' => true,
				'description' => esc_html__( 'Choose field from group.', 'js_composer' ),
				'dependency' => [
					'element' => 'field_group',
					'value' => [ (string) $group[ $id ] ],
				],
			];
		}

		return [
			'groups_param_values' => $groups_param_values,
			'fields_params' => $fields_params,
		];
	}

	/**
	 * Get ACF shortcode params.
	 *
	 * @since 8.3
	 * @return array
	 */
	public function get_shortcode_params() {
		$groups = $this->get_field_groups();
		return array_merge( [
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Field group', 'js_composer' ),
				'param_name' => 'field_group',
				'value' => $groups['groups_param_values'],
				'save_always' => true,
				'description' => esc_html__( 'Select field group.', 'js_composer' ),
			],
		], $groups['fields_params'], [
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show label', 'js_composer' ),
				'param_name' => 'show_label',
				'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
				'description' => esc_html__( 'Enter label to display before key value.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Align', 'js_composer' ),
				'param_name' => 'align',
				'value' => [
					esc_attr__( 'left', 'js_composer' ) => 'left',
					esc_attr__( 'right', 'js_composer' ) => 'right',
					esc_attr__( 'center', 'js_composer' ) => 'center',
					esc_attr__( 'justify', 'js_composer' ) => 'justify',
				],
				'description' => esc_html__( 'Select alignment.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		] );
	}
}
