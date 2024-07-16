<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRadioButton extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_label():string {
		return __( 'PTB (Radio Button)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			$ptb = PTB::get_option()->get_options();
			if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] ) ) {
				$lang = PTB_Utils::get_current_language_code();
				foreach( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] as $option ) {
					if ( $option['id'] === $cf_value ) {
						$value = $option[ $lang ];
					}
				}
			}
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'radio_button' ),
			),
		);
	}
}