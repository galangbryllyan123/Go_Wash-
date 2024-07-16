<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: WooCommerce Hook
 * Description: 
 */
class TB_WooCommerce_Hook_Module extends Themify_Builder_Component_Module {
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('WooCommerce Hook', 'tbp');
	}

	public static function get_module_icon():string {
		return 'layout-menu-separated';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('woocommerce-hook');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'woocommerce-hook',
				'category' => $this->get_group()
			));
		}
	}

	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

	public function get_name() {//backward
		return self::get_module_name();
	}

	public function get_icon() {//backward
		return self::get_module_icon();
	}


	public static function do_action(string $hook='') {
		global $wp_filter;
		if (isset($wp_filter) && !empty($wp_filter[$hook])) {
			$hooks = $wp_filter[$hook];
			if (is_object($hooks) && is_array($hooks->callbacks)) {
				$wc_dir = dirname(WC_PLUGIN_FILE);
				foreach ($hooks->callbacks as $priority => $action) {
					if (is_array($action) && !empty($action)) {
						foreach ($action as $f) {
							if (is_string($f['function']) && function_exists($f['function'])) {
								$func = new ReflectionFunction($f['function']);
								$func_dir = $func->getFileName();
								if (strpos($func_dir, $wc_dir) === 0) {
									remove_action($hook, $f['function'], $priority);
								}
							}
						}
					}
				}
			}
		}
		do_action($hook);
	}
}

if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_WooCommerce_Hook_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_WooCommerce_Hook_Module();
	} else {
		Themify_Builder_Model::register_module('TB_WooCommerce_Hook_Module');
	}
}
