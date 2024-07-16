<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Add To Cart
 * Description: 
 */
class TB_ACF_Repeater_Module extends Themify_Builder_Component_Module {

    public static $builder_id = null;

	public static function init():void{
		add_filter('tb_select_dataset_tbp_acf_key', array(__CLASS__, 'get_repeaters'));
	}

	public static function is_available():bool{
		return class_exists('acf_pro',false);
	}

	public static function get_module_name():string {
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('ACF Repeater', 'tbp');
	}

	public static function get_module_icon():string {
		return  'loop';
	}

	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
	}

	public static function get_repeaters(array $arr=array()):array{
		return array_merge(['' => ''], Themify_Builder_Plugin_Compat_acf::get_fields_by_type('repeater'));
	}

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script('tbp-active-acf-repeater', TBP_URL . 'editor/js/lazy-components/acf-repeater.js', TBP_VER, array('tbp-active'));
		}
		else{
			$vars['addons'][TBP_URL . 'editor/js/lazy-components/acf-repeater.js']=TBP_VER;
		}
		return $vars;
	}
	
	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('acf-repeater');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'acf-repeater',
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
		return array(
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
							self::get_color_type(['> .tbp_advanced_archive_wrap']),
							self::get_font_size('', 'f_s_g'),
							self::get_line_height('', 'l_h_g'),
							self::get_letter_spacing('', 'l_s_g'),
							self::get_text_align(['', ' .post'], 't_a_g'),
							self::get_text_transform('', 't_t_g'),
							self::get_font_style('', 'f_g', 'f_b'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_g', 'h'),
							self::get_color_type(['> .tbp_advanced_archive_wrap'], 'h'),
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
							'options' => array(self::get_blend())
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
	}
	
	public function get_animation() {//backward
		return false;
	}
	
	public function get_plain_content($module) {//deprecated,backward
		return self::get_static_content($module);
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_c_g' => ''
        ];
    }
}

if (TB_ACF_Repeater_Module::is_available()) {
	if(method_exists('Themify_Builder_Component_Module', 'get_module_class')){
		TB_ACF_Repeater_Module::init();
	}
	elseif (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_ACF_Repeater_Module();
	} else {
		Themify_Builder_Model::register_module('TB_ACF_Repeater_Module');
	}
}
