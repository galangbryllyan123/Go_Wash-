<?php 
global $themify;

Themify_Enqueue_Assets::loadThemeStyleModule( 'portfolio' );
?>

<?php themify_post_before(); //hook ?>
<article id="portfolio-<?php the_id(); ?>" <?php echo post_class('post tf_clearfix portfolio-post'); ?>>
	<?php themify_post_start(); //hook ?>

    <?php if ( $themify->unlink_title !== 'yes' || $themify->unlink_image !== 'yes' ) : ?>
        <a <?php themify_permalink_attr(); ?> aria-label="<?php the_title_attribute() ?>" data-post-permalink="yes" style="display: none;"><?php _e( 'Post', 'themify' ); ?></a>
    <?php endif;?>

	<?php themify_post_media(); ?>

	<?php themify_post_title(); ?>

	<?php
	if ( is_singular( 'portfolio' ) ) {
		get_template_part( 'includes/portfolio-meta' );
	}
	?>

	<div class="post-content">

		<?php themify_post_content();?>

	</div><!-- /.post-content -->

	<?php themify_post_end(); //hook ?>
</article><!-- /.post -->
<?php themify_post_after(); //hook 
