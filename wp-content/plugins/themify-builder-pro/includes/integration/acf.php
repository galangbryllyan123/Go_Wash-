<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://wordpress.org/plugins/advanced-custom-fields/
 */
class Themify_Builder_Plugin_Compat_acf {

	static function init() {
		add_filter( 'tb_select_dataset_acf_fields', array( __CLASS__, 'acf_fields' ), 10, 2 );
	}
	/**
	 * List of custom fields registered by ACF plugin, filtered by $type
	 */
	public static function get_fields_by_type($type):array {
		$support_types=[];
		foreach((array) $type as $t){
			$support_types[$t]=true;
		}
		$field_groups = acf_get_field_groups();
		$options = array();
		foreach ($field_groups as $field_group) {
			$fields = acf_get_fields($field_group['ID']);
			foreach ($fields as $field) {
				if (!empty($field['name'])) {
					if (isset($support_types[$field['type']])) {
						$options["{$field_group['key']}:{$field['name']}"] = sprintf('%s: %s', $field_group['title'], $field['label']);
					} 
					elseif (( $field['type'] === 'repeater' || $field['type'] === 'group') && !empty($field['sub_fields'])) {
						foreach ($field['sub_fields'] as $subfield) {
							if (isset($support_types[$subfield['type']])) {
								$options[$field['type'].":{$field['name']}:{$subfield['name']}"] = sprintf('%s: %s: %s', $field_group['title'], $field['label'], $subfield['label']);
							}
						}
					}
				}
			}
		}

		return $options;
	}

	
	public static function get_field_value(array $args) {
		$value = '';
		$pieces = explode(':', $args['key']);
		if ($pieces[0] === 'repeater') {
			$value = get_sub_field($pieces[2]);
		} elseif ($pieces[0] === 'group') {
			/* Group fields are stored as an array */
			$group = get_field($pieces[1], self::get_context($args));
			if (isset($group[$pieces[2]])) {
				$value = $group[$pieces[2]];
			}
		} else {
			$value = get_field($pieces[1], self::get_context($args));
		}

		return $value;
	}

	/**
	 * Returns correct meta type for get_field() function in ACF
	 * Used by ACF Dynamic Content items.
	 *
	 * @return mixed
	 */
	public static function get_context(array $args) {
		$post_id = null;
		if (!empty($args['acf_ctx'])) {
			if ($args['acf_ctx'] === 'term') {
				$term = get_queried_object();
				$post_id = $term;
			} elseif ($args['acf_ctx'] === 'user') {
				$user = get_current_user_id();
				$post_id = 'user_' . $user;
			} elseif ($args['acf_ctx'] === 'author') {
				global $post;
				$post_id = 'user_' . $post->post_author;
			} elseif ($args['acf_ctx'] === 'option') {
				$post_id = 'option';
			} elseif ($args['acf_ctx'] === 'custom' && !empty($args['acf_ctx_c'])) {
				$post_id = $args['acf_ctx_c'];
			}
		}

		return $post_id;
	}

	/**
	 * Populate the ACF field selects
	 */
	public static function acf_fields( $data, $post_id ):array {
		$args = json_decode( stripslashes( $_POST['args'] ), true );
		return self::get_fields_by_type( $args['type'] );
	}
}