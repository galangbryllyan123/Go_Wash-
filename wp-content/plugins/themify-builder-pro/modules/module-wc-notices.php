<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: WooCommerce Notices
 * Description: 
 */
class TB_Wc_Notices_Module extends Themify_Builder_Component_Module {
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('WooCommerce Notices', 'tbp');
	}

	public static function get_module_icon():string {
		return 'comments';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('wc-notices');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'wc-notices',
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
							self::get_image(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'b_i', 'bg_c', 'b_r', 'b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'b_i', 'bg_c', 'b_r', 'b_p', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_f_g'),
							self::get_color_type(array('', '.module p'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_s_g', ''),
							self::get_line_height(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'l_h_g'),
							self::get_letter_spacing(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'l_s_g'),
							self::get_text_align(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_a_g'),
							self::get_text_transform(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_t_g'),
							self::get_font_style(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_st_g', 'f_w_g'),
							self::get_text_decoration(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_d_r_g'),
							self::get_text_shadow(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_sh_g', 'h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_f_g_h'),
							self::get_color_type(array('', '.module p'), '', 'f_c_t_g_h', 'f_c_g_h', 'f_g_c_g_h'),
							self::get_font_size(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_s_g', '', 'h'),
							self::get_font_style(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_d_r_g', 'h'),
							self::get_text_shadow(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 't_sh_g', 'h'),
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
							self::get_padding(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'm')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'fl')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'fl_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_width(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'w')
			)),
			// Height & Min Height
			self::get_expand('ht', array(
				method_exists($this, 'get_max_width') ? self::get_height(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'g_h') : self::get_height(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'g_h', '', 'g_m_h', 'g_mx_h'),
				self::get_min_height(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'g_m_h'),
				self::get_max_height(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'g_mx_h')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info'), 'sh', 'h')
						)
					)
				))
			)),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$button = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .button', 'b_c_btn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .button', 'b_c_btn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .button', 'f_f_btn'),
							self::get_color('.module .button', 'f_c_btn'),
							self::get_font_size('.module .button', 'f_s_btn'),
							self::get_line_height('.module .button', 'l_h_btn'),
							self::get_letter_spacing('.module .button', 'l_s_btn'),
							self::get_text_align('.module .button', 't_a_btn'),
							self::get_text_transform('.module .button', 't_t_btn'),
							self::get_font_style('.module .button', 'f_st_btn', 'f_b_btn'),
							self::get_text_shadow('.module .button', 't_sh_btn'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .button', 'f_f_btn', 'h'),
							self::get_color('.module .button:hover', 'f_c_btn_h', 'h'),
							self::get_font_size('.module .button', 'f_s_btn', '', 'h'),
							self::get_font_style('.module .button', 'f_st_btn', 'f_b_btn', 'h'),
							self::get_text_shadow('.module .button', 't_sh_btn', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .button', 'p_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .button', 'p_btn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .button', 'm_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .button', 'm_btn', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .button', 'b_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .button', 'b_btn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .button', 'r_c_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .button', 'r_c_btn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .button', 'sh_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .button', 'sh_btn', 'h')
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
				'btn' => array(
					'options' => $button
				)
			)
		);
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => [ ' .woocommerce-message', ' .woocommerce-error', ' .woocommerce-info' ]
        ];
    }
}

if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Wc_Notices_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Wc_Notices_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Wc_Notices_Module');
	}
}
