<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorMeta extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}


	public static function get_label():string {
		return __( 'Post Author Meta', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$user_id = get_post_field( 'post_author', get_the_ID() );
			if ( ! empty( $user_id ) ) {
				$value = get_user_meta( $user_id, $args['key'], true );
			}
		}
		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => __( 'Meta Key', 'tbp' ),
				'id' => 'key',
				'type' => 'text',
				'help' => __( 'Any user meta field can be displayed. To display user Biographical Info, set this option to <code>description</code>.', 'tbp' )
			),
		);
	}
}
