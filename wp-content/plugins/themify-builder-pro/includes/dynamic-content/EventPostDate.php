<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_EventPostDate extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return function_exists( 'themify_event_post_date' );
	}

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Themify Event Post - Event Date', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		if ( ! isset( $args['date_format'] ) || $args['date_format'] === 'default' ) {
		    $date_format = get_option( 'date_format' );
	    } elseif ( $args['date_format'] === 'custom' ) {
		    $date_format = isset( $args['custom_date_format'] ) ? $args['custom_date_format'] : '';
	    } else {
		    $date_format = $args['date_format'];
	    }
		if ( ! isset( $args['time_format'] ) || $args['time_format'] === 'default' ) {
		    $time_format = get_option( 'time_format' );
	    } elseif ( $args['time_format'] === 'custom' ) {
		    $time_format = isset( $args['custom_time_format'] ) ? $args['custom_time_format'] : '';
	    } else {
		    $time_format = $args['time_format'];
	    }

		ob_start();
		themify_event_post_date( $date_format, $time_format );
		return ob_get_clean();
	}

	public static function get_options():array {
		$custom_format_help = sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'tbp' ), 'https://wordpress.org/support/article/formatting-date-and-time/' );

		return array(
			array(
				'label' => 'd_f',
				'id' => 'date_format',
				'type' => 'select',
				'options' => array(
					'default'      => 'def',
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
				  'not_empty'=>array('hide'=>'custom_date_format'),
				  'custom'=>array('show'=>'custom_date_format')
				)
			),
			array(
				'label' => __( 'Custom Date Format', 'tbp' ),
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => $custom_format_help,
			),
			array(
				'label' => 't_f',
				'id' => 'time_format',
				'type' => 'select',
				'options' => array(
					'default' => 'def',
					'g:i a' => 'g_i_a',
					'g:i A'  => 'g_i_A',
					'H:i'  => 'H_i',
					'custom' => 'cus'
				),
				'binding' =>array(
				  'not_empty'=>array('hide'=>'custom_time_format'),
				  'custom'=>array('show'=>'custom_time_format')
				)
			),
			array(
				'label' => __( 'Custom Time Format', 'tbp' ),
				'id' => 'custom_time_format',
				'type' => 'text',
				'help' => $custom_format_help,
			),
		);
	}
}
