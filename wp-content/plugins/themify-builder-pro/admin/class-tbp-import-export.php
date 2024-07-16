<?php

/**
 * Handles importing sample themes.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 */
/**
 * 
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 * @author     Themify <themify@themify.me>
 */
if (!class_exists('Themify_Import_Helper',false)) {
	return;
}

final class Tbp_Import_Export extends Themify_Import_Helper {

	const DEMO_KEY = 'tbp_demo';

	public static function run() {
		add_action('wp_ajax_tbp_ajax_import', array(__CLASS__, 'ajax_import'));
		add_action('wp_ajax_tbp_ajax_erase_demo', array(__CLASS__, 'erase_demo_data'));
		add_action('wp_ajax_tbp_import_file_templates', array(__CLASS__, 'import_file_templates'));
		add_action('wp_ajax_tbp_export', array(__CLASS__, 'export'));
	}

	public static function ajax_import() {
		if (!empty($_POST['postData'])) {
			self::import_ajax_image();
		} elseif (isset($_POST['type'])) {
			$type = $_POST['type'];
			if ($type === 'menu') {
				self::import_ajax_theme_data();
			} elseif ($type === 'file_templates') {
				self::import_file_templates();
			} elseif ($type === 'terms') {
				self::import_ajax_terms();
			} elseif ($type === 'posts' || $type === 'menu_items' || $type === 'templates') {
				self::import_ajax_posts();
			}
		}
	}

	public static function import_file_templates() {
		check_ajax_referer('tf_nonce', 'nonce');
		if (current_user_can('upload_files')) {
			if (isset($_POST['data'])) {
				$posts = stripslashes_deep($_POST['data']);
			} elseif (isset($_FILES['data'])) {
				$posts = file_get_contents($_FILES['data']['tmp_name']);
			}
			if (!empty($posts)) {
				self::set_time_limit();
				self::raise_memory_limit();
				$posts = json_decode($posts, true);
				$ids = array();
				foreach ($posts as $post) {
					$themeId = false;
					if (empty($post['post_parent'])) {
						$id = __('Theme isn`t selected', 'tbp');
					} else {
						$themeId = Tbp_Themes::get_theme_id($post['post_parent']);
						if ($themeId === false) {
							$id = sprintf(__('Theme "%s" doesn`t exist', 'tbp'), $post['post_parent']);
						}
					}
					if ($themeId !== false) {
						if (isset($post['post_title']) && !empty($post['post_excerpt']) && !empty($post['post_mime_type'])) {
							$post['post_parent'] = $themeId;
							$post['post_mime_type'] = '/' . trim($post['post_mime_type'], '/');
							$post['post_type'] = Tbp_Templates::SLUG;
							if (is_array($post['post_excerpt'])) {
								$post['post_excerpt'] = json_encode($post['post_excerpt']);
							}
							$id = self::import_post($post);
						} else {
							$id = __('Data is corrupted', 'tbp');
						}
					}
					$ids[$post['ID']] = $id;
				}
				if (!empty($ids)) {
					wp_send_json_success($ids);
				}
			}
		} else {
			wp_send_json_error(__('You don`t have permissions to upload file', 'tbp'));
		}
		wp_send_json_error();
	}

	/**
	 * Remove all demo posts
	 *
	 * @return null
	 */
	public static function erase_demo_data() {
		self::erase_demo();
	}

	public static function export() {
		check_ajax_referer('tf_nonce', 'nonce');
		if (!empty($_POST['id'])) {
			$id = (int) $_POST['id'];
			$post = get_post($id);
			if (!empty($post) && ($post->post_type === Tbp_Themes::SLUG || $post->post_type === Tbp_Templates::SLUG)) {
				if ($post->post_type === Tbp_Templates::SLUG) {
					$data = array(//backward v5
						'tbp_template_name' => html_entity_decode(get_the_title($id)),
						'tbp_template_type' => Tbp_Templates::get_template_type($id),
						'tbp_template_conditions' => Tbp_Templates::get_template_conditions($id),
						'tbp_associated_theme' => get_post($post->post_parent)->post_name
					);
					unset($post);
					$data['builder_data'] = Themify_Builder_Import_Export::prepare_builder_data(ThemifyBuilder_Data_Manager::get_data($id));
					$usedGS = Themify_Global_Styles::used_global_styles($id);
					if (!empty($usedGS)) {
						foreach ($usedGS as $gsID => $gsPost) {
							unset($usedGS[$gsID]['id'], $usedGS[$gsID]['url']);
							$styling = Themify_Builder_Import_Export::prepare_builder_data($gsPost['data']);
							$styling = $styling[0];
							if ($gsPost['type'] === 'row') {
								$styling = $styling['styling'];
							} elseif ($gsPost['type'] === 'column') {
								$styling = $styling['cols'][0]['styling'];
							} else {
								$styling = $styling['cols'][0]['modules'][0]['mod_settings'];
							}
							$usedGS[$gsID]['data'] = $styling;
						}
						$data['gs'] = $usedGS;
						unset($gsPost, $usedGS);
					}
					if ($custom_fonts = Themify_Custom_Fonts::get_fonts($id)) {
						$custom_fonts_names = array(); /* post_name of all the custom fonts used in the theme */
						$custom_fonts = explode('|', $custom_fonts);
						foreach ($custom_fonts as $font) {
							if (!empty($font)) {
								$font = explode(':', $font);
								if (!isset($custom_fonts_names[$font[0]])) {
									$custom_fonts_names[$font[0]] = $font[0];
								}
							}
						}
						unset($custom_fonts);
						if (!empty($custom_fonts_names)) {
							$query = new WP_Query(array(
								'post_type' => Themify_Custom_Fonts::SLUG,
								'post_name__in' => $custom_fonts_names,
								'no_found_rows' => true,
								'ignore_sticky_posts' => true,
								'posts_per_page' => -1,
								'nopaging' => true,
								'update_post_meta_cache' => false,
								'update_post_term_cache' => false,
								'orderby' => 'none'
							));
							$fonts = $query->get_posts();
							unset($custom_fonts_names);

							if (!empty($fonts)) {
								$custom_fonts = array();
								foreach ($fonts as $font) {
									$custom_fonts[] = array(
										'post_title' => $font->post_title,
										'post_name' => $font->post_name,
										'meta_input' => array(
											'variations' => get_post_meta($font->ID, 'variations', true)
										)
									);
								}
								$data['cf'] = $custom_fonts;
								unset($fonts, $custom_fonts);
							}
						}
					}
				} else {
					$data = array(
						'title' => get_the_title($id),
						'tbp_theme_screenshot' => get_the_post_thumbnail_url($post),
						'templates' => array()
					);
					$templates = Tbp_Themes::get_theme_templates('any', -1, $id);
					if (!empty($templates)) {
						foreach ($templates as $tid) {
							$data['templates'][$tid] = get_the_title($tid);
						}
					}
					unset($templates);
				}
				wp_send_json_success($data);
			}
			wp_send_json_error();
		}
	}
}

Tbp_Import_Export::run();
