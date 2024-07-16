<?php 
	global $product; 
	$isExist = isset($args['mod_name']) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true) ? false : null;?>
<?php if (!empty($args['meta']) && is_object($product)): ?>
	<div class="entry-meta tbp_product_meta">
		<?php
		foreach ($args['meta'] as $arg):
			$arg['val'] = empty($arg['val']) ? array() : $arg['val'];
			?>
			<span class="tbp_product_meta_item tbp_product_meta_<?php echo $arg['type']; ?>">
				<?php if (!empty($arg['val']['before'])): ?>
					<?php $isExist = true; ?>
					<span class="tbp_product_meta_before"><?php echo $arg['val']['before']; ?></span>
				<?php endif; ?>
				<?php
				switch ($arg['type']):
					case 'text':
						if (!empty($arg['val']['t'])) {
							echo do_shortcode($arg['val']['t']);
						}
						break;
					case 'terms':
						$arg['val']+= array(
							'taxonomy' => 'product_cat',
							'sep' => ',',
							'l' => 'yes'
						);
						$terms = get_the_terms(get_the_ID(), $arg['val']['taxonomy']);
						$hasLink = 'yes' === $arg['val']['l'];
						?>

						<?php if (!empty($arg['val']['icon'])): ?>
							<span><?php echo themify_get_icon($arg['val']['icon']) ?></span>

							<?php $isExist = true;
						endif;
						?>

						<?php if (!empty($terms) && !is_wp_error($terms) && is_array($terms)): ?>
							<?php
							$isExist = true;
							$num_of_terms = count($terms) - 1;
							$template = $hasLink === true ? '<a href="%s">%s</a>%s' : '%s%s%s';
							foreach ($terms as $i => $term):
								if ($hasLink === true) {
									$term_link = get_term_link($term, array($arg['val']['taxonomy']));
									if (is_wp_error($term_link)) {
										--$num_of_terms;
										continue;
									}
								} else {
									$term_link = '';
								}
								printf($template, $term_link, $term->name, ($i < $num_of_terms && $num_of_terms >= 1) ? $arg['val']['sep'] : '' );
								?>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php break;?>
				<?php endswitch; ?>
				<?php if (isset($arg['val']['after']) && '' !== $arg['val']['after']): ?>
					<?php $isExist = true; ?>
					<span class="tbp_product_meta_after"><?php echo $arg['val']['after'] ?></span>
		<?php endif; ?>
			</span>
		</span>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if ($isExist === false&& is_string($args['mod_name']) ): ?>
	<div class="tbp_empty_module">
		<?php echo self::get_module_class($args['mod_name'])::get_module_name() ?>
	</div>
<?php
endif;
$args = null;
