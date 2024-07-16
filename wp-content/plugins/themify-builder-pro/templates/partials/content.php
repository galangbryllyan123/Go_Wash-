<?php if (!empty($args['drop_cap'])): ?>
	<div class="tb_text_dropcap">
<?php endif; ?>
    <div class="tb_text_wrap">
		<?php
		if(isset(Themify_Builder::$is_loop)){
			$isLoop=Themify_Builder::$is_loop;
			Themify_Builder::$is_loop=true;
		}
		else{//backward
			global $ThemifyBuilder;
			$isLoop = $ThemifyBuilder->in_the_loop === true;
			$ThemifyBuilder->in_the_loop = true;
		}
        $more_text = !empty($args['more_text']) ? $args['more_text'] : __( 'Read More', 'tbp' );
		if (isset($args['content_type']) && $args['content_type'] === 'excerpt') {
            if ( isset( $args['more_link'] ) && 'on' === $args['more_link'] ) {
                $args['more'] = sprintf(' <a class="more-link" href="%s">%s</a>', get_the_permalink(), $more_text);
            }
			echo Tbp_Utils::get_excerpt($args);
		} 
		else {
			if (!empty($args['pro_paged'])) {
				global $paged, $page;
				$paged = $page = $args['pro_paged'];
			}
			the_content($more_text);
			// Paging
			wp_link_pages();
		}
		if(isset(Themify_Builder::$is_loop)){
			Themify_Builder::$is_loop=$isLoop;
		}
		else{//backward
			$ThemifyBuilder->in_the_loop = $isLoop;
		}
		?>
    </div>
	<?php if (!empty($args['drop_cap'])): ?>
	</div>
	<?php
endif;
$args = null;
