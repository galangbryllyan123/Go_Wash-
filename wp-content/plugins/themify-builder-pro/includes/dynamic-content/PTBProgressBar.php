<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBProgressBar extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'number', 'range' );
	}

	public static function get_label():string {
		return __( 'PTB (Progress Bar)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'value';
			list( $post_type, $field_name, $option_id ) = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( $show === 'label' ) {
				$ptb = PTB::get_option()->get_options();
				if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] ) ) {
					$lang = PTB_Utils::get_current_language_code();
					foreach( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] as $option ) {
						if ( $option_id === $option['id'] ) {
							$value = $option[ $lang ];
						}
					}
				}
			} else {
				if ( isset( $cf_value[ $option_id ] ) ) {
					$value = $cf_value[ $option_id ];
				}
			}
		}

		return $value;
	}

	public static function get_options():array {
		$options = array();

		/* collect "progress_bar" field types in all post types */
		$post_types = Themify_Builder_Model::get_public_post_types();
		$lang = PTB_Utils::get_current_language_code();
		foreach ( $post_types as $post_type => $post_type_label ) {
			$ptb_fields = PTB::$options->get_cpt_cmb_options( $post_type );
			if ( ! empty( $ptb_fields ) ) {
				foreach ( $ptb_fields as $key => $field ) {
					if ( $field['type'] === 'progress_bar' && is_array( $field['options'] ) ) {
						$field_name = PTB_Utils::get_label( $field['name'] );
						foreach ( $field['options'] as $option ) {
							$options[ "{$post_type}:{$key}:{$option['id']}" ] = sprintf( '%s: %s: %s', $post_type_label, $field_name, $option[ $lang ] );
						}
					}
				}
			}
		}

		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => $options,
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'value' => __( 'Value', 'tbp' ),
					'label' => 'label'
				),
			),
		);
	}
}