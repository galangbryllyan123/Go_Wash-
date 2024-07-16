<?php

/*
  Plugin Name:  Builder Slideshow
  Plugin URI:	https://themify.me/addons/slideshow
  Version:      3.5.2
  Author:       Themify
  Author URI:   https://themify.me
  Text Domain:  builder-slideshow
  Description:  Builder Slideshow is an addon to use with a latest Themify theme or Themify Builder plugin. It converts the Builder layout into a slideshow. Each slide can have a different effects (select slide effect in Builder row options).
  Requires PHP: 7.2
  Compatibility: 7.0.0
 */

defined('ABSPATH') or die;

class Builder_Slideshow {

	private static $url;

	public static function init() {
		add_action('init', array(__CLASS__, 'i18n'));
		if (is_admin()) {
			add_filter('plugin_row_meta', array(__CLASS__, 'themify_plugin_meta'), 10, 2);
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'action_links'));
			add_action('themify_do_metaboxes', array(__CLASS__, 'themify_do_metaboxes'));
		}
		add_action('themify_builder_setup_modules', array(__CLASS__, 'run'));
		add_action('plugins_loaded', array(__CLASS__, 'constants'));
	}

	public static function get_version(): string {
		return '3.5.2';
	}

	public static function constants() {
		self::$url = trailingslashit(plugin_dir_url(__FILE__));
	}

	public static function run() {
		if (is_admin() || Themify_Builder_Model::is_front_builder_activate()) {
			add_filter('themify_builder_row_fields_options', array(__CLASS__, 'row_fields_options'));
		}
		if (!is_admin()) {
			add_filter('body_class', array(__CLASS__, 'body_class'));
		}
	}

	public static function themify_plugin_meta(array $links, $file): array {
		if (plugin_basename(__FILE__) === $file) {
			$row_meta = array(
				'changelogs' => '<a href="' . esc_url('https://themify.org/changelogs/') . basename(dirname($file)) . '.txt" target="_blank" aria-label="' . esc_attr__('Plugin Changelogs', 'themify') . '">' . esc_html__('View Changelogs', 'themify') . '</a>'
			);

			return array_merge($links, $row_meta);
		}
		return (array) $links;
	}

	public static function action_links(array $links): array {
		if (is_plugin_active('themify-updater/themify-updater.php')) {
			$tlinks = array(
				'<a href="' . admin_url('index.php?page=themify-license') . '">' . __('Themify License', 'themify') . '</a>',
			);
		} else {
			$tlinks = array(
				'<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">' . __('Themify Updater', 'themify') . '</a>',
			);
		}
		return array_merge($links, $tlinks);
	}

	public static function i18n() {
		load_plugin_textdomain('builder-slideshow', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	public static function assets() {
		$ver = self::get_version();
		themify_enque_style('themify-builder-slideshow', self::$url . 'assets/styles.css', null, $ver, null, true);
		themify_enque_script('themify-builder-slideshow', self::$url . 'assets/scripts.js', $ver, array('themify-main-script'));
		if (method_exists('Themify_Enqueue_Assets', 'addPreLoadJs')) {
			Themify_Enqueue_Assets::addPreLoadJs(self::$url . 'assets/slider-pro.js', '1.3');
			Themify_Enqueue_Assets::addLocalization('builder_slideshow', apply_filters('builder_slidershow_script_vars', array(
				'autoplay' => self::get_option('auto_play'),
				'url' => self::$url
			)));
		}
	}

	public static function body_class(array $classes): array {
		/**
		 * Disable the plugin in Full Page Scrolling pages
		 * Specific to Themify themes
		 */
		if ((!function_exists('themify_theme_is_fullpage_scroll') || !themify_theme_is_fullpage_scroll()) && is_page() && self::get_option('enable_slides')) {
			if (!Themify_Builder_Model::is_front_builder_activate()) {
				add_filter('themify_builder_row_start', array(__CLASS__, 'do_slideshow'), 10, 2);
				add_action('themify_builder_row_end', array(__CLASS__, 'row_end'), 10, 2);
				add_action('wp_body_open', array(__CLASS__, 'body_start'), 1);
				add_action('wp_footer', array(__CLASS__, 'assets'));
				add_filter('themify_builder_scroll_highlight_vars', array(__CLASS__, 'disable_scroll_highlight'));
			} else {
				add_action('themify_builder_active_enqueue', array(__CLASS__, 'frontend_active'));
			}
			$classes[] = 'builder-slideshow';
		}
		return $classes;
	}

	public static function frontend_active(string $type) {
		if ($type !== 'admin') {
			themify_enque_style('themify-builder-slideshow', self::$url . 'assets/admin.css', null, self::get_version());
		}
	}

	public static function admin_enqueue() {
		themify_enque_script('themify-builder-slideshow-admin', self::$url . 'assets/admin.js', self::get_version());
	}

	public static function themify_do_metaboxes(array $panels):array {

		add_action('admin_footer', array(__CLASS__, 'admin_enqueue'));
		$options = array(
			array(
				'id' => 'builder-slideshow-enable-slides',
				'type' => 'checkbox',
				'title' => __('Builder Slideshow', 'builder-slideshow'),
				'label' => __('Enable slideshow', 'builder-slideshow'),
				'option_js' => true,
				'description' => __('When enabled, Builder rows will have full height and perform a slideshow transition on page scroll. Sidebar, page titles, and comments will be disabled on this mode. Set transition effect with the Builder > Row options.'),
				'name' => 'builder_slideshow_enable_slides',
				'enable_toggle' => true,
				'class' => 'builder-slideshow-enable-slides'
			),
			array(
				'id' => 'builder-slideshow-auto-play',
				'name' => 'builder_slideshow_auto_play',
				'title' => __('Auto Slide (Auto Play Timer)', 'builder-slideshow'),
				'type' => 'dropdown',
				'meta' => array(
					array('value' => '0', 'name' => __('Off', 'builder-slideshow'), 'selected' => true),
					array('value' => '1', 'name' => __('1 second', 'builder-slideshow')),
					array('value' => '2', 'name' => __('2 seconds', 'builder-slideshow')),
					array('value' => '3', 'name' => __('3 seconds', 'builder-slideshow')),
					array('value' => '4', 'name' => __('4 seconds', 'builder-slideshow')),
					array('value' => '5', 'name' => __('5 seconds', 'builder-slideshow')),
					array('value' => '6', 'name' => __('6 seconds', 'builder-slideshow')),
					array('value' => '7', 'name' => __('7 seconds', 'builder-slideshow')),
					array('value' => '8', 'name' => __('8 seconds', 'builder-slideshow')),
					array('value' => '9', 'name' => __('9 seconds', 'builder-slideshow')),
					array('value' => '10', 'name' => __('10 seconds', 'builder-slideshow')),
				),
				'toggle' => 'builder_slideshow_enable_slides-toggle',
				'default_toggle' => 'hidden',
			),
			array(
				'id' => 'builder-slideshow-transition-speed',
				'name' => 'builder_slideshow_transition_speed',
				'title' => __('Transition Speed', 'builder-slideshow'),
				'type' => 'dropdown',
				'meta' => array(
					array('value' => '0.5', 'name' => __('Fast', 'builder-slideshow'), 'selected' => true),
					array('value' => '1', 'name' => __('Normal', 'builder-slideshow')),
					array('value' => '2', 'name' => __('Slow', 'builder-slideshow')),
				),
				'toggle' => 'builder_slideshow_enable_slides-toggle',
				'default_toggle' => 'hidden',
			)
		);
		$panels[] = array(
			'name' => __('Builder Slideshow', 'builder-slideshow'),
			'id' => 'builder-slideshow',
			'options' => $options,
			'pages' => 'page'
		);
		return $panels;
	}

	public static function get_option(string $name) {
		$value = null;
		if (is_page()) {
			static $result = array();
			if (!isset($result[$name])) {
				$k = 'builder_slideshow_' . $name;
				if (themify_is_themify_theme()) {
					$value = themify_get($k);
				} else {
					static $options = null;
					if ($options === null) {
						$options = get_option('builder_slideshow', array())+self::get_defaults();
					}
					$value = isset($options[$name]) ? $options[$name] : null;
				}
				if ($value === null) {
					$value = self::get_defaults($k);
				}

				$result[$name] = $value;
			}
			$value = $result[$name];
		}

		return $value;
	}

	public static function body_start() {
		echo '<div id="builder_slideshow_loader" class="tf_lazy tf_w tf_h" style="background-color:#fff;position:fixed;top:0;left:0;z-index:1000"></div>';
	}

	private static function get_defaults(string $key = '') {
		$options = array(
			'builder_slideshow_enable_slides' => false,
			'builder_slideshow_auto_play' => 0,
			'builder_slideshow_transition_speed' => .5
		);
		return $key !== '' ? $options[$key] : $options;
	}

	public static function row_fields_options(array $options):array {
		$options[] = array(
			'type' => 'separator'
		);
		$options[] = array(
			'id' => 'row_slide_direction',
			'label' => __('Slide Transition', 'builder-slideshow'),
			'type' => 'select',
			'options' => array(
				'' => __('Slide Left', 'builder-slideshow'),
				'slideRight' => __('Slide Right', 'builder-slideshow'),
				'slideTop' => __('Slide Up', 'builder-slideshow'),
				'slideBottom' => __('Slide Down', 'builder-slideshow'),
				'slideLeftFade' => __('Slide Left Fade', 'builder-slideshow'),
				'slideRightFade' => __('Slide Right Fade', 'builder-slideshow'),
				'slideTopFade' => __('Slide Up Fade', 'builder-slideshow'),
				'slideBottomFade' => __('Slide Down Fade', 'builder-slideshow'),
				'zoomOut' => __('Zoom Out', 'builder-slideshow'),
				'zoomTop' => __('Zoom Top', 'builder-slideshow'),
				'zoomBottom' => __('Zoom Bottom', 'builder-slideshow'),
				'zoomLeft' => __('Zoom Left', 'builder-slideshow'),
				'zoomRight' => __('Zoom Right', 'builder-slideshow')
			)
		);
		return $options;
	}

	/**
	 * Control front end display of row module.
	 * @access	public
	 * @return	void
	 */
	public static function do_slideshow($builder_id, $row) {
		if (get_queried_object_id() === $builder_id) {
			$slide_direction = !empty($row['styling']['row_slide_direction']) ? $row['styling']['row_slide_direction'] : 'slideLeft';
			$slide_duration = self::get_option('transition_speed');
			static $first = true;
			if ($first === true) {
				$first = false;
				add_filter('themify_builder_row_classes', array(__CLASS__, 'fullheight_row'), 100, 3);
				echo '<div class="slider-pro tf_rel" id="builder-slideshow"><div class="sp-mask tf_rel tf_overflow" style="height:100vh"><div class="sp-slides tf_rel"></div></div>';
			}
			echo '<div data-transition="' . $slide_direction . '" data-duration="' . $slide_duration . '" class="sp-slide builder-slideshow-slide">';
		}
	}

	public static function row_end($builder_id, $row) {
		if ($builder_id === get_queried_object_id()) {
			echo '</div>';
		}
	}

	public static function disable_scroll_highlight(array $vars):array {
		$vars['scroll'] = 'external';
		return $vars;
	}

	public static function fullheight_row(array $row_classes, $row, $builder_id):array {
		if ($builder_id === get_queried_object_id() && !in_array('fullheight', $row_classes, true)) {
			$row_classes[] = 'fullheight';
		}
		return $row_classes;
	}
}

Builder_Slideshow::init();
