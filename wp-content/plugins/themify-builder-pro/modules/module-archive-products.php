<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Archive Products
 * Description:
 */
class TB_Archive_Products_Module extends Themify_Builder_Component_Module {

	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Archive Products', 'tbp');
	}

	public static function get_module_icon():string {
		return 'layout-grid2';
	}

	public static function get_query(array &$args,array $fields_args) {
		if (Tbp_Public::$isTemplatePage === true) {
			$args['ignore_sticky_posts'] = true;
		}
		if(!themify_is_shop() && Tbp_Utils::is_wc_archive()){
			/* in WC archive pages show the main query of the page */
			global $woocommerce;
			$obj = get_queried_object();
			if(isset($obj,$woocommerce)){
				$args['tax_query'] = array(
					array(
						'taxonomy' => $obj->taxonomy,
						'field' => 'term_id',
						'terms' => $obj->term_id,
						'operator' => 'IN'
					)
				);
				$args['tax_query'] = $woocommerce->query->get_tax_query($args['tax_query'], true);
			}
		}
		else{
			global $wp_query;
			if (is_array($wp_query->query)) {
				$args +=$wp_query->query;
			}
		}
	}

    public static function join_filter( $join ) {
        global $wpdb;
        $join .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

        return $join;
    }

    public static function orderby_asc_filter( $orderby ) {
        $orderby = "wc_product_meta_lookup.min_price ASC, wc_product_meta_lookup.product_id ASC";
        return $orderby;
    }

    public static function orderby_desc_filter( $orderby ) {
        $orderby = "wc_product_meta_lookup.max_price DESC, wc_product_meta_lookup.product_id DESC";
        return $orderby;
    }

	/**
	 * Render plain content for static content.
	 * 
	 * @param array $module 
	 * @return string
	 */
	public static function get_static_content(array $module):string {
		return '';
	}
	
	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('archive-products');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'archive-products',
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
							self::get_color('', 'b_c_g', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('', 'b_c_g', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_f_g'),
							self::get_color_type(array(' .product', ' p', ' button'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size(array(' .tbp_title', ' p', ' button'), 'f_s_g'),
							self::get_line_height(array(' .tbp_title', ' p', ' button'), 'l_h_g'),
							self::get_letter_spacing(array(' .tbp_title', ' p', ' button'), 'l_s_g'),
							self::get_text_align(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_a_g'),
							self::get_text_transform(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_t_g'),
							self::get_font_style(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b'),
							self::get_text_shadow(array(' a:not(.post-edit-link)', ' p', ' button'), 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_f_g', 'h'),
							self::get_color_type(array(' .product', ' p', ' button'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g', 'h'),
							self::get_font_size(array(' .tbp_title', ' p', ' button'), 'f_s_g', 'h'),
							self::get_font_style(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b', 'h'),
							self::get_text_shadow(array(' a:not(.post-edit-link)', ' p', ' button'), 't_sh', 'h'),
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
				)
			),
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
				)
			),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$ap_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .product', 'b_c_a_p_cn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .product', 'b_c_a_p_cn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .product', 'p_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .product', 'p_cn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .products', 'li', 'top'),
							self::get_heading_margin_multi_field(' .products', 'li', 'bottom')
						)
					),
					'h' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .products', 'li', 'top', 'h'),
							self::get_heading_margin_multi_field(' .products', 'li', 'bottom', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .product', 'b_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .product', 'b_cn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_cn', 'h')
						)
					)
				))
			)),
		);

		$ap_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array('.module .tbp_title', '.module .tbp_title a'), 'f_f_a_p_t'),
							self::get_color(array('.module .tbp_title', '.module .tbp_title a'), 'f_c_a_p_t'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t'),
							self::get_line_height('.module .tbp_title', 'l_h_a_p_t'),
							self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t'),
							self::get_text_transform(array('.module .tbp_title', ' .tbp_title a'), 't_t_a_p_t'),
							self::get_font_style(array('.module .tbp_title', ' .tbp_title a'), 'f_sy_a_p_t', 'f_w_a_p_t'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t'),
							self::get_text_shadow(array('.module .tbp_title', '.module .tbp_title a'), 't_sh_a_p_t'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('.module .tbp_title', '.module .tbp_title a'), 'f_f_a_p_t', 'h'),
							self::get_color(array('.module .tbp_title', '.module .tbp_title a'), 'f_c_a_p_t', null, null, 'hover'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t', '', 'h'),
							self::get_font_style(array('.module .tbp_title', ' .tbp_title a'), 'f_sy_a_p_t', 'f_w_a_p_t', 'h'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t', 'h'),
							self::get_text_shadow(array('.module .tbp_title', '.module .tbp_title a'), 't_sh_a_p_t', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .tbp_title', 'p_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .tbp_title', 'p_a_p_t', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .tbp_title', 'm_a_p_t'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .tbp_title', 'm_a_p_t', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .tbp_title', 'b_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .tbp_title', 'b_a_p_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t', 'h')
						)
					)
				))
			)),
		);

		$ap_image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .product-image img', 'b_c_a_p_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .product-image img', 'b_c_a_p_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .product-image img', 'p_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .product-image img', 'p_a_p_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .product-image', 'm_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .product-image', 'm_a_p_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .product-image img', 'b_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .product-image img', 'b_a_p_i', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product-image img', 'r_c_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product-image img', 'r_c_a_p_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product-image img', 'sh_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product-image img', 'sh_a_p_i', 'h')
						)
					)
				))
			))
		);

		$ap_description = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_c_a_p_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_c_a_p_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_f_a_p_c'),
							self::get_color(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_c_a_p_c'),
							self::get_font_size(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_s_a_p_c'),
							self::get_line_height(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'l_h_a_p_c'),
							self::get_text_align(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_a_a_p_c'),
							self::get_text_shadow(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_sh_a_p_c'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_f_a_p_c', 'h'),
							self::get_color(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_c_a_p_c', null, null, 'h'),
							self::get_font_size(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_s_a_p_c', '', 'h'),
							self::get_text_shadow(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_sh_a_p_c', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .product-description', ' .woocommerce-product-details__short-description'), 'p_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .product-description', ' .woocommerce-product-details__short-description'), 'p_a_p_c', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array(' .product-description', ' .woocommerce-product-details__short-description'), 'm_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array(' .product-description', ' .woocommerce-product-details__short-description'), 'm_a_p_c', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_a_p_c', 'h')
						)
					)
				))
			))
		);

		$ap_price = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_f_p'),
							self::get_color_type(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_c_t_p', 'f_c_p', 'f_g_c_p'),
							self::get_font_size(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_s_p', ''),
							self::get_line_height(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_h_p'),
							self::get_letter_spacing(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_s_p'),
							self::get_text_align(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_a_p'),
							self::get_text_transform(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_t_p'),
							self::get_font_style(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_st_p', 'f_w_p'),
							self::get_text_decoration(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_d_r_p'),
							self::get_text_shadow(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_sh_p'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_f_p', 'h'),
							self::get_color_type(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_c_t_p', 'f_c_p', 'f_g_c_p', 'h'),
							self::get_font_size(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_s_p', '', 'h'),
							self::get_font_style(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_st_p', 'f_w_p', 'h'),
							self::get_text_decoration(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_d_r_p', 'h'),
							self::get_text_shadow(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_sh_p', 'h'),
						)
					)
				))
			))
		);

		$ap_rating = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .star-rating', 'b_c_ap_r', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .star-rating', 'b_c_ap_r', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .star-rating', 'f_c_ap_r'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .star-rating', 'f_c_g_ap_r', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .star-rating', 'p_ap_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .star-rating', 'p_ap_r', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .star-rating', 'm_ap_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .star-rating', 'm_ap_r', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .star-rating', 'b_ap_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .star-rating', 'b_ap_r', 'h')
						)
					)
				))
			))
		);

		$ap_add_to_cart = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .add_to_cart_button', 'b_c_ap_atc', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .add_to_cart_button', 'b_c_ap_atc', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .add_to_cart_button', 'f_f_ap_atc'),
							self::get_color_type('.module .product .add_to_cart_button', '', 'f_c_t_ap_atc', 'f_c_ap_atc', 'f_c_g_ap_atc'),
							self::get_font_size('.module .product .add_to_cart_button', 'f_s_ap_atc', ''),
							self::get_line_height('.module .add_to_cart_button', 'l_h_ap_atc'),
							self::get_letter_spacing('.module .add_to_cart_button', 'l_s_ap_atc'),
							self::get_text_align('.module .add_to_cart_button', 't_a_ap_atc'),
							self::get_text_transform('.module .add_to_cart_button', 't_t_ap_atc'),
							self::get_font_style('.module .add_to_cart_button', 'f_st_ap_atc', 'f_w_ap_atc'),
							self::get_text_decoration('.module .add_to_cart_button', 't_d_r_ap_atc'),
							self::get_text_shadow('.module .add_to_cart_button', 't_sh_ap_atc'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .add_to_cart_button', 'f_f_ap_act', 'h'),
							self::get_color_type('.module .product .add_to_cart_button:hover', '', 'f_c_t_ap_act_h', 'f_c_ap_act_h', 'f_c_g_ap_act_h', 'h'),
							self::get_font_size('.module .product .add_to_cart_button', 'f_s_ap_act', '', 'h'),
							self::get_font_style('.module .add_to_cart_button', 'f_st_ap_act', 'f_w_ap_act', 'h'),
							self::get_text_decoration('.module .add_to_cart_button', 't_d_r_ap_act', 'h'),
							self::get_text_shadow('.module .add_to_cart_button', 't_sh_ap_act', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .add_to_cart_button', 'p_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .add_to_cart_button', 'p_ap_act', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .product .add_to_cart_button', 'm_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .product .add_to_cart_button', 'm_ap_act', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .add_to_cart_button', 'b_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .add_to_cart_button', 'b_ap_act', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .add_to_cart_button', 'r_c_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .add_to_cart_button', 'r_c_ap_act', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .add_to_cart_button', 'sh_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .add_to_cart_button', 'sh_ap_act', 'h')
						)
					)
				))
			)),
			// Quantity
			self::get_expand('q', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .cart .quantity .qty', 'b_c_atc_q', 'bg_c', 'background-color'),
							self::get_color(' .cart .quantity .qty', 'c_atc_q'),
							self::get_padding(' .cart .quantity .qty', 'p_atc_q'),
							self::get_margin(' .cart .quantity', 'm_atc_q'),
							self::get_border(' .cart .quantity .qty', 'b_atc_q'),
							self::get_width(' .cart .quantity .qty', 'w_atc_q'),
							self::get_height(' .cart .quantity .qty', 'h_atc_q'),
							self::get_border_radius(' .cart .quantity .qty', 'r_c_atc_q'),
							self::get_box_shadow(' .cart .quantity .qty', 'sh_atc_q')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .cart .quantity .qty:hover', 'b_c_atc_q_h', 'bg_c', 'background-color', null, 'h'),
							self::get_color(' .cart .quantity .qty', 'c_atc_q_h', null, null, 'h'),
							self::get_padding(' .cart .quantity .qty', 'p_atc_q', 'h'),
							self::get_margin(' .cart .quantity', 'm_atc_q', 'h'),
							self::get_border(' .cart .quantity .qty', 'b_atc_q', 'h'),
							self::get_border_radius(' .cart .quantity .qty', 'r_c_atc_q', 'h'),
							self::get_box_shadow(' .cart .quantity .qty', 'sh_atc_q', 'h')
						)
					)
				)),
			))
		);

		$ap_pg_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .pagenav', 'b_c_pg_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .pagenav', 'b_c_pg_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .pagenav', 'f_f_pg_c'),
							self::get_color(' .pagenav', 'f_c_pg_c'),
							self::get_font_size(' .pagenav', 'f_s_pg_c'),
							self::get_line_height(' .pagenav', 'l_h_pg_c'),
							self::get_letter_spacing(' .pagenav', 'l_s_pg_c'),
							self::get_text_align(' .pagenav', 't_a_pg_c'),
							self::get_font_style(' .pagenav', 'f_st_pg_c', 'f_b_pg_c'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .pagenav', 'f_f_pg_c', 'h'),
							self::get_color(' .pagenav', 'f_c_pg_c', 'h'),
							self::get_font_size(' .pagenav', 'f_s_pg_c', '', 'h'),
							self::get_font_style(' .pagenav', 'f_st_pg_c', 'f_b_pg_c', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .pagenav', 'p_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .pagenav', 'p_pg_c', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .pagenav', 'm_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .pagenav', 'm_pg_c', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .pagenav', 'b_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .pagenav', 'b_pg_c', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav', 'r_c_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav', 'r_c_pg_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav', 'sh_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav', 'sh_pg_c', 'h')
						)
					)
				))
			))
		);

		$ap_pg_numbers = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .pagenav a', 'b_c_pg_n', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .pagenav a', 'b_c_pg_n', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(

				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .pagenav a', 'f_f_pg_n'),
							self::get_color(' .pagenav a', 'f_c_pg_n'),
							self::get_font_size(' .pagenav a', 'f_s_pg_n'),
							self::get_line_height(' .pagenav a', 'l_h_pg_n'),
							self::get_letter_spacing(' .pagenav a', 'l_s_pg_n'),
							self::get_text_align(' .pagenav a', 't_a_pg_n'),
							self::get_font_style(' .pagenav a', 'f_st_pg_n', 'f_b_pg_n'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .pagenav a', 'f_f_pg_n', 'h'),
							self::get_color(' .pagenav a', 'f_c_pg_n', 'h'),
							self::get_font_size(' .pagenav a', 'f_s_pg_n', '', 'h'),
							self::get_font_style(' .pagenav a', 'f_st_pg_n', 'f_b_pg_n', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .pagenav a', 'p_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .pagenav a', 'p_pg_n', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .pagenav a', 'm_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .pagenav a', 'm_pg_n', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .pagenav a', 'b_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .pagenav a', 'b_pg_n', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav a', 'r_c_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav a', 'r_c_pg_n', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav a', 'sh_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav a', 'sh_pg_n', 'h')
						)
					)
				))
			))
		);

		$ap_pg_a_numbers = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .pagenav .current', 'b_c_pg_a_n', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .pagenav .current', 'b_c_pg_a_n', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .pagenav .current', 'f_f_pg_a_n'),
							self::get_color(' .pagenav .current', 'f_c_pg_a_n'),
							self::get_font_size(' .pagenav .current', 'f_s_pg_a_n'),
							self::get_line_height(' .pagenav .current', 'l_h_pg_a_n'),
							self::get_letter_spacing(' .pagenav .current', 'l_s_pg_a_n'),
							self::get_text_align(' .pagenav .current', 't_a_pg_a_n'),
							self::get_font_style(' .pagenav .current', 'f_st_pg_a_n', 'f_b_pg_a_n'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .pagenav .current', 'f_f_pg_a_n', 'h'),
							self::get_color(' .pagenav .current', 'f_c_pg_a_n', 'h'),
							self::get_font_size(' .pagenav .current', 'f_s_pg_a_n', '', 'h'),
							self::get_font_style(' .pagenav .current', 'f_st_pg_a_n', 'f_b_pg_a_n', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .pagenav .current', 'p_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .pagenav .current', 'p_pg_a_n', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .pagenav .current', 'm_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .pagenav .current', 'm_pg_a_n', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .pagenav .current', 'b_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .pagenav .current', 'b_pg_a_n', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav .current', 'r_c_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav .current', 'r_c_pg_a_n', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav .current', 'sh_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav .current', 'sh_pg_a_n', 'h')
						)
					)
				))
			))
		);

		$ap_meta = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .product_meta', 'b_c_ap_m', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .product_meta', 'b_c_ap_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .product_meta', ' .product_meta a'), 'f_f_ap_m'),
							self::get_color(array(' .product_meta', ' .product_meta a'), 'f_c_ap_m'),
							self::get_font_size(' .product_meta', 'f_s_ap_m'),
							self::get_line_height(' .product_meta', 'l_h_ap_m'),
							self::get_letter_spacing(' .product_meta', 'l_s_ap_m'),
							self::get_text_transform(' .product_meta', 't_t_ap_m'),
							self::get_font_style(' .product_meta', 'f_sy_ap_m', 'f_w_ap_m'),
							self::get_text_decoration(' .product_meta', 't_d_ap_m'),
							self::get_text_shadow(array(' .product_meta', ' .product_meta a'), 't_sh_ap_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .product_meta', ' .product_meta a'), 'f_f_ap_m', 'h'),
							self::get_color(array(' .product_meta', ' .product_meta a'), 'f_c_ap_m', null, null, 'hover'),
							self::get_font_size(' .product_meta', 'f_s_ap_m', '', 'h'),
							self::get_font_style(' .product_meta', 'f_sy_ap_m', 'f_w_ap_m', 'h'),
							self::get_text_decoration(' .product_meta', 't_d_ap_m', 'h'),
							self::get_text_shadow(array(' .product_meta', ' .product_meta a'), 't_sh_ap_m', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .product_meta a', 'l_c'),
							self::get_text_decoration('.module .product_meta a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .product_meta a', 'l_c', null, null, 'hover'),
							self::get_text_decoration('.module .product_meta a', 't_d_l', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .product_meta', 'p_ap_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .product_meta', 'p_ap_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .product_meta', 'm_ap_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .product_meta', 'm_ap_m', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .product_meta', 'b_ap_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .product_meta', 'b_ap_m', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product_meta', 'sh_ap_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product_meta', 'sh_ap_m', 'h')
						)
					)
				))
			)),
		);

		$ap_sort = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .woocommerce-ordering select', 'b_c_ap_st', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .woocommerce-ordering select', 'b_c_ap_st', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_text_align(' .woocommerce-ordering select', 't_a_ap_st'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_text_align(' .woocommerce-ordering select', 't_a_ap_st', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .woocommerce-ordering select', 'p_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .woocommerce-ordering select', 'p_ap_st', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .woocommerce-ordering select', 'm_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .woocommerce-ordering select', 'm_ap_st', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .woocommerce-ordering select', 'b_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .woocommerce-ordering select', 'b_ap_st', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_ap_st', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_ap_st', 'h')
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
				'tbp_srt' => array(
					'options' => $ap_sort
				),
				'conter' => array(
					'options' => $ap_container
				),
				'title' => array(
					'options' => $ap_title
				),
				'meta' => array(
					'options' => $ap_meta
				),
				'image' => array(
					'options' => $ap_image
				),
				'desc' => array(
					'options' => $ap_description
				),
				'price' => array(
					'options' => $ap_price
				),
				'rating' => array(
					'options' => $ap_rating
				),
				'tbp_addcart' => array(
					'options' => $ap_add_to_cart
				),
				'pagincont' => array(
					'options' => $ap_pg_container
				),
				'paginnum' => array(
					'options' => $ap_pg_numbers
				),
				'paginactiv' => array(
					'options' => $ap_pg_a_numbers
				)
			)
		);
	}

	

	public function get_plain_content($module) {//deprecated,backward
		return self::get_static_content($module);
	}
}

if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Archive_Products_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Archive_Products_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Archive_Products_Module');
	}
}
