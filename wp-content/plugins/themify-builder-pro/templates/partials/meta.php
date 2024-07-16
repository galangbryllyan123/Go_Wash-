<?php
defined( 'ABSPATH' ) || exit;
$empty_module = true;
if (!empty($args['meta'])) :
	?>
	<div class="entry-meta tbp_post_meta">
		<?php
		$TBP_DIR = TBP_DIR . 'templates';
		foreach ($args['meta'] as $arg) {
			$output = '';
			$vals = empty($arg['val']) ? array() : $arg['val'];
			switch ($arg['type']) :
				case 'text':
					if (!empty($vals['t'])) {
						$output = do_shortcode($vals['t']);
					}
					break;

				case 'date':
				case 'time':
					$output = self::retrieve_template('partials/date.php', $arg, $TBP_DIR, '', false);
					break;

				case 'author':
					global $post;
					if(isset($post)){
						$vals+=array(
							'p_s' => 32,
							'a_p' => 'no',
							'l' => 'yes'
						);
						$output = '<span class="author vcard tbp_post_meta_author_inner">';
						if (!empty($vals['icon'])) {
							$output .= '<span>' . themify_get_icon($vals['icon']) . '</span> ';
						}
						if ('yes' === $vals['l']) {
							$output .= '<a class="tbp_post_meta_link" href="' . get_author_posts_url($post->post_author) . '" rel="author">';
						}
						if ('yes' === $vals['a_p']) {
							$output .= get_avatar($post->post_author, $vals['p_s']);
						}
						$output .= '<span>' . get_the_author_meta( 'display_name', $post->post_author ) . '</span>';
						if ('yes' === $vals['l']) {
							$output .= '</a>';
						}
						$output .= '</span>';
					}
					break;

				case 'comments':
					$vals+=array(
						'no' => '',
						'one' => '',
						'comments' => '',
						'l' => 'yes'
					);
					$comments_num = (int) get_comments_number();
					if ($comments_num === 0) {
						$comments = $vals['no'];
					} 
					elseif ($comments_num === 1) {
						$comments = $vals['one'];
					} 
					elseif (isset($vals['comments'])) {
						$comments = sprintf($vals['comments'], $comments_num);
					}
					if (!empty($vals['icon'])) {
						$output .= '<span>' . themify_get_icon($vals['icon']) . '</span> ';
					}
					if ('yes' === $vals['l']) {
						$output .= '<a class="tbp_post_meta_link" href="' . get_comments_link() . '">';
					}
					$output .= $comments;
					if ('yes' === $vals['l']) {
						$output .= '</a>';
					}
					break;

				case 'terms':
					$vals+= array(
						'post_type' => 'post',
						'taxonomy' => 'category',
						'sep' => ',',
						'l' => 'yes',
						'c' => 'no', // cover
						'w' => '', // cover width
						'h' => '', // cover height
						'd' => '', // cover display
					);

					$output .= tbp_get_terms_list( get_the_ID(), $vals['taxonomy'], [
						'before' => !empty($vals['icon']) ? '<span>' . themify_get_icon($vals['icon']) . '</span> ' : '',
						'sep' => $vals['sep'],
						'link' => 'yes' === $vals['l'],
						'cover' => $vals['c'] === 'yes',
						'cover_w' => $vals['w'],
						'cover_h' => $vals['h'],
						'cover_class' => $vals['d'] === 'b' ? 'tf_block' : 'tf_inline_b'
					] );
					break;

			endswitch;

			if (!empty($output)) {
				$empty_module = false;
				echo '<span class="tbp_post_meta_item tbp_post_meta_', $arg['type'] , '">';
				if (!empty($vals['before'])) {
					echo '<span class="tbp_post_meta_before">' , $vals['before'] , '</span>';
				}
				echo $output;
				if (!empty($vals['after'])) {
					echo '<span class="tbp_post_meta_after">' ,$vals['after'] , '</span>';
				}
				echo '</span><!-- .tbp_post_meta_item -->';
			}
		}
		?>
	</div><!-- .tbp_post_meta -->
<?php endif; ?>

<?php if ($empty_module===true && !empty($args['mod_name']) && is_string($args['mod_name']) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true)) : ?>
	<div class="tbp_empty_module">
		<?php echo self::get_module_class($args['mod_name'])::get_module_name(); ?>
	</div>
	<?php
endif;
$args = null;
