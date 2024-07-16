<?php

/*
Plugin Name:  Builder Maps Pro
Plugin URI:   https://themify.me/addons/maps-pro
Version:      3.5.4
Author:       Themify
Author URI:   https://themify.me
Description:  Maps Pro module allows you to insert Google Maps with multiple location markers with custom icons, tooltip text, and various map styles. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-maps-pro
Domain Path:  /languages
Requires PHP: 7.2
Compatibility: 7.0.0
*/

defined('ABSPATH') or die('-1');

class Builder_Maps_Pro {

    public static $url;

    /**
     * Init Builder Maps Pro
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'i18n' ) );
        add_action('themify_builder_setup_modules', array(__CLASS__, 'register_module'));
	    add_action( 'plugins_loaded', array( __CLASS__, 'constants' ) );
        if ( is_admin() ) {
	        add_filter( 'plugin_row_meta', array( __CLASS__, 'themify_plugin_meta'), 10, 2 );
	        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'action_links') );
            add_action( 'wp_ajax_tb_get_maps_pro_styles', array( __CLASS__, 'ajax_get_styles' ) );
			add_filter( 'tb_select_dataset_ptb_map_fields', array( __CLASS__, 'tb_select_dataset_ptb_map_fields' ) );
			add_filter( 'tb_select_dataset_acf_map_fields', array( __CLASS__, 'tb_select_dataset_acf_map_fields' ) );
        } 
    }

    private static function get_plugin_dir():string{
	    return trailingslashit(plugin_dir_path(__FILE__));
    }

    public static function get_version():string{
        return '3.5.4';
    }

    public static function constants() {
        self::$url = trailingslashit(plugin_dir_url(__FILE__));
    }

	public static function themify_plugin_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$row_meta = array(
			  'changelogs'    => '<a href="' . esc_url( 'https://themify.org/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify' ) . '">' . esc_html__( 'View Changelogs', 'themify' ) . '</a>'
			);
	 
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	public static function action_links( $links ) {
		if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
			$tlinks = array(
			 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify') .'</a>',
			 );
		} else {
			$tlinks = array(
			 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify') .'</a>',
			 );
		}
		return array_merge( $links, $tlinks );
	}
    public static function i18n() {
        load_plugin_textdomain( 'builder-maps-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public static function register_module() {
		$dir=self::get_plugin_dir();
        if(method_exists('Themify_Builder_Model', 'add_module')){
            Themify_Builder_Model::add_module($dir  . 'modules/module-maps-pro.php' );
        }
        else{
            Themify_Builder_Model::register_directory('templates', $dir  . 'templates');
            Themify_Builder_Model::register_directory('modules', $dir  . 'modules');
        }
    }

    public static function get_map_styles():array {
        $dir = get_stylesheet_directory() . '/builder-maps-pro/styles/';
        $theme_styles = is_dir($dir) ? self::list_dir($dir) : array();

        return array_merge(self::list_dir(self::get_plugin_dir(). 'styles/'), $theme_styles);
    }

    private static function list_dir(string $path):array {
        $dh = opendir($path);
        $files = array();
        while (false !== ( $filename = readdir($dh) )) {
            if ($filename !== '.' && $filename !== '..' && '.json'===substr($filename,-5,5)) {
                $files[$filename] = $filename;
            }
        }

        return $files;
    }

    public static function get_map_style(string $name):array {
        $file = get_stylesheet_directory() . '/builder-maps-pro/styles/' . $name . '.json';
        if(!is_file($file)){
            $file =  self::get_plugin_dir(). 'styles/' . $name . '.json';
            if (!is_file($file)) {
                return array();
            }
        }
        return json_decode(file_get_contents($file),true);
    }

	/**
	 * Send the list of map styles to Builder editor
	 *
	 * @hooked to "wp_ajax_tb_get_maps_pro_styles"
	 */
	public static function  ajax_get_styles() {
		check_ajax_referer('tf_nonce', 'nonce');
		$map_styles = array();
		$dir = get_stylesheet_directory() . '/builder-maps-pro/styles/';
        $theme_styles = is_dir($dir) ? self::list_dir($dir) : array();
        $data=array_merge(self::list_dir(self::get_plugin_dir(). 'styles/'), $theme_styles);
		foreach ($data as $key => $v) {
			$name = str_replace('.json', '', $key);
			$map_styles[$name] = self::get_map_style($name);
		}
		wp_send_json($map_styles);
	}

	/**
	 * Handles filling dynamic values for the "ptb_map_field" option in Maps Pro
	 *
	 * @return array
	 */
	public static function tb_select_dataset_ptb_map_fields( $values ):array {
		$options = array( '' => '' );
		if ( class_exists( 'PTB' ,false) ) {
			$post_types = Themify_Builder_Model::get_public_post_types();
			foreach ( $post_types as $post_type => $post_type_label ) {
				$ptb_fields = PTB::$options->get_cpt_cmb_options( $post_type );
				if ( ! empty( $ptb_fields ) ) {
					foreach ( $ptb_fields as $key => $field ) {
						if ( $field['type'] === 'map' ) {
							$name = PTB_Utils::get_label( $field['name'] );
							$options[ "{$post_type}:{$key}" ] = sprintf( '%s: %s', $post_type_label, $name );
						}
					}
				}
			}
		}
		return $options;
	}

	public static function tb_select_dataset_acf_map_fields():array {
		$options = array();
		if (  class_exists( 'acf_pro',false ) ) {
			$type = [ 'map' ];
			$field_groups = acf_get_field_groups();
			foreach ( $field_groups as $field_group ) {
				$fields = acf_get_fields( $field_group['ID'] );
				foreach ( $fields as $field ) {
					if ( empty( $field['name'] ) ) {
						continue;
					}

					if ( in_array( $field['type'], $type,true ) ) {
						$options[ "{$field_group['key']}:{$field['name']}" ] = sprintf( '%s: %s', $field_group['title'], $field['label'] );
					} 
					elseif ( $field['type'] === 'group' && ! empty( $field['sub_fields'] ) ) {
						foreach ( $field['sub_fields'] as $subfield ) {
							if ( in_array( $subfield['type'], $type ) ) {
								$options[ "group:{$field['name']}:{$subfield['name']}" ] = sprintf( '%s: %s: %s', $field_group['title'], $field['label'], $subfield['label'] );
							}
						}
					} 
					elseif ( $field['type'] === 'repeater' && ! empty( $field['sub_fields'] ) ) {
						foreach ( $field['sub_fields'] as $subfield ) {
							if ( in_array( $subfield['type'], $type,true ) ) {
								$options[ "repeater:{$field['name']}:{$subfield['name']}" ] = sprintf( '%s: %s: %s', $field_group['title'], $field['label'], $subfield['label'] );
							}
						}
					}
				}
			}
		}
		return $options;
	}
}
Builder_Maps_Pro::init();

/*
 * @deprecated
 */
if ( ! class_exists( 'Maps_Pro_Data_Provider' ,false) ){
	class Maps_Pro_Data_Provider {}
}
