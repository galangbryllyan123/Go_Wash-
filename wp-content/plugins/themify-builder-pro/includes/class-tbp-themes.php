<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
class Tbp_Themes {

	const SLUG = 'tbp_theme';

	private static $active_theme = null;

	/* URL to Pro Theme demos */

	public static function admin_init() {
		add_action('wp_ajax_' . self::SLUG . '_saving', array(__CLASS__, 'save_form'));
		add_action('wp_ajax_' . self::SLUG . '_get_item', array(__CLASS__, 'get_item_data'));
		add_action('admin_init', array(__CLASS__, 'actions'), 15);
		add_action('delete_post', array(__CLASS__, 'delete_associated_templates'));
		add_filter('tbp_localize_vars', array(__CLASS__, 'add_vars'), 10, 2);
	}

	public static function register_cpt() {
		add_action('rest_api_init', array(__CLASS__, 'register_rest_fields'));
		register_post_type(self::SLUG,
			apply_filters('tbp_register_post_type_' . self::SLUG, array(
			'labels' => array(
				'name' => __('Themes', 'tbp'),
				'singular_name' => __('Theme', 'tbp'),
				'menu_name' => _x('Themes', 'admin menu', 'tbp'),
				'name_admin_bar' => _x('Theme', 'add new on admin bar', 'tbp'),
				'add_new' => _x('Add New', 'theme', 'tbp'),
				'add_new_item' => __('Add New Theme', 'tbp'),
				'new_item' => __('New Theme', 'tbp'),
				'edit_item' => __('Edit Theme', 'tbp'),
				'view_item' => __('View Theme', 'tbp'),
				'all_items' => __('All Themes', 'tbp'),
				'search_items' => __('Search Themes', 'tbp'),
				'parent_item_colon' => __('Parent Themes:', 'tbp'),
				'not_found' => __('No themes found.', 'tbp'),
				'not_found_in_trash' => __('No themes found in Trash.', 'tbp')
			),
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => false,
			'show_in_menu' => false,
			'query_var' => true,
			'rewrite' => array('slug' => 'tbp-theme'),
			'capability_type' => array(self::SLUG, self::SLUG . 's'),
			'has_archive' => false,
			'hierarchical' => false,
			'map_meta_cap' => true,
			'menu_position' => null,
			'supports' => array('title', 'author', 'thumbnail', 'custom-fields'),
			'can_export' => true,
			'show_in_rest' => true
			))
		);
	}

	public static function get_defaults(): array {
		return array(
			'tbp_theme_name' => __('New Theme', 'tbp'),
			'tbp_theme_description' => '',
			'tbp_theme_version' => '1.0.0',
			'tbp_theme_screenshot' => '',
			'tbp_theme_screenshot_id' => ''
		);
	}

	public static function prepare_themes_for_js(?array $themes = null):array {
		$current_theme = self::get_active_theme()['post_name'];

		/**
		 * Filter theme data before it is prepared for JavaScript.
		 *
		 * Passing a non-empty array will result in prepare_themes_for_js() returning
		 * early with that value instead.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $prepared_themes An associative array of theme data. Default empty array.
		 * @param null|array $themes          An array of tbp_theme objects to prepare, if any.
		 * @param string     $current_theme   The current theme slug.
		 */
		$prepared_themes = (array) apply_filters('pre_prepare_tbp_themes_for_js', array(), $themes, $current_theme);

		if (!empty($prepared_themes)) {
			return $prepared_themes;
		}

		// Make sure the current theme is listed first.
		if ('' !== $current_theme) {
			$prepared_themes[$current_theme] = array();
		}
		if (null === $themes) {
			$args = array(
				'post_type' => self::SLUG,
				'posts_per_page' => -1,
				'no_found_rows' => true,
				'ignore_sticky_posts' => true,
				'order' => 'DESC'
			);
			$query = new WP_Query($args);
			$themes = $query->get_posts();
		}


		$url = menu_page_url(self::SLUG, false);
		$actions = array('activate', 'deactivate', 'export', 'delete');
		foreach ($themes as $theme) {
			$slug = $theme->post_name;
			$metadata = self::get_defaults();
			$prepared_themes[$slug] = array(
				'id' => $slug,
				'theme_id' => $theme->ID,
				'name' => $theme->post_title,
				'screenshot' => array(get_the_post_thumbnail_url($theme->ID)), // @todo multiple
				'description' => empty($metadata['tbp_theme_description']) ? '' : $metadata['tbp_theme_description'],
				'author' => 'tbp',
				'authorAndUri' => sprintf('<a href="%s">%s</a>', 'https://themify.me', 'tbp'),
				'version' => $metadata['tbp_theme_version'],
				'tags' => '',
				'parent' => false,
				'active' => $slug === $current_theme,
				'hasUpdate' => false,
				'update' => false
			);
			$item_actions = array();
			foreach ($actions as $act) {
				$item_actions[$act] = wp_nonce_url(add_query_arg(array('action' => $act, 'p' => $theme->ID), $url), self::SLUG . '_nonce');
			}
			$prepared_themes[$slug]['actions'] = $item_actions;
		}

		/**
		 * Filter the themes prepared for JavaScript.
		 *
		 * Could be useful for changing the order, which is by name by default.
		 *
		 * @since 1.0.0
		 *
		 * @param array $prepared_themes Array of themes.
		 */
		$prepared_themes = array_values(apply_filters('tbp_prepare_themes_for_js', $prepared_themes));
		return array_filter($prepared_themes);
	}

