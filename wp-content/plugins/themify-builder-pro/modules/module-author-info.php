<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Author Info
 * Description: 
 */
class TB_Author_Info_Module extends Themify_Builder_Component_Module {
	
	
	public static function get_module_name():string {
		return __('Author Info', 'tbp');
	}

	public static function get_module_icon():string {
		return 'id-badge';
	}
	
	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('author-info');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'author-info',
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
							self::get_font_family('', 'f_f'),
							self::get_color_type(array(' .tbp_author_info_link', ' .tbp_author_info_bio'), '', 'f_c_t', 'f_c', 'f_g_c'),
							self::get_font_size('', 'f_s'),
							self::get_line_height(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 'l_h'),
							self::get_letter_spacing(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 'l_s'),
							self::get_text_align('', 't_a'),
							self::get_text_transform(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 't_t'),
							self::get_font_style(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 'f_st', 'f_w'),
							self::get_text_decoration(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 't_d_r'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color_type(array(' .tbp_author_info_link', ' .tbp_author_info_bio'), '', 'f_c_t_h', 'f_c_h', 'f_g_c_h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_font_style(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 'f_st', 'f_w', 'h'),
							self::get_text_decoration(array(' .tbp_author_info_name', ' .tbp_author_info_bio'), 't_d_r', 'h'),
							self::get_text_shadow('', 't_sh', 'h'),
						)
					)
				))
			)),
			// Paragraph
			self::get_expand('pa', array(
				self::get_heading_margin_multi_field('', 'p', 'top'),
				self::get_heading_margin_multi_field('', 'p', 'bottom')
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
			// Position
			self::get_expand('po', array(self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_author_info_img img', 'b_c_ai_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_author_info_img img', 'b_c_ai_i_h', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_author_info_img img', 'p_ai_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_author_info_img img', 'p_ai_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_author_info_img', 'm_ai_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_author_info_img', 'm_ai_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_author_info_img img', 'b_ai_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_author_info_img img', 'b_ai_i', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_author_info_img img', 'r_c_ai_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_author_info_img img', 'r_c_ai_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_author_info_img img', 'sh_ai_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_author_info_img img', 'sh_ai_i', 'h')
						)
					)
				))
			))
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
								self::get_font_family('.module ' . $selector, 'font_family_' . $h),
								self::get_color_type('.module ' . $selector, '', 'font_color_type_' . $h, 'font_color_' . $h, 'font_gradient_color_' . $h),
								self::get_font_size('.module ' . $h, 'font_size_' . $h),
								self::get_line_height('.module ' . $h, 'line_height_' . $h),
								self::get_letter_spacing('.module ' . $h, 'letter_spacing_' . $h),
								self::get_text_transform('.module ' . $h, 'text_transform_' . $h),
								self::get_font_style('.module ' . $h, 'font_style_' . $h, 'font_weight_' . $h),
								self::get_text_shadow('.module ' . $selector, 't_sh' . $h),
								// Heading  Margin
								self::get_heading_margin_multi_field('.module', $h, 'top'),
								self::get_heading_margin_multi_field('.module', $h, 'bottom')
							)
						),
						'h' => array(
							'options' => array(
								self::get_font_family('.module:hover ' . $selector, 'f_f_' . $h . '_h'),
								self::get_color_type('.module:hover ' . $selector, '', 'f_c_t_' . $h . '_h', 'f_c_' . $h . '_h', 'f_g_c_' . $h . '_h'),
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
		new TB_Author_Info_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Author_Info_Module');
	}
}