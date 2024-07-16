<?php
global $product;
$isActive = isset($args['mod_name']) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true);
if(is_object($product)){
	if(isset(Themify_Builder::$is_loop)){
		$isLoop=Themify_Builder::$is_loop;
		Themify_Builder::$is_loop=true;
	}
	else{//backward
		global $ThemifyBuilder;
		$isLoop = $ThemifyBuilder->in_the_loop === true;
		$ThemifyBuilder->in_the_loop = true;
	}
	if (isset($args['description']) && $args['description'] === 'short') {
		if ($isActive == true) {
			ob_start();
		}
		woocommerce_template_single_excerpt();
		if ($isActive == true) {
			$content = ob_get_contents();
			ob_end_clean();
			echo $content;
		}
		if (isset($args['l_b_c']) && $args['l_b_c'] === 'yes') {
			echo isset($ThemifyBuilder)?$ThemifyBuilder->get_builder_output(get_the_ID()):Themify_Builder::render(get_the_ID());
		}
	} 
	else {
		?>
		<div class="product-description">
			<?php
			if ($isActive == true) {
				ob_start();
			}
			the_content();
			if ($isActive == true) {
				$content = ob_get_contents();
				ob_end_clean();
				echo $content;
			}
			?>
		</div>
		<?php
	}
	if(isset(Themify_Builder::$is_loop)){
		Themify_Builder::$is_loop=$isLoop;
	}
	else{//backward
		$ThemifyBuilder->in_the_loop = $isLoop;
	}
}
if ($isActive == true && empty($content)&& is_string($args['mod_name'])):
	?>
	<div class="tbp_empty_module">
		<?php echo self::get_module_class($args['mod_name'])::get_module_name() ?>
	</div>
	<?php
endif;
$args = null;
