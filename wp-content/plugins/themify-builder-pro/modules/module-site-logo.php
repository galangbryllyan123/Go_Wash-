<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Site Logo
 * Description: 
 */
class TB_Site_Logo_Module extends Themify_Builder_Component_Module {
	
	public static function get_module_name():string {
		return __('Site Logo', 'tbp');
	}

	public static function get_module_icon():string {
		return 'flickr';
	}

	public static function get_static_content(array $module):string {
		return isset($module['mod_settings']['display']) && $module['mod_settings']['display']==='image' && !empty($module['mod_settings']['url_image'])?parent::get_static_content($module):'';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('site-logo');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'site-logo',
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
							self::get_font_family(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_f_g'),
							self::get_color_type(array(' .site-logo-inner', ' a'), '', 'f_c_t', 'f_c', 'f_g_c'),
							self::get_font_size(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_s_g', ''),
							self::get_line_height(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_h_g'),
							self::get_letter_spacing(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_s_g'),
							self::get_text_align(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_a_g'),
							self::get_text_transform(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_t_g'),
							self::get_font_style(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_st_g', 'f_w_g'),
							self::get_text_decoration(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p', ' a'), 't_d_r_g'),
							self::get_text_shadow(array('', '.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_sh_g'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('', '.module:hover h1', '.module:hover h2', '.module:hover h3', '.module:hover h4', '.module:hover h5', ':hover h6', ':hover p'), 'f_f_g_h', ''),
							self::get_color_type(array(' .site-logo-inner:hover', ':hover a'), '', 'f_c_t_h', 'f_c_h', 'f_g_c_h', ''),
							self::get_font_size(array('', '.module:hover h1', '.module:hover h2', '.module:hover h3', '.module:hover h4', '.module:hover h5', ':hover h6', ':hover p'), 'f_s_g_h', '', ''),
							self::get_font_style(array('.module:hover h1', '.module:hover h2', '.module:hover h3', '.module:hover h4', '.module:hover h5', '.module:hover h6', ':hover p'), 'f_st_g_h', 'f_w_g_h', ''),
							self::get_text_decoration(array('.module:hover h1', '.module:hover h2', '.module:hover h3', '.module:hover h4', '.module:hover h5', '.module:hover h6', ':hover p', ':hover a'), 't_d_r_g_h', ''),
							self::get_text_shadow(array('.module:hover h1', '.module:hover h2', '.module:hover h3', '.module:hover h4', '.module:hover h5', '.module:hover h6', ':hover p'), 't_sh_g_h', ''),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module a', 'l_c'),
							self::get_text_decoration('.module a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module a', 'l_c', null, null, 'hover'),
							self::get_text_decoration('.module a', 't_d_l', 'h')
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
							'options' => count($a = self::get_blend(array(' > h1', ' > h2', ' > h3', ' > h4', ' > h5', ' > h6', ' > p'), 'fl')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(array(' > h1', ' > h2', ' > h3', ' > h4', ' > h5', ' > h6', ' > p'), 'fl_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
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
				self::get_expand(sprintf(__('Heading %s', 'tbp'), $i), array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_font_family('.module ' . $selector, 'f_f_' . $h),
								self::get_color_type(array('.module ' . $selector, '.module ' . $selector . ' a'), '', 'f_c_t_' . $h, 'f_c_' . $h, 'f_g_c_' . $h),
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

		$image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' img', 'b_c_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' img', 'b_c_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' img', 'p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' img', 'p_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' img', 'm_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' img', 'm_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' img', 'b_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' img', 'b_i', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img', '', 'fl_img')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img', '', 'fl_img_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh_i', 'h')
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
				'head' => array(
					'options' => $heading
				),
				'image' => array(
					'options' => $image
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
		new TB_Site_Logo_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Site_Logo_Module');
	}
}