	public static function render_page() {
		include_once TBP_DIR . 'admin/partials/tbp-admin-theme-page.php';
	}

	/**
	 * Save form post data via Hooks
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public static function save_form() {
		check_ajax_referer('tf_nonce', 'nonce');
		if (current_user_can('manage_options')) {
			$resp = array();
			$post_data = json_decode(stripslashes_deep($_POST['data']), true);
			$post_data = wp_parse_args($post_data, self::get_defaults());
			$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
			$args = array(
				'post_title' => sanitize_text_field($post_data['tbp_theme_name']),
				'post_status' => 'publish'
			);
			if ($id) {
				$args['ID'] = $id;
				$id = wp_update_post($args);
			} else {
				$args['post_type'] = self::SLUG;
				$args['post_name'] = str_replace('-', '_', sanitize_title($args['post_title']));
				$id = wp_insert_post($args);
			}
			if ($id && !is_wp_error($id)) {
				$resp['id'] = $id;
				if (!empty($post_data['tbp_theme_screenshot']) && !empty($post_data['tbp_theme_screenshot_id'])) {
					set_post_thumbnail($id, $post_data['tbp_theme_screenshot_id']);
				}
				// Return activate url
				if (empty($_POST['is_draft']) && self::get_active_theme()['ID'] != $id) {
					self::set_active_theme($id);
				}
				$resp['slug'] = get_post_field('post_name', $id);
				$resp['redirect'] = admin_url('admin.php?page=' . self::SLUG . '&action=activate&p=' . $id . '&_wpnonce=' . wp_create_nonce(self::SLUG . '_nonce'));
				wp_send_json_success($resp);
			}
		} else {
			wp_send_json_error(__('You don`t have permssion to add/edit a theme', 'tbp'));
		}
		wp_send_json_error();
	}

	/**
	 * Activate/Deactivate Theme action.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public static function actions() {
		if (isset($_GET['p'], $_GET['action'], $_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::SLUG . '_nonce')) {

			$action = $_GET['action'];
			$url = menu_page_url(self::SLUG, false);
			$post_id = (int) $_GET['p'];
			switch ($action) {
				case 'activate':
				case 'deactivate':
					if ($action === 'deactivate') {
						$post_id = null;
					}
					self::set_active_theme($post_id);
					$url = add_query_arg(array('status' => $action), $url);
					break;
				case 'delete':
					wp_delete_post($post_id, true);
					break;
			}
			wp_redirect($url);
			exit;
		}
	}

	/**
	 * Delete associated template and template part data.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param int $post_id 
	 */
	public static function delete_associated_templates(?int $post_id) {
		// If this is just a revision, don't send the email or  If this isn't a 'tbp_theme' post, don't update it.
		if (wp_is_post_revision($post_id) || self::SLUG !== get_post_type($post_id))
			return;

		$templates = self::get_theme_templates('any', -1, $post_id);
		if (!empty($templates)) {
			foreach ($templates as $template_id) {
				if ($post_id != $template_id) {
					wp_delete_post($template_id, true);
				}
			}
		}
	}

