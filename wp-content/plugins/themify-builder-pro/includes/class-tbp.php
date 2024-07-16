<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
final class Tbp {

	private function __construct() {
		
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function run() {
		if (class_exists('Themify_Builder',false)) {
			self::i18n();
			self::register_cpt();
			self::load_dependencies();
			self::plugins_compatibility();
			Tbp_Dynamic_Content::run();
			Tbp_Dynamic_Query::run();
			if (class_exists('Tbp_Admin',false)) {
				Tbp_Admin::run();
			}
			if (themify_is_ajax() || !is_admin()) {
				Tbp_Public::run();
			}
			add_action('themify_builder_setup_modules', array(__CLASS__, 'register_module'));
			if (Themify_Builder_Model::is_front_builder_activate() || is_admin()) {
				add_filter('themify_builder_active_vars', array(__CLASS__, 'add_localize'));
			}
		}
	}

	public static function register_cpt() {
		/**
		 * The class responsible for themes functions.
		 */
		require_once TBP_DIR . 'includes/class-tbp-themes.php';
		/**
		 * The class responsible for templates functions.
		 */
		require_once TBP_DIR . 'includes/class-tbp-templates.php';
	}
	
	public static function add_localize(array $vars):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script('tbp-active', TBP_URL . 'editor/js/build/modules.min.js', TBP_VER, array('themify-builder-app-js'));
			if(themify_is_woocommerce_active()){
				themify_enque_script('tbp-active-wc', TBP_URL . 'editor/js/build/wc.min.js', TBP_VER, array('tbp-active'));
			}
		}
		else{
			$vars['addons'][TBP_URL . 'editor/js/build/modules.min.js']=TBP_VER;
			if(themify_is_woocommerce_active()){
				$vars['addons'][TBP_URL . 'editor/js/build/wc.min.js']=TBP_VER;
			}
		}
		$data = array(
			'pro_layout' => __('Pre-designed', 'tbp'),
			'edit' => __('Edit Template', 'tbp'),
			'aap_saved' => __('Module %s has been saved', 'tbp')
		);
		$id = !empty(Themify_Builder::$builder_active_id) ? Themify_Builder::$builder_active_id : get_the_ID();
		$template = get_post_type($id) === Tbp_Templates::SLUG ? Tbp_Templates::get_template_type($id) : '';
		if ($template!=='') {
			$data['template'] = $template;
		}
		if (Tbp_Public::$isTemplatePage === false) {
			$type = $id = null;
			$query_object = Tbp_Public::get_current_query();
			if (Tbp_Public::$is_archive === true) {
				if (Tbp_Public::$is_post_type_archive === true) {
					$id = $query_object->name;
					$type = 'archive';
				} elseif (Tbp_Public::$is_search === true) {
					$id = get_search_query();
					$type = 'search';
				} elseif (!empty($query_object)) {
					$type = $query_object->taxonomy;
					$id = $query_object->term_id;
				}
			} elseif (Tbp_Public::$is_singular === true) {
				$id = $query_object->ID;
				$type = $query_object->post_type;
			} elseif (Tbp_Public::$is_404 === true) {
				$type = '404';
				$id = Themify_Builder::$builder_active_id;
			} elseif (Tbp_Public::$is_author === true) {
				$type = 'author';
				$id = get_the_author_meta('ID');
			}
			if (!empty($id)) {
				$data['id'] = $id;
				$data['type'] = $type;
			}
		}
		$vars['tbp_vars']=$data;
		$vars['tbp_dynamic_vars']=Tbp_Dynamic_Content::get_builder_active_localize();
		$vars['tbp_dynamic_query']=Tbp_Dynamic_Query::get_builder_active_vars($template);
		$i18n = include( TBP_DIR. 'includes/i18n.php' );
		$vars['i18n']['label']+= $i18n;
		if (class_exists('Tbp_Admin',false)) {
			Tbp_Admin::enqueue_scripts();
		}
		if (Themify_Builder::$builder_active_id) {
			add_filter('tb_toolbar_module', array(__CLASS__, 'add_class'));
		}
		add_action('tb_main_panel_styles', array(__CLASS__, 'module_main_panel_style'));
		add_action('tb_small_toolbar_styles', array(__CLASS__, 'module_small_toolbar_style'));
		return $vars;
	}

	public static function module_small_toolbar_style() {
		?>
		<style id="tbp_small_toolbar_style">
		<?php echo file_get_contents(TBP_DIR . 'editor/css/small-toolbar.css'); ?>
		</style>
		<?php
	}


	public static function module_main_panel_style() {
		?>
		<style id="tbp_main_panel_style">
		<?php echo file_get_contents(TBP_DIR . 'editor/css/main-panel.css'); ?>
		</style>
		<?php
	}

	public static function add_class(string $cl):string {
		$cl .= ' tbp_edit_' . Tbp_Templates::get_template_type(Themify_Builder::$builder_active_id);
		return $cl;
	}
	public static function register_module():void {
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			$modules = array(
				'acf-repeater',
				'ptb-repeater',
				'add-to-cart',
				'advanced-posts',
				'advanced-products',
				'archive-description',
				'archive-image',
				'archive-posts',
				'archive-products',
				'archive-title',
				'author-info',
				'breadcrumbs',
				'cart-icon',
				'comments',
				'featured-image',
				'post-content',
				'post-meta',
				'post-navigation',
				'post-title',
				'product-description',
				'product-image',
				'product-meta',
				'product-price',
				'product-rating',
				'product-reviews',
				'product-stock-status',
				'product-taxonomy',
				'product-title',
				'related-posts',
				'related-products',
				'search-form',
				'site-logo',
				'site-tagline',
				'stats',
				'taxonomy',
				'upsell-products',
				'wc-notices',
				'woocommerce-breadcrumb',
				'woocommerce-hook',
				'readtime'
			);
			$dir = TBP_DIR . 'modules/module-';
			foreach ($modules as $m) {
				Themify_Builder_Model::add_module($dir . $m . '.php');
			}
			add_filter('tb_json_files',array(__CLASS__,'add_json'));
		} 
		else {
			Themify_Builder_Model::register_directory('templates', TBP_DIR . 'templates');
			Themify_Builder_Model::register_directory('modules', TBP_DIR . 'modules');
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tbp_Admin. Defines all hooks for the admin area.
	 * - Tbp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private static function load_dependencies():void {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		/**
		 * The class responsible for various functions.
		 */
		require_once TBP_DIR . 'includes/class-tbp-utils.php';

		/**
		 * Handles Dynamic Content feature.
		 */
		require_once TBP_DIR . 'includes/class-tbp-dynamic-content.php';

		require_once TBP_DIR . 'includes/class-tbp-dynamic-query.php';

		if (is_admin() || themify_is_ajax()) {
			/**
			 * The class responsible for pointer functions.
			 */
			require_once TBP_DIR . 'admin/class-tbp-import-export.php';

			/* load Term Cover feature in Themify framework */
			add_theme_support('themify-term-cover');
		}

		if (Tbp_Utils::has_access()) {
			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once TBP_DIR . 'admin/class-tbp-admin.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once TBP_DIR . 'public/class-tbp-public.php';

		require_once TBP_DIR . 'includes/template-tags.php';
	}

	/**
	 * Load compatibility patches for Pro plugin
	 */
	private static function plugins_compatibility() {
		$plugins = array(
			'wooVariationSwatches' => 'woo-variation-swatches-pro/woo-variation-swatches-pro.php',
			'wooProductFeeds' => 'woocommerce-product-feeds/woocommerce-gpf.php',
			'polylang' => 'polylang/polylang.php',
			'acf' => 'advanced-custom-fields/acf.php',
			'wcPaypalPayments' => 'woocommerce-paypal-payments/woocommerce-paypal-payments.php',
			'ptb' => 'themify-ptb/themify-ptb.php',
		);
		foreach ($plugins as $plugin => $active_check) {
			if (Tbp_Utils::plugin_active($active_check) || ($plugin==='acf' && Tbp_Utils::plugin_active('advanced-custom-fields-pro/acf.php'))) {
				include( TBP_DIR . 'includes/integration/' . $plugin . '.php' );
				$classname = "Themify_Builder_Plugin_Compat_{$plugin}";
				$classname::init();
			}
		}
	}

	/**
	 * Load language files
	 */
	public static function i18n() {
		load_plugin_textdomain('tbp', false, 'themify-builder-pro/languages');
	}

	public static function add_json(array $files):array{
		$files['tbp']=['f'=>TBP_URL.'json/style.json','v'=>TBP_VER];
		
		if (themify_is_woocommerce_active()) {
			$files['tbpwc']=['f'=>TBP_URL.'json/wc.json','v'=>TBP_VER];
		}
		return $files;
	}
}
