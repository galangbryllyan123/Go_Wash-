<?php

defined('ABSPATH') || exit; // Exit if accessed directly

/**
 * Module Name: Statistics
 * Description: 
 */
class TB_Stats_Module extends Themify_Builder_Component_Module {
	
	public static function get_module_name():string {
		return __('Statistics', 'tbp');
	}

	public static function get_module_icon():string {
		return 'bar-chart';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('stats');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'stats',
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

	public function get_styling() {//backward
		return array(
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
							self::get_color_type('', '', 'f_c_t', 'f_c', 'f_g_c'),
							self::get_font_size('', 'f_s'),
							self::get_line_height('', 'l_h'),
							self::get_letter_spacing('', 'l_s'),
							self::get_text_align('', 't_a'),
							self::get_text_transform('', 't_t'),
							self::get_font_style('', 'f_st', 'f_w'),
							self::get_text_decoration('', 't_d_r'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color_type('', '', 'f_c_t_h', 'f_c_h', 'f_g_c_h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_font_style('', 'f_st', 'f_w', 'h'),
							self::get_text_decoration('', 't_d_r', 'h'),
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
							self::get_color_type('', '', 'l_c_t', 'l_c', 'l_g_c'),
							self::get_text_decoration('', 't_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color_type('', '', 'l_c_t_h', 'l_c_h', 'l_g_c_h'),
							self::get_text_decoration('', 't_d', 'h')
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
		new TB_Stats_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Stats_Module');
	}
}