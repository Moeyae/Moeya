<?php 
namespace B2\Modules\Settings;

use \WP_List_Table;
use B2\Modules\Common\Message;

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//分销订单表
class distributionOrderListTable extends WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'id',
            'ajax' => false  
        ));
    }

    function column_default($item, $column_name) {

        $content = Message::msg_value($item->msg_value,$item->msg_type);

        switch ($column_name) {
            case 'msg_users':
                $users = json_decode($item->msg_users);
                $user_data = get_userdata($users[0]);
                if($user_data){
                    return '<a href="'.get_author_posts_url($users[0]).'" target="_blank">'.$user_data->display_name.'</a><p>(ID:'.$users[0].')</p>';
                }else{
                    return __('已删除','b2');
                }
            case 'user_id':
                $user_data = get_userdata($item->user_id);
                if($user_data){
                    return '<a href="'.get_author_posts_url($item->user_id).'" target="_blank">'.$user_data->display_name.'</a><p>(ID:'.$item->user_id.')</p>';
                }else{
                    return __('已删除','b2');
                }
            case 'msg_date':
                return $item->$column_name;
            case 'msg_key':
                $link = is_numeric($item->msg_key) ? get_permalink($item->msg_key) : 'javascript:void(0)';
                $title = array(
                    'name'=>the_title_attribute(array('post'=>$item->msg_key,'echo'=>0)),
                    'link'=>$link
                );
                return '['.$content['type'].']<a href="'.$title['link'].'" target="_blank">'.$title['name'].'</a>';
            case 'lv':
                if($item->msg_type === '66'){
                    return __('一级伙伴','b2');
                }else if($item->msg_type === '67'){
                    return __('二级伙伴','b2');
                }else if($item->msg_type === '68'){
                    return __('三级伙伴','b2');
                }
            case 'money':
                return B2_MONEY_SYMBOL.$content['money'];
            case 'ratio':
                return ($content['ratio']*100).'%';
            case 'number':
                return B2_MONEY_SYMBOL.$item->msg_credit;
        }
    }
    
    function delete_coupons($ids){
        global $wpdb;
        $table_name = $wpdb->prefix.'zrz_message';
        
        if(is_array($ids)){
            foreach ($ids as $id) {
                $mark = '0+'.$id;

                $wpdb->query(
                    $wpdb->prepare( 
                        "DELETE FROM $table_name WHERE `mark`=%s AND `from` = %d",
                        $mark,$id
                    )
                );

                $wpdb->query(
                    $wpdb->prepare( 
                        "DELETE FROM $table_name WHERE `mark`=%s AND `to` = %d",
                        $mark,$id
                    )
                );
            }
        }
    }

    function column_umeta_id($item){
        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

        $actions = array(
            'delete'    => sprintf('<a onclick="return confirm(\'您确定删除该工单吗?\')" href="?page=%s&action=%s&id=%s&paged=%s">'.__('删除','b2').'</a>','b2_distribution_list','delete',$item->umeta_id,$paged),
            'edit'    => sprintf('<a class="green" href="?page=%s&action=%s&id=%s&paged=%s">'.__('编辑','b2').'</a>','b2_distribution_list','edit',$item->umeta_id,$paged)
        );

        return sprintf('%1$s %2$s',
            $item->umeta_id,
            $this->row_actions($actions)
        );
    }

    function column_cb($item){

        return sprintf(
            '<input type="checkbox" name="id[]" value="%1$s" />',
            $item->umeta_id
        );
    }

    function get_columns() {
        return $columns = array(
            'msg_users' => __('消费者','b2'),
            'msg_date'=>__('购买日期','b2'),
            'msg_key' => __('商品名称','b2'),
            'money' => __('商品总价','b2'),
            'user_id' => __('收益人','b2'),
            'lv' => __('伙伴层级','b2'),
            'ratio'=>__('收益比','b2'),
            'number'=>__('收益','b2'),
        );
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'status' => array('status',false)
        );
        return $sortable_columns;
    }

    function display_tablenav( $which ) {

        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <?php if ( $this->has_items() ): ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
            <?php endif;
                $this->extra_tablenav( $which );
                $this->pagination( $which );
            ?>

            <br class="clear" />
        </div>
        <?php
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => __('删除','b2')
        );
        return $actions;
    }

    function prepare_items($val ='') {

        $this->process_bulk_action();

        global $wpdb; 
        $table_name = $wpdb->prefix . 'zrz_message';

        $types = array(66,67,68);
        $types = implode("','",$types);

        $query = "
            SELECT * FROM $table_name
            WHERE msg_type IN ('".$types."') ORDER BY msg_id DESC
            ";

        //搜索
        $s = isset($_REQUEST["s"]) ? esc_sql($_REQUEST["s"]) : '';
      
        if(!empty($s)){

            $users = new \WP_User_Query( array(
                'search'         => '*'.$s.'*',
                'search_columns' => array(
                    'display_name',
                ),
                'number' => 15,
                'paged' => 1
            ) );
    
            $users_found = $users->get_results();
    
           
            $ids = array();
    
            foreach ($users_found as $user) {
    
                $ids[] = $user->ID;
           
            }
            unset($users_found);
            
            $a = implode("','",$ids);

            $query = "
            SELECT * FROM $table_name WHERE `user_id` IN ('".$a."') ORDER BY msg_id DESC
            ";
        }

        $totalitems = $wpdb->query($query);

        $perpage = 20;

        $paged = isset($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';

        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        $totalpages = ceil($totalitems / $perpage);

        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $wpdb->get_results($query);
    }
}