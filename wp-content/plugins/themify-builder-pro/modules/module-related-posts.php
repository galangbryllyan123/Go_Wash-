<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Related Posts
 * Description: Display related posts by category/tag in single post templates
 */
class TB_Related_Posts_Module extends Themify_Builder_Component_Module {
	
	public static function init():void{
		add_filter('builder_get_public_post_types', array(__CLASS__, 'get_current_post_type'));
	}

	public static function get_module_name():string {
		return __('Related Posts', 'tbp');
	}

	public static function get_module_icon():string {
		return 'layout-grid2';
	}


	public static function get_query(array &$args,array $fields_args) {
		if(!isset( $fields_args[Tbp_Dynamic_Query::FIELD_NAME] ) || $fields_args[Tbp_Dynamic_Query::FIELD_NAME] === 'off'){
			$args['ignore_sticky_posts'] = true;
		}

        $args['post_type'] = $fields_args['term_type_select'];
        $terms = get_the_terms( get_the_ID(), $fields_args['term_type'] );
        $args['tax_query'] = array(
            array(
                'taxonomy' => $fields_args['term_type']==='tag'?'post_tag':$fields_args['term_type'],
                'terms' => is_array( $terms )?array_column( $terms, 'term_id' ):[]
            )
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
			parent::__construct('related-posts');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'related-posts',
				'category' => $this->get_group()
			));
		}
		self::init();
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
							self::get_image('', 'b_c_g', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image(':hover', 'b_c_g_h', 'bg_c_h', 'background-color', 'h')
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
							self::get_color_type(array(' span', ' p', ' .tbp_post_date', '.module .tbp_title', '.module .tbp_title a')),
							self::get_font_size('', 'f_s_g'),
							self::get_line_height('', 'l_h_g'),
							self::get_letter_spacing(' .post', 'l_s_g'),
							self::get_text_align(' .post', 't_a_g'),
							self::get_text_transform('', 't_t_g'),
							self::get_font_style('', 'f_g', 'f_b'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_g', 'h'),
							self::get_color_type(array(' span', ' p', ' .tbp_post_date', '.module .tbp_title', '.module .tbp_title a'), 'h'),
							self::get_font_size('', 'f_s_g', '', 'h'),
							self::get_font_style('', 'f_g', 'f_b', 'h'),
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
							self::get_color(' a:not(.post-edit-link)', 'l_c_gl'),
							self::get_text_decoration('a:not(.post-edit-link)', 't_d_gl')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post a:not(.post-edit-link):hover', 'l_c_gl_h', null, null, ''),
							self::get_text_decoration(' .post a:not(.post-edit-link):hover', 't_d_gl_h', '')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('', 'g_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'g_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('', 'g_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'g_m', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('', 'g_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'g_b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' .loops-wrapper .post')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' .loops-wrapper .post', 'bl_m_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
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
				)
			),
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
							self::get_box_shadow('', 'g_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'g_sh', 'h')
						)
					)
				))
			)),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$archive_post_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_a_p_cn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_a_p_cn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .post', '', 'top', 'article'),
							self::get_heading_margin_multi_field(' .post', '', 'bottom', 'article')
						)
					),
					'h' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .post', '', 'top', 'article', 'h'),
							self::get_heading_margin_multi_field(' .post', '', 'bottom', 'article', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post', 'b_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post', 'b_cn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cn', 'h')
						)
					)
				))
			)),
		);

		$archive_post_title = array(
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
							self::get_font_family('.module .tbp_title', 'f_f_a_p_t'),
							self::get_color(array('.module .tbp_title', ' .tbp_title a'), 'f_c_a_p_t'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t'),
							self::get_line_height('.module .tbp_title', 'l_h_a_p_t'),
							self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t'),
							self::get_text_transform('.module .tbp_title', 't_t_a_p_t'),
							self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t'),
							self::get_text_shadow('.module .tbp_title', 't_sh_a_p_t'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .tbp_title', 'f_f_a_p_t', 'h'),
							self::get_color(array('.module .tbp_title', ' .tbp_title a'), 'f_c_a_p_t', null, null, 'hover'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t', '', 'h'),
							self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t', 'h'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t', 'h'),
							self::get_text_shadow('.module .tbp_title', 't_sh_a_p_t', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c', null, null, 'h'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l', 'h')
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

		$archive_featured_image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post-image img', 'b_c_a_f_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post-image img', 'b_c_a_f_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post-image img', 'p_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post-image img', 'p_a_f_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .post-image', 'm_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .post-image', 'm_a_f_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post-image img', 'b_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post-image img', 'b_a_f_i', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'r_c_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'r_c_a_f_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'sh_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'sh_a_f_i', 'h')
						)
					)
				))
			))
		);

		$archive_post_meta = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta', 'b_c_a_p_m', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta', 'b_c_a_p_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_meta', 'f_f_a_p_m'),
							self::get_color(array(' .tbp_post_meta', ' .tbp_post_meta span', ' .tbp_post_meta a'), 'f_c_a_p_m'),
							self::get_font_size(' .tbp_post_meta', 'f_s_a_p_m'),
							self::get_line_height(' .tbp_post_meta', 'l_h_a_p_m'),
							self::get_letter_spacing(' .tbp_post_meta', 'l_s_a_p_m'),
							self::get_font_style(' .tbp_post_meta', 'f_g_a_p_m', 'f_b_a_p_m'),
							self::get_text_transform(' .tbp_post_meta', 't_t_a_p_m'),
							self::get_text_decoration(' .tbp_post_meta', 't_d_a_p_m'),
							self::get_text_shadow(' .tbp_post_meta', 't_sh_a_p_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_meta', 'f_f_a_p_m', 'h'),
							self::get_color(array(' .tbp_post_meta', ' .tbp_post_meta span', ' .tbp_post_meta a'), 'f_c_a_p_m', null, null, 'hover'),
							self::get_font_size(' .tbp_post_meta', 'f_s_a_p_m', '', 'h'),
							self::get_font_style(' .tbp_post_meta', 'f_g_a_p_m', 'f_b_a_p_m', 'h'),
							self::get_text_decoration(' .tbp_post_meta', 't_d_a_p_m', 'h'),
							self::get_text_shadow(' .tbp_post_meta', 't_sh_a_p_m', 'h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta a', 'f_c_a_p_m_l'),
							self::get_text_decoration(' .tbp_post_meta a', 't_d_a_p_m_l'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta a', 'f_c_a_p_m_l', null, null, 'hover'),
							self::get_text_decoration(' .tbp_post_meta a', 't_d_a_p_m_l', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_post_meta', 'p_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_post_meta', 'p_a_p_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_post_meta', 'm_a_p_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_post_meta', 'm_a_p_m', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_post_meta', 'b_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_post_meta', 'b_a_p_m', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta', 'sh_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta', 'sh_a_p_m', 'h')
						)
					)
				))
			))
		);

		$archive_post_date = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_date', 'b_c_a_p_d', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_date', 'b_c_a_p_d', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date', 'f_f_a_p_d'),
							self::get_color(' .tbp_post_date', 'f_c_a_p_d'),
							self::get_font_size(' .tbp_post_date', 'f_s_a_p_d'),
							self::get_line_height(' .tbp_post_date', 'l_h_a_p_d'),
							self::get_letter_spacing(' .tbp_post_date', 'l_s_a_p_d'),
							self::get_text_align(' .tbp_post_date', 't_a_a_p_d'),
							self::get_text_transform(' .tbp_post_date', 't_t_a_p_d'),
							self::get_font_style(' .tbp_post_date', 'f_st_a_p_d', 'f_w_a_p_d'),
							self::get_text_decoration(' .tbp_post_date', 't_d_r_a_p_d'),
							self::get_text_shadow(' .tbp_post_date', 't_sh_a_p_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date', 'f_f_a_p_d', 'h'),
							self::get_color(' .tbp_post_date', 'f_c_a_p_d', null, null, 'h'),
							self::get_font_size(' .tbp_post_date', 'f_s_a_p_d', '', 'h'),
							self::get_font_style(' .tbp_post_date', 'f_st_a_p_d', 'f_w_a_p_d', 'h'),
							self::get_text_decoration(' .tbp_post_date', 't_d_r_a_p_d', 'h'),
							self::get_text_shadow(' .tbp_post_date', 't_sh_a_p_d', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_post_date', 'p_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_post_date', 'p_a_p_d', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_post_date', 'm_a_p_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_post_date', 'm_a_p_d', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_post_date', 'b_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_post_date', 'b_a_p_d', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_date', 'sh_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_date', 'sh_a_p_d', 'h')
						)
					)
				))
			)),
			// Month Font
			self::get_expand('tbp_mthf', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_month', 'f_f_a_p_d_m'),
							self::get_color(' .tbp_post_date .tbp_post_month', 'f_c_a_p_d_m'),
							self::get_font_size(' .tbp_post_date .tbp_post_month', 'f_s_a_p_d_m'),
							self::get_line_height(' .tbp_post_date .tbp_post_month', 'l_h_a_p_d_m'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_month', 't_sh_a_p_d_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_month', 'f_f_a_p_d_m', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_month', 'f_c_a_p_d_m', null, null, 'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_month', 'f_s_a_p_d_m', '', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_month', 't_sh_a_p_d_m', 'h'),
						)
					)
				))
			)),
			// Day Font
			self::get_expand('tbp_dayf', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_day', 'f_f_a_p_d_d'),
							self::get_color(' .tbp_post_date .tbp_post_day', 'f_c_a_p_d_d'),
							self::get_font_size(' .tbp_post_date .tbp_post_day', 'f_s_a_p_d_d'),
							self::get_line_height(' .tbp_post_date .tbp_post_day', 'l_h_a_p_d_d'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_day', 't_sh_a_p_d_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_day', 'f_f_a_p_d_d', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_day', 'f_c_a_p_d_d', null, null, 'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_day', 'f_s_a_p_d_d', '', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_day', 't_sh_a_p_d_d', 'h'),
						)
					)
				))
			)),
			// Year Font
			self::get_expand('tbp_yearf', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_year', 'f_f_a_p_d_y'),
							self::get_color(' .tbp_post_date .tbp_post_year', 'f_c_a_p_d_y'),
							self::get_font_size(' .tbp_post_date .tbp_post_year', 'f_s_a_p_d_y'),
							self::get_line_height(' .tbp_post_date .tbp_post_year', 'l_h_a_p_d_y'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_year', 't_sh_a_p_d_y'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_year', 'f_f_a_p_d_y', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_year', 'f_c_a_p_d_y', null, null, 'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_year', 'f_s_a_p_d_y', '', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_year', 't_sh_a_p_d_y', 'h'),
						)
					)
				))
			)),
		);

		$archive_post_content = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tb_text_wrap', 'b_c_a_p_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tb_text_wrap', 'b_c_a_p_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tb_text_wrap', 'f_f_a_p_c'),
							self::get_color(' .tb_text_wrap', 'f_c_a_p_c'),
							self::get_font_size(' .tb_text_wrap', 'f_s_a_p_c'),
							self::get_line_height(' .tb_text_wrap', 'l_h_a_p_c'),
							self::get_letter_spacing(' .tb_text_wrap', 'l_s_a_p_c'),
							self::get_font_style(' .tb_text_wrap', 'f_g_a_p_c', 'f_b_a_p_c'),
							self::get_text_transform(' .tb_text_wrap', 't_t_a_p_c'),
							self::get_text_align(' .tb_text_wrap', 't_a_a_p_c'),
							self::get_text_shadow(' .tb_text_wrap', 't_sh_a_p_c'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tb_text_wrap', 'f_f_a_p_c', 'h'),
							self::get_color(' .tb_text_wrap', 'f_c_a_p_c', null, null, 'h'),
							self::get_font_size(' .tb_text_wrap', 'f_s_a_p_c', '', 'h'),
							self::get_font_style(' .tb_text_wrap', 'f_g_a_p_c', 'f_b_a_p_c', 'h'),
							self::get_text_shadow(' .tb_text_wrap', 't_sh_a_p_c', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tb_text_wrap', 'p_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tb_text_wrap', 'p_a_p_c', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tb_text_wrap', 'm_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tb_text_wrap', 'm_a_p_c', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tb_text_wrap', 'b_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tb_text_wrap', 'b_a_p_c', 'h')
						)
					)
				))
			))
		);

		$read_more = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .read-more', 'b_c_r_m', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .read-more', 'b_c_r_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .read-more', 'f_f_g'),
							self::get_color('.module .read-more', 'f_c_r_m'),
							self::get_font_size(' .read-more', 'f_s_r_m'),
							self::get_line_height(' .read-more', 'l_h_r_m'),
							self::get_letter_spacing(' .read-more', 'l_s_r_m'),
							self::get_text_align(' .read-more', 't_a_r_m'),
							self::get_text_transform(' .read-more', 't_t_r_m'),
							self::get_font_style(' .read-more', 'f_st_r_m', 'f_b_r_m'),
							self::get_text_shadow(' .read-more', 't_sh_r_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .read-more', 'f_f_g', 'h'),
							self::get_color('.module .read-more:hover', 'f_c_r_m_h', 'h'),
							self::get_font_size(' .read-more', 'f_s_r_m', '', 'h'),
							self::get_font_style(' .read-more', 'f_st_r_m', 'f_b_r_m', 'h'),
							self::get_text_shadow(' .read-more', 't_sh_r_m', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .read-more', 'r_m_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .read-more', 'r_m_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .read-more', 'r_m_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .read-more', 'r_m_m', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .read-more', 'r_m_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .read-more', 'r_m_b', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .read-more', 'r_c_r_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .read-more', 'r_c_r_m', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .read-more', 'sh_r_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .read-more', 'sh_r_m', 'h')
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
				'conter' => array(
					'options' => $archive_post_container
				),
				'title' => array(
					'options' => $archive_post_title
				),
				'fimg' => array(
					'options' => $archive_featured_image
				),
				'meta' => array(
					'options' => $archive_post_meta
				),
				'date' => array(
					'options' => $archive_post_date
				),
				'content' => array(
					'options' => $archive_post_content
				),
				'rmore' => array(
					'options' => $read_more
				),
			)
		);
	}


	public static function get_current_post_type($types) {
		if (isset($_POST['action'],$_POST['just_current']) && 'tb_get_post_types' === $_POST['action'] &&  'true' === $_POST['just_current']) {
			$id = $_POST['id'];
			$post_type = get_post_type($id);
			if ($post_type === Tbp_Templates::SLUG) {
				$post_type = Tbp_Utils::get_post_type(Tbp_Templates::get_template_type($id), Tbp_Templates::get_template_conditions($id)[0]);
			} else {
				$post_type = array($post_type);
			}
			if (!empty($post_type) && is_array($post_type)) {
				$post_type_obj = get_post_type_object($post_type[0]);
				$post_type = array($post_type[0] => $post_type_obj->labels->singular_name);
			}
			return empty($post_type) ? array('post' => 'Post') : $post_type;
		}
		return $types;
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_c_g' => ''
        ];
    }
}

if(method_exists('Themify_Builder_Component_Module', 'get_module_class')){
	TB_Related_Posts_Module::init();
}
elseif (method_exists('Themify_Builder_Model', 'add_module')) {
	new TB_Related_Posts_Module();
} else {
	Themify_Builder_Model::register_module('TB_Related_Posts_Module');
}
