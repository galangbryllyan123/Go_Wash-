<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Product Taxonomy
 * Description: 
 */
class TB_Product_Taxonomy_Module extends Themify_Builder_Component_Module {
	
	public static function init():void{
		add_filter('tb_select_dataset_tbp_product_taxonomy', [__CLASS__, 'get_product_taxonomy']);
	}
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Product Taxonomy', 'tbp');
	}

	public static function get_module_icon():string {
		return 'more';
	}

	public static function get_product_taxonomy(array $taxes) {
		$taxonomies = get_object_taxonomies('product', 'object');
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $tax) {
				if ($tax->public && $tax->name !== 'product_shipping_class') {
					$taxes[$tax->name] =$tax->label;
				}
			}
		}
		return $taxes;
	}
	
	
	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('product-taxonomy');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'product-taxonomy',
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
							self::get_color_type(array(' .tbp_product_meta', ' .tbp_product_meta a'), '', 'f_c_t', 'f_c', 'f_g_c'),
							self::get_font_size('', 'f_s'),
							self::get_line_height('', 'l_h'),
							self::get_letter_spacing('', 'l_s'),
							self::get_text_align(' .tbp_product_meta', 't_a'),
							self::get_text_transform('', 't_t'),
							self::get_font_style('', 'f_st', 'f_w'),
							self::get_text_decoration('', 't_d_r'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color_type(array(' .tbp_product_meta:hover', ' .tbp_product_meta a:hover'), '', 'f_c_t_h', 'f_c_h', 'f_g_c_h'),
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
							self::get_color_type(' .tbp_product_meta a', '', 'l_c_t', 'l_c', 'l_g_c'),
							self::get_text_decoration(' .tbp_product_meta a', 't_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color_type(' .tbp_product_meta a:hover', '', 'l_c_t_h', 'l_c_h', 'l_g_c_h'),
							self::get_text_decoration(' .tbp_product_meta a', 't_d', 'h')
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

		$categories = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_product_meta_terms a', 'b_c_cg', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_product_meta_terms a', 'b_c_cg', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_product_meta_terms a', 'f_f_cg'),
							self::get_color_type(' .tbp_product_meta_terms a', '', 'f_c_t_cg', 'f_c_cg', 'f_g_c_cg'),
							self::get_font_size(' .tbp_product_meta_terms a', 'f_s_cg'),
							self::get_line_height(' .tbp_product_meta_terms a', 'l_h_cg'),
							self::get_letter_spacing(' .tbp_product_meta_terms a', 'l_s_cg'),
							self::get_text_align(' .tbp_product_meta_terms a', 't_a_cg'),
							self::get_text_transform(' .tbp_product_meta_terms a', 't_t_cg'),
							self::get_font_style(' .tbp_product_meta_terms a', 'f_st_cg', 'f_w_cg'),
							self::get_text_decoration(' .tbp_product_meta_terms a', 't_d_r_cg'),
							self::get_text_shadow(' .tbp_product_meta_terms a', 't_sh_cg'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_product_meta_terms a', 'f_f_cg_h'),
							self::get_color_type(' .tbp_product_meta_terms a:hover', '', 'f_c_t_cg_h', 'f_c_cg_h', 'f_g_c_cg_h', 'h'),
							self::get_font_size(' .tbp_product_meta_terms a', 'f_s_cg', '', 'h'),
							self::get_font_style(' .tbp_product_meta_terms a', 'f_st_cg', 'f_w_cg', 'h'),
							self::get_text_decoration(' .tbp_product_meta_terms a', 't_d_r_cg', 'h'),
							self::get_text_shadow(' .tbp_product_meta_terms a', 't_sh_cg', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_product_meta_terms a', 'p_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_product_meta_terms a', 'p_cg', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_product_meta_terms a', 'm_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_product_meta_terms a', 'm_cg', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_product_meta_terms a', 'b_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_product_meta_terms a', 'b_cg', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_product_meta_terms a', 'r_c_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_product_meta_terms a', 'r_c_cg', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_product_meta_terms a', 'sh_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_product_meta_terms a', 'sh_cg', 'h')
						)
					)
				))
			)),
		);

		$divider = array(
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_product_meta > span:after', 'm_dr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_product_meta > span:hover:after', 'm_dr_h', '')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_product_meta > span:after', 'b_dr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_product_meta > span:hover:after', 'b_dr_h', '')
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
				'tbp_terml' => array(
					'options' => $categories
				),
				'div' => array(
					'options' => $divider
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

if (TB_Product_Taxonomy_Module::is_available()) {
	if(method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
		TB_Product_Taxonomy_Module::init();
	}
	elseif (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Product_Taxonomy_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Taxonomy_Module');
	}
}