<?php
defined( 'ABSPATH' ) || exit;

/**
 * Module Name: Lottie
 * Description: Display Lottie
 */

class TB_Lottie_Module extends Themify_Builder_Component_Module {

    public function __construct() {
        parent::__construct('lottie');
    }

    public function get_name(){
        return __('Lottie Animation', 'themify');
    }

    public function get_icon(){
        return 'star';
    }

    public function get_assets() {
        return array(
            'css'=>1
        );
    }

    public function get_options() {
        return array(
            array(
                'id' => 'm_t',
                'type' => 'title'
            ),
            array(
                'id' => 'loop',
                'label'=>__('Loop Animation','themify'),
                'type' => 'toggle_switch',
                'options' => array(
                    'on'=>array(
                        'name'=>'1',
                        'value'=>__('Yes','themify'),
                    ),
                    'off'=>array(
                        'value'=>__('No','themify'),
                    )
                )
            ),
            array(
                'type'=>'builder',
                'id'=>'actions',
                'options'=>array(
                    array(
                        'id'=>'path',
                        'type' => 'lottie',
                        'label'=>__('Json File','themify'),
                        'control'=>false
                    ),
                )
            ),
            array( 'type' => 'custom_css_id', 'custom_css' => 'css' ),
        );
    }

    public function get_styling() {
        $general = array(
            // Background
            self::get_expand('bg', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_image()
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_image('', 'b_i','bg_c','b_r','b_p', 'h')
                        )
                    )
                ))
            )),
            // Font
            self::get_expand('f', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_font_family(),
                            self::get_color('', 'st_g'),
                            self::get_font_size(),
                            self::get_line_height(),
                            self::get_letter_spacing(),
                            self::get_text_align(),
                            self::get_text_transform(),
                            self::get_font_style(),
                            self::get_text_shadow(),
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_font_family('', 'f_f_h'),
                            self::get_color('','f_c_h',null,null,'h'),
                            self::get_font_size('', 'f_s', '', 'h'),
                            self::get_line_height('', 'l_h', 'h'),
                            self::get_letter_spacing('', 'l_s', 'h'),
                            self::get_text_align('', 't_a', 'h'),
                            self::get_text_transform('', 't_t', 'h'),
                            self::get_font_style('', 'f_st', 'f_w', 'h'),
                            self::get_text_shadow('','t_sh','h'),
                        )
                    )
                ))
            )),
            // Padding
            self::get_expand('p', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_padding()
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
                            self::get_margin()
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
                            self::get_border()
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
                            'options' => self::get_blend()

                        ),
                        'h' => array(
                            'options' => self::get_blend('', '', 'h')
                        )
                    ))
                )
            ),
            // Width
            self::get_expand('w', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_width('', 'w')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_width('', 'w', 'h')
                        )
                    )
                ))
            )),
            // Height & Min Height
            self::get_expand('ht', array(
                    self::get_height(),
                    self::get_min_height(),
                    self::get_max_height()
                )
            ),
            // Rounded Corners
            self::get_expand('r_c', array(
                    self::get_tab(array(
                        'n' => array(
                            'options' => array(
                                self::get_border_radius()
                            )
                        ),
                        'h' => array(
                            'options' => array(
                                self::get_border_radius('', 'r_c', 'h')
                            )
                        )
                    ))
                )
            ),
            // Shadow
            self::get_expand('sh', array(
                    self::get_tab(array(
                        'n' => array(
                            'options' => array(
                                self::get_box_shadow()
                            )
                        ),
                        'h' => array(
                            'options' => array(
                                self::get_box_shadow('', 'sh', 'h')
                            )
                        )
                    ))
                )
            ),
            // Position
            self::get_expand('po', array( self::get_css_position())),
            // Display
            self::get_expand('disp', self::get_display())
        );
        $star = array(
            //Gaps
            self::get_expand('gap', array(
                self::get_row_gap(' .tb_star_wrap','','',true),
                self::get_column_gap(' .tb_star_item','','',true)
            )),
            // Star Base Color
            self::get_expand('Star Base Color', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_color(' .tb_star_item', 'st_c')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_color(' .tb_star_item', 'st_c',null, null, 'hover')
                        )
                    )
                ))
            )),
            // Star Highlight Color
            self::get_expand('Star Highlight Color', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_color(array(' .tb_star_item .tb_star_fill'), 'st_h_c')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_color(array(' .tb_star_item .tb_star_fill'), 'st_h_c',null, null, 'hover')
                        )
                    )
                ))
            )),
        );
        $text_before = array(
            // Text Before Color
            self::get_expand('Color', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_color(array(' .tb_star_textbefore'), 'c_t_b')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_color(array(' .tb_star_textbefore'), 'c_t_b',null, null, 'hover')
                        )
                    )
                ))
            )),
            // Margin
            self::get_expand('m', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_margin(' .tb_star_textbefore', 'm_t_b')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_margin(' .tb_star_textbefore', 'm_t_b', 'h')
                        )
                    )
                ))
            )),
            // Width
            self::get_expand('w', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_width(' .tb_star_textbefore', 'w_t_b')
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_width(' .tb_star_textbefore', 'w_t_b', 'h')
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
                'm_t' => array(
                    'options' => $this->module_title_custom_style()
                ),
                'r_s' => array(
                    'label' => __('Star', 'themify'),
                    'options' => $star
                ),
                't_b' => array(
                    'label' => __('Text Before', 'themify'),
                    'options' => $text_before
                )
            )
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args('m_t');
        ?>
        <div class="module module-<?php echo $this->slug; ?> {{ data.css }}">
            <# if( data.m_t ) { #>
            <?php echo $module_args['before_title']; ?>{{{ data.m_t }}}<?php echo $module_args['after_title']; ?>
            <# }
            const json={actions:(data.actions || [])};
            if(data.loop){
            json.loop=1;
            }
            #>
            <tf-lottie class="tf_w tf_h">
                <template>{{{JSON.stringify(json)}}}</template>
            </tf-lottie>
        </div>
        <?php
    }

}

new TB_Lottie_Module();
