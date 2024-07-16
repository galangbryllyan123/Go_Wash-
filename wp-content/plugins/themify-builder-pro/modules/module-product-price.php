<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Product Price
 * Description: 
 */
class TB_Product_Price_Module extends Themify_Builder_Component_Module {

	/** stores the "sale_percentage_lbl" option for later-use */
	public static $cache_sale_label;


	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Product Price', 'tbp');
	}

	public static function get_module_icon():string {
		return 'money';
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_WC_CSS_MODULES . 'product-price'
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
			parent::__construct('product-price');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'product-price',
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
							self::get_font_family('.module .price', 'f_f_g'),
							self::get_color_type('.module .price', '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size('.module .price', 'f_s_g', ''),
							self::get_line_height('.module .price', 'l_h_g'),
							self::get_letter_spacing('.module .price', 'l_s_g'),
							self::get_text_align('.module .price', 't_a_g'),
							self::get_text_transform('.module .price', 't_t_g'),
							self::get_font_style('.module .price', 'f_st_g', 'f_w_g'),
							self::get_text_decoration('.module .price', 't_d_r_g'),
							self::get_text_shadow('.module .price', 't_sh_g', 'h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .price', 'f_f_g_h'),
							self::get_color_type('.module .price', '', 'f_c_t_g_h', 'f_c_g_h', 'f_g_c_g_h'),
							self::get_font_size('.module .price', 'f_s_g', '', 'h'),
							self::get_font_style('.module .price', 'f_st_g', 'f_w_g', 'h'),
							self::get_text_decoration('.module .price', 't_d_r_g', 'h'),
							self::get_text_shadow('.module .price', 't_sh_g', 'h'),
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

		$sale_price = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' p.price ins', 'f_f_s_p'),
							self::get_color_type(array(' p.price ins'), '', 'f_c_t_s_p', 'f_c_s_p', 'f_g_c_s_p'),
							self::get_font_size(' p.price ins', 'f_s_s_p', ''),
							self::get_line_height(' p.price ins', 'l_h_s_p'),
							self::get_letter_spacing(' p.price ins', 'l_s_s_p'),
							self::get_font_style(' p.price ins', 'f_st_s_p', 'f_w_s_p'),
							self::get_text_shadow(' p.price ins', 't_sh_s_p'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' p.price ins', 'f_f_s_p_h'),
							self::get_color_type(array(' p.price ins'), '', 'f_c_t_s_p_h', 'f_c_s_p_h', 'f_g_c_s_p_h'),
							self::get_font_size(' p.price ins', 'f_s_s_p', '', 'h'),
							self::get_font_style(' p.price ins', 'f_st_s_p', 'f_w_s_p', 'h'),
							self::get_text_shadow(' p.price ins', 't_sh_s_p', 'h'),
						)
					)
				))
			)),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'tbp_spric' => array(
					'options' => $sale_price
				)
			)
		);
	}


	private static function get_sale_percentage($product) {
		if ($product->is_type('variable')) {
			$percentages = array();

			// This will get all the variation prices and loop throughout them
			$prices = $product->get_variation_prices();

			foreach ($prices['price'] as $key => $price) {
				// Only on sale variations
				if ($prices['regular_price'][$key] !== $price) {
					// Calculate and set in the array the percentage for each variation on sale
					$percentages[] = round(100 - ( floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100 ));
				}
			}
			// Displays maximum discount value
			$percentage = max($percentages) . '%';
		} elseif ($product->is_type('grouped')) {
			$percentages = array();

			// This will get all the variation prices and loop throughout them
			$children_ids = $product->get_children();

			foreach ($children_ids as $child_id) {
				$child_product = wc_get_product($child_id);
				if(!empty($child_product)){
					$regular_price = (float) $child_product->get_regular_price();
					$sale_price = (float) $child_product->get_sale_price();

					if (!empty($sale_price)) {
						// Calculate and set in the array the percentage for each child on sale
						$percentages[] = round(100 - ( $sale_price / $regular_price * 100 ));
					}
				}
			}
			// Displays maximum discount value
			$percentage = max($percentages) . '%';
		} else {
			$regular_price = (float) $product->get_regular_price();
			$sale_price = (float) $product->get_sale_price();

			$percentage = !empty($sale_price) ? round(100 - ($sale_price / $regular_price * 100)) . '%' : 0;
		}

		return $percentage;
	}

	/**
	 * Filter woocommerce_get_price_html to add sale percentage
	 */
	public static function woocommerce_get_price_html(string $price, $product):string {
		$price .= sprintf('<span class="sale-percentage"> (%s-%s)</span>',
			( $product->is_type('variable') || $product->is_type('grouped') ) ? self::$cache_sale_label : '',
			self::get_sale_percentage($product)
		);

		return $price;
	}

	/**
	 * Filter woocommerce_format_sale_price, returns only the sale price
	 *
	 * @return string
	 */
	public static function woocommerce_format_sale_price($price, $regular_price, $sale_price) {
		return is_numeric($sale_price) ? wc_price($sale_price) : $sale_price;
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}


if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Product_Price_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Product_Price_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Price_Module');
	}
}
