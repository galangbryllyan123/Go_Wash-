<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 * @author     Themify <themify@themify.me>
 */
final class Tbp_Admin {

	private static $currentPage = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public static function run() {
		add_action(is_admin() ? 'admin_enqueue_scripts' : 'themify_builder_setup_modules', array(__CLASS__, 'init'));
		if (is_admin() || themify_is_rest()) {
			add_action('admin_init', array(__CLASS__, 'role_access'));
			add_filter('tb_roles', array(__CLASS__, 'role_access'));
			add_action('admin_menu', array(__CLASS__, 'register_admin_menu'), 11);
			Tbp_Templates::admin_init();
			Tbp_Themes::admin_init();
		}
		add_action( themify_is_themify_theme() ? 'themify_settings_save' : 'themify_builder_settings_save', [ __CLASS__, 'themify_settings_save' ] );
	}

	public static function init() {

		if (isset($_GET['page']) && $_GET['page'] === Tbp_Themes::SLUG && is_admin()) {
			self::$currentPage = Tbp_Themes::SLUG;
		} else {
			add_filter('themify_builder_admin_bar_is_available', array(__CLASS__, 'is_available'));
			$id = !empty(Themify_Builder::$builder_active_id) ? Themify_Builder::$builder_active_id : Themify_Builder_Model::get_ID();
			if ((isset($_GET['post_type']) && $_GET['post_type'] === Tbp_Templates::SLUG) || get_post_type($id) === Tbp_Templates::SLUG) {
				self::$currentPage = Tbp_Templates::SLUG;
			}
			if (Themify_Builder_Model::is_front_builder_activate() || is_admin()) {
				if (self::$currentPage === Tbp_Templates::SLUG && !is_admin()) {
					Tbp_Templates::admin_init();
				}
				add_filter('themify_module_categories', array(__CLASS__, 'module_categories'));
			}
		}
		if (self::$currentPage !== null && is_admin()) {
			add_action('admin_footer', array(__CLASS__, 'enqueue_scripts'));
		}
	}

	public static function is_available(bool $isAvailable):bool {
		remove_filter('themify_builder_admin_bar_is_available', array(__CLASS__, 'is_available'));

		add_filter('themify_builder_admin_bar_menu', array(__CLASS__, 'add_to_admin_bar'), 10, 2);
		return true;
	}

