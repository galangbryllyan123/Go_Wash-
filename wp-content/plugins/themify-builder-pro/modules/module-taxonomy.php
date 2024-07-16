<?php

defined('ABSPATH') || exit;

/**
 * Module Name: Box
 * Description: Display box content
 */
class TB_Taxonomy_Module extends Themify_Builder_Component_Module {


	public static function get_module_icon():string {
		return 'more';
	}

	public static function get_module_name():string {
		return __('Taxonomy', 'tbp');
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_CSS_MODULES . 'taxonomy'
		);
	}
	
	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('taxonomy');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'taxonomy',
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
							self::get_font_family('', 'f_f'),
							self::get_color(array('', ' h3', ' a'), 'f_g_c', null, null, ''),
							self::get_font_size(array('', ' h3'), 'f_s'),
							self::get_line_height(array('', ' h3'), 'l_h'),
							self::get_letter_spacing(array('', ' h3'), 'l_s'),
							self::get_text_align(array('', ' .post'), 't_a'),
							self::get_text_transform(array('', ' h3'), 't_t'),
							self::get_font_style(array('', ' h3'), 'f_st', 'f_w'),
							self::get_text_decoration(array('', ' a'), 't_d_r'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color(array('', ' h3', ' a'), 'f_g_c_h', null, null, ''),
							self::get_font_size(array('', ' h3'), 'f_s', '', 'h'),
							self::get_font_style(array('', ' h3'), 'f_st', 'f_w', 'h'),
							self::get_text_decoration(array('', ' a'), 't_d_r', 'h'),
							self::get_text_shadow('', 't_sh', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array('', ' h3', ' .post a'), 'l_g_c', null, null, ''),
							self::get_text_decoration(' a', 't_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(':hover', ' h3:hover', ' .post a:hover'), 'l_g_c_h', null, null, ''),
							self::get_text_decoration(' a', 't_d', 'h')
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
							'options' => count($a = self::get_blend()) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('', 'bl_m_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
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
				self::get_height(),
				self::get_min_height(),
				self::get_max_height()
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

		$container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_cntr', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_cntr', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .post', ' .post h3'), 'f_f_cntr'),
							self::get_color_type(array(' .post', ' .post h3'), '', 'f_c_t_cntr', 'f_c_cntr', 'f_g_c_cntr'),
							self::get_font_size(array(' .post', ' .post h3'), 'f_s_cntr'),
							self::get_line_height(array(' .post', ' .post h3'), 'l_h_cntr'),
							self::get_letter_spacing(array(' .post', ' .post h3'), 'l_s_cntr'),
							self::get_text_align(array(' .post', ' .post h3'), 't_a_cntr'),
							self::get_text_transform(array(' .post', ' .post h3'), 't_t_cntr'),
							self::get_font_style(array(' .post', ' .post h3'), 'f_st_cntr', 'f_w_cntr'),
							self::get_text_decoration(array(' .post', ' .post h3'), 't_d_r_cntr'),
							self::get_text_shadow(array(' .post', ' .post h3'), 't_sh_cntr'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .post', ' .post h3'), 'f_f_cntr_h'),
							self::get_color_type(array(' .post', ' .post h3'), '', 'f_c_t_cntr_h', 'f_c_cntr_h', 'f_g_c_cntr_h', 'h'),
							self::get_font_size(array(' .post', ' .post h3'), 'f_s_cntr', '', 'h'),
							self::get_font_style(array(' .post', ' .post h3'), 'f_st_cntr', 'f_w_cntr', 'h'),
							self::get_text_decoration(array(' .post', ' .post h3'), 't_d_r_cntr', 'h'),
							self::get_text_shadow(array(' .post', ' .post h3'), 't_sh_cntr', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cntr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cntr', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .post', 'm_cntr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .post', 'm_cntr', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post', 'b_cntr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post', 'b_cntr', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cntr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cntr', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cntr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cntr', 'h')
						)
					)
				))
			)),
		);

		$descptn = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_tax_desc', 'b_c_tx_desc', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_tax_desc', 'b_c_tx_desc', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_tax_desc', 'f_f_tx_desc'),
							self::get_color(' .tbp_tax_desc', 'f_c_tx_desc'),
							self::get_font_size(' .tbp_tax_desc', 'f_s_tx_desc'),
							self::get_line_height(' .tbp_tax_desc', 'l_h_tx_desc'),
							self::get_text_align(' .tbp_tax_desc', 't_a_tx_desc'),
							self::get_text_transform(' .tbp_tax_desc', 't_t_tx_desc'),
							self::get_font_style(' .tbp_tax_desc', 'f_sy_tx_desc', 'f_w_tx_desc'),
							self::get_text_shadow(' .tbp_tax_desc', 't_sh_tx_desc'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_tax_desc', 'f_f_tx_desc', 'h'),
							self::get_color(' .tbp_tax_desc', 'f_c_tx_desc', null, null, 'h'),
							self::get_font_size(' .tbp_tax_desc', 'f_s_tx_desc', '', 'h'),
							self::get_font_style(' .tbp_tax_desc', 'f_sy_tx_desc', 'f_w_tx_desc', 'h'),
							self::get_text_shadow(' .tbp_tax_desc', 't_sh_tx_desc', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_tax_desc', 'p_tx_desc')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_tax_desc', 'p_tx_desc', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_tax_desc', 'm_tx_desc')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_tax_desc', 'm_tx_desc', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_tax_desc', 'b_tx_desc')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_tax_desc', 'b_tx_desc', 'h')
						)
					)
				))
			))
		);

		$tx_image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post img', 'ty_i_bg_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post img', 'ty_i_bg_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post img', 'ty_i_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post img', 'ty_i_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .post img', 'ty_i_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .post img', 'ty_i_m', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post img', 'ty_i_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post img', 'ty_i_b', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post img', 'ty_i_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post img', 'ty_i_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post img', 'ty_i_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post img', 'ty_i_b_sh', 'h')
						)
					)
				))
			))
		);

		$tax_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post h3', 'b_c_tx_tle', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post h3', 'b_c_tx_tle', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .post h3', 'f_f_tx_tle'),
							self::get_color(array('.module .post h3', '.module .post h3 a'), 'f_c_tx_tle'),
							self::get_font_size('.module .post h3', 'f_s_tx_tle'),
							self::get_line_height('.module .post h3', 'l_h_tx_tle'),
							self::get_text_align('.module .post h3', 't_a_tx_tle'),
							self::get_text_transform('.module .post h3', 't_t_tx_tle'),
							self::get_font_style('.module .post h3', 'f_sy_tx_tle', 'f_w_tx_tle'),
							self::get_text_shadow('.module .post h3', 't_sh_tx_tle'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .post h3', 'f_f_tx_tle', 'h'),
							self::get_color(array('.module .post h3', '.module .post h3 a'), 'f_c_tx_tle', null, null, 'h'),
							self::get_font_size('.module .post h3', 'f_s_tx_tle', '', 'h'),
							self::get_font_style('.module .post h3', 'f_sy_tx_tle', 'f_w_tx_tle', 'h'),
							self::get_text_shadow('.module .post h3', 't_sh_tx_tle' . 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post h3', 'p_tx_tle')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post h3', 'p_tx_tle', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .post h3', 'm_tx_tle')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .post h3', 'm_tx_tle', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post h3', 'b_tx_tle')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post h3', 'b_tx_tle', 'h')
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
				'm_t' => array(
					'options' => $this->module_title_custom_style()
				),
				'conter' => array(
					'options' => $container
				),
				'desc' => array(
					'options' => $descptn
				),
				'image' => array(
					'options' => $tx_image
				),
				'title' => array(
					'options' => $tax_title
				)
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
		new TB_Taxonomy_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Taxonomy_Module');
	}
}

