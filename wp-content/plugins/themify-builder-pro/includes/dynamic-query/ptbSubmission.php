<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://themify.me/ptb-addons/submissions
 */
class Tbp_Dynamic_Query_PtbSubmission {

	static function get_id():string {
		return 'ptbSubmission';
	}

	static function get_label():string {
		return __( 'PTB Submissions by current user', 'tbp' );
	}

	static function get_options():array {
		return array(
            array(
				'id' => 'ptbSubmission_post_type',
				'type' => 'select',
				'label' => __( 'Post Type', 'tbp' ),
				'options' => Themify_Builder_Model::get_public_post_types(),
            ),
            array(
				'id' => 'ptbSubmission_show',
				'type' => 'select',
				'label' => __( 'Show', 'tbp' ),
				'options' => [
					'publish' => __( 'Approved Posts', 'tbp' ),
					'pending' => __( 'Pending Approval', 'tbp' ),
					'both' => __( 'All', 'tbp' ),
				],
            ),
		);
	}

	static function pre_get_posts( &$query, ?array $settings = [] ):bool {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$query->set( 'post_type', $settings['ptbSubmission_post_type'] );
		$query->set( 'author', get_current_user_id() );
		if ( isset( $settings['ptbSubmission_show'] ) ) {
			$post_status = $settings['ptbSubmission_show'] === 'both' ? [ 'publish', 'pending' ] : $settings['ptbSubmission_show'];
		} else {
			$post_status = 'publish';
		}
		$query->set( 'post_status', $post_status );

		return true;
	}
}