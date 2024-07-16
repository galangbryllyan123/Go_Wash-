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
class Tbp_Utils {

	public static $isActive = false;
	private static $excerpt_length = null;


	public static function get_post_type(string $location, array $condition) {
		$general = isset($condition['general']) ? $condition['general'] : 'general';
		$query = isset($condition['query']) ? $condition['query'] : '';
		if ($location !== 'header' && $location !== 'footer') {
			$query = $general;
		} else {
			if ($general === 'general') {
				return 'any';
			}
			$location = $general;
		}
		if ($query === 'is_404' || $query === 'page' || $location === 'page' || $query === 'child_of' || ($query === 'is_front' && $location === 'single')) {
			return array('page');
		}
		if ($query === 'all' || $query === 'is_date' || $query === 'is_author' || $query === 'is_search') {
			return 'any';
		}
		if ($location === 'product_single' || $location === 'product_archive' || $query === 'product_cat' || $query === 'product_tag' || $query === 'product') {
			return array('product');
		}
		if ($query === 'post' || ($query === 'is_front' && $location === 'archive')) {
			return array('post');
		}
		if ($query === 'is_attachment') {
			return array('attachment');
		}
		if ($location === 'single' || $location === 'archive') {
			if ($location === 'archive' && strpos($query, 'all_') === 0 && ($post_type=substr($query,4)) && post_type_exists($post_type)) {
				return array($post_type);
			}
			if (($tax=get_taxonomy($query))) {
				return $tax->object_type;
			}
		}
		return array($query);
	}


	public static function getLightBoxParams(array $args): string {

		$lightbox_settings = array();
		if (isset($args['lightbox_w']) && '' !== $args['lightbox_w']) {
			$lightbox_settings[] = $args['lightbox_w'] . $args['lightbox_w_unit'];
		}
		if (isset($args['lightbox_h']) && '' !== $args['lightbox_h']) {
			$lightbox_settings[] = $args['lightbox_h'] . $args['lightbox_h_unit'];
		}
		return implode('|', $lightbox_settings);
	}

	public static function getLinkParams(array $args, $link = ''): array {
		$attr = array();
		if (isset($args['no_follow']) && 'yes' === $args['no_follow']) {
			$attr['rel'] = 'nofollow';
		}
		if ($args['link'] === 'permalink') {
			$attr['href'] = $link !== '' ? $link : get_permalink();
		} elseif ($args['link'] === 'custom' && !empty($args['custom_link'])) {
			$attr['href'] = esc_url($args['custom_link']);
		} elseif ($args['link'] === 'media') {
			$attr['href'] = get_the_post_thumbnail_url();
			$attr['class'] = ' themify_lightbox';
		}
		if (isset($attr['href'])) {
			if ($args['open_link'] === 'newtab') {
				$attr['target'] = '_blank';
			} elseif ($args['open_link'] === 'lightbox') {
				$attr['class'] = ' themify_lightbox';
				if ((isset($args['lightbox_w']) && '' !== $args['lightbox_w']) || (isset($args['lightbox_h']) && '' !== $args['lightbox_h'])) {
					$attr['data-zoom-config'] = self::getLightBoxParams($args);
				}
			}
		}
		return $attr;
	}

	public static function getDateFormat(array $args): string {
		if (isset($args['format']) && 'def' !== $args['format']) {
			if ('custom' === $args['format']) {
				$format = !empty($args['custom']) ? $args['custom'] : '';
			} else {
				$format = $args['format'];
			}
		} else {
			$format = '';
		}
		return $format;
	}

	/**
	 * Get Modules, some modules can be disabled from Themify settings.
	 * @param string $slug 
	 * @param string $get
	 * @return array
	 */
	public static function get_module_settings($slug, $get = 'default') {//deprecated has been moved to js
		if (!isset(Themify_Builder_Model::$modules[$slug])) {
			static $isLoaded = array();
			if (!isset($isLoaded[$slug])) {
				Themify_Builder_Component_Module::load_modules($slug, true);
				$isLoaded[$slug] = Themify_Builder_Model::$modules[$slug] ? get_class(Themify_Builder_Model::$modules[$slug]) : false;
			}
			if (isset(Themify_Builder_Model::$modules[$slug])) {
				$module = Themify_Builder_Model::$modules[$slug];
				unset(Themify_Builder_Model::$modules[$slug]);
			} elseif ($isLoaded[$slug] !== false) {
				$module = new $isLoaded[$slug];
			}
		} else {
			$module = Themify_Builder_Model::$modules[$slug];
		}
		if (!isset($module)) {
			return array();
		}
		$data = $get === 'default' ? $module->get_live_default() : ($get === 'options' ? $module->get_options() : $module->get_styling());
		$module = null;
		return $data;
	}

