<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */

class Tbp_Dynamic_Query_Tep {

	static function get_id():string {
		return 'tep';
	}

	static function get_label():string{
		return __( 'Themify Event Posts', 'tbp' );
	}

	static function get_options():array {
		return array(
			array(
				'id' => 'tep_event_status',
				'type' => 'select',
				'label' => __( 'Event Status', 'tbp' ),
				'options' => [
					'' => __( 'All Events', 'tbp' ),
					'upcoming' => __( 'Upcoming Events', 'tbp' ),
					'past' => __( 'Past Events', 'tbp' ),
				]
			),
			array(
				'id' => 'tep_order',
				'type' => 'select',
				'label' => __( 'Event Order', 'tbp' ),
				'options' => [
					'' => 'def',
					'start' => __( 'Start Date', 'tbp' ),
					'end' => __( 'End Date', 'tbp' ),
				]
			),
		);
	}

	static function pre_get_posts( &$query, ?array $settings = [] ):bool {
		$query->set( 'post_type', 'event' );

		if ( ! empty( $settings['tep_order'] ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', $settings['tep_order'] === 'start' ? 'start_date' : 'end_date' );
		}

		if ( $settings['tep_event_status'] === 'upcoming' ) {
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => 'end_date',
					'value' => date_i18n( 'Y-m-d H:i' ),
					'compare' => '>='
				),
				array(
					'key' => 'start_date',
					'value' => date_i18n( 'Y-m-d H:i' ),
					'compare' => '>='
				),
				array(
					'key' => 'repeat',
					'value' => 'none',
					'compare' => '!=')
			) );
		} elseif ( $settings['tep_event_status'] === 'past' ) {
			$query->set( 'meta_query', array(
				'relation' => 'AND',
				array(
					'key' => 'end_date',
					'value' => date_i18n( 'Y-m-d H:i' ),
					'compare' => '<'
				),
				array(
					'key' => 'end_date',
					'value' => '',
					'compare' => '!='
				),
			) );
		}

		return true;
	}
}