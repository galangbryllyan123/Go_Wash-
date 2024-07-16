<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tbp
 * @subpackage Tbp/public
 * @author     Themify <themify@themify.me>
 */
class Tbp_Public {

	private static $_locations = array();
	private static $taxonomies = array();
	public static $is_page = false;
	public static $is_archive = false;
	public static $is_single = false;
	public static $is_singular = false;
	public static $is_404 = false;
	public static $is_front_page = false;
	public static $is_home = false;
	public static $is_attachemnt = false;
	public static $is_search = false;
	public static $is_category = false;
	public static $is_tag = false;
	public static $is_author = false;
	public static $is_date = false;
	public static $is_tax = false;
	public static $is_post_type_archive = false;
	private static $currentQuery = null;
	private static $originalFile = null;
	public static $isTemplatePage = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $version    The version of this plugin.
	 */
	public static function run() {
		add_action('themify_builder_run', array(__CLASS__, 'init'));
		add_action('pre_get_posts', array(__CLASS__, 'set_archive_per_page'));

		if ( ! empty( $_REQUEST['tbp_s_tax'] ) ) {
            if ( themify_is_ajax() ) {
                add_filter( 'themify_search_args', [ __CLASS__, 'themify_search_args' ] );
            } else {
                add_action('pre_get_posts', array(__CLASS__, 'override_search_query'), 1000);
            }
		}

		if (themify_is_woocommerce_active()) {
			// Adding cart icon and shopdock markup to the woocommerce fragments
			add_filter('woocommerce_add_to_cart_fragments', array(__CLASS__, 'tbp_add_to_cart_fragments'));
		}
		if ( themify_is_ajax() ) {
			add_filter('themify_builder_load_module_partial', array(__CLASS__, 'setup_post_data'),1,1);
		}
	}

