<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBSelect extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_label():string {
		return __( 'PTB (Select)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = array();
		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			$ptb = PTB::get_option()->get_options();
			if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] ) ) {
				foreach( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] as $option ) {
				    if ( $option['id'] === $cf_value || (is_array($cf_value) && in_array($option['id'],$cf_value)) ) {
						$value[] = PTB_Utils::get_label($option);
					}
				}
			}
		}

		return implode(', ',$value);
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'select' ),
			),
		);
	}
}