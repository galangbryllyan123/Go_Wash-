<?php

class Tbp_Dynamic_Content {

	/**
	 * Name of the option that stores Dynamic Content settings
	 */
	const FIELD_NAME = '__dc__';

	public static function run():void {
		add_filter('tf_builder_row', array(__CLASS__, 'tf_builder_row'));
		add_filter('tf_builder_subrow', array(__CLASS__, 'tf_builder_row'));
		add_filter('themify_builder_module_render_vars', array(__CLASS__, 'themify_builder_module_render_vars'));
		add_action('wp_ajax_tpb_get_dynamic_content_fields', array(__CLASS__, 'options'));
		add_action('themify_builder_background_styling', array(__CLASS__, 'background_styling'), 10, 4);

		/* Frontend editor preview */
		add_action('wp_ajax_tpb_get_dynamic_content_preview', array(__CLASS__, 'preview'));
		add_filter('themify_builder_data', array(__CLASS__, 'frontend_editor_preview'), 10, 2);
		add_filter('themify_builder_load_module_partial', array(__CLASS__, 'themify_builder_load_module_partial'));
	}


	public static function get(string $id='all'){
		if($id==='all'){
			$modules=[
				'ACFChoice',
				'ACFDate',
				'ACFEmail',
				'ACFFile',
				'ACFGallery',
				'ACFImage',
				'ACFMap',
				'ACFNumber',
				'ACFoEmbed',
				'ACFPageLink',
				'ACFText',
				'ACFTextarea',
				'ACFURL',
				'ACFWysiwyg',
				'ArchiveDescription',
				'ArchiveTitle',
				'CurrentDate',
				'CustomField',
				'EventPostDate',
				'FileContent',
				'MediaLibrary',
				'Option',
				'PostAuthorAvatar',
				'PostAuthorBio',
				'PostAuthorEmail',
				'PostAuthorMeta',
				'PostAuthorName',
				'PostAuthorURL',
                'PostAuthorClass',
				'PostClass',
				'PostCommentCount',
				'PostDate',
				'PostExcerpt',
				'PostFeaturedImage',
				'PostImageAttachments',
				'PostPermalink',
				'PostTerms',
                'PostTermsClass',
				'PostTitle',
				'ProductAttributes',
				'ProductCatImage',
				'ProductDescription',
				'ProductGallery',
				'ProductImage',
				'ProductPrice',
				'ProductRating',
				'ProductSalePrice',
				'ProductSKU',
				'ProductStock',
				'ProductTitle',
				'ProductCartUrl',
				'PTBAcc',
				'PTBAccordion',
				'PTBAudio',
				'PTBAudioPlaylist',
				'PTBCheckbox',
                'PTBCheckboxClass',
				'PTBDate',
				'PTBDateAsText',
				'PTBEmail',
				'PTBFile',
				'PTBGallery',
				'PTBGalleryAsText',
				'PTBIcon',
				'PTBIconAsIcon',
				'PTBIconAsText',
				'PTBImage',
				'PTBLinkButton',
				'PTBMap',
				'PTBNumber',
				'PTBProgressBar',
				'PTBRadioButton',
                'PTBRadioButtonClass',
				'PTBRating',
				'PTBRatingAsText',
				'PTBRelations',
				'PTBRepeatableText',
				'PTBSelect',
                'PTBSelectClass',
				'PTBTelephone',
				'PTBText',
				'PTBTextarea',
				'PTBVideo',
				'PTBVideoPlaylist',
				'RandomNumber',
				'Shortcode',
				'SiteDescription',
				'SiteIcon',
				'SiteTitle',
				'SiteURL',
				'tbpTermCover'
			];
		}
		else{
			$modules=[$id];
		}
		$items=[];
		foreach ($modules as $slug) {
			$class = "\Tbp_Dynamic_Item_{$slug}";
			if (!class_exists($class,false)) {
				$f = TBP_DIR . 'includes/dynamic-content/'.$slug.'.php';
				if (is_file($f)) {
					include $f;
				}
			}
			if(class_exists($class,false) && $class::is_available()){
				$items[$slug]=$class;
			}
		}
		return $id==='all'?$items:($items[$id]??null);
	}

