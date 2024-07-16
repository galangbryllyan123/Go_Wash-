<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link 
 */
class Themify_Builder_Plugin_Compat_ptb {

	static function init() {}
	
	/**
	 * Return PTB custom fields of certain type(s) across all PTB post types
	 *
	 * @param $type string|array
	 * @return array
	 */
	public static function get_fields_by_type($type): array {
		$options = array();
		$type = (array) $type;
		foreach ( self::get_public_or_ptb_types() as $post_type => $post_type_label) {
			$ptb_fields = PTB::$options->get_cpt_cmb_options($post_type);
			if (!empty($ptb_fields)) {
				foreach ($ptb_fields as $key => $field) {
					if (in_array($field['type'], $type, true)) {
						$name = PTB_Utils::get_label($field['name']);
						$options["{$post_type}:{$key}"] = sprintf('%s: %s', $post_type_label, $name);
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Return a list of post types that are either Public, or registered by PTB
	 */
	private static function get_public_or_ptb_types() : array {
		static $post_types = null;
		if ( $post_types === null ) {
			$post_types = Themify_Builder_Model::get_public_post_types();
			/* always include all PTB types */
			foreach ( PTB::get_option()->get_custom_post_types() as $ptb_post_type => $ptb_post_type_data ) {
				if ( ! isset( $post_types[ $ptb_post_type ] ) ) {
					$post_types[ $ptb_post_type ] = PTB_Utils::get_label( $ptb_post_type_data->plural_label );
				}
			}
		}

		return $post_types;
	}
}

/**
 * Utility class to loop through PTB repeatable fields
 * This should be moved to PTB plugin at some point.
 */
class PTB_Repeater_Field {

	public static $repeater_index = -1;
	public static $in_the_loop = false;

	public static function have_rows(?string $field ):bool {
		$post_id = get_the_ID();
		if ( $post_id === false ) {
			return false;
		}
		list(, $field_name, $field_type ) = explode( ':', $field );
		$value = get_post_meta( $post_id, "ptb_{$field_name}", true );
		if ( $field_type === 'accordion' ) {
			$index_key = 'body';
		} elseif ( $field_type === 'text' ) {
			/* normalize Repeatable Text field value */
			$value = [ 0 => $value ];
			$index_key = 0;
		} else {
			/* url is the array key used by Audio, Video, File, Gallery and Slider fields */
			$index_key = 'url';
		}
		if ( empty( $value[ $index_key ] ) ) {
			return false;
		}
		$count = count( $value[ $index_key ] );
		if ( self::$repeater_index + 1 === $count ) {
			self::$repeater_index = -1; /* reset index */
			return false;
		}
		if ( ! empty( $value[ $index_key ][ self::$repeater_index + 1 ] ) ) {
			return true;
		}
		return false;
	}

	public static function the_row() {
		self::$repeater_index++;
	}

    public static function get_index() {
        return self::$repeater_index;
    }
}