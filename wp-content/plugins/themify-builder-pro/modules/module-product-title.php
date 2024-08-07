<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Product Title
 * Description: 
 */
class TB_Product_Title_Module extends Themify_Builder_Component_Module {
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Product Title', 'tbp');
	}

	public static function get_module_icon():string {
		return 'text';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('product-title');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'product-title',
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
							self::get_font_family('.module .tbp_title', 'f_f_g'),
							self::get_color_type(array('.module .tbp_title', '.module .tbp_title a'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size('.module .tbp_title', 'f_s_g', ''),
							self::get_line_height('.module .tbp_title', 'l_h_g'),
							self::get_letter_spacing('.module .tbp_title', 'l_s_g'),
							self::get_text_align('.module .tbp_title', 't_a_g'),
							self::get_text_transform('.module .tbp_title', 't_t_g'),
							self::get_font_style('.module .tbp_title', 'f_st_g', 'f_w_g'),
							self::get_text_decoration('.module .tbp_title', 't_d_r_g'),
							self::get_text_shadow('.module .tbp_title', 't_sh_g'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .tbp_title', 'f_f_g_h', 'h'),
							self::get_color_type(array('.module .tbp_title', '.module .tbp_title a'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g', 'h'),
							self::get_font_size('.module .tbp_title', 'f_s_g', '', 'h'),
							self::get_font_style('.module .tbp_title', 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration('.module .tbp_title', 't_d_r_g', 'h'),
							self::get_text_shadow('.module .tbp_title', 't_sh_g', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c', null, null, 'h'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l', 'h')
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

		$heading = array();

		for ($i = 1; $i <= 6; ++$i) {
			$h = 'h' . $i;
			$selector = $h;
			if ($i === 3) {
				$selector .= ':not(.module-title)';
			}
			$heading = array_merge($heading, array(
				self::get_expand(sprintf(__('Heading %s Font', 'tbp'), $i), array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_font_family('.module ' . $selector, 'f_f_' . $h),
								self::get_color_type('.module ' . $selector . ' a', '', 'f_c_t_' . $h, 'f_c_' . $h, 'f_g_c_' . $h),
								self::get_font_size('.module ' . $h, 'f_s_' . $h),
								self::get_line_height('.module ' . $h, 'l_h_' . $h),
								self::get_letter_spacing('.module ' . $h, 'l_s_' . $h),
								self::get_text_transform('.module ' . $h, 't_t_' . $h),
								self::get_font_style('.module ' . $h, 'f_st_' . $h, 'f_w_' . $h),
								self::get_text_shadow('.module ' . $selector, 't_sh' . $h),
								// Heading  Margin
								self::get_heading_margin_multi_field('.module', $h, 'top'),
								self::get_heading_margin_multi_field('.module', $h, 'bottom')
							)
						),
						'h' => array(
							'options' => array(
								self::get_font_family('.module:hover ' . $selector, 'f_f_' . $h . '_h'),
								self::get_color_type('.module ' . $selector . ':hover a', '', 'f_c_t_' . $h . '_h', 'f_c_' . $h . '_h', 'f_g_c_' . $h . '_h'),
								self::get_font_size('.module ' . $h, 'f_s_' . $h, '', 'h'),
								self::get_font_style('.module ' . $h, 'f_st_' . $h, 'f_w_' . $h, 'h'),
								self::get_text_shadow('.module:hover ' . $selector, 't_sh' . $h, 'h'),
								// Heading  Margin
								self::get_heading_margin_multi_field('.module', $h, 'top', 'h'),
								self::get_heading_margin_multi_field('.module', $h, 'bottom', 'h')
							)
						)
					))
				))
			));
		}

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'head' => array(
					'options' => $heading
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


if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Product_Title_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Product_Title_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Title_Module');
	}
}
