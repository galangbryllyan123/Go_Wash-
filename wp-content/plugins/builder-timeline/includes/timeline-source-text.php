<?php

class Builder_Timeline_Text_Source {

	public function get_id() {
		return 'text';
	}

	public function get_name() {
		return __( 'Text', 'builder-timeline' );
	}

	public function get_items(array &$args ):array {
		$items = array();
		if(!empty($args['text_source_timeline'])){
			foreach( $args['text_source_timeline'] as $key => $item ) {
					$item+= array(
						'image_timeline' => '',
						'title_timeline' => '',
						'icon_timeline' => '',
						'iconcolor_timeline' => '',
						'icontextcolor_timeline' => '',
						'date_timeline' => '',
						'content_timeline' => '',
						'link_timeline' => ''
					);
					$items[] = array(
							'id' => $key,
							'title' => $item['title_timeline'],
							'icon' => $item['icon_timeline'],
							'icon_color' => $item['iconcolor_timeline'],
							'icon_text_color' => $item['icontextcolor_timeline'],
							'link' => '' !== $item['link_timeline'] ? $item['link_timeline'] : null,
							'date' => $item['date_timeline'],
							'date_formatted' => $item['date_timeline'],
							'hide_featured_image' => $item['image_timeline'] === 'yes',
							'image' => $item['image_timeline'],
							'hide_content' =>$item['content_timeline'] === 'none',
							'content' => apply_filters( 'themify_builder_module_content', $item['content_timeline'] ),
					);
			}
		}
		return apply_filters( 'builder_timeline_source_text_items', $items );
	}
}
