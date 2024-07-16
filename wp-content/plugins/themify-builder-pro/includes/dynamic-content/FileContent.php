<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_FileContent extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'advanced';
	}

	public static function get_type():array {
		return array( 'textarea', 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'File Content', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['path'] ) ) {
		    $file = trailingslashit( ABSPATH ) . $args['path'];
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		    WP_Filesystem();
		    global $wp_filesystem;
		    if ( $wp_filesystem->is_file( $file ) ) {
			    $value = $wp_filesystem->get_contents( $file );
		    }
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'id' => 'path',
				'type' => 'text',
				'label' => __( 'File Path', 'tbp' ),
				'class' => 'large',
				'help' =>  sprintf( __( 'To display content from text file, enter the path. Path is started from: %s', 'tbp' ), trailingslashit( ABSPATH ) ),
			),
		);
	}
}
