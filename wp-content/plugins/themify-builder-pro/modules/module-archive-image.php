<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Archive Image
 * Description: 
 */
class TB_Archive_Image_Module extends Themify_Builder_Component_Module {
	
	
	public static function get_module_name():string {
		return __('Archive Cover Image', 'tbp');
	}


	public static function get_module_icon():string {
		return 'image';
	}

	public static function get_js_css():array{
		return array(
			'css' => 'image'
		);
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('archive-image');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'archive-image',
				'category' => $this->get_group()
			));
		}
	}

	/**
	 * Render plain content for static content.
	 */
	public static function get_static_content(array $module):string {
		return '';
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
		return Tbp_Utils::get_module_settings('image', 'styling');
	}

    public static function get_styling_image_fields() : array {
        return [
            'background_image' => ''
        ];
    }
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Archive_Image_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Archive_Image_Module');
	}
}