	public static function register_rest_fields() {
		add_filter('rest_'.self::SLUG.'_query', array(__CLASS__, 'rest_post_type_query'), 10, 2);
		register_rest_field(self::SLUG, 'tbp_image_thumbnail', array(
			'get_callback' => array(__CLASS__, 'get_theme_thumbnail_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_image_full', array(
			'get_callback' => array(__CLASS__, 'get_theme_img_full_cb'),
			'schema' => null,
			)
		);
	}

	public static function rest_post_type_query(array $args, WP_REST_Request $request): array {
		$args['orderby']='none';
		$args['nopaging']=$args['ignore_sticky_posts']=$args['no_found_rows']=true;
		$args['posts_per_page']=$args['posts_per_archive_page']=-1;
		$args['cache_results']=$args['update_post_meta_cache']=$args['update_post_term_cache']=$args['update_menu_item_cache']=false;
		return $args;
	}

	public static function get_theme_thumbnail_cb(array $data=array()) {
		if (!empty($data['featured_media'])) {
			return wp_get_attachment_image_src($data['featured_media'])[0];
		}
		return false;
	}

	public static function get_theme_img_full_cb(array $data=array()) {
		if (!empty($data['featured_media'])) {
			return wp_get_attachment_image_src($data['featured_media'], 'large')[0];
		}
		return false;
	}

	public static function get_item_data() {

		// Check ajax referer
		check_ajax_referer('tf_nonce', 'nonce');
		if (!empty($_POST['id'])) {
			$id = (int) $_POST['id'];
			$post = get_post($id);
			$data = array();
			if (!empty($post)) {
				$data['tbp_theme_name'] = $post->post_title;
				$data['tbp_theme_screenshot_id'] = get_post_thumbnail_id($post);
				$data['tbp_theme_screenshot'] = get_the_post_thumbnail_url($post);
			}
			echo json_encode($data);
		}
		die;
	}

	public static function add_vars(array $vars, ?string $type):array {
		if ($type === self::SLUG) {
			themify_enque_script('tbp', TBP_URL . 'admin/js/tbp-theme.js', TBP_VER, array('tbp-admin'));
			$vars = array_merge($vars, array(
				'themes' => self::prepare_themes_for_js(),
				'opt_labels' => array(
					'tbp_theme_name' => __('Theme Name', 'tbp'),
					'tbp_theme_screenshot' => __('Thumbnail', 'tbp')
				),
				'erase' => array(
					'processing' => __('Erasing... ', 'tbp'),
					'done' => __('Erasing complete', 'tbp'),
					'error' => __('An error occurred: %error%', 'tbp')
				),
				'preview' => __('Preview', 'tbp'),
				'edit_template' => __('Edit Theme', 'tbp'),
				'add_template' => __('New Theme', 'tbp'),
				'upload_image' => __('Add Image', 'tbp'),
				'confirmDelete' => __("Are you sure you want to delete this theme? All associated templates will be deleted as well.\n\nClick 'Cancel' to go back, 'OK' to confirm the delete.", 'tbp'),
				'import_demo' => __('Import Demo', 'tbp'),
				'import_terms' => __('Importing Taxonomy (%from%/%to%): %post%', 'tbp'),
				'import_menu' => __('Importing menus', 'tbp'),
				'import_menu_items' => __('Importing menu items (%from%/%to%): %post%', 'tbp'),
				'import_posts' => __('Importing Post (%from%/%to%): %post%', 'tbp'),
				'export_theme_name' => __('Exporting Theme: %post%', 'tbp'),
				'export_templates' => __('Exporting Templates of Theme (%from%/%to%): %post%', 'tbp'),
				'theme_imported' => __('Theme %post% imported successfully. Would you like to activate the theme?', 'tbp'),
				'import_warning' => __('Warning: this will import the demo posts, pages, menus, etc. as per our demo. It may take a few minutes. You can erase demo on Pro Themes > Theme > Theme Details.', 'tbp'),
			));
			if (current_user_can('upload_files')) {
				$max_upload_size = (int) (wp_max_upload_size() / ( 1024 * 1024 ));
				$vars['opt_labels']['tbp_theme_screenshot'] = __('Thumbnail', 'tbp');
				$vars['opt_labels']['tbp_theme_screenshot_desc'] = sprintf(__('Maximum upload file size: %d MB.', 'tbp'), $max_upload_size);
			}
		}
		return $vars;
	}

	public static function get_themes() {
		global $wpdb;
		return $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type='" . self::SLUG . "' AND (post_status='publish' OR post_status='draft') LIMIT 100");
	}

	public static function is_theme(string $slug):bool {
		global $wpdb;
		$count = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM $wpdb->posts WHERE post_type='" . self::SLUG . "' AND post_name='%s' LIMIT 1", array(esc_sql($slug))));
		return $count > 0;
	}

	/**
	 * Get active theme.
	 */
	public static function get_active_theme(bool $reinit = false):array {
		if (self::$active_theme === null || $reinit === true) {
			$post_id = get_option('tbp_active_theme');
			$post = $post_id > 0 ? get_post($post_id) : null;
			if (!empty($post)) {
				self::$active_theme = array(
					'post_name' => $post->post_name,
					'ID' => $post->ID
				);
			} else {
				self::$active_theme = array(
					'post_name' => '',
					'ID' => null
				);
				if ($post_id > 0) {
					delete_option('tbp_active_theme');
				}
			}
		}
		return self::$active_theme;
	}

	/**
	 * Set active theme.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param int $id 
	 */
	public static function set_active_theme(?int $id) {
		return update_option('tbp_active_theme', $id)?self::get_active_theme(true):false;
	}

	/**
	 * Return theme id
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param int,string $slug 
	 */
	public static function get_theme_id($slug) {
		$active = self::get_active_theme();
		if (is_numeric($slug)) {
			if ($active['ID'] == $slug) {
				return $slug;
			}
			return get_post_type($slug) === self::SLUG ? $slug : false;
		} 
		elseif ($active['post_name'] === $slug) {
			return $active['ID'];
		}
		$theme = get_posts(
			array(
				'name' => sanitize_text_field($slug),
				'post_type' => self::SLUG,
				'fields' => 'ids',
				'no_found_rows' => true,
				'post_status' => 'any',
				'orderby' => 'none',
				'posts_per_page' => 1,
			)
		);
		return !empty($theme) ? $theme[0] : false;
	}

	/**
	 * Get all related template and template parts ids based on specific theme.
	 * 
	 * @since 1.0.0
	 * @param int $theme_id 
	 * @return array
	 */
	public static function get_theme_templates(string $status = 'publish',int $limit = 50,int $theme_id = 0):array {
		// Get all template data
		$theme = $theme_id > 0 ? get_post($theme_id, ARRAY_A) : self::get_active_theme();
		if (!empty($theme['ID'])) {
			$args = array(
				'post_type' => Tbp_Templates::SLUG,
				'post_status' => $status,
				'posts_per_page' => $limit,
				'post_parent' => $theme['ID'],
				'ignore_sticky_posts' => true,
				'orderby' => 'menu_order date',
				'order' => 'DESC',
				'no_found_rows' => true,
				'fields' => 'ids',
				'ptb_disable' => true,
				'suppress_filters' => true, /* required to disable WPML's filters */
			);
			if (Tbp_Utils::is_polylang()) {
				/* with Polylang, all assignments are saved only for the template in default language */
				$args['lang'] = Tbp_Utils::get_default_language_code();
			}

			$query = new WP_Query($args);
			$posts = $query->get_posts();
			if (empty($posts)) {
				unset($args['post_parent']);
				$args['meta_query'] = array(
					array(
						'key' => 'tbp_associated_theme',
						'value' => $theme['post_name']
					)
				);
				$query = new WP_Query($args);
				$posts = $query->get_posts();
				unset($query);
				if (!empty($posts)) {
					remove_action('pre_post_update', 'wp_save_post_revision');
					foreach ($posts as $id) {
						$type = get_post_meta($id, 'tbp_template_type', true);
						$conditions = get_post_meta($id, 'tbp_template_conditions', true);
						if (empty($conditions)) {
							$conditions = array();
						}
						$res = wp_update_post(array(
							'ID' => $id,
							'post_parent' => $theme['ID'],
							'post_excerpt' => json_encode($conditions),
							'post_mime_type' => '/' . $type
							), true);
						if (!is_wp_error($res)) {
							delete_post_meta($id, 'tbp_associated_theme');
							delete_post_meta($id, 'tbp_template_type');
							delete_post_meta($id, 'tbp_template_conditions');
						}
					}
					add_action('pre_post_update', 'wp_save_post_revision');
				}
			}
			return $posts;
		}
		return array();
	}
}

Tbp_Themes::register_cpt();