	public static function init() {
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'), 9);
		add_filter('template_include', array(__CLASS__, 'template_include'), 15);
		add_action('tbp_render_the_content', array(__CLASS__, 'render_content_page'));
		add_action('template_redirect', array(__CLASS__, 'set_rules'));
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {
		Tbp_Utils::loadCssModules('tbp', TBP_URL . 'public/css/tbp-style.css', TBP_VER);

		if (themify_is_woocommerce_active()) {
			Tbp_Utils::loadCssModules('tbp-woo', TBP_URL . 'public/css/wc/tbp-woocommerce.css', TBP_VER);
		}
		foreach (self::$_locations as $loc) {
			Themify_Builder_Stylesheet::enqueue_stylesheet(false, $loc);
		}
	}

	public static function get_header($name) {
		remove_action('get_header', array(__CLASS__, 'get_header'), 1);
		?><!DOCTYPE html>
		<html <?php language_attributes(); ?>>
			<head>
				<?php if (!current_theme_supports('title-tag')) : ?>
					<title>
						<?php echo wp_get_document_title(); ?>
					</title>
				<?php endif; ?>
				<?php do_action( 'tbp_head' ); ?>
				<?php wp_head(); ?>
			</head>
			<body <?php body_class(); ?>>
				<?php
				$isThemify = themify_is_themify_theme();
				themify_body_start();
				if ($isThemify === true) {
					?>
					<div id="pagewrap" class="tf_box hfeed site">
						<?php
					}
					themify_header_before();
					themify_header_start();

					self::render_location('header');

					themify_header_end();
					themify_header_after();
					if ($isThemify === true) {
						?>
						<div id="body" class="tf_clearfix">
							<?php
						}
						themify_layout_before();

						remove_all_actions('wp_head');
						$templates = array();
						$name = (string) $name;
						if ('' !== $name) {
							$templates[] = "header-{$name}.php";
						}
						$templates[] = 'header.php';
						ob_start();
						locate_template($templates, true);
						ob_get_clean();
    }

	public static function get_footer($name) {
			remove_action('get_footer', array(__CLASS__, 'get_footer'), 1);
			$isThemify = themify_is_themify_theme();
			themify_layout_after();
			if ($isThemify === true) {
				?>
				</div><!-- /body -->
				<?php
			}
			themify_footer_before();
			themify_footer_start();

			self::render_location('footer');

			themify_footer_end();
			themify_footer_after();
			if ($isThemify === true) {
				?>
				</div><!-- /#pagewrap -->
				<?php
			}
			themify_body_end();
			do_action( 'tbp_footer' );
			wp_footer();
		?>
			</body>
		</html>
		<?php
		remove_all_actions('wp_footer');
		$templates = array();
		$name = (string) $name;
		if ('' !== $name) {
			$templates[] = "footer-{$name}.php";
		}
		$templates[] = 'footer.php';
		ob_start();
		locate_template($templates, true);
		ob_get_clean();
	}

	private static function render_template(int $post_id,string $location) {
		if ($template = get_post($post_id)) {

			$tag = $location === 'header' || $location === 'footer' ? $location : 'main';
			$id = $tag === 'main' ? 'content' : $location;
			$classes = array('tbp_template');
			$single_product_hook = false;
			if ($location === 'single' || $location === 'product_single') {
				if ($location === 'product_single' && is_object(wc_setup_product_data(get_the_ID()))) {
					remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
					do_action('woocommerce_before_single_product');
					WC()->structured_data->generate_product_data(); /* originally hooked to "woocommerce_single_product_summary", it's called manually to generate the product schema data */
					$single_product_hook = true;
				}
				$classes = array_merge($classes, get_post_class());
			}

			do_action('tbp_before_render_builder', $post_id, $location);
			$title = $template->post_title;
			if ($location === 'archive' || $location === 'product_archive') {
				Tbp_Utils::disable_ptb_loop();
			}
			$label = Tbp_Utils::has_access() ? sprintf(__('Edit Template<strong>: %s</strong>'), $title) : 'disabled';
			echo sprintf('<!-- Builder Pro Template Start: %s -->', $title), '<', $tag, ' id="tbp_', $id, '" class="', implode(' ', $classes), '" data-label="', esc_attr($label), '">';
			if(method_exists('Themify_Builder', 'render')){
				echo Themify_Builder::render($post_id);
			}
			else{//backward
				global $ThemifyBuilder;
				echo $ThemifyBuilder->get_builder_output($post_id);
			}
			echo '</' , $tag , '>', sprintf('<!-- Builder Pro Template End: %s -->', $title);
			do_action('tbp_after_render_builder', $post_id, $location);

			if ($single_product_hook === true) {
				do_action('woocommerce_after_single_product');
			}
		}
	}

	private static function render_location($location) {
		if (isset(self::$_locations[$location])) {
			if(self::$isTemplatePage===true){
				global $post;
				if ( isset( $post ) ){
					$saved_post = clone $post;
				}
				$args = array();
				self::setup_template_page(self::$_locations[$location],$args);
				query_posts($args); 
				if( have_posts()){
					the_post();
					self::set_condition_tags();
				}
			}
			self::render_template(self::$_locations[$location], $location);
			if(isset($saved_post)){
				$post = $saved_post;
				setup_postdata( $saved_post );
				unset($saved_post);
			}
		}
	}

	private static function collect_display_conditions(): array {
		$conditions = array();
		$templates = Tbp_Themes::get_theme_templates('publish', -1);
		if (!empty($templates)) {
			foreach ($templates as $tid) {
				$condition = Tbp_Templates::get_template_conditions($tid);

				if (!empty($condition)) {
					$list_conditions = array();
					foreach ($condition as $c) {
						$list_conditions[$c['type']][] = $c;
					}
					$conditions[$tid] = $list_conditions;
				}
			}
		}
		return $conditions;
	}

	private static function set_condition_tags() {


		self::$is_404 = is_404();
		if (self::$is_404 === false) {

			self::$is_page = is_page();
			self::$is_attachemnt = self::$is_page === false && is_attachment();
			self::$is_single = self::$is_page === false && self::$is_attachemnt === false && is_single();
			self::$is_singular = self::$is_page === true || self::$is_attachemnt === true || self::$is_single === true;

			if (self::$is_singular === false) {

				self::$is_home = is_home();

				if (self::$is_home === false) {

					self::$is_category = is_category();

					if (self::$is_category === false) {

						self::$is_tag = is_tag();

						if (self::$is_tag === false) {

							self::$is_tax = is_tax();

							if (self::$is_tax === false) {

								self::$is_search = is_search();

								if (self::$is_search === false) {

									self::$is_author = is_author();

									if (self::$is_author === false) {

										self::$is_post_type_archive = is_post_type_archive();

										if (self::$is_post_type_archive === false) {

											self::$is_date = is_date();
										}
									}
								}
							}
						}
					}
				}
				self::$is_archive = self::$is_category === true || self::$is_tag === true || self::$is_tax === true || self::$is_home === true || self::$is_author === true || self::$is_date === true || self::$is_search === true || self::$is_post_type_archive === true || is_archive();
			} else {
				self::$isTemplatePage = is_singular(Tbp_Templates::SLUG);
				self::$is_front_page = self::$is_page === true && is_front_page();
			}
		}

		if (self::$is_author) {
			// on author archives, the query object returns empty until template_redirect
			add_action('template_redirect', array(__CLASS__, 'cache_query_object'), 1);
		} else {
			self::cache_query_object();
		}
	}

	/**
	 * Cache the global query object
	 *
	 * Hooked to "template_redirect"
	 */
	public static function cache_query_object() {
		self::$currentQuery = get_queried_object();
	}

	/**
	 * Get $currentQuery prop
	 *
	 * @return mixed
	 */
	public static function get_current_query() {
		return self::$currentQuery;
	}

	private static function checking_display_rules() {
		if (!empty(self::$_locations) || (self::$is_archive === false && self::$is_page === false && is_singular(array(Themify_Builder_Layouts::LAYOUT_PART_SLUG,Themify_Builder_Layouts::LAYOUT_SLUG,Themify_Global_Styles::SLUG)))) {
			return;
		}
		self::set_condition_tags();

		if (self::$isTemplatePage === true) {
			$id = get_the_ID();
			$template_type = Tbp_Templates::get_template_type($id);
			if ($template_type) {
				self::$_locations[$template_type] = $id;
			}
			if (($template_type === 'product_single' || $template_type === 'product_archive') && themify_is_woocommerce_active()) {
				add_filter('themify_builder_body_class', array(__CLASS__, 'add_wc_to_body'));
			}
		} else {
			$is_multilingual = Tbp_Utils::is_multilingual();
			$conditions = self::collect_display_conditions();
			// Cached the taxonomy lists
			foreach (Themify_Builder_Model::get_public_taxonomies() as $slug => $v) {
				self::$taxonomies[$slug] = true;
			}
			$currentPostType = !empty(self::$currentQuery->post_type) ? self::$currentQuery->post_type : null;
			if (self::$is_404 === true || self::$is_page === true) {
				$currentPostType = 'page';
			} elseif (self::$is_archive === true && empty($currentPostType)) {
				if (self::$is_category === true || self::$is_tag === true || self::$is_tax === true) {
					$tax = self::$currentQuery === null ? false : get_taxonomy(self::$currentQuery->taxonomy);
					if ($tax === false) {// WP doesn't recognized 404 page when taxonomy/term doesn't exist
						$currentPostType = 'page';
						self::$is_404 = true;
						self::$is_archive = self::$is_category = self::$is_tag = self::$is_tax = false;
					} else {
						$currentPostType = $tax->object_type;
					}
					unset($tax);
				}
				elseif (self::$is_post_type_archive === true) {
					$currentPostType = self::$currentQuery->name;
				}
				else {
					$currentPostType = 'post';
				}
			} 
			elseif (self::$is_home === true && !self::$is_front_page) { // Posts Page
				$currentPostType = 'post';
			}
			$isArray = is_array($currentPostType);
			$locationPriroty=[];
			foreach ($conditions as $id => $condition_type) {

				if (isset($condition_type['exclude']) && !isset($condition_type['include'])) {
					/* when only Exclude condition is set, apply the template always except when the Exclude condition applies */
					$condition_type['include'] = array(0 => array('type' => 'include', 'general' => 'general', 'detail' => 'all'));
				}

				if (isset($condition_type['exclude']) || isset($condition_type['include'])) {
					$location = Tbp_Templates::get_template_type($id);
					if ((self::$is_archive === false && ($location === 'archive' || $location === 'product_archive')) || (self::$is_singular === false && ($location === 'single' || $location === 'product_single')) || ($location === 'page' && self::$is_page === false && self::$is_404 === false)) {
						continue;
					}
					$translated_template = false;
					if ($is_multilingual===true) {
						$translated_template = Tbp_Utils::get_translated_object_id($id, Tbp_Templates::SLUG);
						if (empty($translated_template) || 'publish' !== get_post_status($translated_template)) {
							$translated_template=false;
						}
					}
					// Exclude conditions
					if (isset($condition_type['exclude'])) {
						foreach ($condition_type['exclude'] as $condition) {
							$post_type = Tbp_Utils::get_post_type($location, $condition);
							if ($post_type === 'any' || (($isArray === true && self::check_intersect($currentPostType, $post_type) === true) || ($isArray === false && in_array($currentPostType, $post_type, true)))) {
								$view = self::get_condition_settings($location, $condition);
								if (!empty($view)) {
									if ($is_multilingual===true) {
										self::translate_view($view);
									}
									if (!empty($view) && self::is_current_view($view)>0) {
										continue 2;
									}
								}
							}
						}
					}
					// Include conditions
					if (isset($condition_type['include'])) {
						foreach ($condition_type['include'] as $condition) {
							$post_type = Tbp_Utils::get_post_type($location, $condition);
							if ($post_type === 'any' || ( ( $isArray === true && self::check_intersect($currentPostType, $post_type) === true ) || ( $isArray === false && in_array($currentPostType, $post_type, true) ) )) {
								$view = self::get_condition_settings($location, $condition);
								if (!empty($view)) {
									if ($is_multilingual===true) {
										if ($translated_template!==false) {
											$id = $translated_template;
										}
										/* always translate the template assignments; without translation,
										 * the original template is applied and used on all languages.
										 */
										self::translate_view($view);
									}
									if (!empty($view)) {
										// check if template is assigned to the current context, returns the priority of the template
										$priority = self::is_current_view($view);
										if ($priority>0 && (!isset($locationPriroty[$location]) || $locationPriroty[$location]<$priority)) {
											$locationPriroty[$location]=$priority;
											self::$_locations[$location] = $id;
										}
									}
								}
							}
						}
					}

				}
			}
			unset($conditions,$locationPriroty);
		}

		if (isset(self::$_locations['product_archive'])) {
			unset(self::$_locations['archive']);
		}
		if (isset(self::$_locations['product_single']) || isset(self::$_locations['page'])) {
			unset(self::$_locations['single']);
		}

		self::set_location();
	}

	private static function get_condition_settings(string $location,array $condition): array {
		$query = isset($condition['query']) ? $condition['query'] : '';
		$detail = $condition['detail'];
		$general = $condition['general'];
		if ($location === 'header' || $location === 'footer') {
			$location = $general;
			$data = $query;
		} else {
			$data = $general;
		}
		if (($location === 'product_archive' || $location === 'product_single') && !themify_is_woocommerce_active()) {
			return array();
		}
		$views = array($location => array());
		switch ($location) {

			case 'general':
				$views[$location]['all'] = 'all';
				break;

			case 'single':
			case 'archive':
			case 'product_archive':
				if ($data === 'all') {
					$views[$location][$data] = 'all';
				} elseif (($location === 'archive' || $location === 'product_archive') && strpos($data, 'all_') === 0) {
					$p = substr($data,4);
					if (post_type_exists($p)) {
						$views[$location][$p] = 'all';
					}
					else {
						$views[$location][$data] = $detail;
					}
				} else {
					$views[$location][$data] = $detail;
				}
				break;

			default:
				$views[$location][$data] = $detail;
				break;
		}

		return $views;
	}

	/**
	 * Translate posts and term assignments to TBP Templates
	 */
	private static function translate_view(array &$view):void {
		foreach ($view as $location => $assignments) {
			foreach ($assignments as $object_type => $values) {
				if ($values === 'all' || $object_type === 'is_author') {
					continue;
				}
				if (is_array($values)) {
					foreach ($values as $i => $slug) {

						$object_id = $translated_object_id = false;
						$object_type = $object_type === 'child_of' ? 'page' : $object_type;
						$query_object = self::$currentQuery;
						if (is_object($query_object) && $query_object->post_parent !== 0 && $query_object->post_name === $slug) {
							$parents = get_post_ancestors($query_object);
							foreach ($parents as $p) {
								$parent = get_post($p);
								$slug = $parent->post_name . '/' . $slug;
							}
						}
						if (taxonomy_exists($object_type)) {
							$object_id = Tbp_Utils::get_term_id_by_slug($slug, $object_type);
						} elseif (post_type_exists($object_type)) {
							$object_id = Tbp_Utils::get_post_id_by_slug($slug, $object_type);
						}
						if ($object_id) {
							$translated_object_id = Tbp_Utils::get_translated_object_id($object_id, $object_type);
						}

						if (!empty($translated_object_id)) {
							$view[$location][$object_type][$i] = $translated_object_id;
						} else {
							unset($view[$location][$object_type][$i]);
						}
					}

					/* this template has no translated object, disable the template */
					if (empty($view[$location][$object_type])) {
						unset($view[$location][$object_type]);
					}
				}
			}
		}
	}

	private static function set_location() {
		if (self::$isTemplatePage === true || isset(self::$_locations['header'])) {
			add_action('get_header', array(__CLASS__, 'get_header'), 1, 1);
			add_action( 'tbp_head', array( __CLASS__, 'viewport_meta' ) );
		}
		if (self::$isTemplatePage === true || isset(self::$_locations['footer'])) {
			add_action('get_footer', array(__CLASS__, 'get_footer'), 1, 1);
		}
		if (self::$isTemplatePage === false) {
			$locations = self::$_locations;
			unset($locations['header'], $locations['footer']);
			if (!empty($locations)) {
				add_filter('themify_skip_content_id', array(__CLASS__, 'skip_content_id'));
			}
		}
	}

	public static function skip_content_id($id): string {
		return 'tbp_content';
	}

	private static function check_intersect(array $current, array $posts_types): bool {
		foreach ($posts_types as $v) {
			if (in_array($v, $current, true)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Priority example:
	 * 2 : is_archive()
	 * 3 : is_category()
	 * 4 : is_category( 'test' )
	 */
	private static function is_current_view(array $view):int {
		$query_object = self::$currentQuery;
		foreach ($view as $type => $val) {
			switch ($type) {

				case 'general':
					return 1;
				case 'page':
					if (self::$is_page === true || self::$is_404 === true) {
						foreach ($val as $k => $v) {
							if ($k === 'is_404') {
								if (self::$is_404 === true) {
									return 2;
								}
							} elseif ($k === 'is_front') {
								return self::$is_front_page === true ? 3 : 0;
							} elseif (self::$is_page === true) {
								if ($k === 'child_of') {
									if ($query_object->post_parent !== 0) {
										if ($v === 'all') {
											return 3;
										}
										$parents = get_post_ancestors($query_object);
										foreach ($parents as $p) {
											$parent = get_post($p);
											if (in_array($parent->post_name, $v, true)) {
												return 4;
											}
										}
									}
								} else {
									if ($v === 'all') {
										return 3;
									}
									if (in_array($query_object->post_name, $v, true) || in_array($query_object->ID, $v, true)) {
										return 4;
									}
								}
							}
						}
					}
					break;

				case 'single':
					if (self::$is_singular === true || self::$is_404 === true) {
						foreach ($val as $k => $v) {
							if ($k === 'all') {
								return 2;
							}  
							if ($v === 'all' && post_type_exists($k)) {
								return 3;
							}
							if (self::$is_404 === false) {
								if (isset(self::$taxonomies[$k])) {
									if (( $v === 'all' && has_term('', $k))) {
										return 3;
									} 
									if ($v !== 'all' && is_array($v) && has_term($v, $k)) {
										return 4;
									}
								} elseif ($k === 'is_attachment') {
									if (self::$is_attachemnt === true) {
										if ($v === 'all') {
											return 3;
										} 
										if (in_array($query_object->ID, $v)) {
											return 4;
										}
									}
								} elseif ($k === 'page' || $k === 'child_of' || $k === 'is_front') {
									if (self::$is_page === true) {
										return self::is_current_view(array('page' => $val));
									}
								} 
								elseif (is_singular($k) && post_type_exists($k)) {
									if ($v === 'all') {
										return 3;
									} 
									if (in_array($query_object->post_name, $v, true) || in_array($query_object->ID, $v, true)) {
										return 4;
									}
								}
							} 
							elseif ($k === 'is_404') {
								return 2;
							}
						}
					}
					break;

				case 'archive':
					if (self::$is_archive === true) {
						foreach ($val as $k => $v) {
							if ($k === 'all') {
								return 2;
							}  
							if ($v === 'all' && post_type_exists($k)) {
								return 3;
							}
							if (isset(self::$taxonomies[$k])) {
								if (self::$is_category === true || self::$is_tax === true || self::$is_tag === true) {
									if ($k === $query_object->taxonomy) {
										if ($v === 'all') {
											return 3;
										} 
										if (in_array($query_object->term_id, $v, true) || in_array($query_object->slug, $v, true)) {
											return 4;
										}
									}
								}
							} elseif ($k === 'is_date' || $k === 'is_search') {
								if ((self::$is_date === true && $k === 'is_date') || (self::$is_search === true && $k === 'is_search')) {
									return 4;
								}
							} elseif ($k === 'is_author') {
								if (self::$is_author === true) {
									if ($v === 'all') {
										return 3;
									}
									$author = get_user_by('slug', get_query_var('author_name'));
									if (!empty($author) && in_array($author->ID, $v)) {
										return 4;
									}
								}
							} elseif ($k === 'is_front' && is_home()) {
								return 3;
							}
						}
					}
					break;

				case 'product_single':
					if (self::$is_singular === true && themify_is_woocommerce_active() && is_product()) {
						foreach ($val as $k => $v) {
							if ($v === 'all') {
								return 3;
							}
							if (isset(self::$taxonomies[$k])) {
								if (is_array($v) && has_term($v, $k)) {
									return 4;
								}
							}
							elseif (in_array($query_object->post_name, $v, true) || in_array($query_object->ID, $v, true)) {
								return 4;
							}
						}
					}
					break;

				case 'product_archive':
					if (self::$is_archive === true && themify_is_woocommerce_active() && Tbp_Utils::is_wc_archive()) {
						foreach ($val as $k => $v) {
							if ($k === 'product' && $v === 'all') {
								return 1;
							} 
							if ($k === 'shop' && themify_is_shop()) {
								return 2;
							} 
							if (isset(self::$taxonomies[$k])) {
								if ($v === 'all' && is_tax($k)) {
									return 3;
								} 
								if (is_tax($k, (array) $v)) {
									return 4;
								}
							}
						}
					}
					break;
			}
		}
		return 0;
	}

	public static function get_location($location = null) {
		return $location === NULL ? self::$_locations : (isset(self::$_locations[$location]) ? self::$_locations[$location] : null);
	}

	public static function template_include(?string $template):?string {
		if (self::$is_404 === true && Themify_Builder_Model::is_front_builder_activate()) {
			status_header(200);
		}
		self::$originalFile = $template;

		if (!empty(self::$_locations) ) {
			$template_layout_name = 'tbp-public-template.php';
			$template = locate_template(array(
				$template_layout_name
			));
			if (!$template) {
				$template = TBP_DIR . 'public/partials/' . $template_layout_name;
			}
		}
		return $template;
	}

	public static function render_content_page() {
		$location = '';
		$items = self::$_locations;
		$has_footer=$has_header=false;
		if (!empty($items)) {
			$has_header=isset($items['header']);
			$has_footer=isset($items['footer']);
			unset($items['header'], $items['footer']);
			if (!empty($items)) {
				$location = key($items);
			}
		}
		if ('' !== $location) {
			self::before_content();
			self::render_location($location);
			self::after_content();
		} 
		elseif (self::$is_singular !== true || self::$isTemplatePage === false) {
			$is_theme = themify_is_themify_theme();
			if ($is_theme === false) {
				self::before_content();
			}
			load_template(self::$originalFile);
			if($has_header===true){
				remove_all_actions('wp_head');
			}
			if($has_footer===true){//when origfile has  wp_footer(e.g, template-builder-editor.php) wp_footer called twice
				remove_all_actions('wp_footer');
			}
			if ($is_theme === false) {
				self::after_content();
			}
		}
	}

	/**
	 * Custom hooks called before rendering the main content
	 */
	private static function before_content() {
		themify_content_before();
		themify_content_start();
	}

	/**
	 * Custom hooks called after rendering the main content
	 */
	private static function after_content() {
		themify_content_end();
		themify_content_after();
	}

	/**
	 * Fix number of posts displayed in archive pages according to template options
	 * Required for the Archive Post module
	 *
	 * @since 1.0
	 */
	public static function set_archive_per_page($query) {
		if ($query->is_main_query() && ( $query->is_archive() || $query->is_search() )) {
				/* populate self::$_locations before "template_redirect" hook */
				self::set_rules();
				$archive_template = self::get_location('archive');
				if (empty($archive_template)) {
					$archive_template = self::get_location('product_archive');
				}
				if (!empty($archive_template)) {
					$query->set('posts_per_page', 1);
				}
			}
		}

	/**
	 * override the search main query based on search form module setting
	 * Required for the Search form module
	 *
	 */
	public static function override_search_query($query) {
		if ($query->is_search  && $query->is_main_query()) {
			remove_action('pre_get_posts', array(__CLASS__, 'override_search_query'), 1000);
			$args = $query->query_vars;
			Themify_Builder_Model::parseTermsQuery($args, urldecode($_GET['tbp_s_term']), $_GET['tbp_s_tax']);
			if (isset($args['tax_query'])) {
				$query->set('tax_query', $args['tax_query']);
			}
		}
	}

    /**
     * Override the Ajax search query
     * Required for Searchform module
     */
    public static function themify_search_args(array $args ):array {
        if ( ! empty( $_POST['tbp_s_term'] ) ) {
            Themify_Builder_Model::parseTermsQuery( $args, urldecode($_POST['tbp_s_term']), $_POST['tbp_s_tax'] );
        }

        return $args;
    }

	public static function set_rules() {
        if (!is_embed() ) {
			remove_action('pre_get_posts', array(__CLASS__, 'set_archive_per_page'));
			remove_action('template_redirect', array(__CLASS__, 'set_rules'));
			self::checking_display_rules();
		}
	}

	/**
	 * Add cart total and shopdock cart to the WC Fragments
	 * @param array $fragments
	 * @return array
	 */
	public static function tbp_add_to_cart_fragments(array $fragments):array {
		$fragments['.tbp_shopdock'] = Themify_Builder_Component_Base::retrieve_template('wc/shopdock.php', array(), TBP_DIR . 'templates', '', false);
		$total = WC()->cart->get_cart_contents_count();
		$fragments['.tbp_cart_count'] = sprintf('<span class="tbp_cart_count %s tf_textc">%s</span>', ($total > 0 ? 'tf_inline_b' : 'tbp_cart_empty tf_hide'), $total);
		$fragments['.tbp_cart_amount'] = '<span class="tbp_cart_amount">' . WC()->cart->get_cart_subtotal() . '</span>';
		return $fragments;
	}

	public static function add_wc_to_body(array $cl):array {
		$cl[] = 'woocommerce woocommerce-page';
		if (isset(self::$_locations['product_single'])) {
			if (current_theme_supports('wc-product-gallery-zoom')) {
				wp_enqueue_script('zoom');
			}
			if (current_theme_supports('wc-product-gallery-slider')) {
				wp_enqueue_script('flexslider');
			}
			if (current_theme_supports('wc-product-gallery-lightbox')) {
				wp_enqueue_script('photoswipe-ui-default');
				wp_enqueue_style('photoswipe-default-skin');
				add_action('wp_footer', 'woocommerce_photoswipe');
			}
			wp_enqueue_script('wc-single-product');
		}
		return $cl;
	}

	public static function setup_post_data(array $batch=array()):array{
		$args = array(
			'post_type' => 'any',
			'ptb_disable' => true,
			'posts_per_page' => 1,
			'no_found_rows' => true,
		);
		if (isset($_POST['tbp_post_id']) && is_numeric($_POST['tbp_post_id']) && get_post_status($_POST['tbp_post_id'])) {// id can be generated string element id
			$args['p']=$id = (int) $_POST['tbp_post_id'];
		}
		elseif (!empty($_POST['pageId']) || is_numeric($_POST['bid'])) {
			$id = isset($_POST['pageId']) && is_numeric($_POST['pageId'])?(int)$_POST['pageId']:(int)$_POST['bid'];
			$type = $_POST['type']??'';
			if (isset($_POST['post_type'])) {
				$args['post_type'] = sanitize_key($_POST['post_type']);
			}
			if($type!==''){
				if ($type === 'archive'){
					$args['post_type'] = sanitize_key($_POST['pageId']);
					$id=1;//we don't care the id in the archive page, the post can be a random
				}
				elseif ($type === '404') {
					$args['post_type'] = 'page';
					$args['p'] = $id;
				} 
				elseif ($type === 'search') {
					$args['s'] = sanitize_text_field( $_POST['pageId']);
					$id=1;//we don't care the id in the search page, the post can be a random
				}
				elseif ($type === 'author') {
					$args['author'] = $id;
				}else{
					$type=sanitize_key($type);
					/*custom post types can be attached to category/tag taxonomies, that is why the post type can be array of post types*/
					$tax = get_taxonomy($type);
					if (!empty($tax)) {
						$args['post_type'] = !isset($tax->object_type[1])?$tax->object_type[0]:$tax->object_type;
					}
					if ($type === 'category') {
						$args['cat'] = $id;
					} elseif ($type === 'tag') {
						$args['tag'] = $id;
					}  else{
						if (!empty($tax)) {
							$args['tax_query'] = array(
								array(
									'taxonomy' => $type,
									'field' => 'id',
									'terms' => array($id)
								)
							);
							$args['post_type'] = $tax->object_type;
						} 
						else {
							$args['p'] = $id;
							if (post_type_exists($type)) {
								$args['post_type'] = $type;
							}
						}
					}
					$tax = null;
				}
			}
			elseif ($id>0 && get_post_type($id)===Tbp_Templates::SLUG){
				self::setup_template_page($id,$args);
			}
		}
		if(!empty($id)){
			query_posts($args); 
			if( have_posts()){
				the_post();
				self::set_condition_tags();
			}
		}
		return $batch;
	}

	private static function setup_template_page(int $id,array &$args=array()){
		$template_type=Tbp_Templates::get_template_type($id);
		$args+=array(
			'ptb_disable' => true,
			'posts_per_page' => 1,
			'no_found_rows' => true,
			'order'=>'DESC',
			'orderby'=>'modified',
			'post_status'=>'publish'
		);
		if(($template_type==='product_archive' || $template_type==='product_single') && themify_is_woocommerce_active()){
			$args['post_type']='product';
		}
		elseif($template_type==='page'){
			$args['post_type'] = 'page';
		}
		else{
			$post_type = Tbp_Utils::get_post_type($template_type, Tbp_Templates::get_template_conditions($id)[0]);
			$args['post_type'] = $post_type === 'any' ? 'post' : (count($post_type)>1?$post_type:$post_type[0]);
		}
		self::$isTemplatePage=true;
		self::$is_archive=self::$is_category=self::$is_tag=self::$is_tax=self::$is_front_page=self::$is_home=self::$is_author=self::$is_date=self::$is_search=self::$is_post_type_archive=false;
	}

	public static function viewport_meta() {
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
	}
}
