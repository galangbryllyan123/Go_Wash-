<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Slider Pro
 */

class TB_Pro_Slider_Module extends Themify_Builder_Component_Module {

	
	public static function get_json_file():array{
		return ['f'=>Builder_Pro_Slider::$url . 'json/style.json','v'=>Builder_Pro_Slider::get_version()];
	}

    public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
        return __('Slider Pro', 'builder-slider-pro');
    }

    public static function get_module_icon():string{
		return 'layout-slider';
    }

    public static function get_js_css():array {
		$url=Builder_Pro_Slider::$url . 'assets/';
        return array(
            'css' => $url. 'style',
	        'js' => $url. 'scripts',
	        'async'=>true,
            'ver' => Builder_Pro_Slider::get_version()
	    );
    }

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-slider-pro', Builder_Pro_Slider::$url . 'assets/active.js', Builder_Pro_Slider::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Pro_Slider::$url . 'assets/active.js']=Builder_Pro_Slider::get_version();
		}

		$i18n = include dirname( __DIR__ ) . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;
		$vars['slider_pro_vars'] = [
			'url' => Builder_Pro_Slider::$url,
		];

		return $vars;
	}

    public function __construct() {
        if(method_exists('Themify_Builder_Model', 'add_module')){
            parent::__construct('pro-slider');
        }
        else{//backward
             parent::__construct(array(
                'name' =>$this->get_name(),
                'slug' => 'pro-slider',
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
		// Background
		self::get_expand('bg', array(
		   self::get_tab(array(
		       'n' => array(
			   'options' => array(
				self::get_image()
			   )
		       ),
		       'h' => array(
			   'options' => array(
			       self::get_image('', 'b_i','bg_c','b_r','b_p', 'h')
			   )
		       )
		   ))
	       )),
	       // Padding
	       self::get_expand('p', array(
		   self::get_tab(array(
		       'n' => array(
			   'options' => array(
			       self::get_padding()
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
			       self::get_margin()
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
			       self::get_border()
			   )
		       ),
		       'h' => array(
			   'options' => array(
			       self::get_border('', 'b', 'h')
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

		$slide_text_contr = array(
		   self::get_tab(array(
		       'n' => array(
			   'options' => array(
				self::get_color(' .sp-slide-text', 's_t_c_b_c','bg_c', 'background-color'),
				self::get_padding(' .sp-slide-text','s_t_c_p'),
				self::get_border(' .sp-slide-text','s_t_c_b')
			   )
		       ),
		       'h' => array(
			   'options' => array(
				self::get_color(' .sp-slide-text', 's_t_c_b_c','bg_c', 'background-color','h'),
				self::get_padding(' .sp-slide-text','s_t_c_p','h'),
				self::get_border(' .sp-slide-text','s_t_c_b','h')
			   )
		       )
	       )),
		);
		$slide_title = array(
		   self::get_tab(array(
		       'n' => array(
			   'options' => array(
				self::get_font_family('.module .bsp-slide-post-title', 'title_font_family'),
				self::get_color('.module .sp-slide-text .bsp-slide-post-title' ,'f_c_title'),
				self::get_font_size('.module .bsp-slide-post-title', 'font_size_title'),
				self::get_line_height('.module .bsp-slide-post-title', 'title_line_height'),
				self::get_letter_spacing('.module .bsp-slide-post-title', 'letter_spacing_title'),
				self::get_text_align('.module .bsp-slide-post-title', 't_a_title'),
				self::get_text_transform('.module .bsp-slide-post-title', 'text_transform_title'),
				self::get_font_style('.module .bsp-slide-post-title', 'font_style_title','font_style_blod_title'),
               self::get_text_shadow('.module .bsp-slide-post-title', 't_sh_s_t'),
			   )
		       ),
		       'h' => array(
			   'options' => array(
				self::get_font_family('.module .bsp-slide-post-title', 't_f_f','h'),
				self::get_color('.module .sp-slide-text .bsp-slide-post-title' ,'f_c_t',null,null,'h'),
				self::get_font_size('.module .bsp-slide-post-title', 'f_s_t','','h'),
				self::get_font_style('.module .bsp-slide-post-title', 'f_st_t','f_s_b_t','h'),
               self::get_text_shadow('.module .bsp-slide-post-title', 't_sh_s_t','h'),
			   )
		       )
	       )),
		);
		$slide_text = array(
		   self::get_tab(array(
		       'n' => array(
			   'options' => array(
				self::get_font_family(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'text_font_family'),
				self::get_color(array(' .bsp-slide-excerpt', '.module .bsp-slide-excerpt p', ' .bsp-slide-excerpt h1', ' .bsp-slide-excerpt h2', ' .bsp-slide-excerpt h3', ' .bsp-slide-excerpt h4', ' .bsp-slide-excerpt h5', ' .bsp-slide-excerpt h6'),'f_c_text'),
				self::get_font_size(array(' .bsp-slide-excerpt'), 'text_font_size'),
				self::get_line_height(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'text_line_height'),
				self::get_letter_spacing(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'letter_spacing_text'),
				self::get_text_align(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 't_a_text'),
				self::get_text_transform(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'text_transform_text'),
				self::get_font_style(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'font_style_text','font_style_blod_text'),
               self::get_text_shadow(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 't_sh_s_e'),
			   )
		       ),
		       'h' => array(
			   'options' => array(
				self::get_font_family(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'te_f_f','h'),
				self::get_color(array(' .bsp-slide-excerpt', '.module .bsp-slide-excerpt p', ' .bsp-slide-excerpt h1', ' .bsp-slide-excerpt h2', ' .bsp-slide-excerpt h3', ' .bsp-slide-excerpt h4', ' .bsp-slide-excerpt h5', ' .bsp-slide-excerpt h6'),'f_c_te',null,null,'h'),
				self::get_font_size(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'te_f_s','','h'),
				self::get_font_style(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 'f_st_te','f_s_b_te','h'),
               self::get_text_shadow(array(' .bsp-slide-excerpt', ' .bsp-slide-excerpt p'), 't_sh_s_e','h'),
			   )
		       )
	       )),
		);

        $controls = array(
		self::get_expand(__('Timer Bar', 'builder-slider-pro'), array(
		    self::get_tab(array(
			'n' => array(
			    'options' => array(
			       self::get_color(' .bsp-timer-bar', 'timer_bar_background_color', 'bg_c', 'background-color')
			    )
			),
			'h' => array(
			    'options' => array(
			       self::get_color(' .bsp-timer-bar', 't_b_b_c', 'bg_c', 'background-color','h')
			    )
			)
		    ))
		)),
		//Arrow
		self::get_expand(__('Arrow', 'builder-slider-pro'), array(
		    self::get_tab(array(
			'n' => array(
			    'options' => array(
				self::get_color(' .sp-arrow', 'b_c_arrow','bg_c', 'background-color'),
				self::get_color(' .sp-arrow', 'color'),
				self::get_padding(' .sp-arrow','p_arrow')
			    )
			),
			'h' => array(
			    'options' => array(
				self::get_color(' .sp-arrow', 'b_c_a','bg_c', 'background-color','h'),
				self::get_color(' .sp-arrow', 'c',null,null,'h'),
				self::get_padding(' .sp-arrow','p_a','h')
			    )
			)
		    ))
		)),
		 //Pagination
		self::get_expand(__('Pagination', 'builder-slider-pro'), array(
		    self::get_tab(array(
			'n' => array(
			    'options' => array(
				self::get_color('  .sp-button:not(.sp-selected-button)', 'pagination_color'),
				self::get_color('  .sp-selected-button', 'pagination_active_color', __('Active Color', 'builder-slider-pro'))
			    )
			),
			'h' => array(
			    'options' => array(
			       self::get_color('  .sp-button', 'p_c',null,null,'h')
			    )
			)
		    ))
		))
	    );
        $action_button = array(
		    self::get_expand('bg', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_color(' .bsp-slide-button', 'b_c_b', 'bg_c', 'background-color'),
				    self::get_color(' .bsp-slide-button', 'c_b' )
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_color(' .bsp-slide-button', 'b_c_b', 'bg_c', 'background-color','h'),
				    self::get_color(' .bsp-slide-button', 'c_b',null,null,'h' )
				)
			    )
			))
		    )),
		    self::get_expand('f', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_font_family(' .bsp-slide-button', 'button_font_family'),
				    self::get_font_size(' .bsp-slide-button', 'font_size_button'),
				    self::get_line_height(' .bsp-slide-button', 'line_height_button'),
				    self::get_letter_spacing(' .bsp-slide-button', 'l_s_b'),
				    self::get_text_transform(' .bsp-slide-button', 't_t_b'),
				    self::get_font_style(' .bsp-slide-button', 'f_sy_b','f_b_b'),
					self::get_text_align(' .bsp-slide-button', 't_a_btn'),
					self::get_text_shadow(' .bsp-slide-button', 't_sh_a_b'),
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_font_family(' .bsp-slide-button', 'b_f_f','h'),
				    self::get_font_size(' .bsp-slide-button', 'f_s_b','','h'),
				    self::get_font_style(' .bsp-slide-button', 'f_sy_b','f_b_b','h'),
					self::get_text_shadow(' .bsp-slide-button', 't_sh_a_b','h'),
				)
			    )
			))
		    )),
		    // Padding
		   self::get_expand('p', array(
		       self::get_tab(array(
			   'n' => array(
			       'options' => array(
				  self::get_padding(' .bsp-slide-button','p_b')
			       )
			   ),
			   'h' => array(
			       'options' => array(
				   self::get_padding(' .bsp-slide-button','p_b','h')
			       )
			   )
		       ))
		   )),
		   // Margin
		   self::get_expand('m', array(
		       self::get_tab(array(
			   'n' => array(
			       'options' => array(
				   self::get_margin(' .bsp-slide-button','margin_button')
			       )
			   ),
			   'h' => array(
			       'options' => array(
				   self::get_margin(' .bsp-slide-button','m_b','h')
			       )
			   )
		       ))
		   )),
		   // Border
		   self::get_expand('b', array(
		       self::get_tab(array(
			   'n' => array(
			       'options' => array(
				    self::get_border(' .bsp-slide-button','b_b')
			       )
			   ),
			   'h' => array(
			       'options' => array(
				   self::get_border(' .bsp-slide-button','b_b','h')
			       )
			   )
		       ))
		   )),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .bsp-slide-button', 'a_b_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .bsp-slide-button', 'a_b_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .bsp-slide-button', 'a_b_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .bsp-slide-button', 'a_b_b_sh', 'h')
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
					'm_t' => array(
						'options' => $this->module_title_custom_style()
					),
					'a' => array(
						'label' => __('Action Button', 'builder-slider-pro'),
						'options' => $action_button
					),
					's_t_cr' => array(
						'label' => __('Slide Text Container', 'builder-slider-pro'),
						'options' => $slide_text_contr
					),
					's_ti' => array(
						'label' => __('Slide Title', 'builder-slider-pro'),
						'options' => $slide_title
					),
					's_tx' => array(
						'label' => __('Slide Text', 'builder-slider-pro'),
						'options' => $slide_text
					),
					'c' => array(
						'label' => __('Slider Controls', 'builder-slider-pro'),
						'options' => $controls
					),
				)
		);
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Pro_Slider_Module();
	} else {
		Themify_Builder_Model::register_module( 'TB_Pro_Slider_Module' );
	}
}