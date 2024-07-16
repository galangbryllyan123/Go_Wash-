<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Comments
 * Description: 
 */
class TB_Comments_Module extends Themify_Builder_Component_Module {

	public static $comments_order = null;
	
	public static function get_module_name():string {
		return __('Comments', 'tbp');
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_CSS_MODULES . 'comments'
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
			parent::__construct('comments');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'comments',
				'category' => $this->get_group()
			));
		}
	}

	public function get_name() {//backward
		return self::get_module_name();
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
							self::get_font_family(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_f'),
							self::get_color(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_c'),
							self::get_font_size(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_s'),
							self::get_line_height(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'l_h'),
							self::get_letter_spacing(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'l_s'),
							self::get_text_align(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_a'),
							self::get_text_transform(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_t'),
							self::get_font_style(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_st', 'f_w'),
							self::get_text_decoration(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_d_r'),
							self::get_text_shadow(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_f_h'),
							self::get_color(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_c', 'h'),
							self::get_font_size(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_s', '', 'h'),
							self::get_font_style(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_st', 'f_w', 'h'),
							self::get_text_decoration(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_d_r', 'h'),
							self::get_text_shadow(array('', '.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_sh', 'h'),
						)
					)
				))
			)),
			// Paragraph
			self::get_expand('pa', array(
				self::get_heading_margin_multi_field('', 'p', 'top'),
				self::get_heading_margin_multi_field('', 'p', 'bottom')
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' a', 'l_c'),
							self::get_text_decoration(' a', 't_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' a', 'l_c', null, null, 'hover'),
							self::get_text_decoration(' a', 't_d', 'h')
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
			self::get_expand('f_l', array(
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
			// Display
			self::get_expand('disp', self::get_display())
		);

		$labels = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .comment-form label', 'f_f_l'),
							self::get_color(' .comment-form label', 'f_c_l'),
							self::get_font_size(' .comment-form label', 'f_s_l'),
							self::get_line_height(' .comment-form label', 'l_h_lb'),
							self::get_letter_spacing(' .comment-form label', 'l_s_lb'),
							self::get_text_transform(' .comment-form label', 't_t_lb'),
							self::get_font_style(' .comment-form label', 'f_st_lb', 'f_w_lb'),
							self::get_text_decoration(' .comment-form label', 't_d_r_lb'),
							self::get_text_shadow(' .comment-form label', 't_sh_l'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .comment-form label', 'f_f_l', 'h'),
							self::get_color(' .comment-form label', 'f_c_l', null, null, 'h'),
							self::get_font_size(' .comment-form label', 'f_s_l', '', 'h'),
							self::get_font_style(' .comment-form label', 'f_st_lb', 'f_w_lb', 'h'),
							self::get_text_decoration(' .comment-form label', 't_d_r_lb', 'h'),
							self::get_text_shadow(' .comment-form label', 't_sh_l', 'h'),
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .comment-form label', 'm_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .comment-form label', 'm_l', 'h')
						)
					)
				))
			))
		);

		$inputs = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'b_c_i', 'bg_c', 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'b_c_i', 'bg_c', 'background-color', 'h'),
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_f_i'),
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_c_i'),
							self::get_font_size(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_s_i'),
							self::get_text_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 't_sh_i'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_f_i', 'h'),
							self::get_color(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_c_i', null, null, 'h'),
							self::get_font_size(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'f_s_i', '', 'h'),
							self::get_text_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 't_sh_i', 'h'),
						)
					)
				))
			)),
			// Placeholder
			self::get_expand('placeh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="url"]::placeholder', ' textarea::placeholder'), 'f_f_in_ph'),
							self::get_color(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="url"]::placeholder', ' textarea::placeholder'), 'f_c_in_ph'),
							self::get_font_size(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="url"]::placeholder', ' textarea::placeholder'), 'f_s_in_ph'),
							self::get_text_shadow(array(' input[type="text"]::placeholder', ' input[type="email"]::placeholder', ' input[type="url"]::placeholder', ' textarea'), 't_sh_in_ph'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="url"]:hover::placeholder', ' textarea:hover::placeholder'), 'f_f_in_ph_h', ''),
							self::get_color(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="url"]:hover::placeholder', ' textarea:hover::placeholder'), 'f_c_in_ph_h', null, null, ''),
							self::get_font_size(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="url"]:hover::placeholder', ' textarea:hover::placeholder'), 'f_s_in_ph_h', '', ''),
							self::get_text_shadow(array(' input[type="text"]:hover::placeholder', ' input[type="email"]:hover::placeholder', ' input[type="url"]:hover::placeholder', ' textarea:hover::placeholder'), 't_sh_in_ph_h', ''),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_b', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_p', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' input[type="text"]', ' input[type="email"]', ' input[type="url"]', ' textarea'), 'in_b_sh', 'h')
						)
					)
				))
			))
		);

		$checkbox = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' input[type="checkbox"]', 'b_c_cb', 'bg_c', 'background-color'),
							self::get_color(' input[type="checkbox"]', 'f_c_cb'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' input[type="checkbox"]', 'b_c_cb', 'bg_c', 'background-color', 'h'),
							self::get_color(' input[type="submit"]', 'f_c_cb', null, null, 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' input[type="checkbox"]', 'b_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' input[type="checkbox"]', 'b_cb', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' input[type="checkbox"]', 'p_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' input[type="checkbox"]', 'p_cb', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' #commentform input[type="checkbox"]', 'm_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' #commentform input[type="checkbox"]', 'm_cb', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' input[type="checkbox"]', 'r_c_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' input[type="checkbox"]', 'r_c_cb', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' input[type="checkbox"]', 's_cb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' input[type="checkbox"]', 's_cb', 'h')
						)
					)
				))
			))
		);

		$send_button = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' input[type="submit"]', 'b_c_s', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' input[type="submit"]', 'b_c_s', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' input[type="submit"]', 'f_f_s'),
							self::get_color(' input[type="submit"]', 'f_c_s'),
							self::get_font_size(' input[type="submit"]', 'f_s_s'),
							self::get_text_shadow(' input[type="submit"]', 't_sh_b'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' input[type="submit"]', 'f_f_s', 'h'),
							self::get_color(' input[type="submit"]', 'f_c_s', null, null, 'h'),
							self::get_font_size(' input[type="submit"]', 'f_s_s', '', 'h'),
							self::get_text_shadow(' input[type="submit"]', 't_sh_b', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' input[type="submit"]', 'b_s')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' input[type="submit"]', 'b_s', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' input[type="submit"]', 'p_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' input[type="submit"]', 'p_sd', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' input[type="submit"]', 'r_c_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' input[type="submit"]', 'r_c_sd', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' input[type="submit"]', 's_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' input[type="submit"]', 's_sd', 'h')
						)
					)
				))
			))
		);

		$reply = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .comment-reply-link', 'b_c_r', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .comment-reply-link', 'b_c_r', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .comment-reply-link', 'f_f_r'),
							self::get_color(' .comment-reply-link', 'f_c_r'),
							self::get_font_size(' .comment-reply-link', 'f_s_r'),
							self::get_text_shadow(' .comment-reply-link', 't_sh_r'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .comment-reply-link', 'f_f_r', 'h'),
							self::get_color(' .comment-reply-link', 'f_c_r', null, null, 'h'),
							self::get_font_size(' .comment-reply-link', 'f_s_r', '', 'h'),
							self::get_text_shadow(' .comment-reply-link', 't_sh_r', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .comment-reply-link', 'b_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .comment-reply-link', 'b_r', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .comment-reply-link', 'p_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .comment-reply-link', 'p_r', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .comment-reply-link', 'r_c_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .comment-reply-link', 'r_c_r', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .comment-reply-link', 's_r')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .comment-reply-link', 's_r', 'h')
						)
					)
				))
			))
		);

		$avatar = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .avatar', 'b_c_a', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .avatar', 'b_c_a', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .avatar', 'b_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .avatar', 'b_a', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .avatar', 'p_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .avatar', 'p_a', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .avatar', 'r_c_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .avatar', 'r_c_a', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .avatar', 's_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .avatar', 's_a', 'h')
						)
					)
				))
			))
		);

		$comment_title = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'b_c_ct', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'b_c_ct', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_f_ct'),
							self::get_color_type(array('.module .comment-respond .comment-title', '.module .comment-respond h3.comment-reply-title'), '', 'f_c_t_ct', 'f_c_ct', 'f_g_c_ct'),
							self::get_font_size(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_s_ct', ''),
							self::get_line_height(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'l_h_ct'),
							self::get_letter_spacing(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'l_s_ct'),
							self::get_text_align(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_a_ct'),
							self::get_text_transform(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_t_ct'),
							self::get_font_style(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_st_ct', 'f_w_ct'),
							self::get_text_decoration(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_d_r_ct'),
							self::get_text_shadow(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_sh_ct'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_f_ct_h'),
							self::get_color_type(array('.module .comment-respond .comment-title:hover', '.module .comment-respond h3.comment-reply-title:hover'), '', 'f_c_t_ct_h', 'f_c_ct_h', 'f_g_c_ct_h', 'h'),
							self::get_font_size(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_s_ct', '', 'h'),
							self::get_font_style(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'f_st_ct', 'f_w_ct', 'h'),
							self::get_text_decoration(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_d_r_ct', 'h'),
							self::get_text_shadow(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 't_sh_ct', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'b_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'b_ct', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'p_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'p_ct', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'r_c_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 'r_c_ct', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 's_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array('.module .comment-respond .comment-title', '.module .comment-respond .comment-reply-title'), 's_ct', 'h')
						)
					)
				))
			))
		);

		$comment = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .comment', 'b_c_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .comment', 'b_c_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .comment', 'b_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .comment', 'b_c', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .comment', 'p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .comment', 'p_c', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .comment', 'r_c_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .comment', 'r_c_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .comment', 's_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .comment', 's_c', 'h')
						)
					)
				))
			))
		);

		$comment_even = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .comment.even', 'b_c_ce', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .comment.even', 'b_c_ce', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .comment.even', 'b_ce')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .comment.even', 'b_ce', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .comment.even', 'p_ce')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .comment.even', 'p_ce', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .comment.even', 'r_c_ce')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .comment.even', 'r_c_ce', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .comment.even', 's_ce')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .comment.even', 's_ce', 'h')
						)
					)
				))
			))
		);

		$comment_author = array(
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .comment.bypostauthor', 'b_c_ca', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .comment.bypostauthor', 'b_c_ca', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .comment.bypostauthor', 'b_ca')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .comment.bypostauthor', 'b_ca', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .comment.bypostauthor', 'p_ca')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .comment.bypostauthor', 'p_ca', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .comment.bypostauthor', 'r_c_ca')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .comment.bypostauthor', 'r_c_ca', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .comment.bypostauthor', 's_ca')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .comment.bypostauthor', 's_ca', 'h')
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
				'title' => array(
					'options' => $comment_title
				),
				'labels' => array(
					'options' => $labels
				),
				'tbp_inp' => array(
					'options' => $inputs
				),
				'chkbox' => array(
					'options' => $checkbox
				),
				'btn' => array(
					'options' => $send_button
				),
				'tbp_rlink' => array(
					'options' => $reply
				),
				'tbp_avtrs' => array(
					'options' => $avatar
				),
				'comments' => array(
					'options' => $comment
				),
				'tbp_comeven' => array(
					'options' => $comment_even
				),
				'tbp_comsautor' => array(
					'options' => $comment_author
				),
			)
		);
	}

	/**
	 * Hooked to "wp_list_comments_args" to change the order of displayed comments
	 *
	 * @return array
	 */
	public static function set_comments_order($args) {
		$args['reverse_top_level'] = self::$comments_order === 'asc' ? false : true;

		return $args;
	}

	/**
	 * Returns false. Custom function is used instead of __return_false()
	 * so that custom filters added by third party sources are not affected.
	 *
	 * @return false
	 */
	public static function return_false() {
		return false;
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => ''
        ];
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Comments_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Comments_Module');
	}
}