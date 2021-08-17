<?php namespace B2\Modules\Settings;

class Seo{
    public function init(){
        add_action('cmb2_admin_init',array($this,'seo_settings'));
    }

    public function seo_settings(){
        $seo_meta = new_cmb2_box(array( 
            'id'            => 'single_post_seo_side_metabox',
            'title'         => __( 'SEO设置', 'b2' ),
            'object_types'  => array( 'post','shop','page','document'), // Post type
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true,
        ));

        $seo_meta->add_field(array(
            'name' => __('SEO标题','b2'),
            'id'   => 'zrz_seo_title',
            'type' => 'text',
            'default'=>'',
        ));

        $seo_meta->add_field(array(
            'name' => __('SEO关键词','b2'),
            'id'   => 'zrz_seo_keywords',
            'type' => 'text',
            'default'=>'',
        ));

        $seo_meta->add_field(array(
            'name' => __('SEO描述','b2'),
            'id'   => 'zrz_seo_description',
            'type' => 'textarea_small',
            'default'=>'',
        ));
    }
}