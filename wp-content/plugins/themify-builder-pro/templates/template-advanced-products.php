<?php
/**
 * Template Advanced Archive Products
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$isActive=Themify_Builder::$frontedit_active===true;
TB_Advanced_Products_Module::$builder_id=$args['module_ID'];
Tbp_Utils::$isActive=$isActive;
Themify_Builder::$frontedit_active=false;
include __DIR__.'/template-archive-products.php';
Themify_Builder::$frontedit_active=$isActive;
$args=TB_Advanced_Products_Module::$builder_id=null;