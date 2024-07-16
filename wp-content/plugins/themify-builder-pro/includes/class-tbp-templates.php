<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
class Tbp_Templates {

	const SLUG = 'tbp_template';

	public static function admin_init() {
		// Templates
		add_action('admin_notices', array(__CLASS__, 'add_menu_tabs'), 9);
		add_filter('manage_edit-' . self::SLUG . '_columns', array(__CLASS__, 'edit_columns'));
		add_action('manage_' . self::SLUG . '_posts_custom_column', array(__CLASS__, 'manage_custom_column'), 10, 2);
		add_filter( 'manage_edit-'.self::SLUG.'_sortable_columns', array(__CLASS__, 'register_sortable_columns'),10,1);
		add_action('wp_ajax_' . self::SLUG . '_saving', array(__CLASS__, 'save_form'));
		add_action('wp_ajax_tbp_get_titles', array(__CLASS__, 'get_selected_titles'));
		add_action('wp_ajax_' . self::SLUG . '_get_item', array(__CLASS__, 'get_item_data'));
		add_filter('bulk_actions-edit-tbp_template', array(__CLASS__, 'row_bulk_actions'));
		add_action('pre_get_posts', array(__CLASS__, 'filter_template_query'));
		add_filter('post_row_actions', array(__CLASS__, 'post_row_actions'), 11);
		add_filter('themify_builder_post_types_support', array(__CLASS__, 'register_builder_post_type_metabox'));
		add_filter('themify_post_types', array(__CLASS__, 'extend_post_types'));
		add_action('wp_ajax_tbp_load_data', array(__CLASS__, 'load_data'));
		add_filter('views_edit-tbp_template', array(__CLASS__, 'views_edit_template'));
		add_filter('themify_builder_post_types_support', array(__CLASS__, 'exclude_post_type'));
		add_filter('tbp_localize_vars', array(__CLASS__, 'add_vars'), 10, 2);
		global $pagenow;
		if (is_admin() && (($pagenow === 'post.php' && !empty($_GET['post']) && get_post_type($_GET['post']) === self::SLUG) || (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] === self::SLUG))) {
			if ($pagenow === 'post.php') {
				add_action('admin_bar_menu', array(__CLASS__, 'admin_bar'), 999);
			}
			add_filter('themify_exclude_cpt_post_options', array(__CLASS__, 'exclude_post_type'));
		}
	}

	public static function register_cpt() {

		add_action('rest_api_init', array(__CLASS__, 'register_rest_fields'));
		register_post_type(self::SLUG,
			apply_filters('tbp_register_post_type_' . self::SLUG, array(
			'labels' => array(
				'name' => __('Templates', 'tbp'),
				'singular_name' => __('Template', 'tbp'),
				'menu_name' => _x('Templates', 'admin menu', 'tbp'),
				'name_admin_bar' => _x('Template', 'add new on admin bar', 'tbp'),
				'add_new' => _x('Add New', 'template', 'tbp'),
				'add_new_item' => __('Add New Template', 'tbp'),
				'new_item' => __('New Template', 'tbp'),
				'edit_item' => __('Edit Template', 'tbp'),
				'view_item' => __('View Template', 'tbp'),
				'all_items' => __('All Templates', 'tbp'),
				'search_items' => __('Search Templates', 'tbp'),
				'parent_item_colon' => __('Parent Templates:', 'tbp'),
				'not_found' => __('No templates found.', 'tbp'),
				'not_found_in_trash' => __('No templates found in Trash.', 'tbp'),
                'attributes' => __('Template Priority', 'tbp'),
			),
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => current_user_can('manage_options'),
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_admin_bar' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'tbp-template'),
			'capability_type' => array(self::SLUG, self::SLUG . 's'),
			'map_meta_cap' => true,
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'revisions', 'thumbnail', 'page-attributes'),
			'can_export' => true,
			'show_in_rest' => true
			))
		);
	}

	private static function get_templates_types(): array {
		$args = array(
			'header' => __('Header', 'tbp'),
			'footer' => __('Footer', 'tbp'),
			'single' => __('Single', 'tbp'),
			'archive' => __('Archive', 'tbp'),
			'page' => __('Page', 'tbp')
		);
		if (themify_is_woocommerce_active()) {
			$args['product_single'] = __('Product Single', 'tbp');
			$args['product_archive'] = __('Product Archive', 'tbp');
		}
		return $args;
	}

	private static function get_labels(): array {
		return array(
			'general' => __('Entire Site', 'tbp'),
			'archives' => __('Archives', 'tbp'),
			'singlular' => __('Singular', 'tbp'),
			'page' => __('Pages', 'tbp'),
			'is_front' => __('Homepage', 'tbp'),
			'is_404' => __('404 Page', 'tbp'),
			'child_of' => __('Child of', 'tbp'),
			'is_date' => __('Date Archive', 'tbp'),
			'is_author' => __('Author Archive', 'tbp'),
			'is_search' => __('Search Results', 'tbp'),
			'is_attachment' => __('Media', 'tbp'),
			'shop' => __('Shop Page', 'tbp'),
			'post' => __('Posts', 'tbp'),
			'product' => __('Products', 'tbp'),
			'all_post' => __('Post Archives', 'tbp'),
			'all_product' => __('Product Archives', 'tbp'),
			'single' => array(
				'all' => __('Singular', 'tbp'),
				'product_cat' => __('In Product Category', 'tbp'),
				'product_tag' => __('In Product Tag', 'tbp'),
				'category' => __('In Category', 'tbp'),
				'post_tag' => __('In Tag', 'tbp')
			),
			'archive' => array(
				'all' => __('Archives', 'tbp'),
				'category' => __('Categories', 'tbp'),
				'post_tag' => __('Tags', 'tbp'),
				'product_cat' => __('Product categories', 'tbp'),
				'product_tag' => __('Product tags', 'tbp')
			)
		);
	}

	public static function add_menu_tabs() {
		global $pagenow;

		if (isset($_GET['post_type']) && $_GET['post_type'] === self::SLUG && $pagenow === 'edit.php') {
			$tabs = array('active' => __('Active', 'tbp')) + self::get_templates_types() + array('all' => __('All Templates', 'tbp'));
			$current_tab = ( empty($_GET['tab']) ) ? 'active' : sanitize_text_field(urldecode($_GET['tab']));
			?>

			<div class="notice tbp_notice_template">
				<h2 class="nav-tab-wrapper tbp_nav_tab_wrapper">
					<?php foreach ($tabs as $name => $label): ?>
						<a href="<?php echo admin_url('edit.php?post_type=' . self::SLUG . '&tab=' . $name) ?>" class="nav-tab<?php if ($current_tab === $name): ?> nav-tab-active<?php endif; ?>"><?php echo $label ?></a>
					<?php endforeach; ?>
				</h2>
			</div>
			<?php
		}
	}

	/**
	 * Post Type Custom column.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $columns 
	 */
	public static function edit_columns($columns): array {
		return array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title', 'tbp'),
			'type' => __('Type', 'tbp'),
			'status' => __('Status', 'tbp'),
			'theme' => __('Theme', 'tbp'),
			'condition' => __('Conditions', 'tbp'),
			'author' => __('Author', 'tbp'),
			'date' => __('Date', 'tbp'),
            'order' => __('Priority', 'tbp'),
		);
	}
	
	public static function register_sortable_columns(array $columns):array{
		$columns['order'] = 'menu_order';
		return $columns;
	}

	/**
	 * Manage Post Type Custom Columns.
	 */
	public static function manage_custom_column(string $column,?int $post_id) {
		$labels = self::get_labels();
		global $post;
		switch ($column) {
			case 'type':
				$type = self::get_template_type($post_id);
                $labels = self::get_templates_types();
                echo isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
				break;

			case 'status':
				echo get_post_status_object( $post->post_status )->label;
				break;

			case 'order':
				echo $post->menu_order;
				break;

			case 'theme':
				echo get_the_title($post->post_parent);
				break;

			case 'condition':
				$type = self::get_template_type($post_id);
				$records = self::get_template_conditions($post_id);
				foreach ($records as $rec) {
					$output = [];
					$cat=null;
					foreach ($rec as $k => $v) {
						$val = $v;
						if ($k !== 'type' && $k !== 'detail') {
							if (isset($labels[$v])) {
								$val = !is_array($labels[$v])?$labels[$v]:$labels[$v]['all'];
							} else {
								$key = $type === 'header' || $type === 'footer'?'_' . $rec['general']:'';
								if (isset($labels[$key][$v])) {
									$val = $labels[$key][$v];
								} else{
									if(strpos($v, 'all_') === 0){
										$obj=get_post_type_object(substr($v,4));
									}
									if(empty($obj)){
										$obj= get_post_type_object($v);
										if(empty($obj)){
											$obj=get_taxonomy($v);
											$cat=empty($obj)?null:$v;
										}
									}
									if(!empty($obj)){
										$val=$obj->labels->singular_name;
										unset($obj);
									}
								}
							}
						} elseif ($k === 'detail' && (!isset($rec['general']) || $rec['general'] === 'general' || $rec['general'] === 'all' || $rec['general'] === 'is_404' || $rec['general'] === 'is_front' || $rec['general'] === 'is_search' || $rec['general'] === 'is_date' || (isset($rec['query']) && ($rec['query'] === 'all' || strpos($rec['query'], 'all_') === 0)))) {
							continue;
						}
						
						if (is_array($val)) {
							$items = [];
							if($k === 'detail'){
								if($cat!==null){
									$terms = get_terms(array(
											'taxonomy' => $cat,
											'hide_empty' => false,
											'cache_results'=>false,
											'update_term_meta_cache'=>false,
											'slug' => $val
										)
									);
									foreach($terms as $t){
										$items[$t->slug]=sprintf('<a href="%s" target="_blank">%s</a>', esc_url(get_term_link($t,$cat)), esc_html( $t->name ));
									}
								}
								else{
									$posts = get_posts(array(
											'post_name__in' => $val,
											'post_type' => 'any',
											'post_status' => 'any',
											'no_found_rows' => true,
											'posts_per_page' => count($val),
											'orderby'=>'none',
										)
									);
									foreach($posts as $p){
										$items[$p->post_name]=sprintf('<a href="%s" target="_blank">%s</a>', esc_url(get_permalink($p)), esc_html(get_the_title($p)));
									}
								}
							}
							$foundItems=[];
							foreach ($val as $v) {
								$foundItems[]=$items[$v]??$v;
							}
							$val = implode(', ', $foundItems);
						}
						$output[] = $val;
					}
					echo '<p>', implode(' > ', $output), '</p>';
				}
				break;
		}
	}

	/**
	 * Post Row Actions
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $actions 
	 * @return array
	 */
	public static function post_row_actions(array $actions):array {
		global $post;
		if (self::SLUG === $post->post_type) {
            if ( themify_is_woocommerce_active() || ! in_array( self::get_template_type( $post->ID ), [ 'product_single', 'product_archive' ], true ) ) {
                $actions['edit'] = sprintf('<a href="#" class="tbp_lightbox_edit" data-post-id="%d">%s</a>',
                    $post->ID,
                    __('Options', 'tbp')
                );
                $actions['backend'] = sprintf('<a href="%s">%s</a>', admin_url('post.php?post=' . $post->ID . '&action=edit'), __('Backend', 'tbp'));

                if (isset($actions['themify-builder'])) {
                    unset($actions['themify-builder']);
                    $builder_link = sprintf('<a href="%s" target="_blank">%s</a>', esc_url(get_permalink($post->ID) . '#builder_active'), __('Frontend', 'tbp'));
                    $actions['themify-builder'] = $builder_link;
                }
                $actions['tbp_export'] = sprintf('<a href="#" data-post-id="%s">%s</a>', $post->ID, __('Export', 'tbp'));
            } else {
                unset( $actions['edit'], $actions['themify-builder'] );
                $actions['tbp_wc_notice'] = __( 'WooCommerce plugin is required.', 'tbp' );
                add_action( 'admin_footer', [ __CLASS__, 'admin_footer' ] );
            }

			if (isset($actions['trash'])) {
				$actions['tbp-trash'] = $actions['trash'];
			}
			unset($actions['trash'], $actions['view']);
		}
		return $actions;
	}

    public static function admin_footer() {
        echo '
        <style>
            .wp-list-table tr:has(.tbp_wc_notice) { opacity: .5 }
            .wp-list-table tr:has(.tbp_wc_notice) .row-title { pointer-events: none }
        </style>
        ';
    }

	/**
	 * Template form fields.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	private static function get_options():array {
		$post_types = Themify_Builder_Model::get_public_post_types(false);
		$single_tax_arr = $archive_tax_arr = array();
		foreach ($post_types as $post_type => $label) {
			if ($post_type !== 'post' && $post_type !== 'page') {
				$post_type_object = get_post_type_object($post_type);

				$single_tax_arr[$post_type] = array(
					'label' => $post_type_object->labels->singular_name,
					'id' => $post_type,
					'options' => array(
						$post_type => $post_type_object->labels->singular_name
					)
				);
				if ($post_type_object->has_archive) {
					$archive_tax_arr[$post_type] = array(
						'label' => $post_type_object->labels->name,
						'id' => $post_type,
						'options' => array(
							'all_' . $post_type => sprintf('All %s Archives', $label)
						)
					);
				}

				$post_type_taxonomies = wp_filter_object_list(get_object_taxonomies($post_type, 'objects'), array(
					'public' => true,
					'show_in_nav_menus' => true
				));

				if (empty($post_type_taxonomies) || is_wp_error($post_type_taxonomies)) {
					continue;
				}

				foreach ($post_type_taxonomies as $slug => $object) {
					$single_tax_arr[$post_type]['options'][$slug] = sprintf('In %s', $object->labels->singular_name);
					if ($post_type_object->has_archive) {
						$archive_tax_arr[$post_type]['options'][$slug] = $object->label;
					}
				}
			}
		}
		return array('single_tax' => $single_tax_arr, 'archive_tax' => $archive_tax_arr);
	}

	/**
	 * Saving form data.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $post_data 
	 */
	public static function save_form() {
		check_ajax_referer('tf_nonce', 'nonce');
		if (!empty($_POST['data'])) {
			if (Tbp_Utils::has_access()) {
				$post_data = json_decode(stripslashes_deep($_POST['data']), true);
				if (!empty($post_data)) {
					$id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
					$newId = self::save($post_data, $id);
					if (is_wp_error($newId)) {
						wp_send_json_error($newId->get_error_message());
					}
					if (!$id) {
						/** Polylang: for new templates always set the language to default site language */
						if (Tbp_Utils::is_polylang()) {
							pll_set_post_language($newId, Tbp_Utils::get_default_language_code());
						}
						$callback_uri = get_permalink($newId) . '#builder_active';
						if (!empty($_POST['is_draft'])) {
							$callback_uri = add_query_arg('preview', true, $callback_uri);
						}
						$response['redirect'] = $callback_uri;
					}
					$response['id'] = $newId;
					wp_send_json_success($response);
				}
			} else {
				wp_send_json_error(__('You don`t have permssion to add/edit a template', 'tbp'));
			}
		}
		wp_send_json_error();
	}

	public static function save(array $post_data, $id = null) {
		if (empty($post_data['post_parent'])) {
			return new WP_Error('error', __('Theme isn`t selected', 'tbp'));
		}
		$themeId = Tbp_Themes::get_theme_id($post_data['post_parent']);
		if ($themeId === false) {
			return new WP_Error('error', __('Theme doesn`t exist', 'tbp'));
		}
		$conditions = !empty($post_data['post_excerpt']) ? $post_data['post_excerpt'] : array();
		$args = array(
			'post_title' => !empty($post_data['post_title']) ? sanitize_text_field($post_data['post_title']) : __('New Template', 'tbp'),
			'menu_order' => !empty($post_data['menu_order']) ? $post_data['menu_order'] : 0,
			'post_type' => self::SLUG,
			'post_mime_type' => '/' . sanitize_mime_type(trim($post_data['post_mime_type'], '/')),
			'post_excerpt' => is_array($conditions) ? json_encode($conditions) : '',
			'post_parent' => $themeId
		);
		if ($id) {
			if (current_user_can('edit_post', $id)) {
				$args['ID'] = $id;
				unset($args['post_type']);
				$id = wp_update_post($args, true);
			} else {
				$id = new WP_Error('error', __('You don`t have permission to edit this post', 'tbp'));
			}
		} elseif (current_user_can('publish_posts')) {
			$args['post_status'] = !empty($_POST['is_draft']) ? 'draft' : 'publish';
			$args['post_content'] = '';
			$args['post_name'] = sanitize_title($args['post_title']);
			$themeName = get_post($themeId)->post_name;
			if (strpos($args['post_name'], $themeName) !== 0 || self::is_template($args['post_name'])) {
				$args['post_name'] = sanitize_title($themeName . ' ' . $args['post_title']);
			}
			$id = wp_insert_post($args, true);
		} else {
			$id = new WP_Error('error', __('You don`t have permission to publish a post', 'tbp'));
		}
		if (is_wp_error($id) || $id === 0) {
			return $id;
		}
		if (!empty($post_data['builder'])) {
			$res = ThemifyBuilder_Data_Manager::update_builder_meta($id, $post_data['builder']);
			if (empty($res['mid'])) {
				$id = new WP_Error('error', __('Failed to update builder', 'tbp'));
			}
		}
		return $id;
	}

	public static function filter_template_query($query) {
		if (is_admin() && self::SLUG === $query->get('post_type') && $query->is_main_query()) {

			Tbp_Themes::get_theme_templates('any', -1); //update db structure
			$tab = !empty($_GET['tab']) ? $_GET['tab'] : 'active';
			$status = !empty($_GET['post_status']) ? $_GET['post_status'] : 'active';
			if ($tab !== 'all' && ('active' === $tab || 'active' === $status )) {
				$post_parent = Tbp_Themes::get_active_theme()['ID'];
				if ($post_parent === null) {
					$post_parent = 0;
				}
				$query->set('post_parent', $post_parent);
			}

			if ('active' !== $tab && 'all' !== $tab) {
				$query->set('post_mime_type', '/' . $tab);
			}

			if ('active' === $status) {
				$query->set('post_status', 'publish');
			} elseif ('all' === $status) {
				$query->set('post_status', 'any');
			}
		}
		return $query;
	}

	public static function register_builder_post_type_metabox(array $post_types): array {
		$post_types[ self::SLUG ] = self::SLUG;

		return $post_types;
	}

	/**
	 * Includes this custom post to array of cpts managed by Themify
	 */
	public static function extend_post_types(array $types): array {
		return array_merge($types, array(self::SLUG));
	}

	public static function register_rest_fields() {
		add_filter('rest_'.self::SLUG.'_query', array(__CLASS__, 'rest_post_type_query'), 10, 2);
		register_rest_field(self::SLUG, 'template_type', array(
			'get_callback' => array(__CLASS__, 'get_template_type_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'post_excerpt', array(
			'get_callback' => array(__CLASS__, 'get_conditions_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_image_thumbnail', array(
			'get_callback' => array(__CLASS__, 'get_template_thumbnail_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_image_full', array(
			'get_callback' => array(__CLASS__, 'get_template_img_full_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_custom_css', array(
			'get_callback' => array(__CLASS__, 'get_css_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'template_builder_content', array(
			'get_callback' => array(__CLASS__, 'get_builder_content_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_template_options', array(
			'get_callback' => array(__CLASS__, 'get_template_options_cb'),
			'schema' => null,
			)
		);

		register_rest_field(self::SLUG, 'tbp_template_gs', array(
			'get_callback' => array(__CLASS__, 'get_template_gs'),
			'schema' => null,
			)
		);
	}

	public static function get_template_type_cb($data):string {
		$id = is_numeric($data) ? $data : $data['id'];
		return self::get_template_type($id);
	}

	public static function get_conditions_cb($data):array {
		$id = is_numeric($data) ? $data : $data['id'];
		return self::get_template_conditions($id);
	}

	public static function get_template_thumbnail_cb(array $data=array(),?string $size = 'thumbnail') {
		if (!empty($data['featured_media'])) {
			return wp_get_attachment_image_src($data['featured_media'], $size)[0];
		}
		return false;
	}

	public static function get_template_img_full_cb($data) {
		return self::get_template_thumbnail_cb($data, 'large');
	}

	public static function get_builder_content_cb($data):?string {
		return json_encode(ThemifyBuilder_Data_Manager::get_data($data['id']));
	}

	public static function rest_post_type_query(array $args, WP_REST_Request $request): array {
		if (!empty($request['template_type'])) {
			$args['post_mime_type'] = '/' . sanitize_mime_type(trim($request['template_type'], '/'));
		}

		if (!empty($request['associated_theme'])) {
			$args['post_parent'] = is_numeric($request['associated_theme']) ? ((int) $request['associated_theme']) : Tbp_Themes::get_theme_id($request['associated_theme']);
		}
		$args['orderby']='none';
		$args['nopaging']=$args['ignore_sticky_posts']=$args['no_found_rows']=true;
		$args['posts_per_page']=$args['posts_per_archive_page']=-1;
		$args['cache_results']=$args['update_post_meta_cache']=$args['update_post_term_cache']=$args['update_menu_item_cache']=false;
		return $args;
	}

	public static function get_template_options_cb(array $data): array {
		$post = get_post($data['id']);
		return array(//backward for v5
			'tbp_template_type' => self::get_template_type($data['id']),
			'tbp_template_conditions' => self::get_template_conditions($data['id']),
			'tbp_associated_theme' => get_post($post->post_parent)->post_name,
			'tbp_template_name' => get_the_title($data['id'])
		);
	}

	public static function get_template_gs(array $data):array {
		$used_gs = Themify_Global_Styles::used_global_styles($data['id']);
		foreach ($used_gs as $key => $post) {
			if ('row' === $post['type']) {
				$used_gs[$key]['data'] = $post['data'][0]['styling'];
			} elseif ('column' === $post['type']) {
				$used_gs[$key]['data'] = $post['data'][0]['cols'][0]['styling'];
			} else {
				$used_gs[$key]['data'] = $post['data'][0]['cols'][0]['modules'][0]['mod_settings'];
			}
		}
		return $used_gs;
	}

	public static function get_css_cb(array $data) {
		return get_post_meta($data['id'], 'tbp_custom_css', true);
	}

	/**
	 * Load_data action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public static function load_data() {
		// Check ajax referer
		check_ajax_referer('tf_nonce', 'nonce');
		$type = $_POST['type']?? 'post';
		$page = isset($_POST['p']) ? (int) $_POST['p'] : 1;
		$limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 10;
		$s = $_POST['s']??'';
		if ($page <= 0) {
			$page = 1;
		}
		$count = 0;
		$result = array();
		switch ($type) {
			case 'is_author':
				$query_params = array(
					'who' => 'authors',
					'has_published_posts' => true,
					'paged' => $page,
					'number' => $limit,
					's' => $s,
					'fields' => array(
						'ID',
						'display_name'
					)
				);
				$query = new WP_User_Query($query_params);
				if (!is_wp_error($query)) {
					$count = $query->total_users;
					$query = $query->get_results();
					foreach ($query as $author) {
						$result[$author->ID] = $author->display_name;
					}
				}
				break;
			default:
				$args = array(
					'post_type' => $type,
					'posts_per_page' => $limit,
					's' => $s,
					'cache_results' => false,
					'ignore_sticky_posts' => true,
					'paged' => $page,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				);
				if (Tbp_Utils::is_polylang()) {
					/* Polylang: only show posts from default language */
					$args['lang'] = Tbp_Utils::get_default_language_code();
				}
				$is_taxonomy = FALSE;
				if ($type === 'child_of' || $type === 'page') {
					$args['post_type'] = 'page';
					if ($type === 'child_of') {
						$args['post_parent'] = 0;
						$args['hierarchical'] = false;
					}
					if ('page' === $type) {
						$args['order'] = 'ASC';
						$args['orderby'] = 'title';
					}
				} elseif ($type === 'is_attachment') {
					$args['post_type'] = 'attachment';
					$args['post_status'] = 'inherit';
				} elseif ($type === 'category' || $type === 'post_tag' || taxonomy_exists($type)) {
					$args = array(
						'taxonomy' => $type,
						'update_term_meta_cache' => false,
						'hide_empty' => true,
						'pad_counts' => true,
						'number' => $limit,
						'search' => $s,
					);
					if (Tbp_Utils::is_polylang()) {
						/* Polylang: only show terms from default language */
						$args['lang'] = Tbp_Utils::get_default_language_code();
					}
					$count = wp_count_terms($args);
					if ($page > 1) {
						$args['offset'] = ($page - 1) * $limit;
					}
					$query = get_terms($args);
					$is_taxonomy = true;
				}
				$result = array();
				if ($is_taxonomy === false) {
					$query = new WP_Query($args);
				}
				if (!empty($query) && !is_wp_error($query)) {
					if ($is_taxonomy === false) {
						$count = $query->found_posts;
						$query = $query->posts;
					}
					foreach ($query as $post) {
						$key = $type === 'is_attachment' ? $post->ID : ($is_taxonomy === true ? $post->slug : $post->post_name);
						$result[$key] = $is_taxonomy === true ? $post->name . ' <span class="post_count">(' . $post->count . ')</span>' : $post->post_title;
					}
				}
				break;
		}
		wp_send_json_success(array(
			'data' => $result,
			'limit' => $limit,
			'count' => $count
		));
	}

	public static function get_item_data() {
		check_ajax_referer('tf_nonce', 'nonce');
		if (!empty($_POST['id'])) {
			$id = (int) $_POST['id'];
			$data = array();
			$post = get_post($id);
			if (!empty($post) && $post->post_type === self::SLUG) {
				$data['post_mime_type'] = self::get_template_type($id);
				$data['post_title'] = html_entity_decode($post->post_title);
				$data['post_parent'] = $post->post_parent;
				$data['post_excerpt'] = json_decode($post->post_excerpt, true);
				$data['menu_order'] = $post->menu_order;
			}
			echo json_encode($data);
		}
		die;
	}

	public static function add_vars(array $vars,?string $type): array {
		if ($type === self::SLUG) {
			$vars = array_merge($vars, array(
				'options' => self::get_options(),
				'edit_template' => __('Edit Template', 'tbp'),
				'add_template' => __('New Template', 'tbp'),
				'add_conition' => __('Add Condition', 'tbp'),
				'select' => __('Select', 'tbp'),
				'include' => __('Include', 'tbp'),
				'exclude' => __('Exclude', 'tbp'),
				'all' => __('All', 'tbp'),
				'export_templates' => __('Exporting Templates(%from%/%to%): %post%', 'tbp'),
				'template_types' => self::get_templates_types(),
				'opt_labels' => self::get_labels(),
				'show_on_front' => get_option('show_on_front')
			));

			$vars['opt_labels']['type'] = __('Type', 'tbp');
			$vars['opt_labels']['name'] = __('Name', 'tbp');
			$vars['opt_labels']['condition'] = __('Display Conditions', 'tbp');
			$vars['opt_labels']['home_latest'] = __('Homepage Latest posts', 'tbp');
			$vars['opt_labels']['p'] = __('Post', 'tbp');
            $vars['opt_labels']['order'] = __('Priority', 'tbp');
			$vars['opt_labels']['orderh'] = __('If multiple templates satisfy the same display conditions, assign a priority number to indicate precedence. A higher number indicates higher priority.', 'tbp');

			$vars['opt_labels']['theme_name'] = __('Associated Theme', 'tbp');
			$vars['opt_labels']['theme_help'] = __('Select the theme which you want this template to associated. Templates are used base on the activated theme.', 'tbp');

			$themes = Tbp_Themes::get_themes();
			$themesSelect = array();

			if (!empty($themes)) {
				foreach ($themes as $t) {
					$themesSelect[$t->ID] = $t->post_title;
				}
				$vars['themes'] = $themesSelect;
				$vars['active_theme'] = Tbp_Themes::get_active_theme()['ID'];
			}
			if (themify_is_woocommerce_active()) {
				$taxonomies = get_object_taxonomies('product', 'object');
				if (!empty($taxonomies)) {
					$result = array();
					foreach ($taxonomies as $tax) {
						if ($tax->public && $tax->name !== 'product_shipping_class') {
							$result[] = array(
								'slug' => $tax->name,
								'label' => $tax->label
							);
						}
					}
					$vars['product_tax'] = $result;
				}
			}
		}
		return $vars;
	}

	public static function views_edit_template($views) {
		global $current_screen;
		if (isset($current_screen) && $current_screen->id === 'edit-tbp_template') {
			$views['all'] = self::get_edit_link(array(
					'post_status' => 'all'
					), esc_html__('All', 'tbp'), self::check_current_link('all'));

			$views['publish'] = self::get_edit_link(array(
					'post_status' => 'publish'
					), esc_html__('Published', 'tbp'), self::check_current_link('publish'));

			if (isset($views['draft'])) {
				$views['draft'] = self::get_edit_link(array(
						'post_status' => 'draft'
						), esc_html__('Draft', 'tbp'), self::check_current_link('draft'));
			}

			if (isset($views['trash'])) {
				$views['trash'] = self::get_edit_link(array(
						'post_status' => 'trash'
						), esc_html__('Trash', 'tbp'), self::check_current_link('trash'));
			}

			$views = array_merge(array(
				'active' => self::get_edit_link(array('post_status' => 'active'), esc_html__('Active', 'tbp'), self::check_current_link('active'))
				), $views);
		}
		return $views;
	}

	protected static function get_edit_link($args, $label, $class = ''):string {
		$args['post_type'] = self::SLUG;
		$args['tab'] = $_GET['tab'] ?? 'active';
		$url = add_query_arg($args, 'edit.php');

		$class_html = $aria_current = '';
		if (!empty($class)) {
			$class_html = sprintf(
				' class="%s"',
				esc_attr($class)
			);

			if ('current' === $class) {
				$aria_current = ' aria-current="page"';
			}
		}

		return sprintf(
			'<a href="%s"%s%s>%s</a>',
			esc_url($url),
			$class_html,
			$aria_current,
			$label
		);
	}

	private static function check_current_link($check):string {
		$status = isset($_GET['post_status']) ? $_GET['post_status'] : 'active';
		return $status === $check ? 'current' : '';
	}

	public static function exclude_post_type(array $post_types):array {
		$post_types[self::SLUG] = self::SLUG;
		return $post_types;
	}

	/**
	 * Add custom link actions in templates rows bulk action
	 *
	 * @access public
	 * @param array $actions
	 * @return array
	 */
	public static function row_bulk_actions(array $actions):array {
		$actions['bulk-export'] = __('Export', 'tbp');
		return $actions;
	}

	public static function admin_bar($wp_admin_bar) {
		$wp_admin_bar->add_node(array(
			'id' => 'tbp_view_template',
			'title' => __('View Template', 'tbp'),
			'href' => get_post_permalink($_GET['post']),
		));
	}

	public static function get_selected_titles() {
		if (!empty($_POST['data']) && !empty($_POST['type'])) {
			check_ajax_referer('tf_nonce', 'nonce');
			$res = array('exist' => array());
			$slugs = json_decode(stripslashes_deep($_POST['data']), true);
			$type = $_POST['type'];
			if ($type === 'is_author') {
				$query_params = array(
					'who' => 'authors',
					'include' => $slugs,
					'orderby' => 'ID',
					'count_total' => false,
					'fields' => array(
						'ID',
						'display_name'
					)
				);
				$query = new WP_User_Query($query_params);
				if (!is_wp_error($query)) {
					$query = $query->get_results();
					foreach ($query as $author) {
						$res['exist'][$author->ID] = $author->display_name;
					}
				}
			} else {
				if ($type === 'post_tag' || $type === 'category' || $type === 'product_tag' || $type === 'product_cat') {
					$data = get_terms(array(
						'slug' => $slugs,
						'taxonomy' => $type,
						'hide_empty' => false,
						'nopaging' => true,
						'count' => false,
						'get' => 'all',
						'update_term_meta_cache' => false,
						'orderby' => 'none'
					));
				} else {
					$get_posts = new WP_Query;
					$data = $get_posts->query(array(
						'post_type' => $type,
						'post_name__in' => $slugs,
						'post_status' => 'any',
						'no_found_rows' => false,
						'cache_results' => false,
						'ignore_sticky_posts' => true,
						'nopaging' => true,
						'orderby' => 'none',
						'update_post_term_cache' => false,
						'update_post_meta_cache' => false
					));
				}
				foreach ($data as $post) {
					$s = isset($post->taxonomy) ? $post->slug : $post->post_name;
					$res['exist'][$s] = isset($post->taxonomy) ? $post->name : get_the_title($post);
				}
			}
			unset($data);
			foreach ($slugs as $s) {
				if (!isset($res['exist'][$s])) {
					$res['deleted'][] = $s;
				}
			}
			wp_send_json_success($res);
		}
	}

	public static function is_template(string $slug):bool {
		global $wpdb;
		return $wpdb->get_var($wpdb->prepare("SELECT 1 FROM $wpdb->posts WHERE post_type='" . self::SLUG . "' AND post_name='%s' LIMIT 1", array(esc_sql($slug))))> 0;
	}

	public static function get_template_type($id):string {
		$type = get_post_mime_type($id);
		if (!$type && get_post_type($id) === self::SLUG) {//update DB strcuture
			$type = get_post_meta($id, 'tbp_template_type', true);
			if ($type) {
				$theme_slug = get_post_meta($id, 'tbp_associated_theme', true);
				$conditions = get_post_meta($id, 'tbp_template_conditions', true);
				$post_parent = 0;
				if (!empty($theme_slug)) {
					$post_parent = Tbp_Themes::get_theme_id($theme_slug);
					if ($post_parent === false) {
						$post_parent = 0;
					}
				}
				if (empty($conditions)) {
					$conditions = array();
				}
				remove_action('pre_post_update', 'wp_save_post_revision');
				$res = wp_update_post(array(
					'ID' => $id,
					'post_parent' => $post_parent,
					'post_excerpt' => json_encode($conditions),
					'post_mime_type' => '/' . $type
					), true);
				if (!is_wp_error($res)) {
					delete_post_meta($id, 'tbp_associated_theme');
					delete_post_meta($id, 'tbp_template_type');
					delete_post_meta($id, 'tbp_template_conditions');
				}
				add_action('pre_post_update', 'wp_save_post_revision');
			}
		}
		$type = trim($type, '/');
		if ($type === 'productsingle') {
			$type = 'product_single';
		} elseif ($type === 'productarchive') {
			$type = 'product_archive';
		}
		return $type;
	}

	public static function get_template_conditions($post_id, $data = array()):array {
		if (!empty($data)) {
			$condition = $data;
		} else {
			$condition = get_post($post_id)->post_excerpt;
			if (!empty($condition)) {
				$condition = json_decode($condition, true);
			} else {
				self::get_template_type($post_id); //convert data
				$condition = get_post($post_id)->post_excerpt;
			}
			if (empty($condition)) {
				$condition = get_post_meta($post_id, 'tbp_template_conditions', true);
			}
		}
		$records = array();
		if (!empty($condition)) {
			foreach ($condition as $c) {
				$new_arr = array(
					'type'=>isset($c['include']) ? ($c['include'] === 'ex' ? 'exclude' : $c['include']) : 'include'
				);
				if (isset($c['general'])) {
					$new_arr['general'] = $c['general'];
				}
				if (isset($c['query'])) {
					$new_arr['query'] = $c['query'];
				}
				$new_arr['detail'] = !empty($c['detail']) && $c['detail'] !== 'all' ? array_map('strval', array_keys($c['detail'])) : 'all';
				$records[] = $new_arr;
			}
		}

		return $records;
	}
}

Tbp_Templates::register_cpt();
