<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Search Form
 * Description: 
 */
class TB_Search_Form_Module extends Themify_Builder_Component_Module {
	
	public static function get_module_name():string {
		return __('Search Form', 'tbp');
	}

	public static function get_module_icon():string {
		return 'search';
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_CSS_MODULES . 'search-form',
			'async' => true
		);
	}

	public static function get_static_content(array $module):string {
		return ''; // no static content for dynamic content
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('search-form');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'search-form',
				'category' => $this->get_group()
			));
		}
	}

	public function get_name() {//backward
		return self::get_module_name();
	}

	public function get_icon() {//backward
		return self::get_module_icon();
	}
	
	public function get_assets() {//backward
		return self::get_js_css();
	}


	public function get_styling() {//backward
		$general = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_image('', 'b_i', 'bg_c', 'b_r', 'b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('', 'b_i', 'bg_c', 'b_r', 'b_p', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .tbp_searchform', 'f_f_g'),
							self::get_color_type('.module .tbp_searchform', '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size('.module .tbp_searchform', 'f_s_g', ''),
							self::get_line_height('.module .tbp_searchform', 'l_h_g'),
							self::get_letter_spacing('.module .tbp_searchform', 'l_s_g'),
							self::get_text_align('.module .tbp_searchform', 't_a_g'),
							self::get_text_transform('.module .tbp_searchform', 't_t_g'),
							self::get_font_style('.module .tbp_searchform', 'f_st_g', 'f_w_g'),
							self::get_text_decoration('.module .tbp_searchform', 't_d_r_g'),
							self::get_text_shadow('.module .tbp_searchform', 't_sh_g', 'h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .tbp_searchform', 'f_f_g_h'),
							self::get_color_type('.module .tbp_searchform', '', 'f_c_t_g_h', 'f_c_g_h', 'f_g_c_g_h'),
							self::get_font_size('.module .tbp_searchform', 'f_s_g', '', 'h'),
							self::get_font_style('.module .tbp_searchform', 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration('.module .tbp_searchform', 't_d_r_g', 'h'),
							self::get_text_shadow('.module .tbp_searchform', 't_sh_g', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' a', 'l_c'),
							self::get_text_decoration(' a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' a', 'l_c', null, null, 'hover'),
							self::get_text_decoration(' a', 't_d_l', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('', 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('', 'm')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('', 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend('', 'fl')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('', 'fl_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_width('', 'w')
			)),
			// Height & Min Height
			self::get_expand('ht', array(
				method_exists($this, 'get_max_width') ? self::get_height('', 'g_h') : self::get_height('', 'g_h', '', 'g_m_h', 'g_mx_h'),
				self::get_min_height('', 'g_m_h'),
				self::get_max_height('', 'g_mx_h')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('', 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array(self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$inputs = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_searchform input', 'b_c_i', 'bg_c', 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_searchform input', 'b_c_i', 'bg_c', 'background-color', 'h'),
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'f_f_i'),
							self::get_color(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'f_c_i'),
							self::get_font_size(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'f_s_i'),
							self::get_line_height(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'l_h_i'),
							self::get_text_transform(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 't_tf_i'),
							self::get_text_shadow(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 't_sh_i'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'f_f_i', 'h'),
							self::get_color(array(' .tbp_searchform input:hover', ' .tbp_searchform input:hover::placeholder'), 'f_c_i_h', null, null, ''),
							self::get_font_size(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 'f_s_i', '', 'h'),
							self::get_text_shadow(array(' .tbp_searchform input', ' .tbp_searchform input::placeholder'), 't_sh_i', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_searchform input', 'in_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_searchform input', 'in_b', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_searchform input', 'in_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_searchform input', 'in_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_searchform input', 'in_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_searchform input', 'in_m', 'h')
						)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_width(' .tbp_searchform input', 'in_w')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_searchform input', 'in_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_searchform input', 'in_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_searchform input', 'in_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_searchform input', 'in_b_sh', 'h')
						)
					)
				))
			)),
		);

		$search_button = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_searchform button', 'b_c_s', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_searchform button', 'b_c_s', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_searchform button', 'f_f_s'),
							self::get_color(' .tbp_searchform button', 'f_c_s'),
							self::get_font_size(' .tbp_searchform button', 'f_s_s'),
							self::get_line_height(' .tbp_searchform button', 'l_h_s'),
							self::get_text_transform(' .tbp_searchform button', 't_tf_s'),
							self::get_text_shadow(' .tbp_searchform button', 't_sh_s'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_searchform button', 'f_f_s', 'h'),
							self::get_color(' .tbp_searchform button', 'f_c_s', null, null, 'h'),
							self::get_font_size(' .tbp_searchform button', 'f_s_s', '', 'h'),
							self::get_text_shadow(' .tbp_searchform button', 't_sh_s', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_searchform button', 'b_s')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_searchform button', 'b_s', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_searchform button', 'p_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_searchform button', 'p_sd', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_searchform button', 'm_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_searchform button', 'm_sd', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_searchform button', 'r_c_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_searchform button', 'r_c_sd', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_searchform button', 's_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_searchform button', 's_sd', 'h')
						)
					)
				))
			))
		);

		$search_overlay = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'b_c_so', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'b_c_so', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_f_so'),
							self::get_color(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_c_so'),
							self::get_font_size(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_s_so'),
							self::get_line_height(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'l_h_so'),
							self::get_text_transform(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 't_tf_so'),
							self::get_text_shadow(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 't_sh_so'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_f_so', 'h'),
							self::get_color(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_c_so', null, null, 'h'),
							self::get_font_size(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'f_s_so', '', 'h'),
							self::get_text_shadow(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 't_sh_so', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .search-lightbox-wrap a', '.tf_s_dropdown .search-results-wrap a', ' .tf_search_lightbox a'), 'l_c_so'),
							self::get_text_decoration(array(' .search-lightbox-wrap a', '.tf_s_dropdown .search-results-wrap a', ' .tf_search_lightbox a'), 't_d_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .search-lightbox-wrap a', '.tf_s_dropdown .search-results-wrap a', ' .tf_search_lightbox a'), 'l_c_so_h', null, null, 'hover'),
							self::get_text_decoration(array(' .search-lightbox-wrap a', '.tf_s_dropdown .search-results-wrap a', ' .tf_search_lightbox a'), 't_d_so_h', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'b_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'b_so', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'p_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'p_so', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'm_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'm_so', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'r_c_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 'r_c_so', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 's_so')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .search-lightbox-wrap', '.tf_s_dropdown .search-results-wrap', ' .tf_search_lightbox'), 's_so', 'h')
						)
					)
				))
			))
		);

		$keywords = array(
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_search_keywords', 'f_f_k'),
							self::get_color(' .tbp_search_keywords', 'f_c_k'),
							self::get_font_size(' .tbp_search_keywords', 'f_s_k'),
							self::get_line_height(' .tbp_search_keywords', 'l_h_k'),
							self::get_text_transform(' .tbp_search_keywords', 't_tf_k'),
							self::get_text_shadow(' .tbp_search_keywords', 't_sh_k'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_search_keywords', 'f_f_k', 'h'),
							self::get_color(' .tbp_search_keywords', 'f_c_k', null, null, 'h'),
							self::get_font_size(' .tbp_search_keywords', 'f_s_k', '', 'h'),
							self::get_text_shadow(' .tbp_search_keywords', 't_sh_k', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_search_keywords a', 'l_c_k'),
							self::get_color(' .tbp_search_keywords a', 'b_c_k', 'bg_c', 'background-color'),
							self::get_text_decoration(' .tbp_search_keywords a', 't_d_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_search_keywords a', 'l_c_k_h', null, null, 'hover'),
							self::get_color(' .tbp_search_keywords a', 'b_c_k', 'bg_c', 'background-color', 'h'),
							self::get_text_decoration(' .tbp_search_keywords a', 't_d_k_h', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_search_keywords', 'b_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_search_keywords', 'b_k', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_search_keywords', 'p_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_search_keywords', 'p_k', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_search_keywords', 'm_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_search_keywords', 'm_k', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_search_keywords', 'r_c_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_search_keywords', 'r_c_k', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_search_keywords', 's_k')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_search_keywords', 's_k', 'h')
						)
					)
				))
			))
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'tbp_srchinp' => array(
					'options' => $inputs
				),
				'btn' => array(
					'options' => $search_button
				),
				'overlay' => array(
					'options' => $search_overlay
				),
				'tbp_sugstkw' => array(
					'options' => $keywords
				),
			)
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}


if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Search_Form_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Search_Form_Module');
	}
}