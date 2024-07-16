<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Progress Bar
 */

class TB_ProgressBar_Module extends Themify_Builder_Component_Module {

	
	public static function get_json_file():array{
		return ['f'=>Builder_ProgressBar::$url . 'json/style.json','v'=>Builder_ProgressBar::get_version()];
	}

    public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('Progress Bar', 'builder-progressbar');
    }

    public static function get_module_icon():string{
		return 'bar-chart';
    }

    public static function get_js_css():array {
		$url=Builder_ProgressBar::$url . 'assets/';
		return array(
			'async'=>true,
			'css' => $url . 'style',
			'js' => $url . 'scripts',
			'ver' => Builder_ProgressBar::get_version()
		);
    }

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-progressbar', Builder_ProgressBar::$url . 'assets/active.js', Builder_ProgressBar::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_ProgressBar::$url . 'assets/active.js']=Builder_ProgressBar::get_version();
		}
		$i18n = include dirname( __DIR__ )  . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;

		return $vars;
	}


    public function __construct() {
        if(method_exists('Themify_Builder_Model', 'add_module')){
            parent::__construct('progressbar');
        }
        else{//backward
             parent::__construct(array(
                'name' =>$this->get_name(),
                'slug' => 'progressbar',
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
			    self::get_color('', 'background_color', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(),
			    self::get_color_type(' .tb-progress-bar-label'),
			    self::get_font_size(),
			    self::get_font_style( '', 'f_fs_g', 'f_fw_g' ),
			    self::get_line_height(),
			    self::get_text_align(' .tb-progress-bar-label'),
				self::get_text_shadow(),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family('', 'f_f', 'h'),
			    self::get_color_type(' .tb-progress-bar-label', 'h'),
			    self::get_font_size('', 'f_s', '', 'h'),
				self::get_font_style( '', 'f_fs_g', 'f_fw_g', 'h' ),
				self::get_text_shadow('','t_sh','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding('', 'p', 'h'),
			)
		    )
		))
	    )),
	    // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin(),
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
			    self::get_border(),
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
	$bar = array(
	    // Background
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color(' .tb-progress-bar', 'b_c_b', 'bg_c', 'background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color(' .tb-progress-bar', 'b_c_b', 'bg_c', 'background-color', 'h')
			)
		    )
		))
	    )),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family(' .tb-progress-bar', 'f_f_b'),
			    self::get_color(' .tb-progress-bar', 'f_c_b'),
			    self::get_font_size(' .tb-progress-bar', 'f_s_b'),
			    self::get_line_height(' .tb-progress-bar', 'l_h_b'),
			    self::get_letter_spacing(' .tb-progress-bar', 'l_s_b'),
			    self::get_text_transform(array(' .tb-progress-bar', '.module .tb-progress-bar-label'), 't_t_b'),
			    self::get_font_style(' .tb-progress-bar', 'f_sy_b', 'f_b_b'),
				self::get_text_shadow(' .tb-progress-bar', 't_sh_b'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family(' .tb-progress-bar', 'f_f_b', 'h'),
			    self::get_color(' .tb-progress-bar', 'f_c_b', null, null, 'h'),
			    self::get_font_size(' .tb-progress-bar', 'f_s_b', '', 'h'),
			    self::get_font_style(' .tb-progress-bar', 'f_sy_b', 'f_b_b', 'h'),
				self::get_text_shadow(' .tb-progress-bar', 't_sh_b','h'),
			)
		    )
		))
	    )),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding(' .tb-progress-bar', 'b_p')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding(' .tb-progress-bar', 'b_p', 'h')
			)
		    )
		))
	    )),
	    // Margin
	    self::get_expand('m', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_margin(' .tb-progress-bar', 'b_m'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_margin(' .tb-progress-bar', 'b_m', 'h'),
			)
		    )
		))
	    )),
	    // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border(' .tb-progress-bar', 'b_b'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border(' .tb-progress-bar', 'b_b', 'h')
			)
		    )
		))
	    ))
	);
	return
		array(
		    'type' => 'tabs',
		    'options' => array(
			'g' => array(
			    'options' => $general
			),
			'm_t' => array(
				'options' => $this->module_title_custom_style()
			),
			'b' => array(
			    'label' => __('Bar', 'builder-progress-bar'),
			    'options' => $bar
			)
		    )
	);
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_ProgressBar_Module();
	} else {
		Themify_Builder_Model::register_module('TB_ProgressBar_Module');
	}
}