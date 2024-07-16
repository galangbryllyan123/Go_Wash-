<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostDate extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Post Published Date', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
	    if ( ! isset( $args['date_format'] ) || $args['date_format'] === 'default' ) {
		    $date_format = get_option( 'date_format' );
	    } elseif ( $args['date_format'] === 'custom' ) {
		    $date_format = $args['custom_date_format']??'';
	    } else {
		    $date_format = $args['date_format'];
	    }

		return get_the_date( $date_format, $args['post_id']??get_the_ID());
	}

	public static function get_options():array {
		return array(
			array(
				'label' =>'d_f',
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
				'label' => 'cus_f',
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'tbp' ), 'https://wordpress.org/support/article/formatting-date-and-time/' )
			),
		);
	}
}
