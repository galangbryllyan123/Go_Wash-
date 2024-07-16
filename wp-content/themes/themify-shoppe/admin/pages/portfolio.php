<?php

/**
 * Portfolio Meta Box Options
 * @return array
 * @since 1.0.7
 */
if (!function_exists('themify_theme_portfolio_meta_box')) {

    function themify_theme_portfolio_meta_box() {
	return array(
	    // Layout
	    array(
		'name' => 'layout',
		'title' => __('Sidebar Option', 'themify'),
		'description' => '',
		'type' => 'page_layout',
		'show_title' => true,
		'meta' => array(
		    array('value' => 'default', 'img' => 'themify/img/default.svg', 'selected' => true, 'title' => __('Default', 'themify')),
		    array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
		    array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
		    array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify')),
		    array('value' => 'full_width', 'img' => 'themify/img/fullwidth.svg', 'title' => __('Fullwidth (Builder Page)', 'themify')),
		),
		'default' => 'default',
		'hide' => 'sidebar-none post_sticky_sidebar',
	    ),
	    array(
		'name' => 'post_sticky_sidebar',
		'title' => __('Sticky Sidebar', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		'show_title' => true,
		'class' => 'hide-if sidebar-none',
		'meta' => array(
		    array('value' => '', 'name' => '', 'selected' => true),
		    array('value' => 1, 'name' => __('Enable', 'themify')),
		    array('value' => 0, 'name' => __('Disable', 'themify'))
		),
	    ),
	    // Featured Image Size
	    array(
		'name' => 'feature_size',
		'title' => __('Image Size', 'themify'),
		'description' => sprintf(__('Image sizes can be set at <a href="%s">Media Settings</a> and <a href="%s" target="_blank">Regenerated</a>', 'themify'), 'options-media.php', 'https://wordpress.org/plugins/regenerate-thumbnails/'),
		'type' => 'featimgdropdown',
		'display_callback' => 'themify_is_image_script_disabled'
	    ),
	    // Multi field: Image Dimension
	    themify_image_dimensions_field(),
	    // Hide Title
	    array(
		'name' => 'hide_post_title',
		'title' => __('Post Title', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		'meta' => array(
		    array('value' => 'default', 'name' => '', 'selected' => true),
		    array('value' => 'yes', 'name' => __('Hide', 'themify')),
		    array('value' => 'no', 'name' => __('Show', 'themify'))
		),
		'default' => 'default',
	    ),
	    // Unlink Post Title
	    array(
		'name' => 'unlink_post_title',
		'title' => __('Post Title Link', 'themify'),
		'type' => 'dropdown',
		'meta' => array(
		    array('value' => 'default', 'name' => '', 'selected' => true),
		    array('value' => 'yes', 'name' => __('Unlinked', 'themify')),
		    array('value' => 'no', 'name' => __('Linked', 'themify'))
		),
		'default' => 'default',
	    ),
	    // Hide Post Image
	    array(
		'name' => 'hide_post_image',
		'title' => __('Featured Image', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		'meta' => array(
		    array('value' => 'default', 'name' => '', 'selected' => true),
		    array('value' => 'yes', 'name' => __('Hide', 'themify')),
		    array('value' => 'no', 'name' => __('Show', 'themify'))
		),
		'default' => 'default',
	    ),
	    // Unlink Post Image
	    array(
		'name' => 'unlink_post_image',
		'title' => __('Featured Image Link', 'themify'),
		'type' => 'dropdown',
		'meta' => array(
		    array('value' => 'default', 'name' => '', 'selected' => true),
		    array('value' => 'yes', 'name' => __('Unlinked', 'themify')),
		    array('value' => 'no', 'name' => __('Linked', 'themify'))
		),
		'default' => 'default',
	    ),
	    // External Link
	    array(
		'name' => 'external_link',
		'title' => __('External Link', 'themify'),
		'description' => __('Link Featured Image and Post Title to external URL', 'themify'),
		'type' => 'textbox',
		'meta' => array()
	    ),
	    // Lightbox Link
	    themify_lightbox_link_field(),
	    // Custom menu
	    array(
		'name' => 'custom_menu',
		'title' => __('Custom Menu', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		// extracted from $args
		'meta' => themify_get_available_menus(),
	    ),
	    // Separator - Project Information
	    array(
		'name' => '_separator_project_info',
		'title' => '',
		'description' => '',
		'type' => 'separator',
		'meta' => array(
		    'html' => '<h4>' . __('Project Info', 'themify') . '</h4><hr class="meta_fields_separator"/>'
		),
	    ),
	    // Project Date
	    array(
		'name' => 'project_date',
		'title' => __('Date', 'themify'),
		'description' => '',
		'type' => 'textbox',
		'meta' => array()
	    ),
	    // Project Client
	    array(
		'name' => 'project_client',
		'title' => __('Client', 'themify'),
		'description' => '',
		'type' => 'textbox',
		'meta' => array()
	    ),
	    // Project Services
	    array(
		'name' => 'project_services',
		'title' => __('Services', 'themify'),
		'description' => '',
		'type' => 'textbox',
		'meta' => array()
	    ),
	    // Project Launch
	    array(
		'name' => 'project_launch',
		'title' => __('Link to Launch', 'themify'),
		'description' => '',
		'type' => 'textbox',
		'meta' => array()
	    ),
	);
    }

}

/**
 * Options get metabox
 * @since 1.0.0
 * @var array
 */
if (!function_exists('themify_theme_get_portfolio_metaboxes')) {

    function themify_theme_get_portfolio_metaboxes(array $args, &$meta_boxes) {
	/* remove the "portfolio-options" metabox added by Portfolio module in Builder */
	foreach ($meta_boxes as $i => $m) {
	    if ($m['id'] === 'portfolio-options' && $m['pages'] === 'portfolio') {
		unset($meta_boxes[$i]);
		break;
	    }
	}
	return array(
	    array(
		'name' => __('Portfolio Options', 'themify'),
		'id' => 'portfolio-options',
		'options' => themify_theme_portfolio_meta_box(),
		'pages' => 'portfolio'
	    ),
	);
    }

}