	/**
	 * Disable PTB loop archive
	 * @return void
	 */
	public static function disable_ptb_loop() {
		if (function_exists('run_ptb')) {
			PTB_Public::get_instance()->disable_ptb(true);
		}
	}


	public static function is_multilingual(): bool {
		return defined('ICL_SITEPRESS_VERSION') || self::is_polylang();
	}

	/**
	 * Returns true if the website is using Polylang plugin
	 *
	 * @return bool
	 */
	public static function is_polylang(): bool {
		return defined('POLYLANG_VERSION');
	}


	/**
	 * Returns the current language code
	 *
	 * @since 1.0.0
	 *
	 * @return string the language code, e.g. "en"
	 */
	public static function get_default_language_code(): string {
		static $lng = null;
		if ($lng === null) {
			global $sitepress;
			if (isset($sitepress)) {
				$lng = $sitepress->get_default_language();
			} else if (function_exists('pll_default_language')) {
				$lng = pll_default_language();
			}
			if (empty($lng)) {
				$lng = substr(get_bloginfo('language'), 0, 2);
			}
			$lng = strtolower(trim($lng));
		}
		return $lng;
	}


	public static function loadCssModules($slug, $url, $v = null) {
		static $isNew = null;
		if ($isNew === null) {
			$isNew = method_exists('Themify_Builder_Model', 'loadCssModules');
		}
		if ($v === null) {
			$v = TBP_VER;
		}
		if ($isNew === true) {
			Themify_Builder_Model::loadCssModules($slug, $url, $v);
		} elseif (function_exists('themify_enque_style')) {
			themify_enque_style($slug, $url, null, $v);
		} else {
			wp_enqueue_style($slug, $url, null, $v);
		}
	}

	/**
	 * Translate post or term ID to $language (default: current language)
	 *
	 * @return int|false
	 */
	public static function get_translated_object_id($object_id, $type) {
		if (defined('ICL_SITEPRESS_VERSION')) {
			return apply_filters('wpml_object_id', $object_id, $type, false);
		} elseif (self::is_polylang()) {
			return taxonomy_exists( $type ) ? pll_get_term( $object_id ) : pll_get_post( $object_id );
		}
	}


	/**
	 * Gets a term $slus and returns its ID.
	 *
	 * With Polylang plugin, search is restricted to the default site language only.
	 *
	 * @return false|int
	 */
	public static function get_term_id_by_slug($slug, $taxonomy) {
		if (!taxonomy_exists($taxonomy)) {
			return false;
		}

		$args = array(
			'get' => 'all',
			'number' => 1,
			'taxonomy' => $taxonomy,
			'update_term_meta_cache' => false,
			'orderby' => 'none',
			'suppress_filter' => true,
			'slug' => (string) $slug,
		);

		if (self::is_polylang()) {
			$args['lang'] = Tbp_Utils::get_default_language_code();
		}

		$terms = get_terms($args);
		if (is_wp_error($terms) || empty($terms)) {
			return false;
		}

		return $terms[0]->term_id;
	}

	public static function get_post_id_by_slug($slug, $post_type) {
		$result = get_page_by_path($slug, OBJECT, $post_type);
		if ($result) {
			return $result->ID;
		}
	}


	/**
	 * Conditional Tag, check if the query is for any WC archive pages.
	 *
	 * @return bool
	 */
	public static function is_wc_archive(): bool {
		static $is = null;
		if ($is === null) {
			$is = themify_is_shop() || is_tax(get_object_taxonomies('product'));
		}

		return $is;
	}

