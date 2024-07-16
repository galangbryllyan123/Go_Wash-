<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name:Advanced Products
 * Description:
 */
class TB_Advanced_Products_Module extends Themify_Builder_Component_Module {

	public static $builder_id = null;
	
	public static function get_module_name():string {
		return __('Advanced Products', 'tbp');
	}

	public static function get_module_icon():string {
		return 'layout-grid2';
	}

	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_WC_CSS_MODULES .  'advanced-products'
		);
	}

	public static function get_query(array &$args,array &$fields_args) {
		if(!isset( $fields_args[Tbp_Dynamic_Query::FIELD_NAME] ) || $fields_args[Tbp_Dynamic_Query::FIELD_NAME] === 'off' || !Tbp_Utils::is_wc_archive()){
			$args['ignore_sticky_posts'] = true;
			if ( $fields_args['term_type'] === 'category' ) {
				/* migration routine: translate old options to new */
				if ($fields_args['query_type'] === 'category') {
					$fields_args['query_type'] = 'product_cat';
					$fields_args['product_cat_terms'] = $fields_args['category_products'];
				} 
				elseif ($fields_args['query_type'] === 'tag') {
					$fields_args['query_type'] = 'product_tag';
					$fields_args['product_tag_terms'] = $fields_args['tag_products'];
				}
				$terms_id = $fields_args['query_type'] . '_terms';
				if (!empty($fields_args[$terms_id])) {
					Themify_Builder_Model::parseTermsQuery($args, $fields_args[$terms_id], $fields_args['query_type']);
				}
			}
			elseif ( $fields_args['term_type'] === 'post_slug' && ! empty( $fields_args['slug'] )) {
				$args['post__in'] = Themify_Builder_Model::parse_slug_to_ids( $fields_args['slug'], 'product' );
			}

			if (method_exists('Themify_Builder_Model', 'parse_query_filter')) {
				Themify_Builder_Model::parse_query_filter($fields_args, $args);
			}
			if ( isset( $fields_args['outofstock'] ) && $fields_args['outofstock'] === 'yes' ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => array('exclude-from-catalog', 'outofstock'),
					'operator' => 'NOT IN'
				);
			}
			if ( isset( $fields_args['onsale'] ) && $fields_args['onsale'] === 'yes' ) {
				$product_ids_on_sale = wc_get_product_ids_on_sale();
				$product_ids_on_sale[] = 0; // Use 0 when there's no on sale products to avoid return all products.
				$args['post__in'] = $product_ids_on_sale;
			}
			if ( isset( $fields_args['featured'] ) && $fields_args['featured'] === 'yes' ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',
					'operator' => 'IN'
				);
			}
			if ( isset( $fields_args['free'] ) && $fields_args['free'] === 'yes' ) {
				$args['meta_query'][] = array(
					'key' => '_price',
					'value' => 0,
					'compare' => '>',
					'type' => 'DECIMAL'
				);
			}
		}elseif(class_exists('\TB_Archive_Products_Module',false) || self::load_modules('archive-products',true)!==''){
			TB_Archive_Products_Module::get_query($args,$fields_args);
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
			parent::__construct('advanced-products');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'advanced-products',
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
							self::get_font_family(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_f_g'),
							self::get_color_type(array(' .product', ' p', ' button'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g'),
							self::get_font_size(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_s_g'),
							self::get_line_height(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_h_g'),
							self::get_letter_spacing(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_s_g'),
							self::get_text_align(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_a_g'),
							self::get_text_transform(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_t_g'),
							self::get_font_style(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b'),
							self::get_text_shadow(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_f_g', 'h'),
							self::get_color_type(array(' .product', ' p', ' button'), '', 'f_c_t_g', 'f_c_g', 'f_g_c_g', 'h'),
							self::get_font_size(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_s_g', 'h'),
							self::get_font_style(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b', 'h'),
							self::get_text_shadow(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_sh', 'h'),
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

		$aap_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .product', 'b_c_aap_cn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .product', 'b_c_aap_cn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .product', 'p_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .product', 'p_aap_cn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .tbp_posts_wrap.products .product', '', 'top', 'article'),
							self::get_heading_margin_multi_field(' .tbp_posts_wrap.products .product', '', 'bottom', 'article')
						)
					),
					'h' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .tbp_posts_wrap.products .product', '', 'top', 'article', 'h'),
							self::get_heading_margin_multi_field(' .tbp_posts_wrap.products .product', '', 'bottom', 'article', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .product', 'p_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .product', 'p_aap_cn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_aap_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_aap_cn', 'h')
						)
					)
				))
			)),
		);

		$pg_container = array(
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

		$pg_numbers = array(
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

		$pg_a_numbers = array(
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

		$aap_sort = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .woocommerce-ordering select', 'b_c_aap_st', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .woocommerce-ordering select', 'b_c_aap_st', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_text_align(' .woocommerce-ordering select', 't_a_aap_st'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_text_align(' .woocommerce-ordering select', 't_a_aap_st', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .woocommerce-ordering select', 'p_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .woocommerce-ordering select', 'p_aap_st', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .woocommerce-ordering select', 'm_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .woocommerce-ordering select', 'm_aap_st', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .woocommerce-ordering select', 'b_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .woocommerce-ordering select', 'b_aap_st', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_aap_st', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_aap_st', 'h')
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
					'options' => $aap_sort
				),
				'tbp_prdcont' => array(
					'options' => $aap_container
				),
				'pagincont' => array(
					'options' => $pg_container
				),
				'paginnum' => array(
					'options' => $pg_numbers
				),
				'paginactiv' => array(
					'options' => $pg_a_numbers
				)
			)
		);
	}


	public function get_plain_content($module) {//deprecated,backward
		return self::get_static_content($module);
	}

	public function get_animation() {//deprecated,backward
            return false;
    }

    public static function get_styling_image_fields() : array {
        return [
            'b_c_g' => ''
        ];
    }
}

if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Advanced_Products_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Advanced_Products_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Advanced_Products_Module');
	}
}
