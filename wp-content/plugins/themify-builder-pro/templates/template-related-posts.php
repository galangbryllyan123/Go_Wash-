<?php
/**
 * Template Related Posts
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$args['mod_settings']+=array(
	'heading' => '',
	'term_type' => 'category',
	'term_type_select'=>'post',
	'per_page' => 3
);
$args['mod_settings']['pagination'] = 'no';
$isActive = Themify_Builder::$frontedit_active === true;
Tbp_Utils::$isActive = $isActive;
Themify_Builder::$frontedit_active = false;
self::retrieve_template('template-archive-posts.php', $args, __DIR__);
Themify_Builder::$frontedit_active = $isActive;
$args=null;
