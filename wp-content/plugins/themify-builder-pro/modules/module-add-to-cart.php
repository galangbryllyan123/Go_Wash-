<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Add To Cart
 * Description: 
 */
class TB_Add_To_Cart_Module extends Themify_Builder_Component_Module {

	public static $cartText;
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return _x('Add To Cart', 'Builder Pro module name', 'tbp');
	}

	public static function get_module_icon():string {
		return  'shopping-cart-full';
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'js' => TBP_JS_MODULES . 'add-to-cart',
			'css' => TBP_WC_CSS_MODULES . 'add-to-cart'
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
			parent::__construct('add-to-cart');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'add-to-cart',
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
							self::get_font_family('', 'f_f_g'),
							self::get_color_type('', '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size('', 'f_s_g', ''),
							self::get_line_height('', 'l_h_g'),
							self::get_letter_spacing(array(' .qty', '.module .button.alt'), 'l_s_g'),
							self::get_text_align('', 't_a_g'),
							self::get_text_transform(array(' .qty', '.module .button.alt'), 't_t_g'),
							self::get_font_style(array(' .qty', '.module .button.alt'), 'f_st_g', 'f_w_g'),
							self::get_text_decoration(array(' .qty', '.module .button.alt'), 't_d_r_g'),
							self::get_text_shadow('', 't_sh_g', 'h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_g_h'),
							self::get_color_type('', '', 'f_c_t_g_h', 'f_c_g_h', 'f_g_c_g_h'),
							self::get_font_size('', 'f_s_g', '', 'h'),
							self::get_font_style(array(' .qty', '.module .button.alt'), 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration(array(' .qty', '.module .button.alt'), 't_d_r_g', 'h'),
							self::get_text_shadow('', 't_sh_g', 'h'),
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

		$quantity_input = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .quantity .qty', 'b_c_q_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .quantity .qty', 'b_c_q_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .quantity .qty', 'f_f_q_i'),
							self::get_color('.module .quantity .qty', 'f_c_q_i'),
							self::get_font_size('.module .quantity .qty', 'f_s_q_i', ''),
							self::get_line_height('.module .quantity .qty', 'l_h_q_i'),
							self::get_letter_spacing('.module .quantity .qty', 'l_s_q_i'),
							self::get_text_transform('.module .quantity .qty', 't_t_q_i'),
							self::get_font_style('.module .quantity .qty', 'f_st_q_i', 'f_w_q_i'),
							self::get_text_decoration('.module .quantity .qty', 't_d_r_q_i'),
							self::get_text_shadow('.module .quantity .qty', 't_sh_q_i'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .quantity .qty', 'f_f_q_i', 'h'),
							self::get_color('.module .quantity .qty', 'f_c_q_i', null, null, 'h'),
							self::get_font_size('.module .quantity .qty', 'f_s_q_i', '', 'h'),
							self::get_font_style('.module .quantity .qty', 'f_st_q_i', 'f_w_q_i', 'h'),
							self::get_text_decoration('.module .quantity .qty', 't_d_r_q_i', 'h'),
							self::get_text_shadow('.module .quantity .qty', 't_sh_q_i', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .quantity .qty', 'p_q_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .quantity .qty', 'p_q_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .quantity .qty', 'm_q_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .quantity .qty', 'm_q_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .quantity .qty', 'b_q_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .quantity .qty', 'b_q_i', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .quantity .qty', 'r_c_q_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .quantity .qty', 'r_c_q_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .quantity .qty', 'sh_q_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .quantity .qty', 'sh_q_i', 'h')
						)
					)
				))
			))
		);

		$variable_select = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .variations select', 'b_c_v_s', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .variations select', 'b_c_v_s', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .variations select', 'f_f_v_s'),
							self::get_color(' .variations select', 'f_c_v_s'),
							self::get_font_size(' .variations select', 'f_s_v_s', ''),
							self::get_line_height(' .variations select', 'l_h_v_s'),
							self::get_letter_spacing(' .variations select', 'l_s_v_s'),
							self::get_text_transform(' .variations select', 't_t_v_s'),
							self::get_font_style(' .variations select', 'f_st_v_s', 'f_w_v_s'),
							self::get_text_decoration(' .variations select', 't_d_r_v_s'),
							self::get_text_shadow(' .variations select', 't_sh_v_s'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .variations select', 'f_f_v_s', 'h'),
							self::get_color(' .variations select', 'f_c_v_s', null, null, 'h'),
							self::get_font_size(' .variations select', 'f_s_v_s', '', 'h'),
							self::get_font_style(' .variations select', 'f_st_v_s', 'f_w_atc_btn', 'h'),
							self::get_text_decoration(' .variations select', 't_d_r_v_s', 'h'),
							self::get_text_shadow(' .variations select', 't_sh_v_s', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .variations select', 'b_v_s')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .variations select', 'b_v_s', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .variations select', 'r_c_v_s')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .variations select', 'r_c_v_s', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .variations select', 'sh_v_s')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .variations select', 'sh_v_s', 'h')
						)
					)
				))
			))
		);

		$button = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .button', 'b_c_atc_btn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .button', 'b_c_atc_btn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .button', 'f_f_atc_btn'),
							self::get_color('.module .button', 'f_c_atc_btn'),
							self::get_font_size('.module .button', 'f_s_atc_btn', ''),
							self::get_line_height('.module .button', 'l_h_atc_btn'),
							self::get_letter_spacing('.module .button', 'l_s_atc_btn'),
							self::get_text_align('.module .button', 't_a_atc_btn'),
							self::get_text_transform('.module .button', 't_t_atc_btn'),
							self::get_font_style('.module .button', 'f_st_atc_btn', 'f_w_atc_btn'),
							self::get_text_decoration('.module .button', 't_d_r_atc_btn'),
							self::get_text_shadow('.module .button', 't_sh_atc_btn'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .button', 'f_f_atc_btn', 'h'),
							self::get_color('.module .button', 'f_c_atc_btn', null, null, 'h'),
							self::get_font_size('.module .button', 'f_s_atc_btn', '', 'h'),
							self::get_font_style('.module .button', 'f_st_atc_btn', 'f_w_atc_btn', 'h'),
							self::get_text_decoration('.module .button', 't_d_r_atc_btn', 'h'),
							self::get_text_shadow('.module .button', 't_sh_atc_btn', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .button', 'p_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .button', 'p_atc_btn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .button', 'm_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .button', 'm_atc_btn', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .button', 'b_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .button', 'b_atc_btn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .button', 'r_c_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .button', 'r_c_atc_btn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .button', 'sh_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .button', 'sh_atc_btn', 'h')
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
				'q' => array(
					'options' => $quantity_input
				),
				'tbp_varsel' => array(
					'options' => $variable_select
				),
				'btn' => array(
					'options' => $button
				)
			)
		);
	}


	public static function filterQuantityInput($located, $template_name, $args, $template_path, $default_path):string {
		if ($template_name === 'global/quantity-input.php') {
			$located = TBP_DIR . 'templates/wc/empty.php';
		}

		return $located;
	}

	public static function changeCartText($text) {
		return self::$cartText;
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}

if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Add_To_Cart_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Add_To_Cart_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Add_To_Cart_Module');
	}
}
