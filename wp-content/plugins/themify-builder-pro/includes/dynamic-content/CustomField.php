<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_CustomField extends Tbp_Dynamic_Item {

    public static function get_category():string {
        return 'advanced';
    }

    public static function get_type():array {
        return array( 'text', 'textarea', 'image', 'wp_editor', 'url', 'custom_css', 'address', 'audio', 'video','slider_range' );
    }

    public static function get_label():string {
        return __( 'Custom Field', 'tbp' );
    }

    public static function get_value(array $args = array()):?string {
        $value = '';
        if ( ! empty( $args['custom_field'] ) ) {
            $value = get_post_meta( $args['post_id']??get_the_ID(), $args['custom_field'], true );
            if($value!==''){
                if ( is_array( $value ) ) {
                    $value = current_user_can( 'manage_options' )?__( 'The data in this custom field is probably serialized and thus cannot be displayed directly.', 'tbp' ):'';
                } else {
					$type = isset( $args['type'] ) ? $args['type'] : 's';
                    if ( $type === 's' && is_string( $value ) && isset( $args['custom_field_shortcode'] ) && $args['custom_field_shortcode'] === 'yes') {
                        $value = do_shortcode( $value );
                    } elseif ( $type === 'n' && isset( $args['decimals'] ) ) {
                        $value = number_format( (float)$value, (int) $args['decimals'], $args['dec_point']?? '.', $args['thousands_sep']?? '' );
                    } elseif ( $type === 'd' && isset( $args['date_format'] ) && $args['date_format'] !== 'default' ) {
                        if ( $args['date_format'] === 'custom' ) {
                            $date_format = $args['custom_date_format']??'';
                        } else {
                            $date_format = $args['date_format'];
                        }
                        $value = wp_date( $date_format, strtotime( get_gmt_from_date( $value ) ) );
                    }
                }
            }
        }

        return $value;
    }

    public static function get_options():array {
        return array(
            array(
                'label' => 'cfield',
                'id' => 'custom_field',
                'type' => 'autocomplete',
                'dataset' => 'custom_fields',
            ),
            array(
                'label' => __('Data Type', 'tbp'),
                'id' => 'type',
                'type' => 'select',
                'options' => [
                    's' => __( 'Text', 'tbp' ),
                    'n' => __( 'Number', 'tbp' ),
                    'd' => __( 'Date', 'tbp' ),
                ],
                'binding' => [
                    's' => [
                        'show' => [ 'custom_field_shortcode' ],
                        'hide' => [ 'date_format', 'custom_date_format', 'decimals', 'dec_point', 'thousands_sep' ]
                    ],
                    'n' => [
                        'show' => [ 'decimals', 'dec_point', 'thousands_sep' ],
                        'hide' => [ 'custom_field_shortcode', 'date_format', 'custom_date_format' ]
                    ],
                    'd' => [
                        'show' => [ 'date_format', 'custom_date_format' ],
                        'hide' => [ 'custom_field_shortcode', 'decimals', 'dec_point', 'thousands_sep' ]
                    ]
                ],
                'default' => 's'
            ),
            array(
                'label' => __( 'Enable Shortcodes', 'tbp' ),
                'id' => 'custom_field_shortcode',
                'type' => 'select',
                'choose' => true,
                'help' => __( 'Enable parsing shortcodes on the custom field value.', 'tbp' ),
            ),
            ...Tbp_Dynamic_Item_PostDate::get_options(),
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
            array(
                'label' => 'pstid',
                'id' => 'post_id',
                'type' => 'number',
                'help' => 'tbp_pstidh'
            ),
        );
    }
}
