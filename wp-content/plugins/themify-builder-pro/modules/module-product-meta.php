<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Product Meta
 * Description: 
 */
class TB_Product_Meta_Module extends Themify_Builder_Component_Module {
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Product Meta', 'tbp');
	}

	public static function get_module_icon():string {
		return 'more';
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_WC_CSS_MODULES . 'product-meta'
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
			parent::__construct('product-meta');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'product-meta',
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
							self::get_font_family(' .product_meta', 'f_f_g'),
							self::get_color_type(array(' .product_meta', ' .product_meta a'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size(' .product_meta', 'f_s_g', ''),
							self::get_line_height(' .product_meta', 'l_h_g'),
							self::get_letter_spacing(array(' .product_meta span', ' .product_meta a'), 'l_s_g'),
							self::get_text_align(' .product_meta', 't_a_g'),
							self::get_text_transform(array(' .product_meta', ' .product_meta a'), '', 't_t_g'),
							self::get_font_style(' .product_meta', 'f_st_g', 'f_w_g'),
							self::get_text_decoration(' .product_meta', 't_d_r_g'),
							self::get_text_shadow(' .product_meta', 't_sh_g', 'h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .product_meta', 'f_f_g_h'),
							self::get_color_type(array(' .product_meta:hover', ' .product_meta:hover a'), '', 'f_c_t_g_h', 'f_c_g_h', 'f_g_c_g_h'),
							self::get_font_size(' .product_meta', 'f_s_g', '', 'h'),
							self::get_font_style(' .product_meta', 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration(' .product_meta', 't_d_r_g', 'h'),
							self::get_text_shadow(' .product_meta', 't_sh_g', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .product_meta a', 'l_c'),
							self::get_text_decoration(' .product_meta a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .product_meta a', 'l_c', null, null, 'hover'),
							self::get_text_decoration(' .product_meta a', 't_d_l', 'h')
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
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}


if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Product_Meta_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Product_Meta_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Meta_Module');
	}
}