	public static function add_to_admin_bar(array $args, bool $isAvailable):array {
		remove_filter('themify_builder_admin_bar_menu', array(__CLASS__, 'add_to_admin_bar'), 10);
		$_locations = Tbp_Public::get_location();
		if (Themify_Builder::builder_is_available()) {
			$args[] = array('parent' => 'themify_builder', 'title' => __('Edit Builder Content', 'tbp'), 'id' => 'tb_edit_content', 'href' => '#', 'meta' => array('class' => 'tbp_admin_bar toggle_tb_builder', 'target' => '_self'));
		}
		if (Tbp_Public::$isTemplatePage === FALSE && !empty($_locations)) {
			$pid = Tbp_Templates::SLUG . '-dropdown';
			$args[] = array('parent' => 'themify_builder', 'title' => __('Edit Templates', 'tbp'), 'id' => $pid, 'href' => '#', 'meta' => array('class' => 'tbp_admin_bar_templates'));
			//out by order header, condition archive,footer
			$locations = array();
			if (isset($_locations['header'])) {
				$locations[] = $_locations['header'];
			}
			$footer = isset($_locations['footer']) ? $_locations['footer'] : null;

			unset($_locations['header'], $_locations['footer']);
			if (!empty($_locations)) {
				$locations[] = current($_locations);
			}
			if (isset($footer)) {
				$locations[] = $footer;
			}
			foreach ($locations as $v) {
				$title = '<span data-id="' . $v . '"></span>' . get_the_title($v);
				$args[] = array('parent' => $pid, 'id' => $v, 'title' => '<a href="#" class="js-turn-on-builder">' . $title . '</a>');
			}
			$locations = $_locations = null;
		}
		$args[] = array('parent' => 'themify_builder', 'title' => __('Pro Themes', 'tbp'), 'id' => Tbp_Themes::SLUG, 'href' => admin_url('admin.php?page=' . Tbp_Themes::SLUG), 'meta' => array('class' => 'tbp_admin_bar', 'target' => '_self'));
		$args[] = array('parent' => 'themify_builder', 'title' => __('Pro Templates', 'tbp'), 'id' => Tbp_Templates::SLUG, 'href' => admin_url('edit.php?post_type=' . Tbp_Templates::SLUG), 'meta' => array('class' => 'tbp_admin_bar', 'target' => '_self'));
		return $args;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {
		if (is_admin() && (self::$currentPage === Tbp_Themes::SLUG || (self::$currentPage === Tbp_Templates::SLUG && isset($_GET['post_type']) && $_GET['post_type'] === self::$currentPage))) {
			themify_enque_style('tbp_top_admin', TBP_URL . 'admin/css/components/top-admin.css', null, TBP_VER, null, true);
			themify_enque_style('tf_base', THEMIFY_URI . '/css/base.min.css');
			themify_enque_script('themify-main-script', THEMIFY_URI . '/js/main.js', THEMIFY_VERSION);
			themify_enque_script('tbp-admin', TBP_URL . 'admin/js/tbp-admin.js', TBP_VER, array('themify-main-script'));
		}
		wp_localize_script('themify-main-script', 'tbp_admin', self::localize_vars());
		include_once TBP_DIR . 'admin/partials/lightbox-tpl.php';
	}

	private static function localize_vars():array {
		$activeTheme = Tbp_Themes::get_active_theme();
		$vars = array(
			'nonce' => wp_create_nonce('tf_nonce'),
			'type' => self::$currentPage,
			'download_images' => __('Downloading image (%from%/%to%):', 'tbp'),
			'upload_images' => __('Uploading image (%from%/%to%):', 'tbp'),
			'import_skip' => __('Failed import. Skipping import %post%', 'tbp'),
			'import_failed' => __('Failed import: %post%', 'tbp'),
			'download_fail' => __('Download failed: %post%.', 'tbp'),
			'upload_fail' => __('Upload failed (%msg%): %post%', 'tbp'),
			'import_templates' => __('Importing Templates (%from%/%to%): %post%', 'tbp'),
			'import_theme_name' => __('Importing Theme: %post%', 'tbp'),
			'import_file_templates' => __('Importing Templates (%from%/%to%): %post%', 'tbp'),
			'import_gs_data' => __('Importing Global Styling', 'tbp'),
			'zipFileEmpty' => __('Zip file doesn&apos;t contain files', 'tbp'),
			'done' => __('Done', 'tbp'),
			'blank' => __('Blank', 'tbp'),
			'importBuilderNotExist' => __('Import file doesn&apos;t contain Builder data', 'tbp'),
			'importWrongFormat' => __('Import file should be zip or text', 'tbp'),
			'active' => empty($activeTheme['ID']) ? false : $activeTheme['ID'],
			'memory' => (int) (wp_convert_hr_to_bytes(WP_MEMORY_LIMIT) * MB_IN_BYTES)
		);
		if ($vars['active'] === false) {
			$vars['no_active'] = sprintf('<div><h3>%s</h3><p>%s</p></div>', esc_html__('No Theme Activated', 'tbp'), __("You don't have a Pro Theme activated. Please create or activate a <a href='" . admin_url('admin.php?page=tbp_theme') . "'>Pro Theme</a> First.", 'tbp'));
		}
		if (current_user_can('manage_options')) {
			$vars['import_btn'] = __('Import', 'tbp');
		}
		return apply_filters('tbp_localize_vars', $vars, self::$currentPage);
	}


	public static function register_admin_menu() {
		global $submenu;
		$menu_id = themify_is_themify_theme() ? 'themify' : 'themify-builder';
		if (!empty($submenu[$menu_id])) {
			$index = current_user_can('manage_options') ? 1 : 0;
			$label = '<span class="update-plugins"><span class="plugin-count" aria-hidden="true">PRO</span></span>';
			add_submenu_page($menu_id, esc_html__('Templates', 'tbp'), sprintf(__('%s Templates', 'tbp'), $label), 'edit_' . Tbp_Templates::SLUG . 's', 'edit.php?post_type=' . Tbp_Templates::SLUG, '', $index);
			add_submenu_page($menu_id, esc_html__('Themes ', 'tbp'), sprintf(__('%s Themes', 'tbp'), $label), 'manage_options', Tbp_Themes::SLUG, array('Tbp_Themes', 'render_page'), $index);
		}
	}

	/**
	 * Add role access settings to builder/Temify settimgs
	 */
	public static function role_access($roles = array()) {
		if (themify_is_themify_theme()) {
			add_filter('themify_theme_config_setup', array(__CLASS__, 'theme_role_access'), 15);
		} 
		elseif (!empty($roles)) {
			$roles['tbp'] = __('Builder Pro Templates', 'tbp');
			return $roles;
		}
	}

	/**
	 * Add Role Access Control to Themify Theme
	 * @param array $themify_theme_config
	 * @return array
	 */
	public static function theme_role_access(array $themify_theme_config):array {
		// Add role acceess control tab on settings page
		$themify_theme_config['panel']['settings']['tab']['role_access']['custom-module'][] = array(
			'title' => __('Builder Pro Templates', 'tbp'),
			'function' => array('Themify_Access_Role', 'config_view'),
			'setting' => 'tbp'
		);

		return $themify_theme_config;
	}


	/**
	 * apply filter on module panel categories to show only related categories to current template
	 *
	 * @since     1.0.0
	 */
	public static function module_categories(array $categories):array {
		if (Themify_Builder::$builder_active_id) {
			$id = Themify_Builder::$builder_active_id;
		} elseif (is_admin()) {
			$id = Themify_Builder_Model::get_ID();
		}
		if (!empty($id)) {
			$template_location = Tbp_Templates::get_template_type($id);
			if(empty($template_location)){
				$template_location=get_post_type($id);
			}

			$categories+= array(
				'archive' => array('label' => __('Archive', 'tbp'), 'active' => false),
				'single' => array('label' => __('Single', 'tbp'), 'active' => false),
				'product_single' => array('label' => __('Product Single', 'tbp'), 'active' => false),
				'product_archive' => array('label' => __('Product Archive', 'tbp'), 'active' => false),
			);

			switch ($template_location) {
				case 'archive':
					$categories['archive']['active'] = true;
					break;
				case 'product_archive':
					$categories['product_archive']['active'] = true;
					break;
				case 'single':
				case 'page':
				case 'post':
				case Themify_Builder_Layouts::LAYOUT_PART_SLUG:
					$categories['single']['active'] = true;
					break;
				case 'product':
				case 'product_single':
					$categories['product_single']['active'] = true;
					break;
				case 'header':
				case 'footer':
					break;
			}
		}
		return $categories;
	}
	


	public static function themify_settings_save() {
		global $wp_roles;
		foreach ( array_keys( $wp_roles->get_names() ) as $role ) {
			$setting = 'setting-tbp-' . $role;
			/* always enable all capabilities for Admin role */
			$value = $role === 'administrator' ? 'enable' : themify_builder_get( $setting, $setting, true );
			if ( in_array( $value, [ 'enable', 'enableown', 'disable' ], true ) ) {
				$role = get_role( $role );
				$enabled = $value === 'enable' || $value === 'enableown';
				foreach( [ Tbp_Templates::SLUG, Tbp_Themes::SLUG ] as $post_type ) {
					$role->add_cap( 'read_'. $post_type, $enabled );
					$role->add_cap( 'read_private_'. $post_type, $enabled );
					$role->add_cap( 'edit_'. $post_type, $enabled );
					$role->add_cap( 'edit_'. $post_type . 's', $enabled );
					$role->add_cap( 'edit_published_'. $post_type . 's', $enabled );
					$role->add_cap( 'publish_'. $post_type . 's', $enabled );
					$role->add_cap( 'delete_'. $post_type, $enabled );
					$role->add_cap( 'delete_'. $post_type .'s', $enabled );
					$role->add_cap( 'delete_private_'. $post_type .'s', $enabled );
					$role->add_cap( 'delete_published_'. $post_type . 's', $enabled );
					$role->add_cap( 'edit_others_'. $post_type . 's', $value === 'enable' );
					$role->add_cap( 'delete_others_'. $post_type . 's', $value === 'enable' );
				}
			}
		}
	}
}