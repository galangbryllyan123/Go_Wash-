<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Featured Image
 * Description: 
 */
class TB_Featured_Image_Module extends Themify_Builder_Component_Module {
	
	
	public static function get_module_name():string {
		return __('Featured Image', 'tbp');
	}

	public static function get_module_icon():string {
		return 'image';
	}

	public static function get_js_css():array{
		return array(
			'css' => 'image'
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
			parent::__construct('featured-image');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'featured-image',
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
							self::get_image('.module img', 'b_i', 'bg_c', 'b_r', 'b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('.module img', 'b_i', 'bg_c', 'b_r', 'b_p', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module img', 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module img', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module', 'm')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module img', 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module img', 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img', 'bl_m_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_width('', 'w')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module img', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module img', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module img', 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module img', 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array(self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$featured_caption = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .image-content', 'c_b_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .image-content', 'c_b_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Background
			self::get_expand('coverlay', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array('.image-overlay .image-content', '.image-full-overlay .image-content::before', '.image-card-layout .image-content'), 'b_c_c', __('Overlay', 'tbp'), 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array('.image-overlay:hover .image-content', '.image-full-overlay:hover .image-content::before', '.image-card-layout:hover .image-content'), 'b_c_c_h', __('Overlay', 'tbp'), 'background-color'),
							self::get_color(array('.image-overlay:hover .image-title', '.image-overlay:hover .image-caption', '.image-full-overlay:hover .image-title', '.image-full-overlay:hover .image-caption', '.image-card-layout:hover .image-content', '.image-card-layout:hover .image-title'), 'f_c_c_h', __('Overlay Font Color', 'tbp'))
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .image-caption', 'font_family_caption'),
							self::get_color('.module .image-caption', 'font_color_caption'),
							self::get_font_size('.module .image-caption', 'font_size_caption'),
							self::get_line_height('.module  .image-caption', 'line_height_caption'),
							self::get_text_shadow('.module .image-caption', 't_sh_c'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .image-caption', 'f_f_c', 'h'),
							self::get_color(array('.module:hover .image-caption', '.module:hover .image-title'), 'f_c_c_h', NULL, NULL, ''),
							self::get_font_size('.module .image-caption', 'f_s_c', '', 'h'),
							self::get_text_shadow('.module .image-caption', 't_sh_c', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .image-content', 'c_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .image-content', 'c_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .image-content', 'c_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .image-content', 'c_m', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .image-content', 'c_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .image-content', 'c_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .image-content', 'c_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .image-content', 'c_sh', 'h')
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
				'imgc' => array(
					'options' => $featured_caption
				)
			)
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => '.module img'
        ];
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Featured_Image_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Featured_Image_Module');
	}
}