<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: PTB Repeater
 * Description: 
 */
class TB_PTB_Repeater_Module extends Themify_Builder_Component_Module {

    public static $builder_id = null;

	public static function init():void{
		add_filter('tb_select_dataset_tbp_ptb_key', array(__CLASS__, 'get_repeaters'));
	}

	public static function is_available():bool{
		return class_exists('PTB_CMB_Base',false);
	}

	public static function get_module_name():string {
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('PTB Repeater', 'tbp');
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

	public static function get_repeaters():array {
		$type = ['video', 'audio', 'file', 'gallery', 'slider', 'accordion'];
		$options = array();

		$post_types = Themify_Builder_Model::get_public_post_types();
		foreach ( PTB::get_option()->get_custom_post_types() as $ptb_post_type => $ptb_post_type_data ) {
			if ( ! isset( $post_types[ $ptb_post_type ] ) ) {
				$post_types[ $ptb_post_type ] = PTB_Utils::get_label( $ptb_post_type_data->plural_label );
			}
		}

		foreach ($post_types as $post_type => $post_type_label) {
			$ptb_fields = PTB::$options->get_cpt_cmb_options($post_type);
			if (!empty($ptb_fields)) {
				foreach ($ptb_fields as $key => $field) {
					if (in_array($field['type'], $type, true) || ( $field['type'] === 'text' && $field['repeatable'] === true )) {
						$name = PTB_Utils::get_label($field['name']);
						$options["{$post_type}:{$key}:{$field['type']}"] = sprintf('%s: %s', $post_type_label, $name);
					}
				}
			}
		}

		return [ '' => '' ] + $options;
	}

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script('tbp-active-ptb-repeater', TBP_URL . 'editor/js/lazy-components/ptb-repeater.js', TBP_VER, array('tbp-active'));
		}
		else{
			$vars['addons'][TBP_URL . 'editor/js/lazy-components/ptb-repeater.js']=TBP_VER;
		}
		$vars['ptbRepeater'] = [
            'placeholder_image' => TBP_URL . 'editor/img/transparent.webp'
        ];
		return $vars;
	}

	function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('ptb-repeater');
		} else {
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'ptb-repeater',
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

		$items = array(
			// Items Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post', 'ptb_rpt_bg', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post', 'ptb_rpt_bg', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Items Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .post', '', 'top', '', 'ptb_rpt_m'),
							self::get_heading_margin_multi_field(' .post', '', 'bottom', '', 'ptb_rpt_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_heading_margin_multi_field(':hover > .post', '', 'top', '', 'ptb_rpt_m_h'),
							self::get_heading_margin_multi_field(':hover > .post', '', 'bottom', '', 'ptb_rpt_m_h')
						)
					)
				))
			)),
			// Items Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post', 'ptb_rpt_p'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post', 'ptb_rpt_p_h'),
						)
					)
				))
			)),
			// Items Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post', 'ptb_rpt_b'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post', 'ptb_rpt_b_h'),
						)
					)
				))
			)),
			// Items Border Radius
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post', 'ptb_rpt_rc'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post', 'ptb_rpt_rc_h'),
						)
					)
				))
			)),
			// Items Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'ptb_rpt_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'ptb_rpt_sh_h'),
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
				'items' => array(
					'options' => $items
				)
			)
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

if (TB_PTB_Repeater_Module::is_available()) {
	if(method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
		TB_PTB_Repeater_Module::init();
	}
	elseif (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_PTB_Repeater_Module();
	} else {
		Themify_Builder_Model::register_module('TB_PTB_Repeater_Module');
	}
}