	public static function has_access(string $key = 'tbp'): bool {
		static $is = null;
		if ($is === null) {
			$user = wp_get_current_user();
			if ($user->exists()) {
				$role = is_array($user->roles) ? $user->roles : array();
				$the_role = array_shift($role);
				if (empty($user->roles) || is_super_admin()) {
					$is = true;
				} else {
					$setting = 'setting-' . $key . '-' . $the_role;
					$is = themify_builder_get($setting, $setting, true) !== 'disable';
				}
			} else {
				$is = false;
			}
		}
		return $is;
	}

	/**
	 * Generate the_excerpt with consistent length trim & added ellipsis
	 * regardless of where the excerpt is coming from.
	 *
	 * @return string
	 */
	public static function get_excerpt(array $args = array()):string {
		$postId = isset($args['post_id']) ? $args['post_id'] : null;
		$post = get_post($postId);
		if (!$post) {
			return '';
		}
		$args['excerpt_length'] = !empty($args['excerpt_length']) ? $args['excerpt_length'] : 55;
		$args['ellipsis'] = isset($args['ellipsis']) && '1' === $args['ellipsis'] ? '' : ' [&hellip;]'; /* "1" : disable the ellipsis */
		$args['auto_excerpt'] = isset($args['auto_excerpt']) && '1' === $args['auto_excerpt'] ? false : true;

		if (has_excerpt($postId)) {
			/* if post has custom excerpt, "excerpt_length" and "excerpt_more" filters are not applied,
			 * so the excerpt text is trimmed manualy
			 */
			$value = wp_trim_words($post->post_excerpt, $args['excerpt_length'], $args['ellipsis']);
		} elseif ($args['auto_excerpt']) {
			self::$excerpt_length = $args['excerpt_length'];
			add_filter('excerpt_length', array(__CLASS__, 'excerpt_length'), 1000);
			if ($args['ellipsis'] === '') {
				add_filter('excerpt_more', array(__CLASS__, 'excerpt_more'), 1000);
			}

			$value = get_the_excerpt($postId);

			remove_filter('excerpt_length', array(__CLASS__, 'excerpt_length'), 1000);
			remove_filter('excerpt_more', array(__CLASS__, 'excerpt_more'), 1000);
			self::$excerpt_length = null;
		} else {
			$value = '';
		}
		if ($value !== '' && !empty($args['more'])) {
			$value .= $args['more'];
		}

		return $value;
	}

	public static function excerpt_length():?int {
		return self::$excerpt_length;
	}

	/**
	 * Disable ellipsis on post excerpt.
	 * Custom function in favor of __return_empty_string so it does not interfere with other filters.
	 *
	 * @return string
	 */
	public static function excerpt_more():string {
		return '';
	}

	/**
	 * Convert toggleable_fields associative array to numeric array 
	 */
	public static function convert_toggleable_fields(array &$array) {
        $old_more_link = isset( $array['more_l'] ) ? $array['more_l'] : false;
        unset( $array['more_l'] );

		if (!isset($array[0]) && !empty($array)) {
			$items = [];
			foreach ($array as $key => $vals) {
				$v = array(
					'id' => $key
				);
				if (isset($vals['on'])) {
					$v['on'] = $vals['on'];
				}
				if (isset($vals['val'])) {
					$v['val'] = $vals['val'];
				}

                /* backward compatibility with deprecated option */
                if ( $key === 'cont' && $old_more_link !== false && $old_more_link['on'] === '1' ) {
                    $v['val']['more_link'] = 'on';
                    if ( ! isset( $v['val']['more_text'] ) ) {
                        $v['val']['more_text'] = isset( $old_more_link['val']['link_text'] ) ? $old_more_link['val']['link_text'] : __('More &rarr;', 'tbp');
                    }
                }

				$items[] = $v;
			}
			$array = $items;
		}

	}

	
	/**
	 * Check if plugin(s) are active or not.
	 *
	 * @return bool
	 */
	public static function plugin_active($plugins):bool {
		foreach ((array) $plugins as $plugin) {
			if (!Themify_Builder_Model::is_plugin_active($plugin)) {
				return false;
			}
		}

		return true;
	}
}
