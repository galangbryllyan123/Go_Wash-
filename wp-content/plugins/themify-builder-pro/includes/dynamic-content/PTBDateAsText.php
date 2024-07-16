<?php
/**
 * Maps PTB's "date" field type to "text" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBDateAsText extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_label():string {
		return __( 'PTB (Date)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		$args = wp_parse_args( $args, array(
			'show' => 'start_date',
			'date_format' => 'F j, Y',
			'custom_date_format' => '',
		) );

		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] )[1];
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );

			// 'range' date fields
			if ( is_array( $value ) ) {
				$value = $value[ $args['show'] ];
			}
		}

		if ( ! empty( $value ) ) {
			$date_format = $args['date_format'] === 'custom' ? $args['custom_date_format'] : $args['date_format'];
			$timestamp = strtotime( get_gmt_from_date( $value ) );
			$value = wp_date( $date_format, $timestamp );
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'event_date' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'start_date' => 'startat',
					'end_date' => 'endat'
				),
			),
			array(
				'label' => 'd_f',
				'id' => 'date_format',
				'type' => 'select',
				'options' => array(
					'F j, Y'       => 'F_j_Y',
					'Y-m-d'        => 'Y_m_d',
					'm/d/Y'        => 'm_d_Y',
					'd/m/Y'        => 'd_m_Y',
					'l, F jS, Y'   => date_i18n( 'l, F jS, Y' ),
					'F j, Y g:i a' => date_i18n( 'F j, Y g:i a' ),
					'M j, Y @ G:i' => date_i18n( 'M j, Y @ G:i' ),
					'custom'       => 'cus'
				),
				'binding' =>array(
				  'not_empty' => array( 'hide' => 'custom_date_format' ),
				  'custom' => array( 'show' => 'custom_date_format' )
				)
			),
			array(
				'label' => 'cus_f',
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'tbp' ), 'https://wordpress.org/support/article/formatting-date-and-time/' )
			),
		);
	}
}