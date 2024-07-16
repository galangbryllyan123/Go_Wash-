<?php

/*
Plugin Name:       Themify Builder Pro
Plugin URI:        https://themify.me/builder-pro
Description:       Build custom WordPress themes and templates (header, footer, post/page templates, etc.) using Themify Builder.
Version:           3.6.3
Author:            Themify
Author URI:        https://themify.me/
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       tbp
Domain Path:       /languages
Requires PHP: 7.2
Compatibility:     7.0.0
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'TBP_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
const TBP_VER='3.6.3';
const TBP_DIR=__DIR__.DIRECTORY_SEPARATOR;
const TBP_CSS_MODULES=TBP_URL.'public/css/modules/';
const TBP_WC_CSS_MODULES=TBP_URL.'public/css/wc/modules/';
const TBP_JS_MODULES=TBP_URL.'public/js/modules/';
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tbp-activator.php
 */
function tbp_activate() {
	tbp_loaded();
    Tbp::register_cpt();
    flush_rewrite_rules();

	require_once TBP_DIR. 'admin/class-tbp-admin.php';
	Tbp_Admin::themify_settings_save();
}
register_activation_hook( __FILE__, 'tbp_activate' );

function tbp_init() {
    Tbp::run();
}

function tbp_loaded(){

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require TBP_DIR. 'includes/class-tbp.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    add_action( 'init', 'tbp_init');
}
add_action('plugins_loaded','tbp_loaded');


function tbp_admin_notices() {
	if ( ! class_exists( 'Themify_Builder',false ) ) {
	?>
		<div class="error">
			<p><?php printf( __( 'Builder Pro plugin requires <a href="%s">Themify framework</a>, or the free <a href="%s">Themify Builder plugin</a>.', 'tbp' ), 'https://themify.me/themes', 'https://wordpress.org/plugins/themify-builder/' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'tbp_admin_notices' );
