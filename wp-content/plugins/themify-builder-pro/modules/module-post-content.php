<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Post Content
 * Description: 
 */
class TB_Post_Content_Module extends Themify_Builder_Component_Module {
	

	public static function get_module_name():string {
		return __('Post Content', 'tbp');
	}

	public static function get_module_icon():string {
		return 'align-left';
	}

	public static function get_js_css():array{
		return array(
			'css' => 'text'
		);
	}

	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('post-content');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'post-content',
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
							self::get_font_family('', 'f_f'),
							self::get_color_type(' .tb_text_wrap', '', 'f_c_t', 'f_c', 'f_g_c'),
							self::get_font_size('', 'f_s'),
							self::get_line_height('', 'l_h'),
							self::get_letter_spacing('', 'l_s'),
							self::get_text_align('', 't_a'),
							self::get_text_transform('', 't_t'),
							self::get_font_style('', 'f_st', 'f_w'),
							self::get_text_decoration('', 't_d_r'),
							self::get_text_shadow(' .tb_text_wrap', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color_type(':hover .tb_text_wrap', '', 'f_c_t_h', 'f_c_h', 'f_g_c_h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_font_style('', 'f_st', 'f_w', 'h'),
							self::get_text_decoration('', 't_d_r', 'h'),
							self::get_text_shadow(':hover .tb_text_wrap', 't_sh', 'h'),
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
							self::get_text_decoration(' a', 't_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' a', 'l_c', null, null, 'hover'),
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
			// Display
			self::get_expand('disp', self::get_display())
		);

		$dropcap = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_b_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'd_b_c_h', 'bg_c', 'background-color')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_f'),
							self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_c'),
							self::get_font_size(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_s'),
							self::get_line_height(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_l_h'),
							self::get_text_transform(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_t'),
							self::get_font_style(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_st', 'd_f_b'),
							self::get_text_decoration(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_d'),
							self::get_text_shadow(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_f_h'),
							self::get_color(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_c_h'),
							self::get_font_size(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_s_h'),
							self::get_font_style(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_st_h', 'd_f_b_h'),
							self::get_text_decoration(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_t_d_h'),
							self::get_text_shadow(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_t_sh_h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_p_h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_m_h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_b_h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'r_c_dp')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'r_c_dp_h', '')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'sh_dp')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'sh_dp_h', '')
						)
					)
				))
			))
		);

		$progress_bar = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' #tbp_read_bar', 'b_c_pb', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' #tbp_read_bar', 'b_c_pb', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Height & Min Height
			self::get_expand('ht', array(
				self::get_height(' #tbp_read_bar'),
			)),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'dropcap' => array(
					'options' => $dropcap
				),
				'tbp_pbar' => array(
					'options' => $progress_bar
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
		new TB_Post_Content_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Post_Content_Module');
	}
}