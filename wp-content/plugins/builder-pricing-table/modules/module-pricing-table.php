<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Module Name: Pricing table
 * Description:
 */

class TB_Pricing_Table_Module extends Themify_Builder_Component_Module {

	
    public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
        return __('Pricing Table', 'builder-pricing-table');
    }

    public static function get_json_file():array{
		return ['f'=>Builder_Pricing_Table::$url . 'json/style.json','v'=>Builder_Pricing_Table::get_version()];
	}

    public static function get_module_icon():string{
		return 'clipboard';
    }

    public static function get_js_css():array {
		return array(
			'css' => Builder_Pricing_Table::$url . 'assets/style',
			'ver' => Builder_Pricing_Table::get_version()
		);
    }

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-pricingtable', Builder_Pricing_Table::$url . 'assets/active.js', Builder_Pricing_Table::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Pricing_Table::$url . 'assets/active.js']=Builder_Pricing_Table::get_version();
		}

		$i18n = include dirname( __DIR__ )  . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;

		return $vars;
	}

    public function __construct() {
        if(method_exists('Themify_Builder_Model', 'add_module')){
            parent::__construct('pricing-table');
        }
        else{//backward
             parent::__construct(array(
                'name' =>$this->get_name(),
                'slug' => 'pricing-table',
                'category' =>$this->get_group()
            ));
        }
    }

    public function get_name(){
		return self::get_module_name();
    }

    public function get_icon(){
		return self::get_module_icon();
    }

    function get_assets() {
		return self::get_js_css();
    }

    public function get_styling() {
	$general = array(
	    //bacground
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_image('.ui')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_image('.ui', 'b_i','bg_c','b_r','b_p', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family('.ui'),
			    self::get_color_type(array('.ui .module-pricing-table-header', '.ui .module-pricing-table-header i', '.ui .module-pricing-table-content')),
			    self::get_font_size('.ui'),
			    self::get_line_height('.ui'),
			    self::get_letter_spacing('.ui'),
			    self::get_text_align('.ui'),
			    self::get_text_transform('.ui'),
			    self::get_font_style('.ui'),
			    self::get_text_decoration('.ui', 'text_decoration_regular'),
				self::get_text_shadow('.ui'),
			    
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family('.ui','f_f','h'),
			    self::get_color_type(array('.ui .module-pricing-table-header:hover', '.ui .module-pricing-table-header:hover i', '.ui .module-pricing-table-content:hover'),''),
			    self::get_font_size('.ui','f_s','','h'),
			    self::get_font_style('.ui','f_st','f_w','h'),
			    self::get_text_decoration('.ui', 't_d_r','h'),
				self::get_text_shadow('.ui','t_sh','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding('.ui')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding('','p','h')
			)
		    )
		))
	    )),
	    // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin('.ui')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin('.ui','m','h')
			)
		    )
		))
	    )),
	    // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border('.ui')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border('.ui','b','h')
			)
		    )
		))
	    )),
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
							self::get_border_radius()
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
							self::get_box_shadow()
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
	$table_header = array(
	    // Background
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-header', 'mod_title_background_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-header', 'm_t_b_c', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-header', 'mod_title_font_family'),
			    self::get_color('.ui.module .module-pricing-table-header', 'mod_title_font_color'),
			    self::get_font_size(' .module-pricing-table-header', 'font_size_title'),
			    self::get_font_style(' .module-pricing-table-header', 'f_fs_t', 'f_fw_t'),
			    self::get_line_height(' .module-pricing-table-header', 'mod_line_height_title'),
			    self::get_letter_spacing(' .module-pricing-table-title', 'letter_spacing_title'),
			    self::get_text_align(' .module-pricing-table-header', 'mod_text_align_title'),
				self::get_text_shadow(' .module-pricing-table-header', 't_sh_t_h'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-header', 'm_t_f_f','h'),
			    self::get_color('.ui.module .module-pricing-table-header', 'm_t_f_c',null,null,'h'),
			    self::get_font_size(' .module-pricing-table-header', 'f_s_t','','h'),
				self::get_font_style(' .module-pricing-table-header', 'f_fs_t', 'f_fw_t', 'h'),
				self::get_text_shadow(' .module-pricing-table-header', 't_sh_t_h','h'),
			)
		    )
		))
	    )),
	    // Font Price
	    self::get_expand(__('Price', 'themify'), array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-price', 'font_family_price'),
			    self::get_color(' .module-pricing-table-price', 'font_color_price'),
			    self::get_font_size(' .module-pricing-table-price', 'font_size_price'),
			    self::get_font_style(' .module-pricing-table-price', 'f_fs_p', 'f_fw_p'),
			    self::get_line_height(' .module-pricing-table-price', 'line_height_price'),
			    self::get_letter_spacing(' .module-pricing-table-price', 'letter_spacing_price'),
			    self::get_text_align(' .module-pricing-table-price', 'text_align_price'),
				self::get_text_shadow(' .module-pricing-table-price', 't_sh_f_p'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-price', 'f_f_p','h'),
			    self::get_color(' .module-pricing-table-price', 'f_c_p',null,null,'h'),
			    self::get_font_size(' .module-pricing-table-price', 'f_s_p','','h'),
				self::get_font_style(' .module-pricing-table-price', 'f_fs_p', 'f_fw_p', 'h'),
				self::get_text_shadow(' .module-pricing-table-price', 't_sh_f_p','h'),
			)
		    )
		))
	    )),
	    // Font Description
	    self::get_expand(__('Description', 'themify'), array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family(' .module-pricing-table-description', 'font_family_description'),
					self::get_color(' .module-pricing-table-description', 'font_color_description'),
					self::get_font_size(' .module-pricing-table-description', 'font_size_description'),
					self::get_font_style(' .module-pricing-table-description', 'f_fs_d', 'f_fw_d'),
					self::get_line_height(' .module-pricing-table-description', 'line_height_description'),
					self::get_letter_spacing(' .module-pricing-table-description', 'letter_spacing_description'),
					self::get_text_align(' .module-pricing-table-description', 'text_align_description'),
					self::get_text_shadow(' .module-pricing-table-description', 't_sh_f_d'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family(' .module-pricing-table-description', 'f_f_p','h'),
					self::get_color(' .module-pricing-table-description', 'f_c_p',null,null,'h'),
					self::get_font_size(' .module-pricing-table-description', 'f_s_p','','h'),
					self::get_font_style(' .module-pricing-table-description', 'f_fs_d', 'f_fw_d', 'h'),
					self::get_text_shadow(' .module-pricing-table-description', 't_sh_f_d','h'),
				)
				)
			))
	    )),
		// Rounded Corners
		self::get_expand('r_c', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-header', 'r_c_t_h')
					)
				),
				'h' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-header', 'r_c_t_h', 'h')
					)
				)
			))
		)),
		// Shadow
		self::get_expand('sh', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-header', 'sh_t_h')
					)
				),
				'h' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-header', 'sh_t_h', 'h')
					)
				)
			))
		)),
		
	);
	// Features list
	$feature_list = array(
	    // Background
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-content', 'mod_feature_bg_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-content', 'm_f_b_c', 'bg_c', 'background-color','h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-content', 'mod_feature_font_family'),
			    self::get_color(' .module-pricing-table-content', 'mod_feature_font_color'),
			    self::get_font_size(' .module-pricing-table-content', 'font_size_content'),
			    self::get_font_style(' .module-pricing-table-content', 'f_fs_f', 'f_fw_f'),
			    self::get_line_height(' .module-pricing-table-content', 'mod_line_height_content'),
			    self::get_text_align(' .module-pricing-table-content', 'mod_text_align_content'),
			    self::get_text_transform(' .module-pricing-table-content', 'f_l_t_tf'),
				self::get_text_shadow(' .module-pricing-table-content', 't_sh_f_l'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-content', 'm_f_f_f','h'),
			    self::get_color(' .module-pricing-table-content', 'm_f_f_c',null,null,'h'),
			    self::get_font_size(' .module-pricing-table-content', 'f_s_c','','h'),
				self::get_font_style(' .module-pricing-table-content', 'f_fs_f', 'f_fw_f', 'h'),
				self::get_text_shadow(' .module-pricing-table-content', 't_sh_f_l','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(' .module-pricing-table-content', 'f_l_padding')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding(' .module-pricing-table-content', 'f_l_p', 'h')
			)
		    )
		))
	    )),
		// Rounded Corners
		self::get_expand('r_c', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-content', 'f_l_r_c')
					)
				),
				'h' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-content', 'f_l_r_c', 'h')
					)
				)
			))
		)),
		// Shadow
		self::get_expand('sh', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-content', 'f_l_b_sh')
					)
				),
				'h' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-content', 'f_l_b_sh', 'h')
					)
				)
			))
		))
	);
	//Pop text
	$pop_text = array(
	    self::get_seperator('f'),
	    self::get_tab(array(
		'n' => array(
		    'options' => array(
			self::get_font_family(' .module-pricing-table-pop', 'mod_pop_font_family'),
			self::get_color(' .module-pricing-table-pop', 'mod_pop_font_color'),
			self::get_font_size(' .module-pricing-table-pop', 'mod_pop_font_size'),
			self::get_font_style(' .module-pricing-table-pop', 'f_fs_q', 'f_fw_q'),
            self::get_text_shadow(' .module-pricing-table-pop', 't_sh_p_t'),
		    )
		),
		'h' => array(
		    'options' => array(
			self::get_font_family(' .module-pricing-table-pop', 'm_p_f_f','h'),
			self::get_color(' .module-pricing-table-pop', 'm_p_f_c',null,null,'h'),
			self::get_font_size(' .module-pricing-table-pop', 'm_p_f_st','','h'),
			self::get_font_style(' .module-pricing-table-pop', 'f_fs_q', 'f_fw_q', 'h'),
            self::get_text_shadow(' .module-pricing-table-pop', 't_sh_p_t','h'),
		    )
		)
	    ))
	);
	//Buy button
	$buy_button = array(
	    // Background
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-button', 'mod_button_bg_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color(' .module-pricing-table-button', 'm_b_b_c', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-button', 'mod_button_font_family'),
			    self::get_color(' .module-pricing-table-button', 'mod_button_font_color'),
			    self::get_font_size(' .module-pricing-table-button', 'font_size_button'),
			    self::get_font_style(' .module-pricing-table-button', 'f_fs_b', 'f_fw_b'),
			    self::get_line_height(' .module-pricing-table-button', 'mod_line_height_button'),
			    self::get_text_align(' .module-pricing-table-button', 'mod_text_align_button'),
				self::get_text_shadow(' .module-pricing-table-button', 't_sh_b_b'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .module-pricing-table-button', 'b_f_f','h'),
			    self::get_color(' .module-pricing-table-button', 'b_f_c','h'),
			    self::get_font_size(' .module-pricing-table-button', 'f_s_b','','h'),
				self::get_font_style(' .module-pricing-table-button', 'f_fs_b', 'f_fw_b', 'h'),
				self::get_text_shadow(' .module-pricing-table-button', 't_sh_b_b','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(' .module-pricing-table-button', 'b_b_padding')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding(' .module-pricing-table-button', 'b_b_p','h')
			)
		    )
		))
	    )),
	    // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin(' .module-pricing-table-button', 'buy_button_margin')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin(' .module-pricing-table-button', 'b_b_m','h')
			)
		    )
		))
	    )),
	    // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border(' .module-pricing-table-button', 'b_b_border'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border(' .module-pricing-table-button', 'b_b_b', 'h')
			)
		    )
		))
	    )),
		// Rounded Corners
		self::get_expand('r_c', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-button', 'b_b_r_c')
					)
				),
				'h' => array(
					'options' => array(
						self::get_border_radius(' .module-pricing-table-button', 'b_b_r_c', 'h')
					)
				)
			))
		)),
		// Shadow
		self::get_expand('sh', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-button', 'b_b_b_sh')
					)
				),
				'h' => array(
					'options' => array(
						self::get_box_shadow(' .module-pricing-table-button', 'b_b_b_sh', 'h')
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
		    't' => array(
			'label' => __('Top Header', 'builder-pricing-table'),
			'options' => $table_header
		    ),
		    'f' => array(
			'label' => __('Features List', 'builder-pricing-table'),
			'options' => $feature_list
		    ),
		    'b' => array(
			'label' => __('Buy Button', 'builder-pricing-table'),
			'options' => $buy_button
		    ),
		    'p' => array(
			'label' => __('Pop-out Text', 'builder-pricing-table'),
			'options' => $pop_text
		    )
		)
	);
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Pricing_Table_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Pricing_Table_Module');
	}
}