	/**
	 * Get value from saved DC settings
	 */
	private static function get_value(array $options):string {	
		if (isset($options['item']) && ( $item = self::get($options['item']) )) {		
			$value = $item::get_value($options);

			if (empty($value)) {
				if (!empty($options['condition']) && !Themify_Builder::$frontedit_active) {
					if ($options['condition'] === 'show_text') {
						if (isset($options['__fb_val'])) {
							return $options['__fb_val'];
						}
					} elseif ($options['condition'] === 'hide_module') {
						return '__disable_module__'; // flag to remove the module
					}
					if ($options['condition'] === 'hide_row') {
						return '__disable_row__'; // flag to remove the row
					}
					if ($options['condition'] === 'hide_subrow') {
						return '__disable_subrow__'; // flag to remove the subrow
					}
				}
				$value='';
			} 
			else {
				// general formatting for various field types
				if (isset($options['text_before'])) {
					$value = $options['text_before'] . $value;
				}
				if (isset($options['text_after'])) {
					$value .= $options['text_after'];
				}
				if (!empty($options['uri_scheme'])) {
					$value = $options['uri_scheme'] . ':' . $value;
				}
			}

			return $value;
		}

		return '';
	}

	/**
	 * Adds inline styles for styling the background image of Builder components
	 *
	 * hooked to "themify_builder_background_styling"
	 */
	public static function background_styling($builder_id, array $settings, $type) {
		if (!isset($settings['styling'][self::FIELD_NAME]) || $settings['styling'][self::FIELD_NAME] === '{}') {
			return;
		}
		$dc = is_string($settings['styling'][self::FIELD_NAME]) ? json_decode($settings['styling'][self::FIELD_NAME], true) : $settings['styling'][self::FIELD_NAME];
		if (!is_array($dc)) {
			return;
		}

		$element_id = 'tb_' . $settings['element_id'];
		if ($type === 'row' || $type === 'column' || $type === 'subrow') {
			$bg_fields = self::get_background_image_fields($type);
			$type_selector = '.module_' . $type;
		} else {
			$mod_name = $settings['mod_name'];
			$bg_fields = self::get_background_image_fields($mod_name);
			$type_selector = '.module-' . $mod_name;
		}
        if (empty($bg_fields)) {
			return;
		}
		$intersect = array_intersect_key($dc, $bg_fields);
		if (empty($intersect)) {
			return;
		}
        $dc = null;
		$styles = $base = $unique_item_id = '';
		if(isset(Themify_Builder::$is_loop)){
			$isLoop=Themify_Builder::$is_loop;
		}
		else{//backward
			global $ThemifyBuilder;
			$isLoop = $ThemifyBuilder->in_the_loop === true;
		}
		if ($isLoop === true) {
			if (class_exists('TB_Advanced_Posts_Module',false) && TB_Advanced_Posts_Module::$builder_id !== null) {
				$builder_id = str_replace('tb_', '', TB_Advanced_Posts_Module::$builder_id);
                $unique_item_id = ' .post-' . get_the_ID();
			} elseif (class_exists('TB_Advanced_Products_Module',false) && TB_Advanced_Products_Module::$builder_id !== null) {
				$builder_id = str_replace('tb_', '', TB_Advanced_Products_Module::$builder_id);
                $unique_item_id = ' .post-' . get_the_ID();
			} elseif ( class_exists('TB_ACF_Repeater_Module',false) && TB_ACF_Repeater_Module::$builder_id !== null ) {
				$builder_id = TB_ACF_Repeater_Module::$builder_id;
                $unique_item_id = ' .acf_row_' . get_row_index();
			} elseif ( class_exists( 'TB_PTB_Repeater_Module',false ) && TB_PTB_Repeater_Module::$builder_id !== null ) {
                $builder_id = TB_PTB_Repeater_Module::$builder_id;
                $unique_item_id = ' .tb_ptb_repeater_row_' . PTB_Repeater_Field::get_index();
            }
		} else {
			$base = '.themify_builder';
		}
		$base .= '.themify_builder_content-' . $builder_id;
		foreach ($intersect as $key => $options) {
			if ($value = self::get_value($options)) {
				$selector = $base;
                if ( $unique_item_id !== '' ) {
					$selector .= $unique_item_id;
				}
				$selector .= " {$type_selector}.{$element_id}";
				if (is_string($bg_fields[$key])) {
					$selector .= $bg_fields[$key];
				} else {
					$selector .= implode(',', $bg_fields[$key]);
				}
				$styles .= $selector . '{background-image:url("' . $value . '")}';
			}
		}
		if ($styles !== '') {
			echo '<style class="tbp_dc_styles">', $styles, '</style>';
		}
	}

