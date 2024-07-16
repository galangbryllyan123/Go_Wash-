<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://themify.me/ptb-addons/search
 */

class Tbp_Dynamic_Query_PtbSearch {

	static function get_id():string {
		return 'ptbSearch';
	}

	static function get_label():string {
		return __( 'PTB Search', 'tbp' );
	}

	static function get_options():array {
		$options = [];
		$ptb_options = PTB::get_option();
		if ( ! empty( $ptb_options->option_post_type_templates ) ) {
            foreach ( $ptb_options->option_post_type_templates as $id => $t ) {
                if ( isset( $t['search'] ) && isset( $t['post_type'] ) ) {
                    $options[ sanitize_key( $id ) ] = $t['title'];
                }
			}
        }

		return [
			array(
				'id' => 'ptbsearch_form',
				'type' => 'select',
				'label' => __( 'PTB Search Form', 'tbp' ),
				'options' => $options,
			),
		];
	}

	static function pre_get_posts( &$query, ?array $settings = [] ):bool {
		/* safety check for PTB Search 2.0.1 */
		if ( ! method_exists( 'PTB_Search_Public', 'get_active_form' ) ) {
			return false;
		}

		$slug = isset( $settings['ptbsearch_form'] ) ? $settings['ptbsearch_form'] : null;
		if ( ! $post_type = self::get_post_type_from_form( $slug ) ) {
			return false;
		}

		$active_form = PTB_Search_Public::get_active_form();
		/* show default list of posts */
		if ( empty( $active_form['slug'] ) || $slug !== $active_form['slug'] ) {
			$query->set( 'post_type', $post_type );
			return true;
		}
		$result = PTB_Search_Public::get_instance()->ptb_shortcode_args( [
			'post_type' => $active_form['post_type'],
			'posts_per_page' => (int) $query->get( 'posts_per_page' ),
		] );

		foreach ( $result['query']->query as $query_arg => $value ) {
			$query->set( $query_arg, $value );
		}
		$query->set( 'post_status', 'publish' );

		return true;
	}

	/**
	 * Finds the post type associated with a PTB Search form slug
	 */
	private static function get_post_type_from_form( ?string $slug ):string {
		$ptb_options = PTB::get_option();
		/* selected PTB Search form doesn't exist, bail */
        return isset( $ptb_options->option_post_type_templates[ $slug ] )?$ptb_options->option_post_type_templates[ $slug ]['post_type']:'';
	}

	/**
	 * Add HTML data parameters to module when PTB Search dynamic is active
	 *
	 * @return array
	 */
	public static function module_container_props(array &$attr,array $module_settings,string $mod_name,array $dynamic_query_settings ) {
		if ( isset( $dynamic_query_settings['ptbsearch_form'] ) ) {
            $post_type = self::get_post_type_from_form( $dynamic_query_settings['ptbsearch_form'] );
            if ( $post_type !== '' ) {
                $attr['data-ptb-search-posttype'] = $post_type;
            }
        }
	}
}