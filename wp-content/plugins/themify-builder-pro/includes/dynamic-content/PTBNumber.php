<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBNumber extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor','slider_range' );
	}

	public static function get_label():string {
		return __( 'PTB (Number)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		if(!empty($args['field'])){
			$field_name = explode( ':', $args['field'] )[1];
			$value = get_post_meta(get_the_ID(), "ptb_{$field_name}", true );
			$display = $args['display']?? 'from';
			if ( is_array( $value ) ) { // range input
				$value = $value[ $display ];
			}
			
			if ( isset( $args['decimals'] ) ) {
				$value = number_format( (float)$value, (int) $args['decimals'], $args['dec_point']?? '.', $args['thousands_sep']?? '' );
			}
		}
		return $value;
	}

	public static function get_options():array {
        $binding = [];
        $fields = Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'number' );
        foreach ( $fields as $field_key => $field_label ) {
            list( $post_type, $field_id ) = explode( ':', $field_key );
            $field_def = ptb_get_field_definition( $field_id, $post_type );
            $binding[ $field_key ] = [ ( isset( $field_def['range'] ) ? 'show' : 'hide' ) => 'display' ];
        }

		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => $fields,
                'binding' => $binding
			),
			array(
				'label' => 'disp',
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'from' => __( 'From', 'tbp' ),
					'to' => __( 'To', 'tbp' ),
				),
				'help' => __( 'In Range inputs, select to display the From value or To value.', 'tbp' ),
			),
			array(
				'label' => __( 'Decimals', 'tbp' ),
				'id' => 'decimals',
				'type' => 'number',
                'binding' => [
                    'empty' => [ 'hide' => [ 'dec_point', 'thousands_sep' ] ],
                    'not_empty' => [ 'show' => [ 'dec_point', 'thousands_sep' ] ]
                ],
                'help' => __( 'Sets the number of decimal digits.', 'tbp' )
			),
			array(
				'label' => __( 'Decimal Point', 'tbp' ),
				'id' => 'dec_point',
				'type' => 'text',
                'help' => __( 'Sets the separator for the decimal point.', 'tbp' )
			),
			array(
				'label' => __( 'Thousands Separator', 'tbp' ),
				'id' => 'thousands_sep',
				'type' => 'text',
			),
		);
	}
}