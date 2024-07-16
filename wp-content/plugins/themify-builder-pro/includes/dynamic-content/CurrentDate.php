<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_CurrentDate extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'date' );
	}

	public static function get_label():string {
		return __( 'Current Date & Time', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$args = wp_parse_args( $args, array(
			'date_format' => 'F j, Y',
			'custom_date_format' => '',
		) );
		return $args['date_format'] === 'custom' ? date_i18n( $args['custom_date_format'] ):date_i18n( $args['date_format'] );
	}

	public static function get_options():array {
		return array(
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
				  'not_empty' => array( 'hide' => 'custom_date_format'  ),
				  'custom' => array( 'show' =>  'custom_date_format' )
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
