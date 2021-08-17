<?php namespace B2\Modules\Templates\PostType;

class Collection{
    public function init(){
        //创建专题分类
        add_action( 'init', array($this,'create_collection' ),10,0);

    }

    public function create_collection(){
        $arr = array(
            'name'              => __( '专题', 'b2' ),
            'singular_name'     => __( '专题', 'b2' ),
            'search_items'      => __( '搜索专题', 'b2' ),
            'all_items'         => __( '所有专题', 'b2' ),
            'parent_item'       => __( '父级专题', 'b2' ),
            'parent_item_colon' => __( '父级专题', 'b2' ),
            'edit_item'         => __( '编辑专题', 'b2' ),
            'update_item'       => __( '更新专题', 'b2' ),
            'add_new_item'      => __( '添加专题', 'b2' ),
            'new_item_name'     => __( '专题名称', 'b2' ),
            'menu_name'         => __( '专题', 'b2' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $arr,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest' => true,
            'rewrite'           => array( 'slug' => 'collection','with_front' => false ),
        );
    
        register_taxonomy( 'collection', array( 'post' ), $args );
    }

}