<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Read Time
 * Description: Provides estimates on how long it takes to read the post.
 */
class TB_Readtime_Module extends Themify_Builder_Component_Module {
	
	public static function init():void{
		add_action('wp_ajax_tbp_readtime_calculate', [__CLASS__, 'calculate']);
		add_action('wp_ajax_nopriv_tbp_readtime_calculate', [__CLASS__, 'calculate']);
		add_action('save_post', [__CLASS__, 'clear_cache']);
	}

	public static function get_module_name():string {
		return __('Reading Time', 'tbp');
	}

	public static function get_module_icon():string {
		return 'time';
	}

	public static function get_js_css():array{
		return array(
			'selector'=>'[data-rtid]',
			'ver' => TBP_VER,
			'js' => TBP_JS_MODULES . 'readtime'
		);
	}

	
	public static function calculate() {
		if(!empty($_POST['ids'])){
			$ids = json_decode(stripcslashes($_POST['ids']), true);
			if(!empty($ids)){
				$result = $changed_batch=[];
				$query = new WP_Query([
					'post_type' => 'any',
					'post_status' => 'any',
					'ignore_sticky_posts' => true,
					'no_found_rows'=>true,
					'update_post_term_cache'=>false,
					'update_menu_item_cache'=>false,
					'posts_per_page'=>count($ids),
					'post__in' => $ids
				]);
				while ($query->have_posts()) {
					$query->the_post();
					$id=get_the_ID();
					if((get_post_status($id)==='publish' || current_user_can( 'read',$id)) && !post_password_required($id) ){
						$batchIndex=self::get_batch_index($id);
						$modified=get_post_timestamp($id, 'modified');
						$count=self::get_word_count($id,$modified);
						if($count!==0){
							$result[$id]=$count;
							continue;
						}
						delete_transient('tbp_readtime_' . $id . $modified);//this line can be removed later
						$content=self::get_content($id);
						if($content!==''){
							$word_count = self::get_words_count($content);
							$result[$id] = self::get_avg_time($word_count);
							$changed_batch[$batchIndex][$id]=$word_count.':'.$modified;
						}
					}
				}
				unset($query);
				if(!empty($result)){
					foreach($changed_batch as $batchIndex=>$vals){
						Themify_Storage::set($batchIndex,$vals,2*MONTH_IN_SECONDS,'tbp_rdtm_');
					}
					wp_send_json_success($result);
				}
			}
		}
		wp_send_json_error('');
	}

	/**
	 * Get the raw unprocessed content for a given $post_id
	 */
	private static function get_content(int $post_id):string {
		$post = get_post($post_id);
		$content = $post->post_content;
		if (!ThemifyBuilder_Data_Manager::has_static_content($content)) {
			/* check if there's Builder data */
			$builder_data = ThemifyBuilder_Data_Manager::get_data($post->ID);
			if (!empty($builder_data)) {
				/* render a text-only version of Builder data and append that to the content */
				$builder_data=ThemifyBuilder_Data_Manager::_get_all_builder_text_content($builder_data);
				if(!str_contains($builder_data,' data-rtid="'. $post_id.'"')){
					return '';
				}
				$content .= $builder_data;
			}
		}

		return $content;
	}

	/**
	 * Count the number of words in $string
	 * This function is localized. For languages that count ‘words’ by the individual character (such as East Asian languages), the function counts the number of individual characters.
	 */
	private static function get_words_count(string $string):int {
		/*
		 * translators: If your word count is based on single characters (e.g. East Asian characters),
		 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
		 * Do not translate into your own language.
		 */
		$reg='/[\n\r\t ]+/';
		if (strpos(_x('words', 'Word count type. Do not translate!'), 'characters') === 0 && preg_match('/^utf\-?8$/i', get_option('blog_charset'))) {
			$string = trim(preg_replace($reg, ' ', $string), ' ');
			preg_match_all('/./u', $string, $words_array);
			$words_array = $words_array[0];
		} else {
			$words_array = preg_split($reg, $string, -1, PREG_SPLIT_NO_EMPTY);
		}

		return count($words_array);
	}

	private static function get_avg_time(int $count):int{
		/**
		* 250 is the average read time for adults
		* source: https://irisreading.com/what-is-the-average-reading-speed/
		*/
		return ceil($count/250);
	}

	private static function get_batch_index(int $id):int{
		return (int) ($id / 100);
	}

	public static function get_word_count(int $id,int $modified=0):int{
		static $cache=[];
		$batchIndex=self::get_batch_index($id);
		if(!isset($cache[$batchIndex])){
			$arr=Themify_Storage::get($batchIndex,'tbp_rdtm_');
			$cache[$batchIndex]=empty($arr)?[]:json_decode($arr,true);
		}
		if(isset($cache[$batchIndex][$id])){
			list($count,$time)=explode(':', $cache[$batchIndex][$id]);
			if($modified===0){
				$modified=get_post_timestamp($id, 'modified');
			}
			if($modified-(int)$time<=3){//human can't do change and save in 3 seconds
				return self::get_avg_time($count);
			}
		}
		return 0;
	}

	public static function clear_cache(?int $post_id, $post = null, $update = true):void {
		if(!empty($post_id) && !wp_is_post_revision($post_id)){
			$batchIndex=self::get_batch_index($post_id);
			$batch=Themify_Storage::get($batchIndex,'tbp_rdtm_');
			if(!empty($batch)){
				$batch=json_decode($batch,true);
				if(isset($batch[$post_id])){
					$content=self::get_content($post_id);
					if($content!==''){
						$batch[$post_id]=self::get_words_count($content).':'.get_post_timestamp($post_id, 'modified');
					}else{
						unset($batch[$post_id]);
					}
					Themify_Storage::set($batchIndex,$batch,2*MONTH_IN_SECONDS,'tbp_rdtm_');
				}
			}
		}
	}
	
