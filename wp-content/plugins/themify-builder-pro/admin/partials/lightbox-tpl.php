<div id="tpl-tbp_builder_lightbox" class="tf_w tf_hide">
    <template shadowrootmode="open">
	<div class="lightbox_parent tf_scrollbar tf_overflow step1">
	    <div class="header flex tf_box">
		<div class="lb_title step1"></div>
		<div class="lb_title flex step2">
		    <button type="button" class="back tf_box" title="<?php _e('Back','tbp')?>"><?php echo themify_get_icon( 'arrow-left','ti' )?></button>
		    <?php echo self::$currentPage===Tbp_Templates::SLUG?__('Import Template','tbp'):__('Import Theme','tbp')?>
		</div>
		<?php if(self::$currentPage===Tbp_Templates::SLUG):?>
		   
		<?php endif;?>
		<button type="button" class="close tf_close"></button>
	    </div>
	    <div class="lightbox tf_scrollbar tf_overflow tf_box step1">
		
	    </div>
	    <div class="lightbox tf_scrollbar tf_overflow tf_box step2">
	    </div>
	    <div class="footer flex tf_box">
		<?php echo themify_get_icon( 'check','ti' ); ?>
		<button type="button" class="save_btn step1" data-active="<?php _e('Activate', 'tbp')?>" data-normal="<?php _e('Save', 'tbp')?>">
		    <span class="new_text"><?php _e('Next', 'tbp')?></span>
		    <span class="edit_text"><?php echo self::$currentPage===Tbp_Templates::SLUG?__('Save', 'tbp'):__('Activate', 'tbp')?></span>
		</button>
		<div class="save_wrap step2 flex">
		    <button type="button" class="draft_btn"><?php echo self::$currentPage===Tbp_Templates::SLUG?__('Save Draft','tbp'):__('Save','tbp')?></button>
		    <button type="button" class="save_btn">
			<?php echo self::$currentPage===Tbp_Templates::SLUG?__('Publish', 'tbp'):__('Activate', 'tbp')?>
		    </button>
		</div>
	    </div>
	</div>
	<div class="overlay tf_abs_t tf_w tf_h"></div>
    </template>
</div>
<template id="tmpl-tbp_box">
    <button class="select_all tf_textl tf_w" type="button" data-select="<?php _e('Select','tbp')?>" data-all="<?php _e('All','tbp')?>"><?php _e('All','tbp')?></button>
    <div class="box_inner tf_box tf_hide">
	<label class="tf_right tf_rel"><?php _e('All','tbp')?></label>
	<input type="checkbox" value="all" class="pagination_all tf_right tf_rel" checked="checked">
	<div class="search_wrap tf_overflow tf_left tf_rel tf_w">
	    <?php echo themify_get_icon('search','ti',false,false,array('aria-label'=>__('Search','themify'))); ?>
	    <input type="search" class="search">
	    <ul class="selected_wrap tf_scrollbar tf_overflow tf_rel"></ul>
	    <div class="result_wrap tf_rel"></div>
	</div>
    </div>
</template>
<?php 
    //load icons
    themify_get_icon('alert','ti');
    themify_get_icon('info','ti');
    themify_get_icon( 'help','ti' );