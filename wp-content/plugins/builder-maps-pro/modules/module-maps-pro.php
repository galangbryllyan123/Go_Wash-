<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Maps Pro
 */
class TB_Maps_Pro_Module extends Themify_Builder_Component_Module {

	public static function get_module_name():string{
		add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
		return __('Maps Pro', 'builder-maps-pro');
	}

	public static function get_json_file():array{
		return ['f'=>Builder_Maps_Pro::$url . 'json/style.json','v'=>Builder_Maps_Pro::get_version()];
	}

	public static function get_module_icon():string{
	    return 'world';
	}

	/**
	 * Filter the marker texts
	 */
	public static function sanitize_text(string $text ):string {
		return preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $text );
	}

	public static function get_js_css():array {
		$url=Builder_Maps_Pro::$url.'assets/';
		return array(
			'css'=>$url.'style',
			'js'=>$url.'scripts',
			'async'=>true,
			'ver'=>Builder_Maps_Pro::get_version()
		);
	}

	/**
	 * Query posts and generate markers based on custom field
	 *
	 * @return array
	 */
	public static function  get_post_markers(array $settings ):array {
		global $post;

		$settings+= array(
			'per_page' => 5,
			'custom_field_type' => '',
			'ptb_map_field' => '',
			'acf_map_field' => '',
			'marker_icon' => '',
			'post_type_post' => 'post',
			'term_type' => 'category',
			'tax' => 'category',
			'post_slug' => '',
			'offset' => '',
			'order' => 'desc',
			'orderby' => 'date',
		);
		$args = array(
			'post_status' => 'publish',
			'post_type' => $settings['post_type_post'],
			'posts_per_page' => $settings['per_page'],
			'order' => $settings['order'],
			'orderby' => $settings['orderby'],
			'no_found_rows'=>true,
			'ignore_sticky_posts'=>true,
			'suppress_filters' => false,
			'offset' => $settings['offset'],
		);
		if ( $settings['term_type'] === 'post_slug' ) {
			if ( $settings['post_slug'] !== '' ) {
				$args['post__in'] = Themify_Builder_Model::parse_slug_to_ids( $settings['post_slug'], $args['post_type'] );
			}
		} elseif($settings['term_type'] !== 'all') {
			$terms = isset( $settings[ "tax_{$settings['tax']}" ] ) ? $settings[ "tax_{$settings['tax']}" ] : ( isset( $settings['tax_category'] ) ? $settings['tax_category'] : '0' );
			if ( $terms === false ) {
				return [];
			}
			Themify_Builder_Model::parseTermsQuery( $args, $terms, $settings['tax'] );
		}

		$query = new WP_Query( apply_filters( 'tb_maps_pro_query', $args, $settings ) );
		if ( is_object( $post ) ){
			$saved_post = clone $post;
		}
		$items = array();
		while ( $query->have_posts() ) {
			$query->the_post();

			$item = self::get_post_marker( $settings );
			if ( ! empty( $item ) ) {
				$items[] = $item;
			}
		}
		if ( isset( $saved_post ) && is_object( $saved_post ) ) {
			$post = $saved_post;
			setup_postdata( $saved_post );
		}

		return $items;
	}

	private static function get_post_marker(array $settings ):array {
		if ( ! empty ( $settings['ptb_map_field'] ) ) {
			$meta_key = explode( ':', $settings['ptb_map_field']);
			$meta_key = !empty($meta_key[1]) ? ('ptb_' . $meta_key[1]) : $settings['ptb_map_field'];
			$address = get_post_meta( get_the_id(), $meta_key, true );
			if ( is_array( $address ) ) {
				$address = json_decode( $address['place'], true );
				$address = $address['location']['lat'] . ', ' . $address['location']['lng'];
			}
		} elseif ( ! empty( $settings['acf_map_field'] ) && class_exists( 'acf_pro' ,false) ) {
			$value = Tbp_Utils::acf_get_field_value( [ 'key' => $settings['acf_map_field'] ] );
			if ( isset( $value['address'] ) ) {
				$address = $value['address'];
			}
		} elseif(!empty($settings['custom_field'])) {
			$address = get_post_meta( get_the_id(), $settings['custom_field'], true );
		}

		// skip posts that don't have the designated "address" meta field
		if ( empty($address) ) {
			return [];
		}
		$text = sprintf(
			'
			<div style="float: left; margin-right: 10px;">
				<a href="%2$s">
					<img src="%1$s" alt="%3$s" />
				</a>
			</div>
			<div>
				<a href="%2$s"><strong>%3$s</strong></a>
			</div>
			<div>
				%4$s
			</div>',
			esc_attr( get_the_post_thumbnail_url( get_the_id(), 'thumbnail' ) ),
			esc_attr( get_permalink() ),
			esc_html( get_the_title() ),
			esc_html( get_the_excerpt() )
		);
		return array(
			'title' => $text,
			'image' => $settings['marker_icon'],
			'address' => $address,
		);
	}

	public static function builder_active_enqueue(array $vars ):array {
		if(!isset($vars['addons'])){//backward
			themify_enque_script( 'tb_builder-maps-pro', Builder_Maps_Pro::$url . 'assets/active.js', Builder_Maps_Pro::get_version(), [ 'themify-builder-app-js' ] );
		}
		else{
			$vars['addons'][Builder_Maps_Pro::$url . 'assets/active.js']=Builder_Maps_Pro::get_version();
		}
		
		

		$i18n = include dirname( __DIR__ )  . '/includes/i18n.php';
		$vars['i18n']['label']+= $i18n;
		$vars['maps_pro_vars'] = [
			'url' => Builder_Maps_Pro::$url,
			'key' => Themify_Builder_Model::getMapKey(),
			'v' => Builder_Maps_Pro::get_version()
		];

		return $vars;
	}


	public function __construct() {
            if(method_exists('Themify_Builder_Model', 'add_module')){
                parent::__construct('maps-pro');
            }
            else{//backward
                 parent::__construct(array(
                    'name' =>$this->get_name(),
                    'slug' => 'maps-pro',
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
				   self::get_color('', 'background_color','bg_c','background-color')
			       )
			   ),
			   'h' => array(
			       'options' => array(
				   self::get_color('', 'bg_c','bg_c','background-color','h')
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

		$marker = array(
			self::get_tab(array(
				'n' =>array(
					'options' => array(
						self::get_font_family(' .maps-pro-content', 'f_f_m'),
						self::get_color(' .maps-pro-content', 'f_c_m'),
						self::get_font_size(' .maps-pro-content', 'f_s_m'),
						self::get_font_style(' .maps-pro-content', 'f_st_m', 'f_fw_m'),
						self::get_line_height(' .maps-pro-content', 'l_h_m'),
						self::get_text_shadow(' .maps-pro-content', 't_s_m'),
					)
				),
				'h' => array(
					'options' => array(
						self::get_font_family(' .maps-pro-content', 'f_f_m', 'h'),
						self::get_color(' .maps-pro-content', 'f_c_m', null, null, 'h'),
						self::get_font_size(' .maps-pro-content', 'f_s_m', '', 'h'),
						self::get_font_style(' .maps-pro-content', 'f_st_m', 'f_fw_m', 'h'),
						self::get_text_shadow(' .maps-pro-content', 't_s_m', 'h'),
					)
				)
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
				'ma' => array(
					'label' => __( 'Markers', 'builder-maps-pro' ),
					'options' => $marker,
				),
			)
		);
	}
}

if(!method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
		new TB_Maps_Pro_Module();
	} else {
		Themify_Builder_Model::register_module( 'TB_Maps_Pro_Module' );
	}
}