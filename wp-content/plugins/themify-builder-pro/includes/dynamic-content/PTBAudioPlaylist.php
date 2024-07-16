<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBAudioPlaylist extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'PTB (Audio Playlist)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $cf_value['url'] ) && is_array( $cf_value['url'] ) ) {
			    $value = self::make_playlist( $cf_value );
			}
		}

		return $value;
	}

	/**
	 * Create audio playlist
	 *
	 * Modified version of wp_playlist_shortcode() where it also support external audio.
	 */
	private static function make_playlist(array $value ):string {
		if ( is_feed() ) {
			$output =PHP_EOL;
			foreach ( $value['url'] as $audio ) {
				$output .= $audio .PHP_EOL;
			}

			return $output;
		}

		$tracks = array();
		foreach ( $value['url'] as $i => $url ) {
			$attachment_id = attachment_url_to_postid( $url );
			$ftype = wp_check_filetype( $url, wp_get_mime_types() );
			$track = array(
				'src'         => $url,
				'type'        => $ftype['type'],
				'title'       => $value['title'][ $i ],
				'caption'     => $value['description'][ $i ],
			);

			$track['meta'] = array();
			$meta = false;
			if ( $attachment_id ) {
				$meta = wp_get_attachment_metadata( $attachment_id );
			}
			if ( ! empty( $meta ) ) {
				$attachment_post = get_post( $attachment_id );
				foreach ( wp_get_attachment_id3_keys( $attachment_post ) as $key => $label ) {
					if ( ! empty( $meta[ $key ] ) ) {
						$track['meta'][ $key ] = $meta[ $key ];
					}
				}
			}

			if ( $attachment_id ) {
				$thumb_id = get_post_thumbnail_id( $attachment_id );
				if ( ! empty( $thumb_id ) ) {
					list( $src, $width, $height ) = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
					$track['thumb']               = compact( 'src', 'width', 'height' );
				} else {
					$mime_icon = wp_mime_type_icon( $attachment_id );
					if ( $mime_icon ) {
						$track['thumb'] = [ 'src' => $mime_icon, 'width' => 48, 'height' => 64 ];
					}
				}
			}

			$tracks[] = $track;
		}

		return Themify_Enqueue_Assets::audio_playlist( array(
			'type' => 'audio',
			'tracks' => $tracks,
		) );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'audio' ),
			),
		);
	}
}