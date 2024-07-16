<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Product Image
 * Description: 
 */
class TB_Product_Image_Module extends Themify_Builder_Component_Module {
	
	public static function is_available():bool{
		return themify_is_woocommerce_active();
	}

	public static function get_module_name():string {
		return __('Product Image', 'tbp');
	}

	public static function get_module_icon():string {
		return 'image';
	}

	public static function get_js_css():array{
		return array(
			'ver' => TBP_VER,
			'css' => TBP_WC_CSS_MODULES . 'product-image'
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
			parent::__construct('product-image');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'product-image',
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
							self::get_image('.module img', 'b_i', 'bg_c', 'b_r', 'b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('.module img', 'b_i', 'bg_c', 'b_r', 'b_p', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module img', 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module img', 'p', 'h')
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
							self::get_border(' img', 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' img', 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img', 'fl')) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img', 'fl_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_width('', 'w')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array('.module img', '.module .woocommerce-product-gallery__wrapper'), 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array('.module img', '.module .woocommerce-product-gallery__wrapper'), 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array(self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$sale_badge = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .onsale', 'f_c_s_b'),
							self::get_font_size('.module .onsale', 'f_s_s_b', ''),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .onsale', 'f_c_s_b', 'h'),
							self::get_font_size('.module .onsale', 'f_s_s_b', '', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .onsale', 'p_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .onsale', 'p_s_b', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .onsale', 'm_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .onsale', 'm_s_b', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .onsale', 'b_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .onsale', 'b_s_b', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b', 'h')
						)
					)
				))
			))
		);

		$thumbnails = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'b_c_tb', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'b_c_tb', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'p_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'p_tb', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array('.module div.product div.images .flex-control-thumbs li', ' .product-thumbnails-carousel .tf_swiper-slide'), 'm_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array('.module div.product div.images .flex-control-thumbs li', ' .product-thumbnails-carousel .tf_swiper-slide'), 'm_tb', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'b_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'b_tb', 'h')
						)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_width(array('.module div.product div.images .flex-control-thumbs li', ' .product-thumbnails-carousel .tf_swiper-slide'), 'w_tb')
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'r_c_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'r_c_tb', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'sh_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .flex-control-thumbs img', ' .product-thumbnails-carousel img'), 'sh_tb', 'h')
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
				'thmb' => array(
					'options' => $thumbnails
				),
				'tbp_salebdg' => array(
					'options' => $sale_badge
				),
			)
		);
	}


	public static function get_product_image_thumbnail_html($args, $attachment_id, $thumbnail = false) {
		$width_attr = true === $thumbnail ? 'thumb_image_w' : 'image_w';
		$height_attr = true === $thumbnail ? 'thumb_image_h' : 'image_h';
		$html = wc_get_gallery_image_html($attachment_id, true);
		if ($args[$width_attr] !== '' || $args[$height_attr] !== '') {
			if (!Themify_Builder_Model::is_img_php_disabled()) {
				$src = wp_get_attachment_image_src($attachment_id, true === $thumbnail ? array($args[$width_attr], $args[$height_attr]) : 'full');
				if (!empty($src[0])) {
					preg_match('/src="([^"]+)"/', $html, $image_src);
					if (!empty($image_src[1])) {
						$url = themify_get_image(array(
							'src' => $src[0],
							'w' => $args[$width_attr],
							'h' => $args[$height_attr],
							'urlonly' => true
						));

						$html = str_replace($image_src[1], $url, $html);
						$image_src = $url = null;
					}
				}
			}
			if ($args[$width_attr] !== '') {
				$html = preg_replace('/ width=\"([0-9]{1,})\"/', ' width="' . $args[$width_attr] . '"', $html);
			}
			if ($args[$height_attr] !== '') {
				$html = preg_replace('/ height=\"([0-9]{1,})\"/', ' height="' . $args[$height_attr] . '"', $html);
			}
		}
		return $html;
	}

	public static function set_image_size_gallery($html, $id) {
		global $post;
		return TB_Product_Image_Module::get_product_image_thumbnail_html(array('image_w' => $post->gallery_image_size_w, 'image_h' => $post->gallery_image_size_h), $id);
	}

	public static function set_image_size_gallery_thumbnail($size) {
		return array(
			empty($GLOBALS['post']->gallery_thumb_size_w) ? $size['width'] : $GLOBALS['post']->gallery_thumb_size_w,
			empty($GLOBALS['post']->gallery_thumb_size_h) ? $size['height'] : $GLOBALS['post']->gallery_thumb_size_h,
		);
	}

	public static function product_gallery_type($type) {
		remove_filter('themify_theme_product_gallery_type', array('TB_Product_Image_Module', 'product_gallery_type'));
		return 'disable-zoom' === $type ? '' : $type;
	}

	public static function remove_srcset($args) {
		unset($args['srcset'], $args['sizes']);
		return $args;
	}

    public static function get_styling_image_fields() : array {
        return [
            'b_i' => '.module img'
        ];
    }
}


if (!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) && TB_Product_Image_Module::is_available()) {
	if (method_exists('Themify_Builder_Model', 'add_module')) {
		new TB_Product_Image_Module();
	} else {
		Themify_Builder_Model::register_module('TB_Product_Image_Module');
	}
}
