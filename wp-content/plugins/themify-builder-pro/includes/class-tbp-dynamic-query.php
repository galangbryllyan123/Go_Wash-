<?php

final class Tbp_Dynamic_Query {

	const FIELD_NAME = 'tbpdq';

	/* temporary cache for module settings to pass on to pre_get_posts */
	private static $_current_item = null;
	private static $_current_settings = array();

	public static function run() {
		add_filter( 'themify_builder_module_render_vars', array( __CLASS__, 'themify_builder_module_render_vars' ), 10, 2 );
	}

	private static function get_items(string $id='') {
		static $items = null;
		if ( $items === null ) {
			$items = array(
				'on' => 1,
				'childs' => 1,
				'sameauthor' =>1,
				'currentuser' => 1
			);
			if($id!=='' && isset($items[$id])){
				return true;
			}
			$plugins = array(
				'acf' => 'advanced-custom-fields-pro/acf.php',
				'tep' => 'themify-event-post/themify-event-post.php'
			);
			if(Tbp_Utils::plugin_active('themify-ptb/themify-ptb.php')){
				$plugins['ptbSubmission']='themify-ptb-submissions/themify-ptb-submissions.php';
				$plugins['ptbSearch']='themify-ptb-search/themify-ptb-search.php';
				$plugins['ptbRelation']='themify-ptb-relation/themify-ptb-relation.php';
			}
			foreach ($plugins as $plugin => $active_check) {
				if (Tbp_Utils::plugin_active($active_check)) {
					$items[$plugin]=1;
				}
			}
			$items=apply_filters( 'tbp_dynamic_query_items',$items);
		}
		
		return $id!==''?isset($items[$id]):$items;
	}

	/**
	 * Get a DQ item by ID, returns its classname
	 */
	public static function get_item(string $id ):string {
		$className='\Tbp_Dynamic_Query_'.ucfirst($id);
		if(!class_exists($className,false)){
			if(!self::get_items($id)){
				return '';
			}
			include __DIR__.'/dynamic-query/'.$id.'.php';
		}
		return $className;
	}

	public static function get_builder_active_vars(string $template):array {
		$vars= array(
			array(
				'id' => self::FIELD_NAME,
				'type' => 'select',
				'label' => __('Dynamic Query', 'tbp'),
				'default'=>'off',
				'options' => array(
					'off' => 'disd'
				),
				'binding' => [
					'not_empty' => [ 'hide' => ['post_filter_options','tbpdq_off'] ],
					'off' => [ 'show' => ['post_filter_options','tb_field[data-type="query_posts"]','tbpdq_off'],'hide'=>'tbpdq_settings' ]
				]
			)
		);

		foreach ( self::get_items() as $id => $_v ) {
			if($id!=='on' || $template === 'archive' || $template === 'product_archive'){
				$classname=self::get_item($id);
				if($classname!==''){
					$vars[0]['options'][ $id ] = $classname::get_label();
					$options = $classname::get_options();
					$vars[0]['binding'][$id]=['hide'=>['tbpdq_settings','tb_field[data-type="query_posts"]','tbpdq_off']];
					if ( ! empty( $options ) ) {
						foreach ( $options as $i => $option ) {
							if ( isset( $option['id'] ) ) {
								$options[ $i ]['id'] = 'tbpdq_' . $option['id'];
							}
						}
						$vars[0]['binding'][$id]['show']='tbpdq_settings_' . $id;
						$vars[] = array(
							'type' => 'group',
							'wrap_class' => 'tbpdq_settings tbpdq_settings_' . $id,
							'options' => $options,
						);
					}
				}
			}
		}

		return $vars;
	}

	/**
	 * Runs just before a module is rendered, enable Dynamic Query if applicable
	 *
	 * @return array
	 */
	public static function themify_builder_module_render_vars(array $vars,string $slug='' ):array {
		if($slug!==''){//backward 
			self::$_current_item =  null;
			self::$_current_settings =[];
			if ( ! empty( $vars['mod_settings'][ self::FIELD_NAME ] ) && self::get_item( $vars['mod_settings'][ self::FIELD_NAME ] )!=='' ) {

				/* disable the Post Filter options */
				unset( $vars['mod_settings']['post_filter'] );

				/* in AP modules, Main Query is handled by the module itself */
				if (($slug==='advanced-posts' || $slug==='advanced-products') && $vars['mod_settings'][ self::FIELD_NAME ] === 'on') {
					return $vars;
				}
				
				self::$_current_item = self::get_item( $vars['mod_settings'][ self::FIELD_NAME ] );
				if(self::$_current_item!==''){
					/* cache module settings related to the DQ */
                    foreach ( $vars['mod_settings'] as $option_name => $option_value ) {
                        if ( substr( $option_name, 0, 6 ) === 'tbpdq_' ) {
                            self::$_current_settings[ substr( $option_name, 6 ) ] = $option_value;
						}
					}
					add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
					if(method_exists( self::$_current_item, 'module_container_props' )){
						add_filter( 'themify_builder_module_container_props', array( __CLASS__, 'module_container_props' ), 10, 4 );
					}
				}
			}
		}
		return $vars;
	}

	/**
	 * Replace all the query vars of the current query with global $wp_query
	 *
	 */
	public static function pre_get_posts( &$query ) {
		/**
		 * In case this is the last module in the page and there are other queries running
		 * after this, reset "pre_get_posts" again to ensure this filter runs only once.
		 */
		remove_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );

		/* cache Limit, Order and Orderby, these are configured in the module and probably need to remain untouched */
		$query_vars = [];
		foreach ( [ 'posts_per_page', 'order', 'orderby', 'paged' ] as $query_var ) {
			$query_vars[ $query_var ] = $query->get( $query_var );
		}
        if ( $query_vars['orderby'] === 'meta_value' && ! empty( $query->get( 'meta_key' ) ) ) {
            $query_vars['meta_key'] = $query->get( 'meta_key' );
            if ( ! empty( $query->get( 'meta_type' ) ) ) {
                $query_vars['meta_type'] = $query->get( 'meta_type' );
            }
        }

		/* reset all query vars */
		$query->query_vars = null;

		$query->set( 'ignore_sticky_posts', true );
		foreach ( $query_vars as $query_var_key => $query_var_value ) {
			$query->set( $query_var_key, $query_var_value );
		}
		unset( $query_vars );

		if (! self::$_current_item::pre_get_posts( $query, self::$_current_settings ) ) {
			$query->set( 'post__in', [ 0 ] ); /* disable posts display */
		}
	}

	/**
	 * Allows filtering the module container HTML attributes
	 *
	 * @return array
	 */
	public static function module_container_props( array $attr, array $module_settings,string $mod_name,string $element_id ):array {
        if ( self::$_current_item ) {
            self::$_current_item::module_container_props( $attr, $module_settings, $mod_name, self::$_current_settings );
        }
		remove_filter( 'themify_builder_module_container_props', array( __CLASS__, 'module_container_props' ), 10);
		return $attr;
	}
}


