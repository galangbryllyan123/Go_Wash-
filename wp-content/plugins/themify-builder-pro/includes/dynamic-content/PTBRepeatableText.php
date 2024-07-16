<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRepeatableText extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb' ,false);
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'PTB (Repeatable Text)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$output = '';
		if (!empty( $args['field'] ) ) {
			$args+= array(
				'icon' => '',
				'color' => '',
			);
			$field_name = explode( ':', $args['field'] )[1];
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if(!empty( $value ) && is_array($value)){
				// repeatable Text fields
				foreach ( $value as $icon_text ) {
					if(!empty($icon_text)){
						$color = ! empty( $args['color'] ) ? ' style="color: ' . $args['color'] . '"' : '';
						$output .= '<div class="module-icon-item">';
                            if ( $args['icon'] !== '' ) {
                                $output .=  '<span class="tbp_icon"' . $color . '>' . themify_get_icon( $args['icon'] ) . '</span> ';
                            }
							$output .= $icon_text;
						$output .= '</div>';
					}
				}
				if($output!==''){
					$output = '<div class="module-icon icon_vertical">'.$output.'</div>';
				}
			}
		}
		return $output;
	}

	public static function get_options():array {
		$options = array();

		/* collect "text" field types in all post types */
		$post_types = Themify_Builder_Model::get_public_post_types();
		foreach ( $post_types as $post_type => $post_type_label ) {
			$ptb_fields = PTB::$options->get_cpt_cmb_options( $post_type );
			if ( ! empty( $ptb_fields ) ) {
				foreach ( $ptb_fields as $key => $field ) {
					if ( $field['type'] === 'text' && ! empty( $field['repeatable'] ) ) {
						$name = PTB_Utils::get_label( $field['name'] );
						$options[ "{$post_type}:{$key}" ] = sprintf( '%s: %s', $post_type_label, $name );
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
				'label' => 'icon',
				'id' => 'icon',
				'type' => 'icon',
			),
			array(
				'id' => 'color',
				'type' => 'color',
				'label' => __( 'Icon Color', 'tbp' ),
			),
		);
	}
}
