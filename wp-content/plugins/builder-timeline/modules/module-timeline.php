<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Timeline
 */

class TB_Timeline_Module extends Themify_Builder_Component_Module {

	
	public static function get_json_file():array{
		return ['f'=>Builder_Timeline::$url . 'json/style.json','v'=>Builder_Timeline::get_version()];
	}

    public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
        return __('Timeline', 'builder-timeline');
    }

    public static function get_module_icon():string{
		return 'time';
    }

    public static function get_js_css():array {
		$url = Builder_Timeline::$url . 'assets/';
		return array(
			'async'=>true,
			'css' => $url. 'style',
			'js' => $url.'scripts',
			'ver' => Builder_Timeline::get_version(),
			'lng'=>self::getLocale()
		);
    }

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-timeline', Builder_Timeline::$url . 'assets/active.js', Builder_Timeline::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Timeline::$url . 'assets/active.js']=Builder_Timeline::get_version();
		}

		$i18n = include dirname( __DIR__ ) . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;

		return $vars;
	}


    private static function getLocale():string{
		$locale = get_locale();
		switch ( $locale ) {
			case 'cs-CZ': $locale = 'cz'; break;
			case 'pt_BR': $locale = 'pt-br'; break;
			case 'zh_TW': $locale = 'zh-tw'; break;
			case 'zh_CN': $locale = 'zh-cn'; break;
			default: $locale = substr( $locale, 0, 2 ); break;
		}
		return $locale;
    }

    public function __construct() {
        if(method_exists('Themify_Builder_Model', 'add_module')){
            parent::__construct('timeline');
        }
        else{//backward
             parent::__construct(array(
                'name' =>$this->get_name(),
                'slug' => 'timeline',
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

    public function get_styling() {//deprecated backward
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
			    self::get_color_type(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline a', ' .tl-headline', ' .tl-text-content p', ' .tl-headline-date')),
			    self::get_font_size(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date')),
			    self::get_font_style(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date'), 'f_fs_g', 'f_fw_g'),
			    self::get_line_height(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date')),
				self::get_text_shadow(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date')),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family('', 'f_f', 'h'),
			    self::get_color_type(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline a', ' .tl-headline', ' .tl-text-content p', ' .tl-headline-date'), 'h'),
			    self::get_font_size(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date'), 'f_s', '', 'h'),
				self::get_font_style(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date'), 'f_fs_g', 'f_fw_g', 'h'),
				self::get_text_shadow(array('.module-timeline .module-timeline-title', '.module-timeline .entry-content', '.layout-list .module-timeline-date', ' .tl-headline', ' .tl-text-content', ' .tl-headline-date'),'t_sh','h'),
			)
		    )
		))
	    )),
	    // Link
	    self::get_expand('l', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color(' a', 'link_color'),
			    self::get_text_decoration(' a')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_color(' a', 'link_color', null, null, 'hover'),
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
			    self::get_margin('', '', 'h')
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
			    self::get_border('', 'm_timeline', 'h')
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

	$timeline_title = array(
	    // Font
	    self::get_seperator('f'),
	    self::get_tab(array(
		'n' => array(
		    'options' => array(
			self::get_font_family(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_f_t_t'),
			self::get_color(array('.module.layout-list .module-timeline-title', '.module.layout-list .module-timeline-title a', '.module .tl-text .tl-headline', '.module .tl-text .tl-headline a'), 'f_c_t_t'),
			self::get_font_size(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_s_t_t'),
			self::get_line_height(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'l_h_t_t'),
			self::get_letter_spacing(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'l_s_t_t'),
			self::get_text_align(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_a_t_t'),
			self::get_text_transform(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_t_t_t'),
			self::get_font_style(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_sy_t_t', 'f_b_t_t'),
			self::get_text_decoration(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_d_t_t'),
			self::get_text_shadow(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_sh_t_t'),
		    )
		),
		'h' => array(
		    'options' => array(
			self::get_font_family(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_f_t_t','h'),
			self::get_color(array('.module.layout-list .module-timeline-title', '.module.layout-list .module-timeline-title a', '.module .tl-text .tl-headline', '.module .tl-text .tl-headline a'), 'f_c_t_t',null,null,'h'),
			self::get_font_size(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_s_t_t','','h'),
			self::get_font_style(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 'f_sy_t_t', 'f_b_t_t','h'),
			self::get_text_decoration(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_d_t_t','h'),
			self::get_text_shadow(array('.module.layout-list .module-timeline-title', '.module .tl-text .tl-headline'), 't_sh_t_t','h'),
		    )
		)
	    ))
	);

	$timeline_date = array(
	    // Font
	    self::get_seperator('f'),
	    self::get_tab(array(
		'n' => array(
		    'options' => array(
			self::get_font_family(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_f_t_d'),
			self::get_color(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_c_t_d'),
			self::get_font_size(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_s_t_d'),
			self::get_line_height(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'l_h_t_d'),
			self::get_text_align(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_a_t_d'),
			self::get_text_transform(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_t_t_d'),
			self::get_font_style(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_sy_t_d', 'f_b_t_d'),
			self::get_text_decoration(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_d_t_d'),
			self::get_text_shadow(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_sh_t_d'),
		    )
		),
		'h' => array(
		    'options' => array(
			self::get_font_family(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_f_t_d','h'),
			self::get_color(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_c_t_d',null,null,'h'),
			self::get_font_size(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_s_t_d','','h'),
			self::get_font_style(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 'f_sy_t_d', 'f_b_t_d','h'),
			self::get_text_decoration(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_d_t_d','h'),
			self::get_text_shadow(array('.layout-list .module-timeline-date', ' .tl-headline-date'), 't_sh_t_d','h'),
		    )
		)
	    ))
	);

	$content = array(
	    // Background
	    self::get_expand('bg', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_color('.layout-list .module-timeline-content .entry-content, .tl-text-content', 'b_c_c','bg_c','background-color')
			)
		    ),
		    'h' => array(
			'options' => array(
			   self::get_color('.layout-list .module-timeline-content .entry-content, .tl-text-content', 'b_c_c','bg_c','background-color','h')
			)
		    )
		))
		    )
	    ),
	    // Font
	    self::get_expand('f', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_font_family('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_f_c'),
			    self::get_color('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_c_c'),
			    self::get_font_size('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_s_c'),
			    self::get_font_style('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_fs_c', 'f_fw_c'),
			    self::get_line_height('.layout-list .module-timeline-content .entry-content, .tl-text-content','l_h_c'),
				self::get_text_shadow('.layout-list .module-timeline-content .entry-content, .tl-text-content','t_sh_c'),
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_font_family('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_f_c','h'),
			    self::get_color('.layout-list .module-timeline-content .entry-content:hover, .tl-text-content:hover','f_c_c_h',null,null,''),
			    self::get_font_size('.layout-list .module-timeline-content .entry-content, .tl-text-content','f_s_c','','h'),
				self::get_font_style('.layout-list .module-timeline-content .entry-content:hover, .tl-text-content:hover','f_fs_c_h', 'f_fw_c_h',null,null,''),
				self::get_text_shadow('.layout-list .module-timeline-content .entry-content, .tl-text-content','t_sh_c','h'),
			)
		    )
		))
		    )
	    ),
	    // Padding
	    self::get_expand('p', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_padding('.layout-list .module-timeline-content .entry-content, .tl-text-content','c_p')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_padding('.layout-list .module-timeline-content .entry-content, .tl-text-content','c_p','h')
			)
		    )
		))
	    )),
	    // Border
	    self::get_expand('b', array(
		self::get_tab(array(
		    'n' => array(
			'options' => array(
			    self::get_border('.layout-list .module-timeline-content .entry-content, .tl-text-content','c_b')
			)
		    ),
		    'h' => array(
			'options' => array(
			    self::get_border('.layout-list .module-timeline-content .entry-content, .tl-text-content','c_b','h')
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
		't_t' => array(
		    'label' => __('Title', 'themify'),
		    'options' => $timeline_title
		),
		't_d' => array(
		    'label' => __('Date', 'themify'),
		    'options' => $timeline_date
		),
		't_c' => array(
		    'label' => __('Content', 'themify'),
		    'options' => $content
		)
	    )
	);
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Timeline_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Timeline_Module');
	}
}