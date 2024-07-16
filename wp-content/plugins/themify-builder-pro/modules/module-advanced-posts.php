<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name:Advanced Posts
 * Description:
 */
class TB_Advanced_Posts_Module extends Themify_Builder_Component_Module {

	public static $builder_id = null;

	public static function get_module_name():string {
		return __('Advanced Posts', 'tbp');
	}

	public static function get_module_icon():string {
		return 'layout-grid2';
	}
	
	public static function get_query(array &$args,array &$fields_args) {
		if(Tbp_Public::$is_archive === false || !isset( $fields_args[Tbp_Dynamic_Query::FIELD_NAME] ) || $fields_args[Tbp_Dynamic_Query::FIELD_NAME] === 'off'){
			$args['ignore_sticky_posts'] = true;
			$args['post_type']=$fields_args['post_type'];
			if ($fields_args['term_type'] === 'post_slug' && $fields_args['slug'] !== '') {
				$args['post__in'] = Themify_Builder_Model::parse_slug_to_ids($fields_args['slug'], $args['post_type']);
			} 
			elseif($fields_args['term_type']!=='all') {
				Themify_Builder_Model::parseTermsQuery($args, $fields_args['terms'], $fields_args['tax']);
			}
			if (method_exists('Themify_Builder_Model', 'parse_query_filter')) {
				Themify_Builder_Model::parse_query_filter($fields_args, $args);
			}
		}
		elseif(class_exists('\TB_Archive_Posts_Module',false) || self::load_modules('archive-posts',true)!==''){
			TB_Archive_Posts_Module::get_query($args,$fields_args);
		}
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
			parent::__construct('advanced-posts');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'advanced-posts',
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
							self::get_image('', 'b_c_g', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('', 'b_c_g', 'bg_c', 'background-color', 'h')
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
							self::get_color_type(array(' span', ' a:not(.post-edit-link)', ' p', ' .tbp_post_date')),
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
							self::get_color_type(array(' span', ' a:not(.post-edit-link)', ' p', ' .tbp_post_date'), 'h'),
							self::get_font_size('', 'f_s_g', '', 'h'),
							self::get_font_style('', 'f_g', 'f_b', 'h'),
							self::get_text_shadow('', 't_sh', 'h'),
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

		$aap_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_aap_cn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_aap_cn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post', 'p_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post', 'p_aap_cn', 'h')
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
							self::get_border(' .post', 'p_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post', 'p_aap_cn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_aap_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_aap_cn', 'h')
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

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'tbp_pstcont' => array(
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

	
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Advanced_Posts_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Advanced_Posts_Module');
	}
}