	public static function get_static_content(array $module):string {
		return '';
	}

	public function __construct() {//backward
		if (method_exists('Themify_Builder_Model', 'add_module')) {
			parent::__construct('readtime');
		} else {//backward
			parent::__construct(array(
				'name' => $this->get_name(),
				'slug' => 'readtime',
				'category' => $this->get_group()
			));
		}
		self::init();
	}

	public function get_name() {//backward
		return self::get_module_name();
	}

	public function get_icon() {//backward
		return self::get_module_icon();
	}
	
	public function get_assets() {//backward
		return self::get_js_css();
	}


	public function get_styling() {//backward
		$general = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_image('', 'b_i', 'bg_c', 'b_r', 'b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('', 'b_i', 'bg_c', 'b_r', 'b_p', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('', 'f_f'),
							self::get_color('', 'f_c'),
							self::get_font_size('', 'f_s'),
							self::get_line_height('', 'l_h'),
							self::get_letter_spacing('', 'l_s'),
							self::get_text_align('', 't_a'),
							self::get_text_transform('', 't_t'),
							self::get_font_style('', 'f_st', 'f_w'),
							self::get_text_decoration('', 't_d_r'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_h'),
							self::get_color('', 'f_c_h', null, null, 'h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_font_style('', 'f_st', 'f_w', 'h'),
							self::get_text_decoration('', 't_d_r', 'h'),
							self::get_text_shadow('', 't_sh', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('', 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('', 'm')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('', 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend()) > 2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('', 'bl_m_h', 'h')) > 2 ? array($a + array('ishover' => true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_width('', 'w')
			)),
			// Height & Min Height
			self::get_expand('ht', array(
				self::get_height(),
				self::get_min_height(),
				self::get_max_height()
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('', 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array(self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$icon = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_rd_tm_ic', 'b_c_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_rd_tm_ic', 'b_c_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_rd_tm_ic', 'f_c_i'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_rd_tm_ic', 'f_c_i', null, null, 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_rd_tm_ic', 'i_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_rd_tm_ic', 'i_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_rd_tm_ic', 'i_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_rd_tm_ic', 'i_m', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_rd_tm_ic', 'i_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_rd_tm_ic', 'i_b', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_rd_tm_ic', 'i_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_rd_tm_ic', 'i_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_rd_tm_ic', 'i_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_rd_tm_ic', 'i_sh', 'h')
						)
					)
				))
			)),
		);

		$text_before = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_rd_tm_b', 'f_f_t_b'),
							self::get_color(' .tbp_rd_tm_b', 'f_c_t_b'),
							self::get_font_size(' .tbp_rd_tm_b', 'f_s_t_b'),
							self::get_letter_spacing(' .tbp_rd_tm_b', 'l_s_t_b'),
							self::get_line_height(' .tbp_rd_tm_b', 'l_h_t_b'),
							self::get_font_style(' .tbp_rd_tm_b', 'f_st_t_b', 'f_w_t_b'),
							self::get_text_shadow(' .tbp_rd_tm_b', 't_sh_t_b'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_rd_tm_b', 'f_f_t_b', 'h'),
							self::get_color(' .tbp_rd_tm_b', 'f_c_t_b', null, null, 'h'),
							self::get_font_size(' .tbp_rd_tm_b', 'f_s_t_b', '', 'h'),
							self::get_font_style(' .tbp_rd_tm_b', 'f_st_t_b', 'f_w_t_b', 'h'),
							self::get_text_shadow(' .tbp_rd_tm_b', 't_sh_t_b', 'h'),
						)
					)
				))
			)),
		);

		$text_after = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_rd_tm_a', 'f_f_t_a'),
							self::get_color(' .tbp_rd_tm_a', 'f_c_t_a'),
							self::get_font_size(' .tbp_rd_tm_a', 'f_s_t_a'),
							self::get_letter_spacing(' .tbp_rd_tm_a', 'l_s_t_a'),
							self::get_line_height(' .tbp_rd_tm_a', 'l_h_t_a'),
							self::get_font_style(' .tbp_rd_tm_a', 'f_st_t_a', 'f_w_t_a'),
							self::get_text_shadow(' .tbp_rd_tm_a', 't_sh_t_a'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_rd_tm_a', 'f_f_t_a', 'h'),
							self::get_color(' .tbp_rd_tm_a', 'f_c_t_a', null, null, 'h'),
							self::get_font_size(' .tbp_rd_tm_a', 'f_s_t_a', '', 'h'),
							self::get_font_style(' .tbp_rd_tm_a', 'f_st_t_a', 'f_w_t_a', 'h'),
							self::get_text_shadow(' .tbp_rd_tm_a', 't_sh_t_a', 'h'),
						)
					)
				))
			)),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'Icon' => array(
					'options' => $icon
				),
				'b_t' => array(
					'options' => $text_before
				),
				'a_t' => array(
					'options' => $text_after
				),
			)
		);
	}


	public function get_plain_content($module) {//backward
		return self::get_static_content($module);
	}
}

if(method_exists( 'Themify_Builder_Component_Module', 'get_module_class' )){
	TB_Readtime_Module::init();
}
elseif (method_exists('Themify_Builder_Model', 'add_module')) {
	new TB_Readtime_Module();
} else {
	Themify_Builder_Model::register_module('TB_Readtime_Module');
}