	/**
	 * Loops through a component styling definition to find all background-image fields
	 *
	 * @return array
	 */
	private static function get_background_image_fields($type) {
		if (empty($type)) {
			return array();
		}
		$list = [];
        if ($type === 'row' || $type === 'column' || $type === 'subrow') {
            $list = array('background_image' => '');
            $inner_select = '>div.';
            $inner_select .= $type === 'column' ? 'tb-column-inner' : $type . '_inner';
            $list['background_image_inner'] = array($inner_select);
            if ($type === 'column' && Themify_Builder::$frontedit_active === true) {
                $list['background_image_inner'][] = '>.tb_holder';
            }
        } else {
            $module_class = Themify_Builder_Component_Module::load_modules($type);
            if ( $module_class && method_exists( $module_class, 'get_styling_image_fields' ) ) {
                $list = $module_class::get_styling_image_fields();
            }
        }

		return $list;
	}

	/**
	 * Update Builder data in frontend preview
	 * uses self::do_replace() and retains the DC data
	 */
	public static function frontend_editor_preview($data, $post_id) {
		if ($post_id === Themify_Builder::$builder_active_id && Themify_Builder_Model::is_front_builder_activate()) {
			if (!empty($data)) {
				foreach ($data as &$row) {
					if (isset($row['styling'])) {
						$replace = self::do_replace($row['styling'], ['unset_dc_data' => false]);
						if (is_array($replace)) {
							$row['styling'] = $replace;
						}
					}

					if (!empty($row['cols'])) {
						foreach ($row['cols'] as &$column) {

							if (isset($column['styling'])) {
								$replace = self::do_replace($column['styling'], ['unset_dc_data' => false]);
								if (is_array($replace)) {
									$column['styling'] = $replace;
								}
							}

							if (!empty($column['modules'])) {
								foreach ($column['modules'] as &$module) {

									if (isset($module['styling'])) {
										$replace = self::do_replace($module['styling'], ['unset_dc_data' => false]);
										if (is_array($module['styling'])) {
											$module['styling'] = $replace;
										}
									}

									/* subrows */
									if (!empty($module['cols'])) {
										foreach ($module['cols'] as &$sub_column) {

											if (isset($sub_column['styling'])) {
												$replace = self::do_replace($sub_column['styling'], ['unset_dc_data' => false]);
												if (is_array($replace)) {
													$sub_column['styling'] = $replace;
												}
											}

											if (!empty($sub_column['modules'])) {
												foreach ($sub_column['modules'] as &$sub_column_module) {
													if (!empty($sub_column_module['mod_settings'])) {
														$replace = self::do_replace($sub_column_module['mod_settings'], ['unset_dc_data' => false]);
														if (is_array($replace)) {
															$sub_column_module['mod_settings'] = $replace;
														}
													}
												}
											}
										}
									} elseif (isset($module['mod_settings'])) {
										$replace = self::do_replace($module['mod_settings'], [
												'ignore_disable_subrow_condition' => true,
												'unset_dc_data' => false,
										]);
										if (is_array($replace)) {
											$module['mod_settings'] = $replace;
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Fix preview in load_module_partial
	 */
	public static function themify_builder_load_module_partial(array $batch=array()):array {
		if (!empty($batch)) {
			foreach ($batch as &$component) {
				if (isset($component['elType'])) {
					$type = $component['elType'];
					$key = $type === 'module' ? 'mod_settings' : 'styling';
					if (!empty($component[$key])) {
						$replace = self::do_replace($component[$key], ['unset_dc_data' => false]);
						if (is_array($replace)) {
							$component[$key] = $replace;
						}
					}
				}
			}
		}

		return $batch;
	}

	/**
	 * Loop through a row
	 *
	 * @return array
	 */
	public static function tf_builder_row($row) {
		if (isset($row['styling'])) {
			$replace = self::do_replace($row['styling'], ['type' => 'row']);
			if ($replace === '__disable_row__') {
				return array();
			} else {
				$row['styling'] = $replace;
			}
		}

		if (!empty($row['cols'])) {
			foreach ($row['cols'] as $column_index => &$column) {

				if (isset($column['styling'])) {
					$replace = self::do_replace($column['styling'], ['type' => 'column']);
					if ($replace === '__disable_row__') {
						return array();
					} else {
						$column['styling'] = $replace;
					}
				}

				if (!empty($column['modules'])) {
					foreach ($column['modules'] as $module_index => &$module) {
						if (isset($module['mod_settings'])) {
							$replace = self::do_replace($module['mod_settings'], ['type' => $module['mod_name']]);
							if ($replace === '__disable_row__') {
								return array();
							} 
							if ($replace === '__disable_subrow__') {
								unset($row['cols'][$column_index]['modules'][$module_index]);
								continue;
							}
							$module['mod_settings'] = $replace;
						}

						/* subrows */
						if (!empty($module['cols'])) {
							foreach ($module['cols'] as $sub_column_index => &$sub_column) {

								if (isset($sub_column['styling'])) {
									$replace = self::do_replace($sub_column['styling'], ['type' => 'column']);
									if ($replace === '__disable_row__') {
										return array();
									} 
									if ($replace === '__disable_subrow__') {
										unset($row['cols'][$column_index]['modules'][$module_index]);
										continue 2;
									}
									$sub_column['styling'] = $replace;
								}

								if (!empty($sub_column['modules'])) {
									foreach ($sub_column['modules'] as $sub_column_module_index => &$sub_column_module) {
										if (isset($sub_column_module['mod_settings'])) {
											$replace = self::do_replace($sub_column_module['mod_settings'], ['type' => $sub_column_module['mod_name']]);
											if ($replace === '__disable_row__') {
												// hide entire row
												return array();
											}
											if ($replace === '__disable_module__') {
												unset($row['cols'][$column_index]['modules'][$module_index]['cols'][$sub_column_index]['modules'][$sub_column_module_index]);
											} 
											if ($replace === '__disable_subrow__') {
												// hide subrow
												unset($row['cols'][$column_index]['modules'][$module_index]);
											} else {
												$sub_column_module['mod_settings'] = $replace;
											}
										}
									}
								}
							}
						} elseif (isset($module['mod_settings'])) {
							$replace = self::do_replace($module['mod_settings'], [
									'ignore_disable_subrow_condition' => true,
									'type' => $module['mod_name']
							]);
							if ($replace === '__disable_row__') {
								/* hide the entire row */
								return array();
							} 
							if ($replace === '__disable_module__') {
								/* hide the module */
								unset($row['cols'][$column_index]['modules'][$module_index]);
							} else {
								$module['mod_settings'] = $replace;
							}
						}
					}
				}
			}
		}


		return $row;
	}

	/**
	 * Filter module settings in preview
	 *
	 * @return array
	 */
	public static function themify_builder_module_render_vars(array $vars):array {
		if (!empty($_POST['action']) && $_POST['action'] === 'tb_save_data') {
			return $vars;
		}

		return self::do_replace($vars);
	}

	/**
	 * Parse DC settings and replace the module settings in $vars with their values.
	 *
	 * @return array|string
	 */
	public static function do_replace($vars, array $args = []) {
		if (!isset($vars[self::FIELD_NAME]) || $vars[self::FIELD_NAME] === '{}'){
			return $vars;
		}
		$fields = is_string($vars[self::FIELD_NAME]) ? json_decode($vars[self::FIELD_NAME], true) : $vars[self::FIELD_NAME];

		if (empty($fields) || !is_array($fields)) {
			return $vars;
		}

		$args+=[
			'ignore_disable_subrow_condition' => false,
			'unset_dc_data' => true, /* whether to remove the DC settings after parsing */
			'type' => '',
		];

		foreach ($fields as $key => $options) {
			if (!isset($options['item']) || isset($options['repeatable'])) {
				if (isset($vars[$key]) && is_array($vars[$key])) {
					if ($args['unset_dc_data']) {
						unset($options['o']);
					}
					unset($options['repeatable']);
					// loop through repeatable items
					if (!empty($options) && is_array($options)) {
						foreach ($options as $i => $items) {
							if (!empty($items)) {
								foreach ($items as $field_name => $field_options) {
									if (isset($field_options['item'])) {
										$value = self::get_value($field_options);
										if ($value==='__disable_module__' ||  $value==='__disable_row__') {
											return $value;
										} 
										if ('__disable_subrow__' === $value) {
											if (!$args['ignore_disable_subrow_condition']) {
												// flag the subrow to be removed
												return '__disable_subrow__';
											}
											// replace field with empty string
											$vars[$key][$i][$field_name] = '';
										}
										 else {
											$vars[$key][$i][$field_name] = $value;
										}
									}
								}
							}
						}
					}
				}
			} else {
				$value = self::get_value($options);
				if ($value==='__disable_module__' || $value==='__disable_row__') {
					// special flags, return the string instead of replacing
					return $value;
				}
				if ('__disable_subrow__' === $value) {
					if (!$args['ignore_disable_subrow_condition']) {
						// flag the subrow to be removed
						return '__disable_subrow__';
					}
					// replace field with empty string
					$vars[$key] = '';
				} else {
					$vars[$key] = $value;
				}
			}
		}

		if ($args['unset_dc_data']) {
			/* a list of DC fields needed for dynamic background styling */
			$dynamic_bg_fields = array_intersect_key($vars[self::FIELD_NAME], self::get_background_image_fields($args['type']));
			/* clear the DC settings so it's not parsed multiple times */
			if (!empty($dynamic_bg_fields)) {
				/* keep the background styling fields to be parsed in background_styling hook */
				$vars[self::FIELD_NAME] = $dynamic_bg_fields;
			} else {
				unset($vars[self::FIELD_NAME]);
			}
		}

		return $vars;
	}


	public static function get_builder_active_localize():array {
		$list = [];
		foreach (self::get() as $id=>$class) {
			$list[$id] = $class::get_type();
		}
		return array(
			'items' => $list,
			'field_name' => self::FIELD_NAME,
			'v' => TBP_VER,
			'd_label' => __('Dynamic', 'tbp'),
			'emptyVal' => __('Empty Value', 'tbp'),
			'placeholder_image' => TBP_URL . 'editor/img/transparent.webp'
		);
	}

	/**
	 * Generate preview value
	 *
	 * Hooked to "wp_ajax_tpb_get_dynamic_content_preview"
	 */
	public static function preview() {
		check_ajax_referer('tf_nonce', 'nonce');
		$options = !empty($_POST['values']) ? json_decode(stripslashes_deep($_POST['values']), true) : array();
		if (isset($options['item'])) {
			// before rendering the dynamic value, first set up the WP Loop
			Themify_Builder::$frontedit_active = true;
			if(isset(Themify_Builder::$is_loop)){
				Themify_Builder::$is_loop=true;
			}
			else{//backward
				global $ThemifyBuilder;
				$ThemifyBuilder->in_the_loop = true;
			}
			Tbp_Public::setup_post_data();
			/* setup the environment for ACF Repeater field preview */
			if (isset($options['key']) && substr($options['key'], 0, 9) === 'repeater:' && substr($options['item'], 0, 3) === 'ACF') {
				$pieces = explode(':', $options['key']);
				if (isset($pieces[1]) && have_rows($pieces[1])) {
					the_row();
				}
			}
			$value = array('value' => self::get_value($options));
		} 
		else {
			$value = array('error' => __('Invalid value.', 'tbp'));
		}
		die(json_encode($value));
	}

	public static function options() {
		check_ajax_referer('tf_nonce', 'nonce');

		$items_list = $items_settings = array();
		$categories = array(
			'disabled' => '',
			'general' => 'g',
			'post' => 'tbp_post',
			'wc' => 'tbp_wc',
			'advanced' => 'adv',
			'ptb' => 'tbp_ptb',
			'acf' => 'tbp_acf'
		);
		$items_list['empty'] = array('options' => array('' => ''));

		foreach (self::get() as $id => $class) {
			$cat_id = $class::get_category();
			if (!isset($items_list[$cat_id])) {
				$items_list[$cat_id] = array(
					'label' => $categories[$cat_id],
					'options' => array()
				);
			}
			$items_list[$cat_id]['options'][$id] = $class::get_label();

			if ($options = $class::get_options()) {
				$items_settings[$id] = array(
					'type' => 'group',
					'options' => $options,
					'wrap_class' => 'field_' . $id,
				);
			}
		}
		$data = array();
		foreach ($categories as $k => $v) {
			if (isset($items_list[$k])) {
				$data[$k] = $items_list[$k];
			}
		}
		$categories = $items_list = null;
		if(isset($data['acf'])){
			/* fields specific to Advanced Custom Fields plugin */
			$items_settings['general_acf'] = array(
				'type' => 'group',
				'options' => array(
					array(
						'label' => 'tbp_cntxt',
						'id' => 'acf_ctx',
						'type' => 'select',
						'options' => array(
							'' => 'tbp_cpst',
							'term' => 'tbp_trm',
							'user' => 'tbp_cuser',
							'author' => 'tbp_athorcpst',
							'option' => 'tbp_opt',
							'custom' => 'cus'
						),
						'binding' => array(
							'empty' => array('hide' => 'acf_ctx_c'),
							'not_empty' => array('hide' => 'acf_ctx_c'),
							'custom' => array('show' => 'acf_ctx_c')
						)
					),
					array(
						'label' => 'tbp_cuscntxt',
						'id' => 'acf_ctx_c',
						'type' => 'text',
						'help' => 'tbp_cuscntxth'
					),
				),
				'wrap_class' => 'tbp_dynamic_content_acf_ctx',
			);
		}
		$items_settings['general_text'] = array(
			'type' => 'group',
			'options' => array(
				array(
					'label' => 'tbefore',
					'id' => 'text_before',
					'type' => 'text'
				),
				array(
					'label' => 'tafter',
					'id' => 'text_after',
					'type' => 'text'
				),
			),
			'wrap_class' => 'field_general_text field_general_textarea field_general_wp_editor'
		);
		$items_settings['general_url'] = array(
			'type' => 'group',
			'options' => array(
				array(
					'label' => 'tbp_usch',
					'id' => 'uri_scheme',
					'type' => 'select',
					'options' => array(
						'0' => 'none',
						'tel' => 'tbp_tel',
						'mailto' => 'em',
						'sms' => 'tbp_sms',
						'fax' => 'tbp_fax'
					)
				),
			),
			'wrap_class' => 'field_general_url',
		);
		$items_settings['general_condition'] = array(
			'type' => 'group',
			'options' => array(
				array(
					'type' => 'separator',
					'label' => 'tbp_dispcond'
				),
				array(
					'label' => 'tbp_whnemp',
					'id' => 'condition',
					'type' => 'select',
					'options' => array(
						'' => '',
						'hide_module' => 'tbp_hmod',
						'hide_subrow' => 'tbp_hsubr',
						'hide_row' => 'tbp_hrow',
						'show_text' => 'tbp_sfalval'
					)
				),
				array(
					'label' => 'tbp_falval',
					'id' => '__fb_val',
					'type' => 'text',
					'help' => 'tbp_falvalh'
				),
			),
			'wrap_class' => 'tbp_dynamic_content_condition field_general_text field_general_textarea field_general_wp_editor field_general_url field_general_image field_general_video field_general_audio field_general_map field_general_gallery',
		);
		$options = array(
			array(
				'id' => 'item',
				'type' => 'select',
				'options' => $data,
				'control' => false,
				'optgroup' => true
			),
			array(
				'type' => 'group',
				'options' => $items_settings,
				'wrap_class' => 'field_settings'
			),
		);
		die(json_encode($options));
	}
}

abstract class Tbp_Dynamic_Item {

	/**
	 * Returns true if this item is available.
	 */
	public static function is_available():bool {
		return true;
	}

	/**
	 * Returns an array of Builder field types this item applies to.
	 */
	public static function get_type():array {
		return array();
	}

	/**
	 * Returns the category this item belongs to
	 */
	public abstract static function get_category():string;

	public abstract static function get_label():string;

	public static function get_value(array $args = array()):?string {
		return null;
	}

	public static function get_options():array {
		return array();
	}

}
